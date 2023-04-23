<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Book;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;


class BookDetailsDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly User $author,
        public readonly string $description,
        public readonly string $isbn,
        public readonly \DateTime $createdAt,
        public readonly Collection $reviews
    )
    {
    }

    public static function fromEntity(Book $book): BookDetailsDto
    {
        return new static(
            $book->getId(),
            $book->getTitle(),
            $book->getAuthor(),
            $book->getDescription(),
            $book->getISBN(),
            $book->getCreatedAt(),
            $book->getReviews()
        );
    }
}
