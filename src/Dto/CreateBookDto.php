<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class CreateBookDto
{
    public function __construct(
        public string    $title,
        public User      $author,
        public string    $description,
        public string    $isbn,
        public \DateTime $createdAt,
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
