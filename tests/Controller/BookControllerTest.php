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
    private UserRepository $userRepository;
    private BookRepository $bookRepository;

    protected function setUp(): void
    {

        $this->client = static::createClient();
        $this->serializer = static::getContainer()->get(SerializerInterface::class);
        $this->userRepository = static::getContainer()->get(UserRepository::class);
        $this->bookRepository = static::getContainer()->get(BookRepository::class);

        parent::setUp();
    }

    public function test_success_on_get_my_books_endpoint()
    {

        $books = $this->bookRepository->findAll();
        $testUser = $this->userRepository->findOneByEmail('test@test.com');

        $expectedResponse = $this->serializer->serialize($books, 'json', [
            'groups' => ['book:read'],
        ]);

        $this->client->loginUser($testUser);
        $this->client->request('GET', '/api/v1/book/my');


        $actualResponse = $this->client->getResponse()->getContent();
        $this->assertTrue(sizeof($books) > 0);
        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonString($expectedResponse, $actualResponse);
    }

    public function test_it_should_add_new_book()
    {
        $bookData = [
            'title' => 'TEST BOOK HELLO',
            'description' => 'This is an example book from test',
            'isbn' => '1234567890'
        ];

        $testUser = $this->userRepository->findOneByEmail('test@test.com');
        $this->client->loginUser($testUser);


        $this->client->request('POST', '/api/v1/book/', $bookData);


        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $book = $this->bookRepository->findOneBy(['title' => 'TEST BOOK HELLO']);
        $this->assertNotNull($book);
        $this->assertEquals($book->getTitle(), $bookData['title']);
        $this->assertEquals($book->getDescription(), $bookData['description']);
        $this->assertEquals($book->getIsbn(), $bookData['isbn']);
        $this->assertEquals($book->getAuthor(), $testUser);

    }

    public function test_it_should_edit_book_title()
    {

        $oldTitle = 'The Book without reviews';
        $newTitle = 'New Title';
        $book = $this->bookRepository->findOneBy(['title' => $oldTitle]);

        $testUser = $this->userRepository->findOneByEmail('test@test.com');
        $this->client->loginUser($testUser);


        $this->client->request('PATCH', '/api/v1/book/' . $book->getId(), [], [], [], json_encode([
            'title' => $newTitle,
        ]));


        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $book2 = $this->bookRepository->findOneBy(['title' => $newTitle]);
        $this->assertNotNull($book2);
        $this->assertEquals($book->getId(), $book2->getId());
    }

    public function test_it_should_edit_book_description()
    {

        $oldDescription = 'This is a book description.';
        $newDescription = 'This is an example book from test with new description';

        $book = $this->bookRepository->findOneBy(['description' => $oldDescription]);
        $testUser = $this->userRepository->findOneByEmail('test@test.com');
        $this->client->loginUser($testUser);


        $this->client->request('PATCH', '/api/v1/book/' . $book->getId(), [], [], [], json_encode([
            'description' => $newDescription,
        ]));


        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $book2 = $this->bookRepository->findOneBy(['description' => $newDescription]);
        $this->assertNotNull($book2);
        $this->assertEquals($book->getId(), $book2->getId());

    }

    public function test_it_should_not_allow_to_edit_book()
    {
        $oldDescription = 'This is a book description.';

        $book = $this->bookRepository->findOneBy(['description' => $oldDescription]);
        $testUser = $this->userRepository->findOneByEmail('test2@test.com');
        $this->client->loginUser($testUser);


        $this->client->request('PATCH', '/api/v1/book/' . $book->getId(), [], [], [], json_encode([
            'description' => "bad",
        ]));


        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        $book2 = $this->bookRepository->findOneBy(['description' => $oldDescription]);
        $this->assertNotNull($book2);
        $this->assertEquals($book->getUpdatedAt(), $book2->getUpdatedAt());
        $this->assertEquals($book->getDescription(), $book2->getDescription());

    }

    public function test_it_should_delete_caller_book()
    {
        $bookTitle = 'The Book without reviews';

        $book = $this->bookRepository->findOneBy(['title' => $bookTitle]);
        $testUser = $this->userRepository->findOneByEmail('test@test.com');
        $this->client->loginUser($testUser);

        $this->client->request('DELETE', '/api/v1/book/' . $book->getId());

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertNull($this->bookRepository->findOneBy(['title' => $bookTitle]));
    }

    public function test_it_should_failed_when_not_onwer_delete_book()
    {
        $bookTitle = 'The Book without reviews';

        $book = $this->bookRepository->findOneBy(['title' => $bookTitle]);
        $testUser = $this->userRepository->findOneByEmail('test2@test.com');
        $this->client->loginUser($testUser);

        $this->client->request('DELETE', '/api/v1/book/' . $book->getId());

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertNotNull($this->bookRepository->findOneBy(['title' => $bookTitle]));
    }

}
