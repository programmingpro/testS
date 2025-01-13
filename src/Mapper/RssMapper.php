<?php

namespace App\Mapper;

use App\Dto\RssItemDto;
use App\Interfaces\RssMapperInterface;

class RssMapper implements RssMapperInterface
{
    public function mapXmlToDto(\SimpleXMLElement $item): RssItemDto
    {
        $dto = new RssItemDto();
        $dto->title = (string)$item->title;
        $dto->link = (string)$item->link;
        $dto->category = (string)$item->category;
        try {
            $pubDate = new \DateTime((string)$item->pubDate);
            $dto->pubDate = $pubDate->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            $dto->pubDate = (string)$item->pubDate;
        }

        return $dto;
    }

}