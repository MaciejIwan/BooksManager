<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\BookDetailsDto;
use App\Dto\CreateBookDto;
use App\Entity\Book;
use App\Entity\User;
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

    public function getBook(int $id): Book
    {
        $book = $this->bookRepository->findOneBy([
            'id' => $id,
        ]);

        if(!$book) {
            throw new \Exception('Book not found', Response::HTTP_NOT_FOUND);
        }

        return $book;
    }

    public function deleteBookOwnByUser(int $id, UserInterface $user)
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

    public function findBooksByAuthor(User $user): array
    {
        return $this->bookRepository->findBy([
            'author' => $user,
        ]);
    }

    public function addBookToUser(CreateBookDto $fromRequest)
    {
        $book = new Book();
        $book->setTitle($fromRequest->title);
        $book->setDescription($fromRequest->description);
        $book->setAuthor($fromRequest->author);
        $book->setISBN($fromRequest->isbn);

        $this->bookRepository->save($book, true);
    }

    public function findBookById(int $bookId)
    {
        $book = $this->bookRepository->findOneBy([
            'id' => $bookId,
        ]);

        if (!$book) {
            throw new EntityNotFoundException('Book not found.');
        }

        return $book;
    }


}
