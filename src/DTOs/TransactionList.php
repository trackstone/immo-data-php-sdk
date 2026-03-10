<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class TransactionList
{
    /**
     * @param Transaction[] $data
     */
    public function __construct(
        public int $total,
        public int $size,
        public ?string $searchAfter,
        public array $data,
    ) {}

    public static function fromArray(array $response): self
    {
        return new self(
            total: $response['total'],
            size: $response['size'],
            searchAfter: $response['searchAfter'] ?? null,
            data: array_map(fn(array $t) => Transaction::fromArray($t), $response['data']),
        );
    }
}
