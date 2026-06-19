<?php

declare(strict_types=1);

namespace ImmoData\Tests\Resources;

use ImmoData\DTOs\Dpe;
use ImmoData\DTOs\DpeList;
use ImmoData\Enums\Dpe as DpeRating;
use ImmoData\Enums\GeoLevel;
use ImmoData\Enums\RealtyType;
use ImmoData\Requests\DpeRequest;
use ImmoData\Resources\DpeResource;
use ImmoData\Tests\MockHttpClient;
use PHPUnit\Framework\TestCase;

final class DpeResourceTest extends TestCase
{
    private function sampleApiResponse(): array
    {
        return [
            'total' => 1,
            'size' => 20,
            'searchAfter' => null,
            'data' => [
                [
                    'dpeNumber' => '2375E1234567A',
                    'dpeRating' => 'D',
                    'gesRating' => 'C',
                    'dpeCreationDate' => '2024-03-04',
                    'energyConsFinal' => 180.5,
                    'energyConsPrimary' => 220.3,
                    'gazEmission' => 25.1,
                    'location' => [
                        'address' => [
                            'inseeCode' => '75114',
                            'departmentCode' => '75',
                            'districtCode' => '7511401',
                            'postCode' => '75014',
                            'cityName' => 'Paris 14e Arrondissement',
                            'streetName' => 'Rue Daguerre',
                            'streetNumber' => '12',
                            'addressId' => '751140001W0001',
                        ],
                        'geometry' => ['type' => 'Point', 'coordinates' => [2.3265, 48.8339]],
                    ],
                    'realty' => [
                        'realtyType' => 'apartment',
                        'livingArea' => 63,
                        'constructionYear' => 1965,
                    ],
                ],
            ],
        ];
    }

    public function test_search_by_code(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/dpe', $this->sampleApiResponse());

        $resource = new DpeResource($http);
        $result = $resource->search(new DpeRequest(
            code: '75114',
            geoLevel: GeoLevel::City,
        ));

        $this->assertInstanceOf(DpeList::class, $result);
        $this->assertSame(1, $result->total);
        $this->assertCount(1, $result->data);

        $dpe = $result->data[0];
        $this->assertInstanceOf(Dpe::class, $dpe);
        $this->assertSame('2375E1234567A', $dpe->dpeNumber);
        $this->assertSame('D', $dpe->dpeRating);
        $this->assertSame(180.5, $dpe->energyConsFinal);
        $this->assertSame('Paris 14e Arrondissement', $dpe->location->address->cityName);
        $this->assertSame(2.3265, $dpe->location->geometry->longitude);
        $this->assertSame('apartment', $dpe->realty->realtyType);
        $this->assertSame(1965, $dpe->realty->constructionYear);
    }

    public function test_search_sends_correct_params(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/dpe', $this->sampleApiResponse());

        $resource = new DpeResource($http);
        $resource->search(new DpeRequest(
            code: '75114',
            geoLevel: GeoLevel::City,
        ));

        $query = $http->getLastRequest()['query'];
        $this->assertSame('75114', $query['code']);
        $this->assertSame('city', $query['geoLevel']);
        $this->assertSame('date', $query['sortBy']);
        $this->assertSame('desc', $query['sortOrder']);
        $this->assertSame(20, $query['size']);
    }

    public function test_search_by_coordinates(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/dpe', $this->sampleApiResponse());

        $resource = new DpeResource($http);
        $resource->search(new DpeRequest(
            latitude: 48.8566,
            longitude: 2.3522,
            radius: 1000,
        ));

        $query = $http->getLastRequest()['query'];
        $this->assertSame(48.8566, $query['latitude']);
        $this->assertSame(2.3522, $query['longitude']);
        $this->assertSame(1000, $query['radius']);
    }

    public function test_search_with_filters(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/dpe', $this->sampleApiResponse());

        $resource = new DpeResource($http);
        $resource->search(new DpeRequest(
            code: '75114',
            geoLevel: GeoLevel::City,
            dpeRating: [DpeRating::F, DpeRating::G],
            gesRating: [DpeRating::E, DpeRating::F, DpeRating::G],
            realtyType: [RealtyType::House, RealtyType::Apartment],
            constructionYearMin: 1950,
            energyConsFinalMax: 450.0,
        ));

        $query = $http->getLastRequest()['query'];
        $this->assertSame('F,G', $query['dpeRating']);
        $this->assertSame('E,F,G', $query['gesRating']);
        $this->assertSame('house,apartment', $query['realtyType']);
        $this->assertSame(1950, $query['constructionYearMin']);
        $this->assertSame(450.0, $query['energyConsFinalMax']);
    }

    public function test_handles_omitted_optional_fields(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/dpe', [
            'total' => 1,
            'size' => 20,
            'searchAfter' => null,
            'data' => [
                ['dpeNumber' => '2375E7654321B'],
            ],
        ]);

        $resource = new DpeResource($http);
        $result = $resource->search(new DpeRequest(code: '75114', geoLevel: GeoLevel::City));

        $dpe = $result->data[0];
        $this->assertSame('2375E7654321B', $dpe->dpeNumber);
        $this->assertNull($dpe->dpeRating);
        $this->assertNull($dpe->location);
        $this->assertNull($dpe->realty);
    }
}
