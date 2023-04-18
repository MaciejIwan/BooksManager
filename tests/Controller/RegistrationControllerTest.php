<?php

namespace App\Tests\Controller;

use App\Controller\RegistrationController;
use App\Entity\User;
use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RegistrationControllerTest extends WebTestCase
{
    protected $client;
    protected $em;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine')->getManager();
        $this->em->getConnection()->beginTransaction();

    }

    protected function tearDown(): void
    {
        parent::tearDown();
        //$this->em->getConnection()->rollback();
    }


    public function testRegistrationSuccess(): void
    {


        $formData = [
                'firstName' => 'Maciej',
                'lastName' => 'Dev',
                'email' => 'maciej@test.com',
                'password' => 'Test123!',
                'repeatPassword' => 'Test123!'
        ];

        $this->client->request('POST', '/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($formData));

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function test_registration_should_failed_when_diffrent_passwords(): void
    {


        $formData = [
            'firstName' => 'Maciej',
            'lastName' => 'Dev',
            'email' => 'maciej@test.com',
            'password' => 'Hello@3sac',
            'repeatPassword' => 'Test123!'
        ];

        $this->client->request('POST', '/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($formData));
        $this->assertStringContainsString('The password fields must be the same', $this->client->getResponse()->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

}
