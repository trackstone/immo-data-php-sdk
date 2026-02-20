<?php

declare(strict_types=1);

namespace ImmoData\Sdk\DTOs;

final readonly class Department
{
    public function __construct(
        public string $departmentCode,
        public string $departmentName,
        public string $regionCode,
        public GeoJsonPolygon $boundaries,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            departmentCode: $data['departmentCode'],
            departmentName: $data['departmentName'],
            regionCode: $data['regionCode'],
            boundaries: GeoJsonPolygon::fromArray($data['boundaries']),
        );
    }
}
