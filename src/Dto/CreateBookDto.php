<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class CreateBookDto
{
    public function __construct(
        public readonly string    $title,
        public readonly User      $author,
        public readonly string    $description,
        public readonly string    $isbn,
        public readonly \DateTime $createdAt,
    )
    {
    }

    public static function fromRequest(Request $request, User $user): CreateBookDto
    {
        return new static(
            $request->request->get('title'),
            $user,
            $request->request->get('description'),
            $request->request->get('isbn'),
            new \DateTime()
        );
    }
}
