<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class BoundingBox
{
    public function __construct(
        public float $minLongitude,
        public float $minLatitude,
        public float $maxLongitude,
        public float $maxLatitude,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            minLongitude: (float) $data[0],
            minLatitude: (float) $data[1],
            maxLongitude: (float) $data[2],
            maxLatitude: (float) $data[3],
        );
    }
}
