<?php

declare(strict_types=1);

namespace ImmoData\Sdk\DTOs;

final readonly class Region
{
    public function __construct(
        public string $regionCode,
        public string $regionName,
        public GeoJsonPolygon $boundaries,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            regionCode: $data['regionCode'],
            regionName: $data['regionName'],
            boundaries: GeoJsonPolygon::fromArray($data['boundaries']),
        );
    }
}
