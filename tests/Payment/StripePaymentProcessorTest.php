<?php

namespace App\Tests\Payment;

use App\Exception\PaymentException;
use App\Payment\StripePaymentProcessor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor as ExternalPaypalPaymentProcessor;

class StripePaymentProcessorTest extends TestCase
{
    private const float PRICE = 140.00;

    private MockObject $externalPaymentProcessor;

    private StripePaymentProcessor $paymentProcessor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->externalPaymentProcessor = $this->createMock(ExternalPaypalPaymentProcessor::class);

        $this->paymentProcessor = new StripePaymentProcessor($this->externalPaymentProcessor);
    }

    public function testProcessThrowsExceptionOnExternalPaymentProcessorFailure(): void
    {
        $this->expectException(PaymentException::class);

        $this->externalPaymentProcessor
            ->expects(self::once())
            ->method('processPayment')
            ->with(self::PRICE)
            ->willReturn(false);

        $this->paymentProcessor->process(self::PRICE);
    }

    public function testProcessInvokesExternalPaymentProcessor(): void
    {
        $this->externalPaymentProcessor
            ->expects(self::once())
            ->method('processPayment')
            ->with(self::PRICE)
            ->willReturn(true);

        $this->paymentProcessor->process(self::PRICE);
    }
}