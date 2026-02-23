<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class PriceHistory
{
    /**
     * @param PriceHistoryDataPoint[] $data
     */
    public function __construct(
        public string $metric,
        public array $data,
    ) {}

    public static function fromArray(array $response): self
    {
        return new self(
            metric: $response['metric'],
            data: array_map(
                fn(array $item) => PriceHistoryDataPoint::fromArray($item),
                $response['data'],
            ),
        );
    }
}
