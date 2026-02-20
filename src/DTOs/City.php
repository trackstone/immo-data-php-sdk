<?php

declare(strict_types=1);

namespace ImmoData\Sdk\DTOs;

final readonly class City
{
    /**
     * @param string[] $postCode
     * @param string[] $districtCodes
     */
    public function __construct(
        public string $inseeCode,
        public string $cityName,
        public string $regionCode,
        public string $departmentCode,
        public array $postCode,
        public GeoJsonPolygon $boundaries,
        public array $districtCodes,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            inseeCode: $data['inseeCode'],
            cityName: $data['cityName'],
            regionCode: $data['regionCode'],
            departmentCode: $data['departmentCode'],
            postCode: $data['postCode'] ?? [],
            boundaries: GeoJsonPolygon::fromArray($data['boundaries']),
            districtCodes: $data['districtCodes'] ?? [],
        );
    }
}
