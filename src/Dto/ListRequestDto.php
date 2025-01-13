<?php

namespace App\Dto;


use Symfony\Component\Validator\Constraints as Assert;

class ListRequestDto
{
    #[Assert\Date]
    #[Assert\NotBlank]
    private ?string $startDate;

    #[Assert\Date]
    #[Assert\NotBlank]
    private ?string $endDate;

    #[Assert\Type("integer")]
    #[Assert\GreaterThanOrEqual(1)]
    private ?int $page = 1;

    #[Assert\Type("integer")]
    #[Assert\GreaterThanOrEqual(1)]
    private ?int $limit = 10;

    public function __construct(
        ?string $startDate = null,
        ?string $endDate = null,
        ?int $page = 1,
        ?int $limit = 10
    ) {
        $this->startDate = $startDate ?? date('Y-m-d', strtotime('-1 day'));
        $this->endDate = $endDate ?? date('Y-m-d');
        $this->page = $page;
        $this->limit = $limit;
    }

    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            startDate: $data['start_date'] ?? null,
            endDate: $data['end_date'] ?? null,
            page: isset($data['page']) ? (int) $data['page'] : 1,
            limit: isset($data['limit']) ? (int) $data['limit'] : 10
        );
    }
}

