<?php

namespace App\Calculator;

use App\Dto\PurchaseRequest;
use App\Exception\InvalidArgumentException;
use App\Repository\ProductRepository;

class ProductPriceCalculator implements PriceCalculatorInterface
{
    public function __construct(private readonly ProductRepository $productRepository)
    {
    }

    public function calculatePrice(PurchaseRequest $purchaseRequest): float
    {
        $product = $this->productRepository->find($purchaseRequest->getProduct());

        if (!$product) {
            throw new InvalidArgumentException(sprintf('Could not find product "%d"', $purchaseRequest->getProduct()));
        }

        return $product->getPrice();
    }
}
