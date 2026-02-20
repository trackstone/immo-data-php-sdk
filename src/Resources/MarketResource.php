<?php

declare(strict_types=1);

namespace ImmoData\Sdk\Resources;

use ImmoData\Sdk\DTOs\CurrentPrice;
use ImmoData\Sdk\DTOs\PriceHistory;
use ImmoData\Sdk\Enums\GeoLevel;
use ImmoData\Sdk\Enums\Interval;
use ImmoData\Sdk\Enums\MarketType;
use ImmoData\Sdk\Enums\Metric;
use ImmoData\Sdk\Enums\RealtyType;
use ImmoData\Sdk\HttpClient\HttpClientInterface;
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
