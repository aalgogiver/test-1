<?php

namespace App\Controller;

use App\Calculator\PriceCalculatorInterface;
use App\Dto\PurchaseRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PriceController extends AbstractController
{
    #[Route('/calculate-price', name: 'calculate_price', methods: ['POST'])]
    public function index(PurchaseRequest $purchaseRequest, PriceCalculatorInterface $priceCalculator): Response
    {
        return $this->json([
            'price' => sprintf('%.2f', $priceCalculator->calculatePrice($purchaseRequest))
        ]);
    }
}