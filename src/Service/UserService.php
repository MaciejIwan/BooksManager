<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\RegisterUserData;
use App\Entity\User;
use App\Exception\EmailTakenException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        EntityManagerInterface      $entityManager,
        UserRepository              $userRepository,
        UserPasswordHasherInterface $userPasswordHasher,
    )
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;

    }

    public function isEmailTaken($email): bool
    {
        return $this->userRepository->findOneBy(['email' => $email]) !== null;
    }

    public function addUser(RegisterUserData $userData): User
    {
        if ($this->isEmailTaken($userData->getEmail())) {
            throw new EmailTakenException('This email address is already registered.');
        }

        $user = new User();
        $user->setFirstName($userData->getFirstName());
        $user->setLastName($userData->getLastName());
        $user->setEmail($userData->getEmail());
        $user->setRoles(['ROLE_USER']);

        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $userData->getPassword()
            )
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }
}
