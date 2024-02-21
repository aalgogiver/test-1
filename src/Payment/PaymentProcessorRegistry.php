<?php

namespace App\Payment;

use App\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

class PaymentProcessorRegistry
{
    private readonly iterable $paymentProcessors;

    public function __construct(iterable $paymentProcessors)
    {
        $this->paymentProcessors = iterator_to_array($paymentProcessors);
    }

    public function getProcessor(string $processorName): PaymentProcessorInterface
    {
        if (!isset($this->paymentProcessors[$processorName])) {
            throw new InvalidArgumentException(sprintf('Could not find payment processor "%s"', $processorName));
        }

        return $this->paymentProcessors[$processorName];
    }
}
