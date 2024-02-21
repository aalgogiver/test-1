<?php

namespace App\Tests\Payment;

use App\Calculator\PriceCalculatorInterface;
use App\Exception\InvalidArgumentException;
use App\Exception\PaymentException;
use App\Payment\PaymentHandler;
use App\Payment\PaymentProcessorInterface;
use App\Payment\PaymentProcessorRegistry;
use App\Tests\Trait\DtoTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PaymentHandlerTest extends TestCase
{
    use DtoTrait;

    private MockObject $priceCalculator;
    private MockObject $paymentProcessorRegistry;

    private PaymentHandler $paymentHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->priceCalculator = $this->createMock(PriceCalculatorInterface::class);
        $this->paymentProcessorRegistry = $this->createMock(PaymentProcessorRegistry::class);

        $this->paymentHandler = new PaymentHandler($this->priceCalculator, $this->paymentProcessorRegistry);
    }

    public function testHandleThrowExceptionIfErrorCalculatingPrice(): void
    {
        self::expectException(InvalidArgumentException::class);

        $paymentPurchaseRequest = $this->createPaymentPurchaseRequest();

        $this->priceCalculator
            ->expects(self::once())
            ->method('calculatePrice')
            ->with($paymentPurchaseRequest)
            ->willThrowException(new InvalidArgumentException(''));

        $this->paymentProcessorRegistry
            ->expects(self::never())
            ->method('getProcessor');

        $this->paymentHandler->handle($paymentPurchaseRequest);
    }

    public function testHandleThrowExceptionIfErrorGettingProcessor(): void
    {
        self::expectException(InvalidArgumentException::class);

        $paymentPurchaseRequest = $this->createPaymentPurchaseRequest();

        $this->priceCalculator
            ->expects(self::once())
            ->method('calculatePrice')
            ->with($paymentPurchaseRequest)
            ->willReturn(100.00);

        $this->paymentProcessorRegistry
            ->expects(self::once())
            ->method('getProcessor')
            ->willThrowException(new InvalidArgumentException('No processor'));

        $this->paymentHandler->handle($paymentPurchaseRequest);
    }

    public function testHandleThrowExceptionIfErrorPaying(): void
    {
        self::expectException(PaymentException::class);

        $paymentPurchaseRequest = $this->createPaymentPurchaseRequest();

        $price = 107.00;
        $this->priceCalculator
            ->expects(self::once())
            ->method('calculatePrice')
            ->with($paymentPurchaseRequest)
            ->willReturn($price);

        $paymentProcessor = $this->createMock(PaymentProcessorInterface::class);
        $paymentProcessor
            ->expects(self::once())
            ->method('process')
            ->with($price)
            ->willThrowException(new PaymentException('Payment error'));

        $this->paymentProcessorRegistry
            ->expects(self::once())
            ->method('getProcessor')
            ->willReturn($paymentProcessor);

        $this->paymentHandler->handle($paymentPurchaseRequest);
    }

    public function testHandle(): void
    {
        $paymentPurchaseRequest = $this->createPaymentPurchaseRequest();

        $price = 107.00;
        $this->priceCalculator
            ->expects(self::once())
            ->method('calculatePrice')
            ->with($paymentPurchaseRequest)
            ->willReturn($price);

        $paymentProcessor = $this->createMock(PaymentProcessorInterface::class);
        $paymentProcessor
            ->expects(self::once())
            ->method('process')
            ->with($price);

        $this->paymentProcessorRegistry
            ->expects(self::once())
            ->method('getProcessor')
            ->willReturn($paymentProcessor);

        $this->paymentHandler->handle($paymentPurchaseRequest);
    }
}