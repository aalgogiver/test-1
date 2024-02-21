<?php

namespace App\ValueResolver;

use App\Dto\PurchaseRequest;
use App\Mapper\RequestMapper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;

class DtoValueResolver implements ValueResolverInterface
{
    public function __construct(private readonly RequestMapper $requestMapper)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if (
            !$argumentType
            || !is_a($argumentType, PurchaseRequest::class, true)
        ) {
            return [];
        }

        return [$this->requestMapper->mapJsonRequestToDto($request, $argumentType)];
    }
}
