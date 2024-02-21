<?php

namespace App\Tests\Validator;

use App\Validator\TaxNumber;
use App\Validator\TaxNumberValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class TaxNumberValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ConstraintValidatorInterface
    {
        return new TaxNumberValidator();
    }

    #[DataProvider('validValuesDataProvider')]
    public function testValidValues(?string $value): void
    {
        $this->validator->validate($value, new TaxNumber());

        $this->assertNoViolation();
    }

    public static function validValuesDataProvider(): iterable
    {
        yield 'null is allowed' => [
            'value' => null,
        ];

        yield 'empty string is allowed' => [
            'value' => null,
        ];

        yield 'german tax number is allowed' => [
            'value' => 'DE012345678',
        ];

        yield 'italian tax number is allowed' => [
            'value' => 'IT01234567891',
        ];

        yield 'greece tax number is allowed' => [
            'value' => 'GR012345678',
        ];

        yield 'french tax number is allowed' => [
            'value' => 'FRZZ012345678',
        ];
    }

    #[DataProvider('invalidValuesDataProvider')]
    public function testTrueIsInvalid(mixed $value): void
    {
        $this->validator->validate($value, new TaxNumber());

        $this->buildViolation('Bad tax number "{{ string }}".')
            ->setParameter('{{ string }}', $value)
            ->assertRaised();
    }

    public static function invalidValuesDataProvider(): iterable
    {
        yield 'alpha in digital part in german tax number' => [
            'value' => 'DE01234A6789',
        ];

        yield 'more (10) digits in german tax number' => [
            'value' => 'DE0123456789',
        ];

        yield 'less (8) digits in german tax number' => [
            'value' => 'DE01234567',
        ];

        yield 'bad prefix case in german tax number' => [
            'value' => 'de01234567',
        ];

        yield 'alpha in digital part in italian tax number' => [
            'value' => 'IT01234A67891',
        ];

        yield 'more (12) digits in italian tax number' => [
            'value' => 'IT012345678912',
        ];

        yield 'less (10) digits in italian tax number' => [
            'value' => 'IT0123456789',
        ];

        yield 'bad prefix case in italian tax number' => [
            'value' => 'it01234567',
        ];

        yield 'alpha in digital part in greece tax number' => [
            'value' => 'GR01234A6789',
        ];

        yield 'more (10) digits in greece tax number' => [
            'value' => 'GR0123456789',
        ];

        yield 'less (8) digits in greece tax number' => [
            'value' => 'GR01234567',
        ];

        yield 'bad prefix case in greece tax number' => [
            'value' => 'gr01234567',
        ];

        yield 'alpha in digital part in french tax number' => [
            'value' => 'FRZZ01234A678',
        ];

        yield 'more (10) digits in french tax number' => [
            'value' => 'FRZZ01234A6789',
        ];

        yield 'less (8) digits in french tax number' => [
            'value' => 'FRZZ01234A67',
        ];

        yield 'bad prefix case in french tax number' => [
            'value' => 'frzz012345678',
        ];

        yield 'digit in alpha part in french tax number' => [
            'value' => 'FR1Z012345678',
        ];
    }
}