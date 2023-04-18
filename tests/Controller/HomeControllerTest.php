<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends WebTestCase
{

    public function testSecured()
    {

        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail('test@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/api/v1/secured');

        // Assert that the response is successful and contains the expected JSON message
        $this->assertResponseIsSuccessful();
        $this->assertSame('{"GoodJob?":"Develito!"}', $client->getResponse()->getContent());
    }
}
