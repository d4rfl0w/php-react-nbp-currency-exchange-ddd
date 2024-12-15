<?php

namespace App\Tests\Functional\UI\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SetupCheckTest extends WebTestCase
{
    public function testConnectivity(): void
    {
        $client = static::createClient();

        // test e.g. the profile page
        $client->request('GET', '/api/setup-check');
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), TRUE);
        $this->assertArrayHasKey('testParam', $responseData);
    }


}