<?php

namespace App\Tests\Functional\UI\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRatesControllerTest extends WebTestCase
{
    public function testShowExchangeRatesPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/exchange-rates');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'NBP - Exchange Rates');
    }

    public function testGetExchangeRates(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/exchange-rates/2024-10-01');

        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }
}

