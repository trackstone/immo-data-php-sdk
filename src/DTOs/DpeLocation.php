<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class DpeLocation
{
    public function __construct(
        public ?DpeAddress $address,
        public ?DpeGeometry $geometry,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            address: isset($data['address']) ? DpeAddress::fromArray($data['address']) : null,
            geometry: isset($data['geometry']) ? DpeGeometry::fromArray($data['geometry']) : null,
        );
    }
}
