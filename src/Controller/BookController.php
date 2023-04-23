<?php

namespace App\Controller;

use App\Dto\BookReviewDto;
use App\Dto\CreateBookDto;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Service\BookReviewService;
use App\Service\BookService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/v1/books')]
class BookController extends AbstractController
{
    public function __construct(
        private readonly BookService         $bookService,
        private readonly BookReviewService   $bookReviewService,
        private readonly SerializerInterface $serializer
    )
    {
    }

    #[Route('/my', methods: ['GET'])]
    public function getMyBooks(): JsonResponse
    {
        $user = $this->getUser();
        $books = $this->bookService->findBooksByAuthor($user);

        $serializedBooks = $this->serializer->serialize($books, 'json', [
            'groups' => ['book:read'],
        ]);

        return new JsonResponse($serializedBooks, Response::HTTP_OK, [], true);
    }


    #[Route("/", name: "user_create_book", methods: ["POST"])]
    public function addBook(Request $request): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        //todo validate data from user
        $this->bookService->addBookToUser(CreateBookDto::fromRequest($request, $user));
        return new JsonResponse(['ok' => "book added successfully"], Response::HTTP_CREATED);
    }

    #[Route("/{id}", name: "update_book", methods: ["PATCH"])]
    public function updateBook(int $id, Request $request, BookRepository $bookRepository, EntityManagerInterface $em): JsonResponse
    {
        try {
            $jsonData = $request->getContent();
            $data = json_decode($jsonData, true);

            $book = $this->bookService->updateBook($id, $this->getUser(), $data);

            return new JsonResponse(['book' => $book]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

    }


    #[Route("/{id}", name: "delete_book", methods: ["DELETE"])]
    public function deleteBook(int $id, Request $request, BookService $bookService): JsonResponse
    {

        try {
            $bookService->deleteBookOwnByUser($id, $this->getUser());
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }


        return $this->json(null, Response::HTTP_OK);
    }

    //todo open this endpoint for everyone (turn off security)
    #[Route('/{id}', name: 'book_details', methods: ['GET'])]
    public function getDetails(int $id, BookService $bookService): JsonResponse
    {
        try {
            $bookDetails = $bookService->getBook($id);
            $serializedBook = $this->serializer->serialize($bookDetails, 'json', [
                'groups' => ['book:details:read'],
            ]);
            return new JsonResponse($serializedBook, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/list/{page}', name: 'book_index', methods: ['GET'])]
    public function index(int $page, BookRepository $bookRepository, Request $request): Response
    {

        $title = $request->request->get('title', '');
        $description = $request->request->get('description', '');

        $books = $this->bookService->findBooks($title, $description, $page);

        if (empty($books)) {
            return $this->json(['error' => 'No books found'], Response::HTTP_NOT_FOUND);
        }
        $serializedBooks = $this->serializer->serialize($books, 'json', [
            'groups' => ['book:read'],
        ]);

        return new JsonResponse($serializedBooks, Response::HTTP_OK, [], true);
    }

    #[Route('/book/{id}/reviews', name: 'add_book_review', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function addBookReview(int $id, Request $request, ValidatorInterface $validator): JsonResponse
    {


        $bookReviewDto = new BookReviewDto();
        $bookReviewDto->author = $request->request->get('author');
        $bookReviewDto->rating = (int)$request->request->get('rating');
        $bookReviewDto->description = $request->request->get('description');
        $bookReviewDto->email = $request->request->get('email');

        $result = $this->bookReviewService->addBookReview($id, $bookReviewDto, $validator);

        if ($result->isValid === false) {
            return new JsonResponse(['error' => implode(', ', $result->errors)], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['success' => true], Response::HTTP_CREATED);
    }

}
