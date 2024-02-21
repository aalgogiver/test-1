<?php

namespace App\Calculator;

use App\Dto\PurchaseRequest;

interface PriceCalculatorInterface
{
    public function calculatePrice(PurchaseRequest $purchaseRequest): float;
}