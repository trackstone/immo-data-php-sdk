<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class CurrentPrice
{
    public function __construct(
        public string $metric,
        public float $value,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            metric: $data['metric'],
            value: (float) $data['value'],
        );
    }
}
