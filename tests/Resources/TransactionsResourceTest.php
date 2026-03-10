<?php

declare(strict_types=1);

namespace ImmoData\Tests\Resources;

use ImmoData\DTOs\Transaction;
use ImmoData\DTOs\TransactionList;
use ImmoData\Enums\GeoLevel;
use ImmoData\Enums\TxRealtyType;
use ImmoData\Enums\TxType;
use ImmoData\Requests\TransactionsRequest;
use ImmoData\Resources\TransactionsResource;
use ImmoData\Tests\MockHttpClient;
use PHPUnit\Framework\TestCase;

final class TransactionsResourceTest extends TestCase
{
    private function sampleApiResponse(): array
    {
        return [
            'total' => 1,
            'size' => 20,
            'searchAfter' => null,
            'data' => [
                [
                    'txId' => 'tx-456',
                    'txDate' => '2024-03-10',
                    'txType' => 'sales',
                    'price' => 280000,
                    'squareMeterPrice' => 7000,
                    'realtyType' => 'apartment',
                    'attributes' => ['livingArea' => 40, 'landArea' => null, 'rooms' => 2],
                    'lot' => [
                        [
                            'parcelId' => '75056000AB0001',
                            'location' => [
                                'address' => [
                                    'inseeCode' => '75056',
                                    'departmentCode' => '75',
                                    'districtCode' => '7505601',
                                    'subdistrictCode' => '750560101',
                                    'postCode' => '75001',
                                    'cityName' => 'Paris',
                                    'streetName' => null,
                                    'streetNumber' => null,
                                    'streetCode' => null,
                                    'streetType' => null,
                                    'streetSuffix' => null,
                                    'addressId' => null,
                                ],
                                'geometry' => ['type' => 'Point', 'coordinates' => [2.347, 48.859]],
                            ],
                            'realty' => [],
                            'landArea' => 0,
                        ],
                    ],
                ],
            ],
        ];
    }

    public function test_search_by_code(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/transactions', $this->sampleApiResponse());

        $resource = new TransactionsResource($http);
        $result = $resource->search(new TransactionsRequest(
            code: '75056',
            geoLevel: GeoLevel::City,
        ));

        $this->assertInstanceOf(TransactionList::class, $result);
        $this->assertSame(1, $result->total);
        $this->assertCount(1, $result->data);
        $this->assertInstanceOf(Transaction::class, $result->data[0]);
        $this->assertSame(TxType::Sales, $result->data[0]->txType);
        $this->assertSame(TxRealtyType::Apartment, $result->data[0]->realtyType);
    }

    public function test_search_sends_correct_params(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/transactions', $this->sampleApiResponse());

        $resource = new TransactionsResource($http);
        $resource->search(new TransactionsRequest(
            code: '75056',
            geoLevel: GeoLevel::City,
        ));

        $query = $http->getLastRequest()['query'];
        $this->assertSame('75056', $query['code']);
        $this->assertSame('city', $query['geoLevel']);
        $this->assertSame('date', $query['sortBy']);
        $this->assertSame('desc', $query['sortOrder']);
        $this->assertSame(20, $query['size']);
    }

    public function test_search_by_coordinates(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/transactions', $this->sampleApiResponse());

        $resource = new TransactionsResource($http);
        $resource->search(new TransactionsRequest(
            latitude: 48.8698,
            longitude: 2.3308,
            radius: 300,
        ));

        $query = $http->getLastRequest()['query'];
        $this->assertSame(48.8698, $query['latitude']);
        $this->assertSame(2.3308, $query['longitude']);
        $this->assertSame(300, $query['radius']);
    }

    public function test_search_with_filters(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/transactions', $this->sampleApiResponse());

        $resource = new TransactionsResource($http);
        $resource->search(new TransactionsRequest(
            code: '75',
            geoLevel: GeoLevel::Department,
            txType: [TxType::Sales],
            realtyType: [TxRealtyType::Apartment],
            dateMin: '2023-01-01',
            priceMax: 500000,
        ));

        $query = $http->getLastRequest()['query'];
        $this->assertSame('sales', $query['txType']);
        $this->assertSame('apartment', $query['realtyType']);
        $this->assertSame('2023-01-01', $query['dateMin']);
        $this->assertSame(500000, $query['priceMax']);
    }

    public function test_empty_results(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/transactions', [
            'total' => 0,
            'size' => 20,
            'searchAfter' => null,
            'data' => [],
        ]);

        $resource = new TransactionsResource($http);
        $result = $resource->search(new TransactionsRequest(code: '01001', geoLevel: GeoLevel::City));

        $this->assertSame(0, $result->total);
        $this->assertCount(0, $result->data);
        $this->assertNull($result->searchAfter);
    }
}
