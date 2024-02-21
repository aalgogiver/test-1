<?php

namespace App\Tests\Controller;

use App\Tests\Trait\AssertTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PaymentControllerTest extends WebTestCase
{
    use AssertTrait;

    public function testIndexWhenBadRequestWithRequiredFieldsAreLeftBlank(): void
    {
        $client = static::createClient();
        $client->jsonRequest('POST', '/purchase', []);

        $expectedErrors = [
            [
                'message' => 'This value should not be blank.',
                'property' => 'paymentProcessor',
            ],
            [
                'message' => 'This value should not be blank.',
                'property' => 'product',
            ],
            [
                'message' => 'This value should not be blank.',
                'property' => 'taxNumber',
            ]
        ];

        self::assertJsonErrorResponse($client, $expectedErrors);
    }

    public function testIndexWhenNoProductExists(): void
    {
        $client = static::createClient();
        $client->jsonRequest('POST', '/purchase', [
            'paymentProcessor' => 'paypal',
            'product' => 7,
            'taxNumber' => 'DE012345678'
        ]);

        self::assertJsonErrorResponse($client, [['message' => 'Could not find product "7"']]);
    }

    public function testIndexWhenBadCoupon(): void
    {
        $client = static::createClient();
        $client->jsonRequest('POST', '/purchase', [
            'paymentProcessor' => 'paypal',
            'product' => 1,
            'taxNumber' => 'DE012345678',
            'couponCode' => 'BAD1'
        ]);

        self::assertJsonErrorResponse($client, [['message' => 'Could not find coupon code "BAD1"']]);
    }

    public function testIndexWhenBadPaymentProcessor(): void
    {
        $client = static::createClient();
        $client->jsonRequest(
            'POST',
            '/purchase',
            ['product' => 1, 'taxNumber' => 'DE012345678', 'paymentProcessor' => 'unknown']
        );

        self::assertJsonErrorResponse($client, [['message' => 'Could not find payment processor "unknown"']]);
    }

    public function testIndexWithFixedDiscountCoupon(): void
    {
        $client = static::createClient();
        $client->jsonRequest(
            'POST',
            '/purchase',
            ['product' => 1, 'taxNumber' => 'DE012345678', 'couponCode' => 'FIXED90', 'paymentProcessor' => 'paypal']
        );

        self::assertResponseIsSuccessful();
    }
}