<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class TransactionLot
{
    /**
     * @param TransactionRealty[] $realty
     */
    public function __construct(
        public string $parcelId,
        public TransactionLocation $location,
        public array $realty,
        public int $landArea,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            parcelId: $data['parcelId'],
            location: TransactionLocation::fromArray($data['location']),
            realty: array_map(fn(array $r) => TransactionRealty::fromArray($r), $data['realty']),
            landArea: $data['landArea'],
        );
    }
}
