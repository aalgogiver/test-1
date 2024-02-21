<?php

namespace App\Payment;

use App\Calculator\PriceCalculatorInterface;
use App\Dto\PaymentPurchaseRequest;

class PaymentHandler
{
    public function __construct(
        private readonly PriceCalculatorInterface $priceCalculator,
        private readonly PaymentProcessorRegistry $paymentProcessorRegistry,
    ) {
    }

    public function handle(PaymentPurchaseRequest $paymentPurchaseRequest): void
    {
        $price = $this->priceCalculator->calculatePrice($paymentPurchaseRequest);

        $this->paymentProcessorRegistry
            ->getProcessor($paymentPurchaseRequest->getPaymentProcessor())
            ->process($price);
    }
}
