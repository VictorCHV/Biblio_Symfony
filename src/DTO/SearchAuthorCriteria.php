<?php

declare(strict_types=1);

namespace App\DTO;

class SearchAuthorCriteria
{
    public ?string $name= '';
    public ?string $orderBy='id';
    public ?string $direction='DESC';
    public ?int $limit = 20;
    public ?int $page = 1;
}