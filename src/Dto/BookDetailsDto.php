<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Book;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;


class BookDetailsDto
{
    public function __construct(
        public int $id,
        public string $title,
        public User $author,
        public string $description,
        public string $isbn,
        public \DateTime $createdAt,
        public Collection $reviews
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
