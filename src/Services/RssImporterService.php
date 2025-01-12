<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\News;
use App\Entity\Source;
use App\Entity\Category;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Dto\RssItemDto;
use App\Interfaces\RssMapperInterface;

class RssImporterService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator,
        private RssMapperInterface $mapper
    ) {
    }

    /**
     * @throws Exception
     */
    public function importRssFeed(Source $source): void
    {
        $rssUrl = $source->getUrl();
        $source = $this->entityManager->getRepository(Source::class)->find($source->getId());

        try {
            $rss = simplexml_load_file($rssUrl);
            if ($rss === false) {
                throw new Exception("Failed to load RSS feed.");
            }
        } catch (Exception $e) {
            throw new Exception("Error loading RSS feed for source: {$source->getName()}. Error: " . $e->getMessage());
        }

        foreach ($rss->channel->item as $item) {
            $rssItemDto = $this->mapper->mapXmlToDto($item);
            $violations = $this->validator->validate($rssItemDto);
            if (count($violations) > 0) {
                foreach ($violations as $violation) {
                    echo sprintf(
                        "Validation Error: %s (Value: %s)\n",
                        $violation->getMessage(),
                        $violation->getInvalidValue()
                    );
                }
                continue;
            }
            $this->processRssItem($rssItemDto, $source);
        }

        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    private function processRssItem(RssItemDto $rssItemDto, Source $source): void
    {
        $catRep = $this->entityManager->getRepository(Category::class);
        $newsRep = $this->entityManager->getRepository(News::class);

        if ($newsRep->findOneBy(['link' => $rssItemDto->link])) {
            return;
        }
        $news = new News();
        $news->setTitle($rssItemDto->title);
        $news->setLink($rssItemDto->link);
        $news->setPubDate(new \DateTime($rssItemDto->pubDate));

        $category = $catRep->findOneBy(['name' => $rssItemDto->category]);


        if (!$category) {
            $persistedEntities = $this->entityManager->getUnitOfWork()->getScheduledEntityInsertions();
            foreach ($persistedEntities as $entity) {
                if ($entity instanceof Category && $entity->getName() === $rssItemDto->category) {
                    $category = $entity;
                    break;
                }
            }

            if (!$category) {
                $category = new Category();
                $category->setName($rssItemDto->category);

                $this->entityManager->persist($category);
            }
        }

        $news->setCategory($category);
        $news->setSource($source);
        $this->entityManager->persist($news);
    }
}