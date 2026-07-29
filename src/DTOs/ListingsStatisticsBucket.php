<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

final readonly class ListingsStatisticsBucket
{
    /**
     * @param array<string, ListingsMetricStats> $metrics
     */
    public function __construct(
        public ?string $key,
        public ?float $from,
        public ?float $to,
        public int $size,
        public array $metrics,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            key: $data['key'] ?? null,
            from: isset($data['from']) ? (float) $data['from'] : null,
            to: isset($data['to']) ? (float) $data['to'] : null,
            size: $data['size'],
            metrics: array_map(
                fn(array $m) => ListingsMetricStats::fromArray($m),
                $data['metrics'] ?? [],
            ),
        );
    }
}
