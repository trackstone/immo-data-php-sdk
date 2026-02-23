<?php

declare(strict_types=1);

namespace ImmoData\Tests\Resources;

use ImmoData\DTOs\City;
use ImmoData\DTOs\Department;
use ImmoData\DTOs\District;
use ImmoData\DTOs\Region;
use ImmoData\DTOs\Subdistrict;
use ImmoData\Resources\GeoResource;
use ImmoData\Tests\MockHttpClient;
use PHPUnit\Framework\TestCase;

final class GeoResourceTest extends TestCase
{
    private static function boundaries(): array
    {
        return [
            'type' => 'Polygon',
            'coordinates' => [[[2.22, 48.81], [2.47, 48.90], [2.22, 48.81]]],
        ];
    }

    public function test_region(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/geo/regions/11', [
            'regionCode' => '11',
            'regionName' => 'Ile-de-France',
            'boundaries' => self::boundaries(),
        ]);

        $resource = new GeoResource($http);
        $region = $resource->region('11');

        $this->assertInstanceOf(Region::class, $region);
        $this->assertSame('Ile-de-France', $region->regionName);
        $this->assertSame('/v1/geo/regions/11', $http->getLastRequest()['path']);
    }

    public function test_department(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/geo/departments/75', [
            'departmentCode' => '75',
            'departmentName' => 'Paris',
            'regionCode' => '11',
            'boundaries' => self::boundaries(),
        ]);

        $resource = new GeoResource($http);
        $department = $resource->department('75');

        $this->assertInstanceOf(Department::class, $department);
        $this->assertSame('Paris', $department->departmentName);
    }

    public function test_city(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/geo/cities/75056', [
            'inseeCode' => '75056',
            'cityName' => 'Paris',
            'regionCode' => '11',
            'departmentCode' => '75',
            'postCode' => ['75001'],
            'boundaries' => self::boundaries(),
            'districtCodes' => ['7501'],
        ]);

        $resource = new GeoResource($http);
        $city = $resource->city('75056');

        $this->assertInstanceOf(City::class, $city);
        $this->assertSame('Paris', $city->cityName);
        $this->assertSame('/v1/geo/cities/75056', $http->getLastRequest()['path']);
    }

    public function test_district(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/geo/districts/7514', [
            'districtCode' => '7514',
            'districtName' => 'Observatoire',
            'regionCode' => '11',
            'departmentCode' => '75',
            'inseeCode' => '75114',
            'boundaries' => self::boundaries(),
            'subdistrictCodes' => ['751401'],
        ]);

        $resource = new GeoResource($http);
        $district = $resource->district('7514');

        $this->assertInstanceOf(District::class, $district);
        $this->assertSame('Observatoire', $district->districtName);
    }

    public function test_subdistrict(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/geo/subdistricts/751104', [
            'subdistrictCode' => '751104',
            'subdistrictName' => 'Val de Grace',
            'regionCode' => '11',
            'departmentCode' => '75',
            'inseeCode' => '75105',
            'districtCode' => '7511',
            'boundaries' => self::boundaries(),
        ]);

        $resource = new GeoResource($http);
        $subdistrict = $resource->subdistrict('751104');

        $this->assertInstanceOf(Subdistrict::class, $subdistrict);
        $this->assertSame('Val de Grace', $subdistrict->subdistrictName);
    }
}
