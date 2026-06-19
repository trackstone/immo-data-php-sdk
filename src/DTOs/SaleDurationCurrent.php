<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class SaleDurationCurrent
{
    public function __construct(
        public string $unit,
        public ?float $value,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            unit: $data['unit'],
            value: isset($data['value']) ? (float) $data['value'] : null,
        );
    }
}
