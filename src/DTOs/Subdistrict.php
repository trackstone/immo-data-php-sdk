<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class Subdistrict
{
    public function __construct(
        public string $subdistrictCode,
        public string $subdistrictName,
        public string $regionCode,
        public string $departmentCode,
        public string $inseeCode,
        public string $districtCode,
        public GeoJsonPolygon $boundaries,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            subdistrictCode: $data['subdistrictCode'],
            subdistrictName: $data['subdistrictName'],
            regionCode: $data['regionCode'],
            departmentCode: $data['departmentCode'],
            inseeCode: $data['inseeCode'],
            districtCode: $data['districtCode'],
            boundaries: GeoJsonPolygon::fromArray($data['boundaries']),
        );
    }
}
