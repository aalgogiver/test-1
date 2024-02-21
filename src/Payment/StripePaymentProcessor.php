<?php

namespace App\Payment;

use App\Exception\PaymentException;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor as ExternalStripePaymentProcessor;

class StripePaymentProcessor implements PaymentProcessorInterface
{
    public function __construct(private readonly ExternalStripePaymentProcessor $stripePaymentProcessor)
    {
    }

    public function process(float $price): void
    {
        if (!$this->stripePaymentProcessor->processPayment($price)) {
            throw new PaymentException('Error processing payment by Stripe processor');
        }
    }
}
