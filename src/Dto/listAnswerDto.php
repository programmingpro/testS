<?php

namespace App\Dto;

class listAnswerDto
{
    public function __construct(
        public int $page,
        public int $limit,
        public int $total,
        public array $items
    ) {}

    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'limit' => $this->limit,
            'total' => $this->total,
            'items' => array_map(fn(ItemAnswerDto $item) => $item->toArray(), $this->items),
        ];
    }
}