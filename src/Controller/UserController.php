<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api/v1/user')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService)
    {
    }


    #[Route('/my-books', name: 'user_books', methods: ["GET"])]
    public function index(): JsonResponse
    {
        $user = $this->getUser();
        $books = $user->getBooks();
        return $this->json($books);
    }

}
