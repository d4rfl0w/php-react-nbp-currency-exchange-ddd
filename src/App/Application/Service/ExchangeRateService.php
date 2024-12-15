<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Validator\DateValidator;
use App\Domain\Exchange\ValueObject\ExchangeRate;
use App\Infrastructure\Service\NbpApiClient;
use DateTime;
use RuntimeException;

class ExchangeRateService
{
    private $nbpApiClient;
    private $dateValidator;
    private const SPECIAL_RATES = ['EUR', 'USD'];
    private const COMMON_RATES = ['EUR', 'USD', 'CZK', 'IDR', 'BRL'];
    private const SELL_RATE_FACTOR = 0.15;
    private const SPECIAL_BUY_FACTOR = 0.05;
    private const SPECIAL_SELL_FACTOR = 0.07;

    public function __construct(NbpApiClient $nbpApiClient, DateValidator $dateValidator)
    {
        $this->nbpApiClient = $nbpApiClient;
        $this->dateValidator = $dateValidator;
    }

    public function getExchangeRates(DateTime $date): array
    {
        $this->dateValidator->validate($date);
        $nbpRates = $this->nbpApiClient->fetchExchangeRates($date);

        if (empty($nbpRates) || !isset($nbpRates[0]['rates'])) {
            throw new RuntimeException('No exchange rates data available for the given date.');
        }

        return $this->processRates($nbpRates[0]['rates'], $date);
    }

    private function processRates(array $rates, DateTime $date): array
    {
        $exchangeRates = [];

        foreach ($rates as $rate) {
            if (in_array($rate['code'], self::COMMON_RATES)) {
                // Calculate buy and sell rates based on currency code and mid rate.
                [$buyRate, $sellRate] = $this->calculateRates($rate['code'], $rate['mid']);

                // Create a new ExchangeRate object.
                $exchangeRate = new ExchangeRate(
                    $rate['code'],
                    $rate['currency'],
                    $rate['mid'],
                    $buyRate,
                    $sellRate,
                    $date
                );

                // Convert ExchangeRate object to array and add to the result.
                $exchangeRates[] = $exchangeRate;
            }
        }

        return $exchangeRates;
    }

    private function calculateRates(string $code, float $mid): array
    {
        $buyRate = null;
        $sellRate = $mid + self::SELL_RATE_FACTOR;

        if (in_array($code, self::SPECIAL_RATES)) {
            $buyRate = $mid - self::SPECIAL_BUY_FACTOR;
            $sellRate = $mid + self::SPECIAL_SELL_FACTOR;
        }

        return [$buyRate, $sellRate];
    }
}
