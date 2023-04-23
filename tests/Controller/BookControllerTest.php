<?php

namespace App\Tests\Controller;

use App\Repository\BookRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class BookControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private SerializerInterface $serializer;
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->serializer = static::getContainer()->get(SerializerInterface::class);
    }

    public function test_success_on_get_my_books_endpoint()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $bookRepository = static::getContainer()->get(BookRepository::class);

        $books = $bookRepository->findAll();
        $testUser = $userRepository->findOneByEmail('test@test.com');

        $expectedResponse = $this->serializer->serialize($books, 'json', [
            'groups' => ['book:read'],
        ]);

        $this->client->loginUser($testUser);
        $this->client->request('GET', '/api/v1/book/my');


        $actualResponse = $this->client->getResponse()->getContent();
        $this->assertTrue(sizeof($books) > 0);
        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonString($expectedResponse,$actualResponse);
    }

    public function test_it_should_add_new_book()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $bookRepository = static::getContainer()->get(BookRepository::class);
        $bookData = [
            'title' => 'TEST BOOK HELLO',
            'description' => 'This is an example book from test',
            'isbn' => '1234567890'
        ];

        $testUser = $userRepository->findOneByEmail('test@test.com');
        $this->client->loginUser($testUser);


        $this->client->request('POST', '/api/v1/book/', $bookData);


        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $book = $bookRepository->findOneBy(['title' => 'TEST BOOK HELLO']);
        $this->assertNotNull($book);

    }


}
