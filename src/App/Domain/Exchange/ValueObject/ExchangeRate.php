<?php

declare(strict_types=1);

namespace App\Domain\Exchange\ValueObject;

use DateTime;
use JsonSerializable;

class ExchangeRate implements JsonSerializable
{
    private $currency;
    private $name;
    private $nbpRate;
    private $buyRate;
    private $sellRate;
    private $date;

    public function __construct(string $currency, string $name, float $nbpRate, ?float $buyRate, float $sellRate, DateTime $date)
    {
        $this->currency = $currency;
        $this->name = $name;
        $this->nbpRate = $nbpRate;
        $this->buyRate = $buyRate;
        $this->sellRate = $sellRate;
        $this->date = $date;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNbpRate(): float
    {
        return $this->nbpRate;
    }

    public function getBuyRate(): ?float
    {
        return $this->buyRate;
    }

    public function getSellRate(): float
    {
        return $this->sellRate;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function jsonSerialize()
    {
        return [
            'currency' => $this->currency,
            'name' => $this->name,
            'nbpRate' => $this->nbpRate,
            'buyRate' => $this->buyRate,
            'sellRate' => $this->sellRate,
            'date' => $this->date->format('Y-m-d')
        ];
    }
}
