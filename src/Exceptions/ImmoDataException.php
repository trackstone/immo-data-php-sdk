<?php

declare(strict_types=1);

namespace ImmoData\Sdk\Exceptions;

use RuntimeException;
use Throwable;

final class ImmoDataException extends RuntimeException
{
    public function __construct(
        string $message,
        int $httpStatusCode = 0,
        public readonly ?array $errorBody = null,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $httpStatusCode, $previous);
    }
}
