<?php

namespace App\Dto;


class ItemAnswerDto
{
    public int $id;

    public string $title;

    public string $link;

    public string $pubDate;

    public array $source;
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