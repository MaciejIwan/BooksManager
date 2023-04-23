<?php

declare(strict_types=1);

namespace App\Dto;

class UpdateBookDto
{
    public function __construct(
        public readonly string    $title,
        public readonly string    $description
    )
    {
    }
}
