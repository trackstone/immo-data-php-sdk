<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class ListingsMetricStats
{
    /**
     * @param PercentileValue[]|null $percentiles
     */
    public function __construct(
        public ?float $mean,
        public ?int $count,
        public ?array $percentiles,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            mean: isset($data['mean']) ? (float) $data['mean'] : null,
            count: $data['count'] ?? null,
            percentiles: isset($data['percentiles'])
                ? array_map(fn(array $p) => PercentileValue::fromArray($p), $data['percentiles'])
                : null,
        );
    }
}
