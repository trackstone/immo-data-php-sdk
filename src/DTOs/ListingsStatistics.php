<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class ListingsStatistics
{
    /**
     * @param ListingsStatisticsBucket[] $data
     */
    public function __construct(
        public ?string $groupBy,
        public array $data,
    ) {}

    public static function fromArray(array $response): self
    {
        return new self(
            groupBy: $response['groupBy'] ?? null,
            data: array_map(
                fn(array $b) => ListingsStatisticsBucket::fromArray($b),
                $response['data'],
            ),
        );
    }
}
