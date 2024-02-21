<?php

namespace App\Tests\Provider;

use App\Exception\InvalidArgumentException;
use App\Provider\CountryTaxProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CountryTaxProviderTest extends TestCase
{
    private CountryTaxProvider $countryTaxProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->countryTaxProvider = new CountryTaxProvider();
    }

    #[DataProvider('countryTaxesDataProvider')]
    public function testGetTaxPercentageByTaxNumberForCountry(string $taxNumber, float $expectedTaxPercentage): void
    {
       self::assertEquals($expectedTaxPercentage, $this->countryTaxProvider->getTaxPercentageByTaxNumber($taxNumber));
    }

    public static function countryTaxesDataProvider(): iterable
    {
        yield 'tax 19% for Germany' => [
            'taxNumber' => 'DE012345678',
            'expectedTaxPercentage' => 19.00,
        ];

        yield 'tax 22% for Italy' => [
            'taxNumber' => 'IT01234567891',
            'expectedTaxPercentage' => 22.00,
        ];

        yield 'tax 24% for Greece' => [
            'taxNumber' => 'GR012345678',
            'expectedTaxPercentage' => 24.00,
        ];

        yield 'tax 20% for France' => [
            'taxNumber' => 'FRZZ012345678',
            'expectedTaxPercentage' => 20.00,
        ];
    }

    public function testGetTaxPercentage(): void
    {
        self::assertEquals(19.00, $this->countryTaxProvider->getTaxPercentageByTaxNumber('DE012345678'));
    }

    public function testGetTaxPercentageByTaxNumberWhenWrongTaxNumber(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Could not find tax percentage by tax number "DR0123456"');


        $this->countryTaxProvider->getTaxPercentageByTaxNumber('DR0123456');
    }
}