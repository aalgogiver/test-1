<?php

namespace App\Calculator;

use App\Dto\PurchaseRequest;
use App\Exception\InvalidArgumentException;
use App\Exception\LogicException;

class DiscountCalculator implements PriceCalculatorInterface
{
    private static $coupons = [
        'FIXED90' => ['fixed' => 90],
        'PERCENT10' => ['percent' => 10],
    ];

    public function __construct(private readonly PriceCalculatorInterface $priceCalculator)
    {
    }

    public function calculatePrice(PurchaseRequest $purchaseRequest): float
    {
        $price = $this->priceCalculator->calculatePrice($purchaseRequest);

        if (!$purchaseRequest->getCouponCode()) {
            return $price;
        }

        $couponCode = $purchaseRequest->getCouponCode();
        if (!isset(self::$coupons[$couponCode])) {
            throw new InvalidArgumentException(sprintf('Could not find coupon code "%s"', $couponCode));
        }

        $couponData = self::$coupons[$couponCode];

        if (isset($couponData['fixed'])) {
            return max(0.00, $price - $couponData['fixed']);
        }

        if (isset($couponData['percent'])) {
            return $price * (100 - $couponData['percent']) / 100;
        }

        throw new LogicException(sprintf('Could not calculate discount for "%s" coupon', $couponCode));
    }
}
