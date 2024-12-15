<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Application\Service\ExchangeRateService;
use App\Application\Validator\DateValidator;
use App\Domain\Exchange\ValueObject\ExchangeRate;
use App\Infrastructure\Service\NbpApiClient;
use PHPUnit\Framework\TestCase;
use DateTime;

class ExchangeRateServiceTest extends TestCase
{
    private $nbpApiClient;
    private $dateValidator;
    private $exchangeRateService;

    protected function setUp(): void
    {
        $this->nbpApiClient = $this->createMock(NbpApiClient::class);
        $this->dateValidator = $this->createMock(DateValidator::class);
        $this->exchangeRateService = new ExchangeRateService($this->nbpApiClient, $this->dateValidator);
    }

    public function testGetExchangeRatesSuccess(): void
    {
        $date = new DateTime('2023-01-01');

        // Set up the mock expectations
        $this->dateValidator->expects($this->once())
            ->method('validate')
            ->with($date);

        $nbpRates = [
            [
                'rates' => [
                    ['code' => 'USD', 'currency' => 'US Dollar', 'mid' => 3.5],
                    ['code' => 'EUR', 'currency' => 'Euro', 'mid' => 4.0],
                    ['code' => 'CZK', 'currency' => 'Czech Koruna', 'mid' => 0.15]
                ]
            ]
        ];

        $this->nbpApiClient->expects($this->once())
            ->method('fetchExchangeRates')
            ->with($date)
            ->willReturn($nbpRates);

        $exchangeRates = $this->exchangeRateService->getExchangeRates($date);

        $this->assertIsArray($exchangeRates);
        $this->assertCount(3, $exchangeRates);

        // Check the first exchange rate
        $firstRate = $exchangeRates[0];
        $this->assertInstanceOf(ExchangeRate::class, $firstRate);
        $this->assertSame('USD', $firstRate->getCurrency());
        $this->assertSame('US Dollar', $firstRate->getName());
        $this->assertSame(3.5, $firstRate->getNbpRate());
        $this->assertSame(3.45, $firstRate->getBuyRate());
        $this->assertSame(3.57, $firstRate->getSellRate());
        $this->assertEquals(new DateTime('2023-01-01'), $firstRate->getDate());
    }

    public function testGetExchangeRatesInvalidDate(): void
    {
        $date = new DateTime('3000-01-01');

        $this->dateValidator->expects($this->once())
            ->method('validate')
            ->with($date)
            ->willThrowException(new \InvalidArgumentException('The date cannot be in the future.'));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The date cannot be in the future.');

        $this->exchangeRateService->getExchangeRates($date);
    }

    public function testGetExchangeRatesNoData(): void
    {
        $date = new DateTime('2023-01-01');

        $this->dateValidator->expects($this->once())
            ->method('validate')
            ->with($date);

        $this->nbpApiClient->expects($this->once())
            ->method('fetchExchangeRates')
            ->with($date)
            ->willReturn([]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No exchange rates data available for the given date.');

        $this->exchangeRateService->getExchangeRates($date);
    }

    public function testJsonSerialize(): void
    {
        $exchangeRate = new ExchangeRate(
            'USD',
            'US Dollar',
            3.5,
            3.45,
            3.57,
            new DateTime('2023-01-01')
        );

        $exchangeRateArray = $exchangeRate->jsonSerialize();

        $this->assertIsArray($exchangeRateArray);
        $this->assertCount(6, $exchangeRateArray);
        $this->assertEquals('USD', $exchangeRateArray['currency']);
        $this->assertEquals('US Dollar', $exchangeRateArray['name']);
        $this->assertEquals(3.5, $exchangeRateArray['nbpRate']);
        $this->assertEquals(3.45, $exchangeRateArray['buyRate']);
        $this->assertEquals(3.57, $exchangeRateArray['sellRate']);
        $this->assertEquals('2023-01-01', $exchangeRateArray['date']);
    }
}
