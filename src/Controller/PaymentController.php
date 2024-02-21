<?php

namespace App\Controller;

use App\Dto\PaymentPurchaseRequest;
use App\Payment\PaymentHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PaymentController extends AbstractController
{
    #[Route('/purchase', name: 'purchase', methods: ['POST'])]
    public function index(PaymentPurchaseRequest $paymentPurchaseRequest, PaymentHandler $paymentHandler): Response
    {
        $paymentHandler->handle($paymentPurchaseRequest);

        return new JsonResponse(status: 200);
    }
}