<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class TransactionLocation
{
    public function __construct(
        public TransactionAddress $address,
        public TransactionGeometry $geometry,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            address: TransactionAddress::fromArray($data['address']),
            geometry: TransactionGeometry::fromArray($data['geometry']),
        );
    }
}
