<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class PaymentPurchaseRequest extends PurchaseRequest
{
    #[Assert\NotBlank, Assert\Type('string')]
    private $paymentProcessor;

    public function setPaymentProcessor($paymentProcessor): static
    {
        $this->paymentProcessor = $paymentProcessor;

        return $this;
    }

    public function getPaymentProcessor(): string
    {
        return $this->paymentProcessor;
    }
}
