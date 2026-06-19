<?php

declare(strict_types=1);

namespace ImmoData\Tests\Resources;

use ImmoData\DTOs\CurrentPrice;
use ImmoData\DTOs\PriceHistory;
use ImmoData\DTOs\SaleDurationCurrent;
use ImmoData\DTOs\SaleDurationHistory;
use ImmoData\Enums\DurationUnit;
use ImmoData\Enums\GeoLevel;
use ImmoData\Enums\RealtyType;
use ImmoData\Resources\MarketResource;
use ImmoData\Tests\MockHttpClient;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class MarketResourceTest extends TestCase
{
    public function test_price_history(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/market/price/history', [
            'metric' => 'sqm_price',
            'data' => [
                ['period' => '2024-01', 'value' => 10234.5],
            ],
        ]);

        $resource = new MarketResource($http);
        $result = $resource->priceHistory(
            code: '75056',
            geoLevel: GeoLevel::City,
            realtyType: RealtyType::Apartment,
        );

        $this->assertInstanceOf(PriceHistory::class, $result);
        $this->assertSame('sqm_price', $result->metric);

        $query = $http->getLastRequest()['query'];
        $this->assertSame('75056', $query['code']);
        $this->assertSame('city', $query['geoLevel']);
        $this->assertSame('sales', $query['marketType']);
        $this->assertSame('apartment', $query['realtyType']);
        $this->assertSame('sqm_price', $query['metric']);
        $this->assertSame('monthly', $query['interval']);
    }

    public function test_price_history_with_dates(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/market/price/history', [
            'metric' => 'sqm_price',
            'data' => [],
        ]);

        $resource = new MarketResource($http);
        $resource->priceHistory(
            code: '75',
            geoLevel: GeoLevel::Department,
            realtyType: RealtyType::House,
            startDate: '2020-01-01',
            endDate: '2024-12-31',
        );

        $query = $http->getLastRequest()['query'];
        $this->assertSame('2020-01-01', $query['startDate']);
        $this->assertSame('2024-12-31', $query['endDate']);
    }

    public function test_current_price(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/market/price/current', [
            'metric' => 'sqm_price',
            'value' => 10500.75,
        ]);

        $resource = new MarketResource($http);
        $result = $resource->currentPrice(
            code: '75',
            geoLevel: GeoLevel::Department,
            realtyType: RealtyType::Apartment,
        );

        $this->assertInstanceOf(CurrentPrice::class, $result);
        $this->assertSame(10500.75, $result->value);
    }

    public function test_rejects_region_geo_level(): void
    {
        $http = new MockHttpClient();
        $resource = new MarketResource($http);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Market endpoints only support geo levels');

        $resource->priceHistory(
            code: '11',
            geoLevel: GeoLevel::Region,
            realtyType: RealtyType::Apartment,
        );
    }

    public function test_rejects_street_geo_level(): void
    {
        $http = new MockHttpClient();
        $resource = new MarketResource($http);

        $this->expectException(InvalidArgumentException::class);

        $resource->currentPrice(
            code: 'some-code',
            geoLevel: GeoLevel::Street,
            realtyType: RealtyType::House,
        );
    }

    public function test_accepts_district_geo_level(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/market/price/current', [
            'metric' => 'sqm_price',
            'value' => 8500.0,
        ]);

        $resource = new MarketResource($http);
        $result = $resource->currentPrice(
            code: '7514',
            geoLevel: GeoLevel::District,
            realtyType: RealtyType::Apartment,
        );

        $this->assertSame(8500.0, $result->value);
    }

    public function test_sale_duration_history(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/market/sale-duration/history', [
            'unit' => 'days',
            'data' => [
                ['period' => '2024-01', 'value' => 82],
                ['period' => '2024-02', 'value' => 90],
            ],
        ]);

        $resource = new MarketResource($http);
        $result = $resource->saleDurationHistory(
            code: '75114',
            geoLevel: GeoLevel::City,
        );

        $this->assertInstanceOf(SaleDurationHistory::class, $result);
        $this->assertSame('days', $result->unit);
        $this->assertCount(2, $result->data);
        $this->assertSame('2024-01', $result->data[0]->period);
        $this->assertSame(82.0, $result->data[0]->value);

        $query = $http->getLastRequest()['query'];
        $this->assertSame('75114', $query['code']);
        $this->assertSame('city', $query['geoLevel']);
        $this->assertSame('monthly', $query['interval']);
        $this->assertSame('days', $query['unit']);
    }

    public function test_sale_duration_history_with_dates_and_unit(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/market/sale-duration/history', [
            'unit' => 'months',
            'data' => [],
        ]);

        $resource = new MarketResource($http);
        $resource->saleDurationHistory(
            code: '75',
            geoLevel: GeoLevel::Department,
            startDate: '2022-01',
            endDate: '2024-12',
            unit: DurationUnit::Months,
        );

        $query = $http->getLastRequest()['query'];
        $this->assertSame('2022-01', $query['startDate']);
        $this->assertSame('2024-12', $query['endDate']);
        $this->assertSame('months', $query['unit']);
    }

    public function test_sale_duration_history_rejects_region_geo_level(): void
    {
        $http = new MockHttpClient();
        $resource = new MarketResource($http);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Market endpoints only support geo levels');

        $resource->saleDurationHistory(
            code: '11',
            geoLevel: GeoLevel::Region,
        );
    }

    public function test_current_sale_duration(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/market/sale-duration/current', [
            'unit' => 'days',
            'value' => 90,
        ]);

        $resource = new MarketResource($http);
        $result = $resource->currentSaleDuration(
            code: '75114',
            geoLevel: GeoLevel::City,
        );

        $this->assertInstanceOf(SaleDurationCurrent::class, $result);
        $this->assertSame('days', $result->unit);
        $this->assertSame(90.0, $result->value);

        $query = $http->getLastRequest()['query'];
        $this->assertSame('75114', $query['code']);
        $this->assertSame('city', $query['geoLevel']);
        $this->assertSame('days', $query['unit']);
    }

    public function test_current_sale_duration_handles_null_value(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/market/sale-duration/current', [
            'unit' => 'months',
            'value' => null,
        ]);

        $resource = new MarketResource($http);
        $result = $resource->currentSaleDuration(
            code: '99999',
            geoLevel: GeoLevel::City,
            unit: DurationUnit::Months,
        );

        $this->assertNull($result->value);
        $this->assertSame('months', $result->unit);
    }
}
