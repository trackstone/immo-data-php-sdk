<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class PriceHistoryDataPoint
{
    public function __construct(
        public string $period,
        public float $value,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            period: $data['period'],
            value: (float) $data['value'],
        );
    }
}
