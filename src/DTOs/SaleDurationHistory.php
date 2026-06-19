<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class SaleDurationHistory
{
    /**
     * @param SaleDurationDataPoint[] $data
     */
    public function __construct(
        public string $unit,
        public array $data,
    ) {}

    public static function fromArray(array $response): self
    {
        return new self(
            unit: $response['unit'],
            data: array_map(
                fn(array $item) => SaleDurationDataPoint::fromArray($item),
                $response['data'],
            ),
        );
    }
}
