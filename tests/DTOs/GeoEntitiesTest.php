<?php

declare(strict_types=1);

namespace ImmoData\Sdk\Tests\DTOs;

use ImmoData\Sdk\DTOs\City;
use ImmoData\Sdk\DTOs\Department;
use ImmoData\Sdk\DTOs\District;
use ImmoData\Sdk\DTOs\Region;
use ImmoData\Sdk\DTOs\Subdistrict;
use PHPUnit\Framework\TestCase;

final class GeoEntitiesTest extends TestCase
{
    private static function boundaries(): array
    {
        return [
            'type' => 'Polygon',
            'coordinates' => [[[2.22, 48.81], [2.47, 48.90], [2.22, 48.81]]],
        ];
    }

    public function test_region_from_array(): void
    {
        $region = Region::fromArray([
            'regionCode' => '11',
            'regionName' => 'Ile-de-France',
            'boundaries' => self::boundaries(),
        ]);

        $this->assertSame('11', $region->regionCode);
        $this->assertSame('Ile-de-France', $region->regionName);
        $this->assertSame('Polygon', $region->boundaries->type);
    }

    public function test_department_from_array(): void
    {
        $department = Department::fromArray([
            'departmentCode' => '75',
            'departmentName' => 'Paris',
            'regionCode' => '11',
            'boundaries' => self::boundaries(),
        ]);

        $this->assertSame('75', $department->departmentCode);
        $this->assertSame('Paris', $department->departmentName);
        $this->assertSame('11', $department->regionCode);
    }

    public function test_city_from_array(): void
    {
        $city = City::fromArray([
            'inseeCode' => '75056',
            'cityName' => 'Paris',
            'regionCode' => '11',
            'departmentCode' => '75',
            'postCode' => ['75001', '75002'],
            'boundaries' => self::boundaries(),
            'districtCodes' => ['7501', '7502'],
        ]);

        $this->assertSame('75056', $city->inseeCode);
        $this->assertSame('Paris', $city->cityName);
        $this->assertSame(['75001', '75002'], $city->postCode);
        $this->assertSame(['7501', '7502'], $city->districtCodes);
    }

    public function test_city_with_empty_optional_arrays(): void
    {
        $city = City::fromArray([
            'inseeCode' => '13055',
            'cityName' => 'Marseille',
            'regionCode' => '93',
            'departmentCode' => '13',
            'boundaries' => self::boundaries(),
        ]);

        $this->assertSame([], $city->postCode);
        $this->assertSame([], $city->districtCodes);
    }

    public function test_district_from_array(): void
    {
        $district = District::fromArray([
            'districtCode' => '7514',
            'districtName' => 'Observatoire',
            'regionCode' => '11',
            'departmentCode' => '75',
            'inseeCode' => '75114',
            'boundaries' => self::boundaries(),
            'subdistrictCodes' => ['751401', '751402'],
        ]);

        $this->assertSame('7514', $district->districtCode);
        $this->assertSame('Observatoire', $district->districtName);
        $this->assertSame(['751401', '751402'], $district->subdistrictCodes);
    }

    public function test_subdistrict_from_array(): void
    {
        $subdistrict = Subdistrict::fromArray([
            'subdistrictCode' => '751104',
            'subdistrictName' => 'Val de Grace',
            'regionCode' => '11',
            'departmentCode' => '75',
            'inseeCode' => '75105',
            'districtCode' => '7511',
            'boundaries' => self::boundaries(),
        ]);

        $this->assertSame('751104', $subdistrict->subdistrictCode);
        $this->assertSame('Val de Grace', $subdistrict->subdistrictName);
        $this->assertSame('7511', $subdistrict->districtCode);
    }
}
