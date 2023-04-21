<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('api/v1/secured', name: 'secured')]
    public function secured(): Response
    {
        $message = [
            'GoodJob?' => 'Develito!',
        ];
        return $this->json($message);
    }

    #[Route('/api/v1/book/my', name: 'user_books', methods: ['GET'])]
    public function getMyBooks(): JsonResponse
    {
        echo "debug 1 ";
        $user = $this->getUser();
        echo "debug 1 ";
        $books = $user->getBooks();

        return $this->json($books);
    }
}
