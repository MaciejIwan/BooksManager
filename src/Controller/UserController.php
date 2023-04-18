<?php

namespace App\Controller;

use App\Dto\RegisterUserData;
use App\Entity\User;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


;
class UserController extends AbstractController
{
//    /**
//     * @Route("/register", name="register", methods={"POST"})
//     */
//    public function register(
//        Request                $request,
//        EntityManagerInterface $entityManager,
//        UserService            $userService
//    ): Response
//    {
//        $userData = new RegisterUserData();
//        $form = $this->createForm(RegisterUserData::class, $userData);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $email = $form->getEmail();
//
//            // Check if email is not taken
//            $existingUser = $userService->isEmailTaken($email);
//
//            if ($existingUser) {
//                $this->addFlash('error', 'This email address is already registered.');
//
//                return $this->redirectToRoute('register');
//            }
//
//            // Create new user entity
//            $user = new User();
//            $user->setEmail($email);
//            $user->setFirstName($form->getFirstName());
//            $user->setLastName($form->getLastName());
//            $user->setRoles(['ROLE_AUTHOR']);
//            //$user->setPassword($passwordEncoder); //todo password encoder
//
//            $entityManager->persist($user);
//            $entityManager->flush();
//
//            return $this->json(['message' => 'User registered successfully']);
//        }
//
//        return $this->json(['message' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
//    }
//
//    /**
//     * @Route("/login", name="login", methods={"POST"})
//     */
//    public function login(Request $request, UserService $userService): Response
//    {
//        $email = $request->request->get('email');
//        $password = $request->request->get('password');
//
//        if (!$email || !$password) {
//            return $this->json(['message' => 'Email and password are required'], Response::HTTP_BAD_REQUEST);
//        }
//
//        $token = $userService->login($email, $password);
//
//        if (!$token) {
//            return $this->json(['message' => 'Invalid email or password'], Response::HTTP_UNAUTHORIZED);
//        }
//
//        return $this->json(['token' => $token]);
//    }
}
