<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class PercentileValue
{
    public function __construct(
        public int $percentile,
        public ?float $value,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            percentile: $data['percentile'],
            value: isset($data['value']) ? (float) $data['value'] : null,
        );
    }
}
