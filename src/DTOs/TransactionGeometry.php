<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class TransactionGeometry
{
    public function __construct(
        public string $type,
        public float $longitude,
        public float $latitude,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            type: $data['type'],
            longitude: $data['coordinates'][0],
            latitude: $data['coordinates'][1],
        );
    }
}
