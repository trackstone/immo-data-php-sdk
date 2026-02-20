<?php

declare(strict_types=1);

namespace ImmoData\Sdk\Resources;

use ImmoData\Sdk\DTOs\GeocodeResult;
use ImmoData\Sdk\Enums\GeoLevel;
use ImmoData\Sdk\HttpClient\HttpClientInterface;

final class GeocodeResource
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
    ) {}

    /**
     * @param GeoLevel[]|null $geoLevels
     * @return GeocodeResult[]
     */
    public function search(string $query, ?array $geoLevels = null, int $limit = 5): array
    {
        $params = [
            'q' => $query,
            'limit' => $limit,
        ];

        if ($geoLevels !== null) {
            $params['geoLevel'] = implode(',', array_map(
                fn(GeoLevel $level) => $level->value,
                $geoLevels,
            ));
        }

        $response = $this->httpClient->get('/v1/geocode', $params);

        return GeocodeResult::fromArrayList($response);
    }
}
