<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

use ImmoData\Enums\GeoLevel;

final readonly class GeocodeResult
{
    /**
     * @param string[] $postCode
     * @param string[] $parcelIds
     */
    public function __construct(
        public GeoLevel $geoLevel,
        public ?string $regionName,
        public ?string $regionCode,
        public ?string $departmentName,
        public ?string $departmentCode,
        public ?string $cityName,
        public ?string $inseeCode,
        public array $postCode,
        public ?BoundingBox $boundingBox,
        public ?Coordinates $center,
        public string $label,
        public ?string $districtCode = null,
        public ?string $subdistrictCode = null,
        public ?string $streetCode = null,
        public ?string $streetName = null,
        public ?string $streetType = null,
        public ?string $streetNumber = null,
        public ?string $streetSuffix = null,
        public ?string $addressId = null,
        public array $parcelIds = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            geoLevel: GeoLevel::from($data['geoLevel']),
            regionName: $data['regionName'] ?? null,
            regionCode: $data['regionCode'] ?? null,
            departmentName: $data['departmentName'] ?? null,
            departmentCode: $data['departmentCode'] ?? null,
            cityName: $data['cityName'] ?? null,
            inseeCode: $data['inseeCode'] ?? null,
            postCode: $data['postCode'] ?? [],
            boundingBox: isset($data['boundingBox']) ? BoundingBox::fromArray($data['boundingBox']) : null,
            center: isset($data['center']) ? Coordinates::fromArray($data['center']) : null,
            label: $data['label'],
            districtCode: $data['districtCode'] ?? null,
            subdistrictCode: $data['subdistrictCode'] ?? null,
            streetCode: $data['streetCode'] ?? null,
            streetName: $data['streetName'] ?? null,
            streetType: $data['streetType'] ?? null,
            streetNumber: $data['streetNumber'] ?? null,
            streetSuffix: $data['streetSuffix'] ?? null,
            addressId: $data['addressId'] ?? null,
            parcelIds: $data['parcelIds'] ?? [],
        );
    }

    /**
     * @return self[]
     */
    public static function fromArrayList(array $list): array
    {
        return array_map(fn(array $item) => self::fromArray($item), $list);
    }
}
