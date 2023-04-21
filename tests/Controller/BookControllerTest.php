<?php

namespace App\Tests\Controller;

use App\Repository\BookRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function test_success_on_get_my_books_endpoint()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $bookRepository = static::getContainer()->get(BookRepository::class);
        $books = $bookRepository->findAll();
        echo sizeof($books);

        $testUser = $userRepository->findOneByEmail('test@test.com');

        $this->client->loginUser($testUser);
        $this->client->request('GET', '/api/v1/book/my');

        $res = $this->client->getResponse()->getContent();
        echo $res;
        $this->assertResponseIsSuccessful();
    }


}
