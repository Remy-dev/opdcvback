<?php

namespace App\tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer\SerializerInterface;

class SecurityControllerTest extends WebTestCase
{
    protected $credentials = [
        'username' => 'James',
        'password' => 'dude',
        'email' => 'dude@gmail.com',
        'producer' => true
    ];
    private $serializer;

    public function testRegister()
    {
        $client = static::createClient();
        self::bootKernel();
        $this->serializer = static::$kernel->getContainer()->get('serializer');
        $payload = $this->serializer->serialize($this->credentials, 'json');
        $client->request('POST', '/register', [], [],['HTTP_ACCEPT' => 'application/json'], $payload );
        $this->assertResponseIsSuccessful();
    }
}
