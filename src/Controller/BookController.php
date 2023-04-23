<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Service\BookService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v1/book')]
class BookController extends AbstractController
{
    public function __construct(
        private readonly BookService $bookService,
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
    public function addBook(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        //todo validate data from user
        $title = $request->request->get('title');
        $description = $request->request->get('description');
        $isbn = $request->request->get('isbn');

        $book = new Book();
        $book->setTitle($title);
        $book->setDescription($description);
        $book->setAuthor($user);
        $book->setISBN($isbn);

        $entityManager->persist($book);
        $entityManager->flush();

        return new JsonResponse(['book' => $book], Response::HTTP_CREATED);
    }

    #[Route("/{id}", name: "update_book", methods: ["PATCH"])]
    public function updateBook(int $id, Request $request, BookRepository $bookRepository, EntityManagerInterface $em): JsonResponse
    {
        $book = $bookRepository->findOneBy([
            'id' => $id,
            'user' => $this->getUser(),
        ]);

        if (!$book) {
            return $this->json(['error' => 'Book not found.'], Response::HTTP_NOT_FOUND);
        }

        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);

        //todo validate data from user / consider creating DTO
        if (isset($data['title'])) {
            $title = $data['title'];
            $book->setTitle($title);
        }

        if (isset($data['description'])) {
            $description = $data['description'];
            $book->setDescription($description);
        }

        $em->flush();

        return new JsonResponse(['book' => $book]);
    }


    #[Route("/{id}", name: "delete_book", methods: ["DELETE"])]
    public function deleteBook(int $id, Request $request, BookService $bookService): JsonResponse
    {

        try {
            $bookService->deleteUserBook($id, $this->getUser());
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }


        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    //todo open this endpoint for everyone (turn off security)
    #[Route('/details/{id}', name: 'book_details', methods: ['GET'])]
    public function getDetails(int $id, BookService $bookService): JsonResponse
    {
        try {
            $bookDetails = $bookService->getBookDetails($id);
            return $this->json($bookDetails);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}