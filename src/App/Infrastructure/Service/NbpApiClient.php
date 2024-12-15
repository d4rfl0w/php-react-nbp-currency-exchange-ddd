<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use DateTime;
use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NbpApiClient
{
    private $client;
    private const BASE_URL = "https://api.nbp.pl/api/exchangerates/tables/A/";

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetchExchangeRates(DateTime $date): array
    {
        $url = $this->getUrl($date);
        try {
            // Send GET request to the NBP API
            $response = $this->client->request('GET', $url);
            // Return the response as an array
            return $response->toArray();
        } catch (Exception $e) {
            // Log the error and return an error message array
            return ['error' => $e->getMessage()];
        }
    }

    private function getUrl(DateTime $date): string
    {
        // Format the date and append it to the base URL
        return self::BASE_URL . $date->format('Y-m-d') . '/?format=json';
    }
}
