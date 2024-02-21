<?php

namespace App\Tests\Controller;

use App\Tests\Trait\AssertTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PriceControllerTest extends WebTestCase
{
    use AssertTrait;

    public function testIndexWhenBadRequestWithRequiredFieldsAreLeftBlank(): void
    {
        $client = static::createClient();
        $client->jsonRequest('POST', '/calculate-price', []);

        $expectedErrors = [
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
        $client->jsonRequest('POST', '/calculate-price', ['product' => 7, 'taxNumber' => 'DE012345678']);

        self::assertJsonErrorResponse($client, [['message' => 'Could not find product "7"']]);
    }

    public function testIndexWhenBadCoupon(): void
    {
        $client = static::createClient();
        $client->jsonRequest('POST', '/calculate-price', [
            'product' => 1,
            'taxNumber' => 'DE012345678',
            'couponCode' => 'BAD1'
        ]);

        self::assertJsonErrorResponse($client, [['message' => 'Could not find coupon code "BAD1"']]);
    }

    public function testIndexWhenNoCoupon(): void
    {
        $client = static::createClient();
        $client->jsonRequest('POST', '/calculate-price', ['product' => 1, 'taxNumber' => 'DE012345678']);

        self::assertResponseIsSuccessful();

        $response = $client->getResponse();

        self::assertJson($response->getContent());
        self::assertJsonStringEqualsJsonString('{"price":"119.00"}', $response->getContent());
    }

    public function testIndexWithFixedDiscountCoupon(): void
    {
        $client = static::createClient();
        $client->jsonRequest(
            'POST',
            '/calculate-price',
            ['product' => 1, 'taxNumber' => 'DE012345678', 'couponCode' => 'FIXED90']
        );

        self::assertResponseIsSuccessful();

        $response = $client->getResponse();

        self::assertJson($response->getContent());
        self::assertJsonStringEqualsJsonString('{"price":"11.90"}', $response->getContent());
    }
}