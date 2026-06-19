<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class DpeRealty
{
    public function __construct(
        public ?string $realtyType,
        public ?float $livingArea,
        public ?int $constructionYear,
        public ?float $ceilingHeight,
        public ?float $apartmentLevel,
        public ?float $buildingNbFloor,
        public ?float $dwellingNbFloor,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            realtyType: $data['realtyType'] ?? null,
            livingArea: isset($data['livingArea']) ? (float) $data['livingArea'] : null,
            constructionYear: isset($data['constructionYear']) ? (int) $data['constructionYear'] : null,
            ceilingHeight: isset($data['ceilingHeight']) ? (float) $data['ceilingHeight'] : null,
            apartmentLevel: isset($data['apartmentLevel']) ? (float) $data['apartmentLevel'] : null,
            buildingNbFloor: isset($data['buildingNbFloor']) ? (float) $data['buildingNbFloor'] : null,
            dwellingNbFloor: isset($data['dwellingNbFloor']) ? (float) $data['dwellingNbFloor'] : null,
        );
    }
}
