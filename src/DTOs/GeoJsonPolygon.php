<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class GeoJsonPolygon
{
    /**
     * @param array<array<array<float>>> $coordinates
     */
    public function __construct(
        public string $type,
        public array $coordinates,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            type: $data['type'],
            coordinates: $data['coordinates'],
        );
    }
}
