<?php

namespace App\Controller;

use App\Dto\RegisterUserData;
use App\Exception\EmailTakenException;
use App\Security\AppCustomAuthenticator;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request                    $request,

        UserAuthenticatorInterface $userAuthenticator,
        AppCustomAuthenticator     $authenticator,
        UserService                $userService,
        ValidatorInterface         $validator,
        SerializerInterface        $serializer
    ): Response
    {

        $json = $request->getContent();
        $registerUserData = $serializer->deserialize($json, RegisterUserData::class, 'json');

        $errors = $validator->validate($registerUserData);

        if (count($errors) > 0) {
            $errorsString = (string)$errors;

            return new JsonResponse([
                'errors' => $errorsString,
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $user = $userService->addUser($registerUserData);
            return new JsonResponse(['message' => 'User registered successfully'], Response::HTTP_CREATED);
        } catch (EmailTakenException $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_CONFLICT);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Unknown error', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
