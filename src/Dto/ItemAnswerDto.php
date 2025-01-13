<?php

namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ItemAnswerDto',
    title: 'ItemAnswerDto',
    description: 'Ответ с данными новости'
)]
class ItemAnswerDto
{
    #[OA\Property(type: 'integer', example: 1)]
    public int $id;

    #[OA\Property(type: 'string', example: 'Новый заголовок')]
    public string $title;

    #[OA\Property(type: 'string', example: 'https://example.com/new-link')]
    public string $link;

    #[OA\Property(type: 'string', format: 'date', example: '2023-10-15')]
    public string $pubDate;

    #[OA\Property(
        properties: [
            new OA\Property(property: 'id', type: 'integer', example: 3),
            new OA\Property(property: 'name', type: 'string', example: 'Источник 3'),
            new OA\Property(property: 'url', type: 'string', example: 'https://example.com/source3'),
        ],
        type: 'object'
    )]
    public array $source;
    #[OA\Property(
        properties: [
            new OA\Property(property: 'id', type: 'integer', example: 2),
            new OA\Property(property: 'name', type: 'string', example: 'Категория 2'),
        ],
        type: 'object'
    )]
    public array $category;

    public function __construct(
        int $id,
        string $title,
        string $link,
        string $pubDate,
        array $source,
        array $category
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->link = $link;
        $this->pubDate = $pubDate;
        $this->source = $source;
        $this->category = $category;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'link' => $this->link,
            'pubDate' => $this->pubDate,
            'source' => $this->source,
            'category' => $this->category,
        ];
    }
}