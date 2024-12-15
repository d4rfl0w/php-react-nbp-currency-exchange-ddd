<?php

declare(strict_types=1);

namespace App\Application\Validator;

use DateTime;
use InvalidArgumentException;

class DateValidator
{
    private const MIN_DATE = '2023-01-01';

    public function validate(DateTime $date): void
    {
        $today = new DateTime();
        $minDate = new DateTime(self::MIN_DATE);

        if ($date > $today) {
            throw new InvalidArgumentException('The date cannot be in the future.');
        }

        if ($date < $minDate) {
            throw new InvalidArgumentException('The date must be on or after ' . self::MIN_DATE . '.');
        }
    }
}
