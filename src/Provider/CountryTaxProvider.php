<?php

namespace App\Provider;

use App\Exception\InvalidArgumentException;

class CountryTaxProvider
{
    private static array $taxPercentages = [
        'DE' => 19.00,
        'IT' => 22.00,
        'FR' => 20.00,
        'GR' => 24.00,
    ];

    public function getTaxPercentageByTaxNumber(string $taxNumber): float
    {
        $countryCode = substr($taxNumber, 0, 2);

        if (!isset(self::$taxPercentages[$countryCode])) {
            throw new InvalidArgumentException(
                sprintf('Could not find tax percentage by tax number "%s"', $taxNumber)
            );
        }

        return self::$taxPercentages[$countryCode];
    }
}
