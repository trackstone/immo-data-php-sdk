<?php

declare(strict_types=1);

namespace ImmoData\Sdk\DTOs;

final readonly class District
{
    /**
     * @param string[] $subdistrictCodes
     */
    public function __construct(
        public string $districtCode,
        public string $districtName,
        public string $regionCode,
        public string $departmentCode,
        public string $inseeCode,
        public GeoJsonPolygon $boundaries,
        public array $subdistrictCodes,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            districtCode: $data['districtCode'],
            districtName: $data['districtName'],
            regionCode: $data['regionCode'],
            departmentCode: $data['departmentCode'],
            inseeCode: $data['inseeCode'],
            boundaries: GeoJsonPolygon::fromArray($data['boundaries']),
            subdistrictCodes: $data['subdistrictCodes'] ?? [],
        );
    }
}
