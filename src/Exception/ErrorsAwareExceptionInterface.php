<?php

namespace App\Exception;

interface ErrorsAwareExceptionInterface
{
    public function getErrors(): array;
}
