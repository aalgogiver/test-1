<?php

namespace App\Tests\Calculator;

use App\Calculator\DiscountCalculator;
use App\Calculator\PriceCalculatorInterface;
use App\Dto\PurchaseRequest;
use App\Tests\Trait\DtoTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DiscountCalculatorTest extends TestCase
{
    use DtoTrait;

    private MockObject $priceCalculator;

    private DiscountCalculator $discountCalculator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->priceCalculator = $this->createMock(PriceCalculatorInterface::class);

        $this->discountCalculator = new DiscountCalculator($this->priceCalculator);
    }

    #[DataProvider('couponsDataProvider')]
    public function testCalculatePrice(PurchaseRequest $purchaseRequest, float $price, float $expectedPrice): void
    {
        $this->priceCalculator
            ->expects(self::once())
            ->method('calculatePrice')
            ->with($purchaseRequest)
            ->willReturn($price);

        self::assertEquals($expectedPrice, $this->discountCalculator->calculatePrice($purchaseRequest));
    }

    public static function couponsDataProvider(): iterable
    {
        yield 'returns original price when empty coupon code' => [
            'purchaseRequest' => self::createPurchaseRequest(couponCode: null),
            'price' => 100.00,
            'expectedPrice' => 100.00,
        ];

        yield 'fixed discount of 90' => [
            'purchaseRequest' => self::createPurchaseRequest(couponCode: 'FIXED90'),
            'price' => 100.00,
            'expectedPrice' => 10.00,
        ];

        yield 'percentage discount of 10%' => [
            'purchaseRequest' => self::createPurchaseRequest(couponCode: 'PERCENT10'),
            'price' => 100.00,
            'expectedPrice' => 90.00,
        ];

        yield 'if discount is greater than price than zero amount returned' => [
            'purchaseRequest' => self::createPurchaseRequest(couponCode: 'FIXED90'),
            'price' => 10.00,
            'expectedPrice' => 0.00,
        ];
    }
}