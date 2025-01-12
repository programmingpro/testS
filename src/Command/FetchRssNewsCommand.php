<?php

namespace App\Command;

use App\Entity\Source;
use App\Service\RssImporterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:fetch-rss-news',
    description: 'Fetches news from an RSS feed',
)]
class FetchRssNewsCommand extends Command
{
    public function __construct(
        private RssImporterService $rssImporterService,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }


    protected function configure(): void
    {
        $this
            ->setDescription('Fetches news from a specified RSS feed.')
            ->setHelp('This command allows you to fetch and display news from a given RSS feed URL.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sources = $this->entityManager->getRepository(Source::class)->findAll();

        if (empty($sources)) {
            $output->writeln('No sources found in the database.');
            return Command::FAILURE;
        }

        foreach ($sources as $source) {
            try {
                $this->rssImporterService->importRssFeed($source);
                $output->writeln("News for {$source->getName()} imported successfully.");
            } catch (\Exception $e) {
                $output->writeln("Error processing source {$source->getName()}: " . $e->getMessage());
                continue;
            }
        }

        return Command::SUCCESS;
    }
}
