<?php

namespace App\Tests\Mapper;

use App\Dto\PaymentPurchaseRequest;
use App\Dto\PurchaseRequest;
use App\Exception\MapperException;
use App\Mapper\RequestMapper;
use App\Tests\Trait\DtoTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class RequestMapperTest extends KernelTestCase
{
    use DtoTrait;

    public function testMapperWhenBadJsonFormat(): void
    {
        try {
            $this->getRequestMapper()->mapJsonRequestToDto(new Request(), PurchaseRequest::class);
        } catch (MapperException $exception) {
            self::assertEquals([['message' => 'Bad request json format']], $exception->getErrors());
        }
    }

    public function testMapperWhenNotArrayFormat(): void
    {
        try {
            $this->getRequestMapper()->mapJsonRequestToDto(new Request(content: '123'), PurchaseRequest::class);
        } catch (MapperException $exception) {
            self::assertEquals([['message' => 'Bad request format']], $exception->getErrors());
        }
    }

    #[DataProvider(('validationPurchaseRequestErrorsDataProvider'))]
    public function testMapperWhenPurchaseRequestValidationErrors(array $data, array $expectedErrors): void
    {
        try {
            $data = $this->populatePurchaseRequestData($data);

            $this->getRequestMapper()->mapJsonRequestToDto(
                new Request(content: json_encode($data)),
                PurchaseRequest::class
            );

            self::fail('Mapper did not fail');
        } catch (MapperException $exception) {
            self::assertEquals($expectedErrors, $exception->getErrors());
        }
    }

    public static function validationPurchaseRequestErrorsDataProvider(): iterable
    {
        yield 'product can not be blank' => [
            'data' => ['product' => null],
            'expectedErrors' => [
                [
                    'property' => 'product',
                    'message' => 'This value should not be blank.',
                ],
            ],
        ];

        yield 'taxNumber can not be blank' => [
            'data' => ['taxNumber' => null],
            'expectedErrors' => [
                [
                    'property' => 'taxNumber',
                    'message' => 'This value should not be blank.',
                ],
            ],
        ];

        yield 'product must be of type int' => [
            'data' => ['product' => 'price'],
            'expectedErrors' => [
                [
                    'property' => 'product',
                    'message' => 'This value should be of type int.',
                ],
            ],
        ];

        yield 'tax number must be of type string' => [
            'data' => ['taxNumber' => 123],
            'expectedErrors' => [
                [
                    'property' => 'taxNumber',
                    'message' => 'This value should be of type string.',
                ],
            ],
        ];

        yield 'tax number bad format' => [
            'data' => ['taxNumber' => 'bad_format'],
            'expectedErrors' => [
                [
                    'property' => 'taxNumber',
                    'message' => 'Bad tax number "bad_format".',
                ],
            ],
        ];

        yield 'coupon code must be of type string' => [
            'data' => ['couponCode' => 123],
            'expectedErrors' => [
                [
                    'property' => 'couponCode',
                    'message' => 'This value should be of type string.',
                ],
            ],
        ];
    }

    #[DataProvider(('validationPaymentPurchaseRequestErrorsDataProvider'))]
    public function testMapperWhenPaymentPurchaseRequestValidationErrors(array $data, array $expectedErrors): void
    {
        try {
            $data = $this->populatePaymentPurchaseRequestData($data);

            $this->getRequestMapper()->mapJsonRequestToDto(
                new Request(content: json_encode($data)),
                PaymentPurchaseRequest::class
            );

            self::fail('Mapper did not fail');
        } catch (MapperException $exception) {
            self::assertEquals($expectedErrors, $exception->getErrors());
        }
    }

    public static function validationPaymentPurchaseRequestErrorsDataProvider(): iterable
    {
        foreach (self::validationPurchaseRequestErrorsDataProvider() as $key => $item) {
            yield $key => $item;
        }

        yield 'payment process cannot be blank' => [
            'data' => ['paymentProcessor' => null],
            'expectedErrors' => [
                [
                    'property' => 'paymentProcessor',
                    'message' => 'This value should not be blank.',
                ],
            ],
        ];

        yield 'payment process must be of type string' => [
            'data' => ['paymentProcessor' => 2],
            'expectedErrors' => [
                [
                    'property' => 'paymentProcessor',
                    'message' => 'This value should be of type string.',
                ],
            ],
        ];
    }

    #[DataProvider(('createDtoDataProvider'))]
    public function testMapperCreateDto(array $data, string $dtoClass, object $expectedDto): void
    {
        $dto = $this->getRequestMapper()->mapJsonRequestToDto(
            new Request(content: json_encode($data)),
            $dtoClass,
        );

        self::assertEquals($expectedDto, $dto);
    }

    public static function createDtoDataProvider(): iterable
    {
        yield 'creates purchase request without coupon code' => [
            'data' => ['product' => 2, 'taxNumber' => 'DE123456789'],
            'dtoClass' => PurchaseRequest::class,
            'expectedDto' => self::createPurchaseRequest(2, 'DE123456789'),
        ];

        yield 'creates purchase request with coupon code' => [
            'data' => ['product' => 2, 'taxNumber' => 'DE123456789', 'couponCode' => 'FIXED20'],
            'dtoClass' => PurchaseRequest::class,
            'expectedDto' => self::createPurchaseRequest(2, 'DE123456789', 'FIXED20'),
        ];

        yield 'creates payment purchase request without coupon code' => [
            'data' => ['product' => 2, 'taxNumber' => 'DE123456789', 'paymentProcessor' => 'paypal'],
            'dtoClass' => PaymentPurchaseRequest::class,
            'expectedDto' =>
                self::createPaymentPurchaseRequest('paypal', 2, 'DE123456789'),
        ];

        yield 'creates payment purchase request with coupon code' => [
            'data' => [
                'product' => 2,
                'taxNumber' => 'DE123456789',
                'paymentProcessor' => 'paypal',
                'couponCode' => 'FIXED20'
            ],
            'dtoClass' => PaymentPurchaseRequest::class,
            'expectedDto' =>
                self::createPaymentPurchaseRequest('paypal', 2, 'DE123456789', 'FIXED20'),
        ];
    }

    private function getRequestMapper(): RequestMapper
    {
        return self::getContainer()->get(RequestMapper::class);
    }

    private function populatePurchaseRequestData(array $data): array
    {
        return array_merge(['product' => self::PRODUCT, 'taxNumber' => self::TAX_NUMBER], $data);
    }

    private function populatePaymentPurchaseRequestData(array $data): array
    {
        $data = $this->populatePurchaseRequestData($data);

        return array_merge(['paymentProcessor' => 'paypal'], $data);
    }
}