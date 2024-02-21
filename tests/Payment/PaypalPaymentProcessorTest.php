<?php

namespace App\Tests\Payment;

use App\Exception\PaymentException;
use App\Payment\PaypalPaymentProcessor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor as ExternalPaypalPaymentProcessor;

class PaypalPaymentProcessorTest extends TestCase
{
    private const float PRICE = 140.00;

    private MockObject $externalPaymentProcessor;

    private PaypalPaymentProcessor $paymentProcessor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->externalPaymentProcessor = $this->createMock(ExternalPaypalPaymentProcessor::class);

        $this->paymentProcessor = new PaypalPaymentProcessor($this->externalPaymentProcessor);
    }

    public function testProcessThrowsExceptionOnExternalPaymentProcessorFailure(): void
    {
        $this->expectException(PaymentException::class);

        $this->externalPaymentProcessor
            ->expects(self::once())
            ->method('pay')
            ->willThrowException(new \Exception('Some error'));

        $this->paymentProcessor->process(self::PRICE);
    }

    public function testProcessInvokesExternalPaymentProcessor(): void
    {
        $this->externalPaymentProcessor
            ->expects(self::once())
            ->method('pay')
            ->with((int)self::PRICE);

        $this->paymentProcessor->process(self::PRICE);
    }
}