<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\BookReview;
use App\Entity\User;
use App\Enum\BookReviewStars;
use App\Repository\BookRepository;
use App\Repository\BookReviewRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
class AuthorFixtures extends Fixture
{
    public function __construct(
        public readonly BookRepository $bookRepository,
        public readonly UserRepository $userRepository,
        public readonly BookReviewRepository $bookReviewRepository
    )
    {
    }
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setEmail('test@test.com');
        $user->setPassword('$2y$13$4BUvmN.6oMMH4PrS7d37OuJX.gXNlGe1CvBS2ClobCRBZWz.UlFGe'); // 1234
        $user->setRoles(['ROLE_USER', 'ROLE_AUTHOR']);

        $this->userRepository->save($user, true);

        $user2 = new User();
        $user2->setFirstName('John');
        $user2->setLastName('Doe');
        $user2->setEmail('test2@test.com');
        $user2->setPassword('$2y$13$4BUvmN.6oMMH4PrS7d37OuJX.gXNlGe1CvBS2ClobCRBZWz.UlFGe'); // 1234
        $user2->setRoles(['ROLE_USER', 'ROLE_AUTHOR']);
        $this->userRepository->save($user2, true);

        $user3 = new User();
        $user3->setFirstName('Kamil');
        $user3->setLastName('Dostojewski');
        $user3->setEmail('noroles@test.com');
        $user3->setPassword('$2y$13$4BUvmN.6oMMH4PrS7d37OuJX.gXNlGe1CvBS2ClobCRBZWz.UlFGe'); // 1234
        $user3->setRoles([]);

        $this->userRepository->save($user3, true);

        $book1 = new Book();
        $book1->setTitle('The Book without reviews');
        $book1->setAuthor($user);
        $book1->setIsbn('1234567890123');
        $book1->setDescription('This is a book description.');
        $this->bookRepository->save($book1, true);

        $book2 = new Book();
        $book2->setTitle('The Book with reviews');
        $book2->setAuthor($user);
        $book2->setIsbn('1234567890124');
        $book2->setDescription('This is a book description.');

        $review = new BookReview();
        $review->setAuthor("Tomek123");
        $review->setRating(BookReviewStars::EIGHT);
        $review->setDescription("This is a review description.");
        $review->setEmail("email");
        $book2->addReview($review);
        $this->bookRepository->save($book2, true);

        //bulk book creation
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $book = new Book();
            $book->setTitle($faker->sentence(3));
            $book->setAuthor($user3);
            $book->setIsbn($faker->isbn13());
            $book->setDescription($faker->paragraph(3));
            $manager->persist($book);
        }

        $manager->flush();

    }
}
