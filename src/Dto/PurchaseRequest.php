<?php

namespace App\Dto;

use App\Validator\TaxNumber;
use Symfony\Component\Validator\Constraints as Assert;

class PurchaseRequest
{
    #[Assert\NotBlank, Assert\Type('int')]
    private $product;

    #[Assert\NotBlank, TaxNumber]
    private $taxNumber;

    #[Assert\Type('string')]
    private $couponCode;

    public function setProduct($product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getProduct(): int
    {
        return $this->product;
    }

    public function setTaxNumber($taxNumber): static
    {
        $this->taxNumber = $taxNumber;

        return $this;
    }

    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    public function setCouponCode($couponCode): static
    {
        $this->couponCode = $couponCode;

        return $this;
    }

    public function getCouponCode(): ?string
    {
        return $this->couponCode;
    }
}
