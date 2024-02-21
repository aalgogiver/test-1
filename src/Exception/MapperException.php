<?php

namespace App\Exception;

use Exception;

class MapperException extends Exception implements ErrorsAwareExceptionInterface
{
    private array $errors = [];

    public function __construct(
        $message = "",
        $code = 0,
        $previous = null,
        $errors = []
    ) {
        parent::__construct($message, $code, $previous);

        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
