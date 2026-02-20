<?php

declare(strict_types=1);

namespace ImmoData\Sdk\Exceptions;

final class ValidationException extends ImmoDataException
{
    /**
     * @return array<string, mixed>
     */
    public function errors(): array
    {
        return $this->errorBody['errors'] ?? [];
    }
}
