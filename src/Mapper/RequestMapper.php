<?php

namespace App\Mapper;

use App\Exception\MapperException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestMapper
{
    public function __construct(
        private readonly PropertyAccessor $propertyAccessor,
        private readonly ValidatorInterface $validator,
    ) {
    }

    /**
     * @throws MapperException
     */
    public function mapJsonRequestToDto(Request $request, string $className): object
    {
        $dto = new $className();
        foreach ($this->getJsonData($request) as $field => $value) {
            if ($this->propertyAccessor->isWritable($dto, $field)) {
                $this->propertyAccessor->setValue($dto, $field, $value);
            }
        }

        $this->validateDto($dto);

        return $dto;
    }

    /**
     * @throws MapperException
     */
    private function validateDto($dto): void
    {
        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = [
                    'property' => $violation->getPropertyPath(),
                    'message' => $violation->getMessage(),
                ];
            }

            throw new MapperException(errors: $errors);
        }
    }

    /**
     * @throws MapperException
     */
    private function getJsonData(Request $request): array
    {
        try {
            $jsonData = json_decode($request->getContent(), associative: true, flags: JSON_THROW_ON_ERROR);
        } catch (\Throwable $exception) {
            throw new MapperException(errors: [['message' => 'Bad request json format']], previous: $exception);
        }

        if (!is_array($jsonData)) {
            throw new MapperException(errors: [['message' => 'Bad request format']]);
        }

        return $jsonData;
    }
}