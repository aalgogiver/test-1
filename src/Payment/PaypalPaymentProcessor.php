<?php

namespace App\Payment;

use App\Exception\PaymentException;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor as ExternalPaypalPaymentProcessor;

class PaypalPaymentProcessor implements PaymentProcessorInterface
{
    public function __construct(private readonly ExternalPaypalPaymentProcessor $paypalPaymentProcessor)
    {
    }

    public function process(float $price): void
    {
        try {
            // TODO: Handle price conversion logic according to business requirements
            $this->paypalPaymentProcessor->pay($price);
        } catch (\Throwable $exception) {
            throw new PaymentException('Error processing payment by Paypal processor', previous: $exception);
        }
    }
}
