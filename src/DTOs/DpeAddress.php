<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class DpeAddress
{
    public function __construct(
        public ?string $inseeCode,
        public ?string $departmentCode,
        public ?string $districtCode,
        public ?string $postCode,
        public ?string $cityName,
        public ?string $streetName,
        public ?string $streetNumber,
        public ?string $addressId,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            inseeCode: $data['inseeCode'] ?? null,
            departmentCode: $data['departmentCode'] ?? null,
            districtCode: $data['districtCode'] ?? null,
            postCode: $data['postCode'] ?? null,
            cityName: $data['cityName'] ?? null,
            streetName: $data['streetName'] ?? null,
            streetNumber: $data['streetNumber'] ?? null,
            addressId: $data['addressId'] ?? null,
        );
    }
}
