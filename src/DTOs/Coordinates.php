<?php

declare(strict_types=1);

namespace ImmoData\Sdk\DTOs;

final readonly class Coordinates
{
    public function __construct(
        public float $longitude,
        public float $latitude,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            longitude: (float) $data[0],
            latitude: (float) $data[1],
        );
    }
}
