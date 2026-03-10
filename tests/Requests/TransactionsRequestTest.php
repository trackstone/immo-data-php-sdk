<?php

declare(strict_types=1);

namespace ImmoData\Tests\Requests;

use ImmoData\Enums\GeoLevel;
use ImmoData\Enums\SortBy;
use ImmoData\Enums\SortOrder;
use ImmoData\Enums\TxRealtyType;
use ImmoData\Enums\TxType;
use ImmoData\Requests\TransactionsRequest;
use PHPUnit\Framework\TestCase;

final class TransactionsRequestTest extends TestCase
{
    public function test_defaults(): void
    {
        $request = new TransactionsRequest();
        $params = $request->toArray();

        $this->assertSame('date', $params['sortBy']);
        $this->assertSame('desc', $params['sortOrder']);
        $this->assertSame(20, $params['size']);
        $this->assertArrayNotHasKey('code', $params);
        $this->assertArrayNotHasKey('searchAfter', $params);
    }

    public function test_code_based_search(): void
    {
        $request = new TransactionsRequest(
            code: '75114',
            geoLevel: GeoLevel::City,
        );
        $params = $request->toArray();

        $this->assertSame('75114', $params['code']);
        $this->assertSame('city', $params['geoLevel']);
    }

    public function test_coordinate_based_search(): void
    {
        $request = new TransactionsRequest(
            latitude: 48.8698,
            longitude: 2.3308,
            radius: 500,
        );
        $params = $request->toArray();

        $this->assertSame(48.8698, $params['latitude']);
        $this->assertSame(2.3308, $params['longitude']);
        $this->assertSame(500, $params['radius']);
    }

    public function test_tx_type_filter(): void
    {
        $request = new TransactionsRequest(
            txType: [TxType::Sales, TxType::Vefa],
        );
        $params = $request->toArray();

        $this->assertSame('sales,vefa', $params['txType']);
    }

    public function test_realty_type_filter(): void
    {
        $request = new TransactionsRequest(
            realtyType: [TxRealtyType::Apartment, TxRealtyType::House],
        );
        $params = $request->toArray();

        $this->assertSame('apartment,house', $params['realtyType']);
    }

    public function test_price_filters(): void
    {
        $request = new TransactionsRequest(
            priceMin: 100000,
            priceMax: 500000,
            sqmPriceMin: 3000,
            sqmPriceMax: 15000,
        );
        $params = $request->toArray();

        $this->assertSame(100000, $params['priceMin']);
        $this->assertSame(500000, $params['priceMax']);
        $this->assertSame(3000, $params['sqmPriceMin']);
        $this->assertSame(15000, $params['sqmPriceMax']);
    }

    public function test_date_filters(): void
    {
        $request = new TransactionsRequest(
            dateMin: '2023-01-01',
            dateMax: '2024-12-31',
        );
        $params = $request->toArray();

        $this->assertSame('2023-01-01', $params['dateMin']);
        $this->assertSame('2024-12-31', $params['dateMax']);
    }

    public function test_area_and_room_filters(): void
    {
        $request = new TransactionsRequest(
            minRoom: 2,
            maxRoom: 5,
            livingAreaMin: 40,
            livingAreaMax: 200,
            landAreaMin: 100,
            landAreaMax: 2000,
        );
        $params = $request->toArray();

        $this->assertSame(2, $params['minRoom']);
        $this->assertSame(5, $params['maxRoom']);
        $this->assertSame(40, $params['livingAreaMin']);
        $this->assertSame(200, $params['livingAreaMax']);
        $this->assertSame(100, $params['landAreaMin']);
        $this->assertSame(2000, $params['landAreaMax']);
    }

    public function test_pagination(): void
    {
        $request = new TransactionsRequest(
            sortBy: SortBy::Price,
            sortOrder: SortOrder::Asc,
            size: 50,
            searchAfter: 'cursor-xyz',
        );
        $params = $request->toArray();

        $this->assertSame('price', $params['sortBy']);
        $this->assertSame('asc', $params['sortOrder']);
        $this->assertSame(50, $params['size']);
        $this->assertSame('cursor-xyz', $params['searchAfter']);
    }

    public function test_subdistrict_geo_level(): void
    {
        $request = new TransactionsRequest(
            code: '751140101',
            geoLevel: GeoLevel::Subdistrict,
        );
        $params = $request->toArray();

        $this->assertSame('subdistrict', $params['geoLevel']);
    }

    public function test_parcel_geo_level(): void
    {
        $request = new TransactionsRequest(
            code: '75114000AB0001',
            geoLevel: GeoLevel::Parcel,
        );
        $params = $request->toArray();

        $this->assertSame('parcel', $params['geoLevel']);
    }
}
