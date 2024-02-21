<?php

namespace App\Tests\Calculator;

use App\Calculator\ProductPriceCalculator;
use App\Entity\Product;
use App\Exception\InvalidArgumentException;
use App\Repository\ProductRepository;
use App\Tests\Trait\DtoTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductPriceCalculatorTest extends TestCase
{
    use DtoTrait;

    private MockObject $productRepository;
    private ProductPriceCalculator $productPriceCalculator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productRepository = $this->createMock(ProductRepository::class);

        $this->productPriceCalculator = new ProductPriceCalculator($this->productRepository);
    }

    public function testCalculatePriceThrowsExceptionWhenNoProduct(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Could not find product "1"');

        $this->productRepository
            ->expects(self::once())
            ->method('find')
            ->with(self::PRODUCT)
            ->willReturn(null);

        $purchaseRequest = self::createPurchaseRequest(product: self::PRODUCT);

        $this->productPriceCalculator->calculatePrice($purchaseRequest);
    }

    public function testCalculatePriceReturnsProductPrice(): void
    {
        $price = 100.00;
        $productEntity = $this->createEntityMock($price);

        $this->productRepository
            ->expects(self::once())
            ->method('find')
            ->with(self::PRODUCT)
            ->willReturn($productEntity);

        $purchaseRequest = self::createPurchaseRequest(product: self::PRODUCT);

        self::assertEquals($price, $this->productPriceCalculator->calculatePrice($purchaseRequest));
    }

    private function createEntityMock(float $price): MockObject
    {
        $entity = $this->createMock(Product::class);
        $entity
            ->expects(self::any())
            ->method('getPrice')
            ->willReturn((string)$price);

        return $entity;
    }
}