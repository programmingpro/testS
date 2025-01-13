<?php

namespace App\Controller\API;

use App\Dto\EditDto;
use App\Dto\ItemAnswerDto;
use App\Dto\ListAnswerDto;
use App\Dto\ListRequestDto;
use App\Entity\Category;
use App\Entity\News;
use App\Entity\Source;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

/**
 * Контроллер для работы с новостями.
 */
#[Route('/api/news', name: 'api_news_')]
class NewsApiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly NewsRepository         $newsRepository
    ) {}

    /**
     * Получить список новостей с пагинацией.
     */
    #[OA\Get(
        path: '/api/news/',
        summary: 'Получить список новостей с пагинацией',
        tags: ['News'],
        parameters: [
            new OA\Parameter(
                name: 'start_date',
                description: 'Начальная дата периода (формат Y-m-d)',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'date', example: '2023-01-01')
            ),
            new OA\Parameter(
                name: 'end_date',
                description: 'Конечная дата периода (формат Y-m-d)',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'date', example: '2023-12-31')
            ),
            new OA\Parameter(
                name: 'page',
                description: 'Номер страницы',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'limit',
                description: 'Количество записей на странице',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 10)
            ),
        ]
    )]
    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $dto = new ListRequestDto(
            $request->query->get('start_date'),
            $request->query->get('end_date'),
            (int) $request->query->get('page', 1),
            (int) $request->query->get('limit', 10)
        );

        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $newsList = $this->newsRepository->findNewsByPeriod(
            $dto->getStartDate(),
            $dto->getEndDate(),
            $dto->getPage(),
            $dto->getLimit()
        );

        $total = $this->newsRepository->countNewsByPeriod(
            $dto->getStartDate(),
            $dto->getEndDate(),
        );

        $items = array_map(function (News $news) {
            return new ItemAnswerDto(
                id: $news->getId(),
                title: $news->getTitle(),
                link: $news->getLink(),
                pubDate: $news->getPubDate()->format('Y-m-d'),
                source: [
                    'id' => $news->getSource()->getId(),
                    'name' => $news->getSource()->getName(),
                    'url' => $news->getSource()->getUrl(),
                ],
                category: [
                    'id' => $news->getCategory()->getId(),
                    'name' => $news->getCategory()->getName(),
                ]
            );
        }, $newsList);

        $responseDto = new ListAnswerDto(
            page: $dto->getPage(),
            limit: $dto->getLimit(),
            total: $total,
            items: $items
        );

        return $this->json($responseDto->toArray());
    }

    /**
     * Получить новость по ID.
     */
    #[OA\Get(
        path: '/api/news/{id}',
        summary: 'Получить новость по ID',
        tags: ['News'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID новости',
                in: 'path',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ]
    )]
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $news = $this->newsRepository->find($id);

        if (!$news) {
            return $this->json(['message' => 'News not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $itemAnswerDto = new ItemAnswerDto(
            id: $news->getId(),
            title: $news->getTitle(),
            link: $news->getLink(),
            pubDate: $news->getPubDate()->format('Y-m-d'),
            source: [
                'id' => $news->getSource()->getId(),
                'name' => $news->getSource()->getName(),
                'url' => $news->getSource()->getUrl(),
            ],
            category: [
                'id' => $news->getCategory()->getId(),
                'name' => $news->getCategory()->getName(),
            ]
        );

        return $this->json($itemAnswerDto->toArray());
    }

    /**
     * Редактировать новость.
     */
    #[OA\Put(
        path: '/api/news/{id}',
        summary: 'Редактировать новость',
        requestBody: new OA\RequestBody(
            description: 'Данные для редактирования новости',
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'title',
                        type: 'string',
                        example: 'Новый заголовок',
                        description: 'Заголовок новости'
                    ),
                    new OA\Property(
                        property: 'link',
                        description: 'Ссылка на новость',
                        type: 'string',
                        example: 'https://example.com/new-link'
                    ),
                    new OA\Property(
                        property: 'pubDate',
                        description: 'Дата публикации в формате Y-m-d',
                        type: 'string',
                        format: 'date',
                        example: '2023-10-15'
                    ),
                    new OA\Property(
                        property: 'category_id',
                        description: 'ID категории',
                        type: 'integer',
                        example: 2
                    ),
                    new OA\Property(
                        property: 'source_id',
                        description: 'ID источника',
                        type: 'integer',
                        example: 3
                    ),
                ],
                type: 'object'
            )
        ),
        tags: ['News'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID новости',
                in: 'path',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ]
    )]
    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(int $id, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $news = $this->newsRepository->find($id);

        if (!$news) {
            return $this->json(['message' => 'News not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        $dto = EditDto::fromArray($data);

        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        if ($dto->getTitle() !== null) {
            $news->setTitle($dto->getTitle());
        }
        if ($dto->getLink() !== null) {
            $news->setLink($dto->getLink());
        }
        if ($dto->getPubDate() !== null) {
            $news->setPubDate(new \DateTime($dto->getPubDate()));
        }

        if ($dto->getCategoryId() !== null) {
            $category = $this->entityManager->getRepository(Category::class)->find($dto->getCategoryId());
            if (!$category) {
                return $this->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
            }
            $news->setCategory($category);
        }

        if ($dto->getSourceId() !== null) {
            $source = $this->entityManager->getRepository(Source::class)->find($dto->getSourceId());
            if (!$source) {
                return $this->json(['message' => 'Source not found'], Response::HTTP_NOT_FOUND);
            }
            $news->setSource($source);
        }

        $this->entityManager->flush();

        $itemAnswerDto = new ItemAnswerDto(
            id: $news->getId(),
            title: $news->getTitle(),
            link: $news->getLink(),
            pubDate: $news->getPubDate()->format('Y-m-d'),
            source: [
                'id' => $news->getSource()->getId(),
                'name' => $news->getSource()->getName(),
                'url' => $news->getSource()->getUrl(),
            ],
            category: [
                'id' => $news->getCategory()->getId(),
                'name' => $news->getCategory()->getName(),
            ]
        );

        return $this->json($itemAnswerDto->toArray());
    }

    /**
     * Удалить новость.
     */
    #[OA\Delete(
        path: '/api/news/{id}',
        summary: 'Удалить новость',
        tags: ['News'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID новости',
                in: 'path',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Новость успешно удалена',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'News deleted successfully'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Новость не найдена'
            ),
        ]
    )]
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $news = $this->newsRepository->find($id);

        if (!$news) {
            return $this->json(['message' => 'News not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($news);
        $this->entityManager->flush();

        return $this->json(['message' => 'News deleted successfully']);
    }
}