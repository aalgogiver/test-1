<?php

namespace App\EventListener;

use App\Exception\ErrorsAwareExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        if ($event->getThrowable() instanceof ErrorsAwareExceptionInterface) {
            $event->setResponse(new JsonResponse(
                data: ['errors' => $event->getThrowable()->getErrors()],
                status: 400
            ));

            return;
        }

        $event->setResponse(new JsonResponse(
            data: ['errors' => [['message' => $event->getThrowable()->getMessage()]]],
            status: 400
        ));
    }
}