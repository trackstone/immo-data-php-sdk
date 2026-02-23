<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class ValuationResult
{
    public function __construct(
        public float $mainValuation,
        public float $upperValuation,
        public float $lowerValuation,
        public int $confidence,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            mainValuation: (float) $data['mainValuation'],
            upperValuation: (float) $data['upperValuation'],
            lowerValuation: (float) $data['lowerValuation'],
            confidence: (int) $data['confidence'],
        );
    }
}
