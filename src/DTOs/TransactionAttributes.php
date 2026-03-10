<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class TransactionAttributes
{
    public function __construct(
        public ?int $livingArea,
        public ?int $landArea,
        public ?int $rooms,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            livingArea: $data['livingArea'] ?? null,
            landArea: $data['landArea'] ?? null,
            rooms: $data['rooms'] ?? null,
        );
    }
}
