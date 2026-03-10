<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class TransactionAddress
{
    public function __construct(
        public string $inseeCode,
        public string $departmentCode,
        public string $districtCode,
        public string $subdistrictCode,
        public string $postCode,
        public string $cityName,
        public ?string $streetName,
        public ?string $streetNumber,
        public ?string $streetCode,
        public ?string $streetType,
        public ?string $streetSuffix,
        public ?string $addressId,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            inseeCode: $data['inseeCode'],
            departmentCode: $data['departmentCode'],
            districtCode: $data['districtCode'],
            subdistrictCode: $data['subdistrictCode'],
            postCode: $data['postCode'],
            cityName: $data['cityName'],
            streetName: $data['streetName'] ?? null,
            streetNumber: $data['streetNumber'] ?? null,
            streetCode: $data['streetCode'] ?? null,
            streetType: $data['streetType'] ?? null,
            streetSuffix: $data['streetSuffix'] ?? null,
            addressId: $data['addressId'] ?? null,
        );
    }
}
