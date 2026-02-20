<?php

declare(strict_types=1);

namespace ImmoData\Sdk\Tests\Resources;

use ImmoData\Sdk\DTOs\GeocodeResult;
use ImmoData\Sdk\Enums\GeoLevel;
use ImmoData\Sdk\Resources\GeocodeResource;
use ImmoData\Sdk\Tests\MockHttpClient;
use PHPUnit\Framework\TestCase;

final class GeocodeResourceTest extends TestCase
{
    public function test_search_basic(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/geocode', [
            [
                'geoLevel' => 'city',
                'regionName' => 'Ile-de-France',
                'regionCode' => '11',
                'cityName' => 'Paris',
                'inseeCode' => '75056',
                'label' => 'Paris',
            ],
        ]);

        $resource = new GeocodeResource($http);
        $results = $resource->search('Paris');

        $this->assertCount(1, $results);
        $this->assertInstanceOf(GeocodeResult::class, $results[0]);
        $this->assertSame('Paris', $results[0]->cityName);

        $lastRequest = $http->getLastRequest();
        $this->assertSame('/v1/geocode', $lastRequest['path']);
        $this->assertSame('Paris', $lastRequest['query']['q']);
        $this->assertSame(5, $lastRequest['query']['limit']);
        $this->assertArrayNotHasKey('geoLevel', $lastRequest['query']);
    }

    public function test_search_with_geo_levels(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/geocode', []);

        $resource = new GeocodeResource($http);
        $resource->search('Lyon', [GeoLevel::City, GeoLevel::District], 10);

        $lastRequest = $http->getLastRequest();
        $this->assertSame('city,district', $lastRequest['query']['geoLevel']);
        $this->assertSame(10, $lastRequest['query']['limit']);
    }
}
