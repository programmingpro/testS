<?php

namespace App\Entity;

use App\Repository\SourceRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SourceRepository::class)]
#[ORM\Table(name: 'source')]
class Source
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $url;

    #[ORM\OneToMany(targetEntity: News::class, mappedBy: 'source')]
    private Collection $news;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getNews(): Collection
    {
        return $this->news;
    }

    public function setNews(Collection $news): void
    {
        $this->news = $news;
    }

    public function getNewsCount(): int
    {
        return $this->news->count();
    }
}
