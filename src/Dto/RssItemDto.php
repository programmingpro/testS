<?php

namespace App\Dto;
use Symfony\Component\Validator\Constraints as Assert;

class RssItemDto
{
    #[Assert\NotBlank(message: "Title cannot be blank.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Title cannot be longer than {{ limit }} characters."
    )]
    public string $title;

    #[Assert\NotBlank(message: "Link cannot be blank.")]
    #[Assert\Url(message: "Link must be a valid URL.")]
    public string $link;

    #[Assert\NotBlank(message: "Category cannot be blank.")]
    #[Assert\Length(
        max: 100,
        maxMessage: "Category cannot be longer than {{ limit }} characters."
    )]
    public string $category;

    #[Assert\NotBlank(message: "Publication date cannot be blank.")]
    #[Assert\DateTime(message: "Publication date must be a valid datetime.")]
    public string $pubDate;
}