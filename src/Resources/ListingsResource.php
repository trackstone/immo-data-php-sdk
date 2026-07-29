<?php

declare(strict_types=1);

namespace ImmoData\Resources;

use ImmoData\DTOs\ListingsStatistics;
use ImmoData\Enums\GeoLevel;
use ImmoData\HttpClient\HttpClientInterface;
use ImmoData\Requests\ListingsStatisticsRequest;
use InvalidArgumentException;

final class ListingsResource
{
    private const array ALLOWED_GEO_LEVELS = [
        GeoLevel::City,
        GeoLevel::District,
        GeoLevel::Subdistrict,
    ];

    public function __construct(
        private readonly HttpClientInterface $httpClient,
    ) {}

    public function statistics(ListingsStatisticsRequest $request): ListingsStatistics
    {
        if ($request->geoLevel !== null) {
            $this->validateGeoLevel($request->geoLevel);
        }

        $response = $this->httpClient->get('/v1/listings/statistics', $request->toArray());

        return ListingsStatistics::fromArray($response);
    }

    private function validateGeoLevel(GeoLevel $geoLevel): void
    {
        if (!in_array($geoLevel, self::ALLOWED_GEO_LEVELS, true)) {
            $allowed = implode(', ', array_map(fn(GeoLevel $l) => $l->value, self::ALLOWED_GEO_LEVELS));
            throw new InvalidArgumentException(
                "Listings endpoints only support geo levels: {$allowed}. Got: {$geoLevel->value}",
            );
        }
    }
}
