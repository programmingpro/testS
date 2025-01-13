<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class EditDto
{
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    private ?string $title;

    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    #[Assert\Url]
    private ?string $link;

    #[Assert\DateTime(format: 'Y-m-d')]
    private ?string $pubDate;

    #[Assert\Type('integer')]
    #[Assert\Positive]
    private ?int $categoryId;

    #[Assert\Type('integer')]
    #[Assert\Positive]
    private ?int $sourceId;

    public function __construct(
        ?string $title = null,
        ?string $link = null,
        ?string $pubDate = null,
        ?int    $categoryId = null,
        ?int    $sourceId = null
    )
    {
        $this->title = $title;
        $this->link = $link;
        $this->pubDate = $pubDate;
        $this->categoryId = $categoryId;
        $this->sourceId = $sourceId;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'] ?? null,
            link: $data['link'] ?? null,
            pubDate: $data['pubDate'] ?? null,
            categoryId: $data['category_id'] ?? null,
            sourceId: $data['source_id'] ?? null
        );
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function getPubDate(): ?string
    {
        return $this->pubDate;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function getSourceId(): ?int
    {
        return $this->sourceId;
    }
}