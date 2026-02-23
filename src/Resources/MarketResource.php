<?php

declare(strict_types=1);

namespace ImmoData\Resources;

use ImmoData\DTOs\CurrentPrice;
use ImmoData\DTOs\PriceHistory;
use ImmoData\Enums\GeoLevel;
use ImmoData\Enums\Interval;
use ImmoData\Enums\MarketType;
use ImmoData\Enums\Metric;
use ImmoData\Enums\RealtyType;
use ImmoData\HttpClient\HttpClientInterface;
use InvalidArgumentException;

final class MarketResource
{
    private const array ALLOWED_GEO_LEVELS = [
        GeoLevel::Department,
        GeoLevel::City,
        GeoLevel::District,
    ];

    public function __construct(
        private readonly HttpClientInterface $httpClient,
    ) {}

    public function priceHistory(
        string $code,
        GeoLevel $geoLevel,
        RealtyType $realtyType,
        ?string $startDate = null,
        ?string $endDate = null,
    ): PriceHistory {
        $this->validateGeoLevel($geoLevel);

        $params = [
            'code' => $code,
            'geoLevel' => $geoLevel->value,
            'marketType' => MarketType::Sales->value,
            'realtyType' => $realtyType->value,
            'metric' => Metric::SqmPrice->value,
            'interval' => Interval::Monthly->value,
        ];

        if ($startDate !== null) {
            $params['startDate'] = $startDate;
        }
        if ($endDate !== null) {
            $params['endDate'] = $endDate;
        }

        $response = $this->httpClient->get('/v1/market/price/history', $params);

        return PriceHistory::fromArray($response);
    }

    public function currentPrice(
        string $code,
        GeoLevel $geoLevel,
        RealtyType $realtyType,
    ): CurrentPrice {
        $this->validateGeoLevel($geoLevel);

        $params = [
            'code' => $code,
            'geoLevel' => $geoLevel->value,
            'marketType' => MarketType::Sales->value,
            'realtyType' => $realtyType->value,
            'metric' => Metric::SqmPrice->value,
        ];

        $response = $this->httpClient->get('/v1/market/price/current', $params);

        return CurrentPrice::fromArray($response);
    }

    private function validateGeoLevel(GeoLevel $geoLevel): void
    {
        if (!in_array($geoLevel, self::ALLOWED_GEO_LEVELS, true)) {
            $allowed = implode(', ', array_map(fn(GeoLevel $l) => $l->value, self::ALLOWED_GEO_LEVELS));
            throw new InvalidArgumentException(
                "Market endpoints only support geo levels: {$allowed}. Got: {$geoLevel->value}",
            );
        }
    }
}
