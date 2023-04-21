<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\BookDetailsDto;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class BookService
{
    public function __construct(
        private readonly BookRepository $bookRepository
    )
    {
    }

    public function getBookDetails(int $id): BookDetailsDto
    {
        $book = $this->bookRepository->findOneBy([
            'id' => $id,
        ]);

        if(!$book) {
            throw new \Exception('Book not found', Response::HTTP_NOT_FOUND);
        }

        return BookDetailsDto::fromEntity($book);
    }

    public function deleteUserBook(int $id, UserInterface $user)
    {
        $book = $this->bookRepository->findOneBy([
            'id' => $id,
            'author' => $user,
        ]);

        if (!$book) {
            throw new EntityNotFoundException('Book not found or you are not the author.');
        }

        if ($book->getReviews()->count() > 0) {
            throw new \Exception('Book has reviews.');
        }

        $this->bookRepository->remove($book, true);

    }
}
