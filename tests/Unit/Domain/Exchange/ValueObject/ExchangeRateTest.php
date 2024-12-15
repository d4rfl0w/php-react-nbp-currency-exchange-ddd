<?php

declare(strict_types=1);

namespace Tests\Unit\ValueObject;

use App\Domain\Exchange\ValueObject\ExchangeRate;
use DateTime;
use PHPUnit\Framework\TestCase;

class ExchangeRateTest extends TestCase
{
    private $exchangeRate;

    protected function setUp(): void
    {
        $this->exchangeRate = new ExchangeRate(
            'USD',
            'US Dollar',
            3.5,
            3.45,
            3.55,
            new DateTime('2023-01-01')
        );
    }

    public function testGetCurrency(): void
    {
        $this->assertEquals('USD', $this->exchangeRate->getCurrency());
    }

    public function testGetName(): void
    {
        $this->assertEquals('US Dollar', $this->exchangeRate->getName());
    }

    public function testGetNbpRate(): void
    {
        $this->assertEquals(3.5, $this->exchangeRate->getNbpRate());
    }

    public function testGetBuyRate(): void
    {
        $this->assertEquals(3.45, $this->exchangeRate->getBuyRate());
    }

    public function testGetSellRate(): void
    {
        $this->assertEquals(3.55, $this->exchangeRate->getSellRate());
    }

    public function testGetDate(): void
    {
        $this->assertEquals(new DateTime('2023-01-01'), $this->exchangeRate->getDate());
    }

    public function testJsonSerialize(): void
    {
        $expected = [
            'currency' => 'USD',
            'name' => 'US Dollar',
            'nbpRate' => 3.5,
            'buyRate' => 3.45,
            'sellRate' => 3.55,
            'date' => '2023-01-01'
        ];

        $this->assertEquals($expected, $this->exchangeRate->jsonSerialize());
    }
}
