<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends WebTestCase
{

    public function test_success_on_secured_endpoint()
    {

        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail('test@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/api/v1/secured');

        $this->assertResponseIsSuccessful();
        $this->assertSame('{"GoodJob?":"Develito!"}', $client->getResponse()->getContent());
    }

    public function test_failed_when_call_secured_endpoint(): void
    {

        $client = static::createClient();

        $client->request('GET', '/api/v1/secured');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $this->assertSame('{"code":401,"message":"JWT Token not found"}', $client->getResponse()->getContent());
    }
}
