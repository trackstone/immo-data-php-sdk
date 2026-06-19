<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class Dpe
{
    public function __construct(
        public string $dpeNumber,
        public ?string $dpeRating,
        public ?string $gesRating,
        public ?string $dpeCreationDate,
        public ?float $energyConsFinal,
        public ?float $energyConsPrimary,
        public ?float $gazEmission,
        public ?DpeLocation $location,
        public ?DpeRealty $realty,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            dpeNumber: $data['dpeNumber'],
            dpeRating: $data['dpeRating'] ?? null,
            gesRating: $data['gesRating'] ?? null,
            dpeCreationDate: $data['dpeCreationDate'] ?? null,
            energyConsFinal: isset($data['energyConsFinal']) ? (float) $data['energyConsFinal'] : null,
            energyConsPrimary: isset($data['energyConsPrimary']) ? (float) $data['energyConsPrimary'] : null,
            gazEmission: isset($data['gazEmission']) ? (float) $data['gazEmission'] : null,
            location: isset($data['location']) ? DpeLocation::fromArray($data['location']) : null,
            realty: isset($data['realty']) ? DpeRealty::fromArray($data['realty']) : null,
        );
    }
}
