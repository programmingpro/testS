<?php

namespace App\Interfaces;

use App\Dto\RssItemDto;

interface RssMapperInterface
{
    public function mapXmlToDto(\SimpleXMLElement $item): RssItemDto;
}