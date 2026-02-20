<?php

declare(strict_types=1);

namespace ImmoData\Sdk\DTOs;

use ImmoData\Sdk\Enums\GeoLevel;

final readonly class GeocodeResult
{
    /**
     * @param string[] $postCode
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
