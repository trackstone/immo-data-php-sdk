<?php

declare(strict_types=1);

namespace ImmoData\Tests\Requests;

use ImmoData\Enums\Condition;
use ImmoData\Enums\DateRef;
use ImmoData\Enums\Dpe;
use ImmoData\Enums\GeoLevel;
use ImmoData\Enums\ListingGroupBy;
use ImmoData\Enums\ListingMetric;
use ImmoData\Enums\ListingStat;
use ImmoData\Enums\RealtyType;
use ImmoData\Requests\ListingsStatisticsRequest;
use PHPUnit\Framework\TestCase;

final class ListingsStatisticsRequestTest extends TestCase
{
    public function test_minimal_request(): void
    {
        $request = new ListingsStatisticsRequest(
            metrics: [ListingMetric::SquareMeterPrice],
            stats: [ListingStat::Mean],
            realtyType: RealtyType::Apartment,
            code: '75114',
            geoLevel: GeoLevel::City,
        );

        $params = $request->toArray();

        $this->assertSame('squareMeterPrice', $params['metrics']);
        $this->assertSame('mean', $params['stats']);
        $this->assertSame('apartment', $params['realtyType']);
        $this->assertSame('sales', $params['marketType']);
        $this->assertSame('75114', $params['code']);
        $this->assertSame('city', $params['geoLevel']);
        $this->assertArrayNotHasKey('percentiles', $params);
        $this->assertArrayNotHasKey('groupBy', $params);
        $this->assertArrayNotHasKey('isActive', $params);
    }

    public function test_multiple_metrics_and_stats_are_comma_separated(): void
    {
        $request = new ListingsStatisticsRequest(
            metrics: [ListingMetric::SquareMeterPrice, ListingMetric::Price, ListingMetric::DaysOnMarket],
            stats: [ListingStat::Mean, ListingStat::Count, ListingStat::Percentile],
            realtyType: RealtyType::House,
            percentiles: [10, 50, 90],
        );

        $params = $request->toArray();

        $this->assertSame('squareMeterPrice,price,daysOnMarket', $params['metrics']);
        $this->assertSame('mean,count,percentile', $params['stats']);
        $this->assertSame('10,50,90', $params['percentiles']);
    }

    public function test_group_by_with_bounds(): void
    {
        $request = new ListingsStatisticsRequest(
            metrics: [ListingMetric::Price],
            stats: [ListingStat::Mean],
            realtyType: RealtyType::Apartment,
            groupBy: ListingGroupBy::Price,
            bounds: [200000, 300000, 500000],
        );

        $params = $request->toArray();

        $this->assertSame('price', $params['groupBy']);
        $this->assertSame('200000,300000,500000', $params['bounds']);
    }

    public function test_coordinates_search(): void
    {
        $request = new ListingsStatisticsRequest(
            metrics: [ListingMetric::SquareMeterPrice],
            stats: [ListingStat::Mean],
            realtyType: RealtyType::Apartment,
            latitude: 48.8566,
            longitude: 2.3522,
            radius: 1000,
        );

        $params = $request->toArray();

        $this->assertSame(48.8566, $params['latitude']);
        $this->assertSame(2.3522, $params['longitude']);
        $this->assertSame(1000, $params['radius']);
        $this->assertArrayNotHasKey('code', $params);
        $this->assertArrayNotHasKey('geoLevel', $params);
    }

    public function test_booleans_are_serialized_as_strings(): void
    {
        $request = new ListingsStatisticsRequest(
            metrics: [ListingMetric::Price],
            stats: [ListingStat::Mean],
            realtyType: RealtyType::House,
            isActive: true,
            isNew: false,
        );

        $params = $request->toArray();

        $this->assertSame('true', $params['isActive']);
        $this->assertSame('false', $params['isNew']);
    }

    public function test_all_filters(): void
    {
        $request = new ListingsStatisticsRequest(
            metrics: [ListingMetric::LivingArea],
            stats: [ListingStat::Count],
            realtyType: RealtyType::Apartment,
            code: '75114',
            geoLevel: GeoLevel::City,
            dateRef: DateRef::Removed,
            dateMin: '2026-01-01',
            dateMax: '2026-06-30',
            condition: Condition::ToRenovate,
            priceMin: 200000,
            priceMax: 600000,
            daysOnMarketMin: 0,
            daysOnMarketMax: 365,
            sqmPriceMin: 5000,
            sqmPriceMax: 15000,
            livingAreaMin: 30,
            livingAreaMax: 120,
            roomsMin: 2,
            roomsMax: 5,
            bedroomsMin: 1,
            bedroomsMax: 3,
            floorMin: 0,
            floorMax: 10,
            landAreaMin: 100,
            landAreaMax: 1000,
            dpeRating: [Dpe::C, Dpe::D, Dpe::E],
        );

        $params = $request->toArray();

        $this->assertSame('removed', $params['dateRef']);
        $this->assertSame('2026-01-01', $params['dateMin']);
        $this->assertSame('2026-06-30', $params['dateMax']);
        $this->assertSame(-1, $params['condition']);
        $this->assertSame(200000, $params['priceMin']);
        $this->assertSame(600000, $params['priceMax']);
        $this->assertSame(0, $params['daysOnMarketMin']);
        $this->assertSame(365, $params['daysOnMarketMax']);
        $this->assertSame(5000, $params['sqmPriceMin']);
        $this->assertSame(15000, $params['sqmPriceMax']);
        $this->assertSame(30, $params['livingAreaMin']);
        $this->assertSame(120, $params['livingAreaMax']);
        $this->assertSame(2, $params['roomsMin']);
        $this->assertSame(5, $params['roomsMax']);
        $this->assertSame(1, $params['bedroomsMin']);
        $this->assertSame(3, $params['bedroomsMax']);
        $this->assertSame(0, $params['floorMin']);
        $this->assertSame(10, $params['floorMax']);
        $this->assertSame(100, $params['landAreaMin']);
        $this->assertSame(1000, $params['landAreaMax']);
        $this->assertSame('C,D,E', $params['dpeRating']);
    }
}
