<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

use ImmoData\Enums\TxRealtyType;

final readonly class TransactionRealty
{
    public function __construct(
        public ?int $livingArea,
        public ?int $landArea,
        public ?int $rooms,
        public ?TxRealtyType $realtyType,
        public ?string $landType,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            livingArea: $data['livingArea'] ?? null,
            landArea: $data['landArea'] ?? null,
            rooms: $data['rooms'] ?? null,
            realtyType: isset($data['realtyType']) ? TxRealtyType::from($data['realtyType']) : null,
            landType: $data['landType'] ?? null,
        );
    }
}
