<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\BookReviewDto;
use App\Dto\ValidationResult;
use App\Entity\BookReview;
use App\Enum\BookReviewStars;
use App\Repository\BookReviewRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookReviewService
{
    public function __construct(
        private readonly BookReviewRepository $bookReviewRepository,
        private readonly BookService          $bookService
    )
    {
    }

    public function addBookReview(int $bookId, BookReviewDto $bookReviewDto, ValidatorInterface $validator): ValidationResult
    {
        $book = $this->bookService->findBookById($bookId);

        $bookReview = new BookReview();
        $bookReview->setAuthor($bookReviewDto->author);
        $bookReview->setRating(BookReviewStars::from($bookReviewDto->rating));
        $bookReview->setDescription($bookReviewDto->description);
        $bookReview->setEmail($bookReviewDto->email);

        $book->addReview($bookReview);

        $errors = $validator->validate($bookReview);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new ValidationResult(false, $errorMessages);
        }

        $this->bookReviewRepository->save($bookReview, true);

        return new ValidationResult(true, []);
    }

}
