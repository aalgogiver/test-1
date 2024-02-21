<?php

namespace App\Tests\Trait;

use App\Dto\PaymentPurchaseRequest;
use App\Dto\PurchaseRequest;

trait DtoTrait
{
    public const int PRODUCT = 1;
    public const string TAX_NUMBER = 'DE012345678';

    public const string PAYMENT_PROCESSOR = 'paypal';

    public static function createPurchaseRequest(
        ?int $product = null,
        ?string $taxNumber = null,
        ?string $couponCode = null
    ): PurchaseRequest {
        $dto = new PurchaseRequest();

        $dto->setProduct($product ?? self::PRODUCT);
        $dto->setTaxNumber($taxNumber ?? self::TAX_NUMBER);
        $dto->setCouponCode($couponCode);

        return $dto;
    }

    public static function createPaymentPurchaseRequest(
        ?string $paymentProcessor = null,
        ?int $product = null,
        ?string $taxNumber = null,
        ?string $couponCode = null
    ): PurchaseRequest {
        $dto = new PaymentPurchaseRequest();

        $dto->setPaymentProcessor($paymentProcessor ?? self::PAYMENT_PROCESSOR);
        $dto->setProduct($product ?? self::PRODUCT);
        $dto->setTaxNumber($taxNumber ?? self::TAX_NUMBER);
        $dto->setCouponCode($couponCode);

        return $dto;
    }
}
