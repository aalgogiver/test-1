<?php

namespace App\Calculator;

use App\Dto\PurchaseRequest;
use App\Provider\CountryTaxProvider;

class TaxCalculator implements PriceCalculatorInterface
{
    public function __construct(
        public readonly PriceCalculatorInterface $priceCalculator,
        public readonly CountryTaxProvider $countryTaxProvider
    ) {
    }

    public function calculatePrice(PurchaseRequest $purchaseRequest): float
    {
        $price = $this->priceCalculator->calculatePrice($purchaseRequest);
        $taxPercentage = $this->countryTaxProvider->getTaxPercentageByTaxNumber($purchaseRequest->getTaxNumber());

        return $price * (100 + $taxPercentage) / 100;
    }
}
