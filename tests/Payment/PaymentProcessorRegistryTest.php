<?php

namespace App\Tests\Payment;

use App\Exception\InvalidArgumentException;
use App\Payment\PaymentProcessorInterface;
use App\Payment\PaymentProcessorRegistry;
use PHPUnit\Framework\TestCase;

class PaymentProcessorRegistryTest extends TestCase
{
    private const string PROCESSOR_NAME = 'processor';

    public function testGetProcessorThrowsExceptionWhenNoProcessor(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Could not find payment processor "processor"');

        $paymentProcessorRegistry = new PaymentProcessorRegistry([]);

        $paymentProcessorRegistry->getProcessor(self::PROCESSOR_NAME);
    }

    public function testGetProcessorReturnsProcessor(): void
    {
        $paymentProcessor = $this->createMock(PaymentProcessorInterface::class);

        $paymentProcessorRegistry = new PaymentProcessorRegistry([
            self::PROCESSOR_NAME => $paymentProcessor,
        ]);

        self::assertSame($paymentProcessor, $paymentProcessorRegistry->getProcessor(self::PROCESSOR_NAME));
    }
}