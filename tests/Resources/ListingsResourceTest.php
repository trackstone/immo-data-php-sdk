<?php

declare(strict_types=1);

namespace ImmoData\Tests\Resources;

use ImmoData\DTOs\ListingsStatistics;
use ImmoData\DTOs\ListingsStatisticsBucket;
use ImmoData\Enums\GeoLevel;
use ImmoData\Enums\ListingGroupBy;
use ImmoData\Enums\ListingMetric;
use ImmoData\Enums\ListingStat;
use ImmoData\Enums\RealtyType;
use ImmoData\Requests\ListingsStatisticsRequest;
use ImmoData\Resources\ListingsResource;
use ImmoData\Tests\MockHttpClient;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ListingsResourceTest extends TestCase
{
    private function sampleApiResponse(): array
    {
        return [
            'data' => [
                [
                    'size' => 1391,
                    'metrics' => [
                        'squareMeterPrice' => [
                            'mean' => 9500.2,
                            'percentiles' => [
                                ['percentile' => 10, 'value' => 7100],
                                ['percentile' => 50, 'value' => 9400],
                                ['percentile' => 90, 'value' => 12000],
                            ],
                        ],
                        'price' => [
                            'mean' => 452000,
                            'count' => 1391,
                        ],
                    ],
                ],
            ],
        ];
    }

    public function test_statistics_ungrouped(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/listings/statistics', $this->sampleApiResponse());

        $resource = new ListingsResource($http);
        $result = $resource->statistics(new ListingsStatisticsRequest(
            metrics: [ListingMetric::SquareMeterPrice, ListingMetric::Price],
            stats: [ListingStat::Mean, ListingStat::Percentile],
            realtyType: RealtyType::Apartment,
            code: '75114',
            geoLevel: GeoLevel::City,
        ));

        $this->assertInstanceOf(ListingsStatistics::class, $result);
        $this->assertNull($result->groupBy);
        $this->assertCount(1, $result->data);

        $bucket = $result->data[0];
        $this->assertInstanceOf(ListingsStatisticsBucket::class, $bucket);
        $this->assertSame(1391, $bucket->size);
        $this->assertNull($bucket->key);
        $this->assertNull($bucket->from);
        $this->assertNull($bucket->to);

        $sqmPrice = $bucket->metrics['squareMeterPrice'];
        $this->assertSame(9500.2, $sqmPrice->mean);
        $this->assertNull($sqmPrice->count);
        $this->assertCount(3, $sqmPrice->percentiles);
        $this->assertSame(50, $sqmPrice->percentiles[1]->percentile);
        $this->assertSame(9400.0, $sqmPrice->percentiles[1]->value);

        $price = $bucket->metrics['price'];
        $this->assertSame(452000.0, $price->mean);
        $this->assertSame(1391, $price->count);
        $this->assertNull($price->percentiles);
    }

    public function test_statistics_sends_correct_params(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/listings/statistics', $this->sampleApiResponse());

        $resource = new ListingsResource($http);
        $resource->statistics(new ListingsStatisticsRequest(
            metrics: [ListingMetric::SquareMeterPrice],
            stats: [ListingStat::Mean],
            realtyType: RealtyType::Apartment,
            code: '75114',
            geoLevel: GeoLevel::City,
        ));

        $request = $http->getLastRequest();
        $this->assertSame('/v1/listings/statistics', $request['path']);
        $this->assertSame('squareMeterPrice', $request['query']['metrics']);
        $this->assertSame('mean', $request['query']['stats']);
        $this->assertSame('apartment', $request['query']['realtyType']);
        $this->assertSame('sales', $request['query']['marketType']);
    }

    public function test_statistics_grouped_by_dpe_rating(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/listings/statistics', [
            'groupBy' => 'dpeRating',
            'data' => [
                ['key' => 'C', 'size' => 320, 'metrics' => ['price' => ['mean' => 410000]]],
                ['key' => 'D', 'size' => 280, 'metrics' => ['price' => ['mean' => 385000]]],
            ],
        ]);

        $resource = new ListingsResource($http);
        $result = $resource->statistics(new ListingsStatisticsRequest(
            metrics: [ListingMetric::Price],
            stats: [ListingStat::Mean],
            realtyType: RealtyType::Apartment,
            groupBy: ListingGroupBy::DpeRating,
            code: '75114',
            geoLevel: GeoLevel::City,
        ));

        $this->assertSame('dpeRating', $result->groupBy);
        $this->assertCount(2, $result->data);
        $this->assertSame('C', $result->data[0]->key);
        $this->assertSame(320, $result->data[0]->size);
        $this->assertSame(410000.0, $result->data[0]->metrics['price']->mean);
    }

    public function test_statistics_grouped_by_bounds(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/listings/statistics', [
            'groupBy' => 'price',
            'data' => [
                ['from' => 200000, 'to' => 300000, 'size' => 120, 'metrics' => []],
                ['from' => 300000, 'size' => 85, 'metrics' => []],
            ],
        ]);

        $resource = new ListingsResource($http);
        $result = $resource->statistics(new ListingsStatisticsRequest(
            metrics: [ListingMetric::Price],
            stats: [ListingStat::Count],
            realtyType: RealtyType::Apartment,
            groupBy: ListingGroupBy::Price,
            bounds: [200000, 300000],
            code: '75114',
            geoLevel: GeoLevel::City,
        ));

        $this->assertSame(200000.0, $result->data[0]->from);
        $this->assertSame(300000.0, $result->data[0]->to);
        $this->assertSame(300000.0, $result->data[1]->from);
        $this->assertNull($result->data[1]->to);
    }

    public function test_statistics_empty_cohort(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/listings/statistics', [
            'data' => [
                ['size' => 0, 'metrics' => ['price' => ['mean' => null]]],
            ],
        ]);

        $resource = new ListingsResource($http);
        $result = $resource->statistics(new ListingsStatisticsRequest(
            metrics: [ListingMetric::Price],
            stats: [ListingStat::Mean],
            realtyType: RealtyType::House,
            code: '75114',
            geoLevel: GeoLevel::City,
        ));

        $this->assertSame(0, $result->data[0]->size);
        $this->assertNull($result->data[0]->metrics['price']->mean);
    }

    public function test_statistics_rejects_unsupported_geo_level(): void
    {
        $resource = new ListingsResource(new MockHttpClient());

        $this->expectException(InvalidArgumentException::class);

        $resource->statistics(new ListingsStatisticsRequest(
            metrics: [ListingMetric::Price],
            stats: [ListingStat::Mean],
            realtyType: RealtyType::House,
            code: '75',
            geoLevel: GeoLevel::Department,
        ));
    }
}
