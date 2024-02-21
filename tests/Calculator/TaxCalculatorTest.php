<?php

namespace App\Tests\Calculator;

use App\Calculator\PriceCalculatorInterface;
use App\Calculator\TaxCalculator;
use App\Provider\CountryTaxProvider;
use App\Tests\Trait\DtoTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TaxCalculatorTest extends TestCase
{
    use DtoTrait;

    private MockObject $countryTaxProviderMock;
    private MockObject $priceCalculator;
    private TaxCalculator $taxCalculator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->priceCalculator = $this->createMock(PriceCalculatorInterface::class);
        $this->countryTaxProviderMock = $this->createMock(CountryTaxProvider::class);

        $this->taxCalculator = new TaxCalculator($this->priceCalculator, $this->countryTaxProviderMock);
    }

    #[DataProvider('validDataProvider')]
    public function testGetTotal(float $price, float $taxPercentage, float $expectedTotal): void
    {
        $purchaseRequest = self::createPurchaseRequest();

        $this->priceCalculator
            ->expects(self::once())
            ->method('calculatePrice')
            ->with($purchaseRequest)
            ->willReturn($price);

        $this->countryTaxProviderMock
            ->expects(self::once())
            ->method('getTaxPercentageByTaxNumber')
            ->with(self::TAX_NUMBER)
            ->willReturn($taxPercentage);


        self::assertEquals($expectedTotal, $this->taxCalculator->calculatePrice($purchaseRequest));
    }

    public static function validDataProvider(): iterable
    {
        yield 'no tax (0% percentage)' => [
            'price' => 100.00,
            'taxPercentage' => 0.00,
            'expectedTotal' => 100.00,
        ];

        yield 'standard tax (19% percentage)' => [
            'price' => 100.00,
            'taxPercentage' => 19.00,
            'expectedTotal' => 119.00,
        ];

        yield 'tax with fractional part (21.5% percentage)' => [
            'price' => 100.00,
            'taxPercentage' => 21.75,
            'expectedTotal' => 121.75,
        ];
    }
}