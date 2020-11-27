<?php

namespace App\tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokerTest extends WebTestCase
{
    public function testApiEndpoint()
    {
        $client = static::createClient([],[
            'HTTP_HOST' => 'localhost:8080',

        ]);
        $crawler = $client->request('GET','/api/itineraries');
        $this->assertResponseIsSuccessful();

    }

}