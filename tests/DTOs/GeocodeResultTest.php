<?php

declare(strict_types=1);

namespace ImmoData\Tests\DTOs;

use ImmoData\DTOs\GeocodeResult;
use ImmoData\Enums\GeoLevel;
use PHPUnit\Framework\TestCase;

final class GeocodeResultTest extends TestCase
{
    public function test_from_array(): void
    {
        $result = GeocodeResult::fromArray([
            'geoLevel' => 'city',
            'regionName' => 'Ile-de-France',
            'regionCode' => '11',
            'departmentName' => 'Paris',
            'departmentCode' => '75',
            'cityName' => 'Paris',
            'inseeCode' => '75056',
            'postCode' => ['75001', '75002'],
            'boundingBox' => [2.22, 48.81, 2.47, 48.90],
            'center' => [2.3488, 48.8534],
            'label' => 'Paris, Ile-de-France',
        ]);

        $this->assertSame(GeoLevel::City, $result->geoLevel);
        $this->assertSame('Paris', $result->cityName);
        $this->assertSame('75056', $result->inseeCode);
        $this->assertSame(['75001', '75002'], $result->postCode);
        $this->assertNotNull($result->boundingBox);
        $this->assertSame(2.22, $result->boundingBox->minLongitude);
        $this->assertNotNull($result->center);
        $this->assertSame(2.3488, $result->center->longitude);
        $this->assertSame(48.8534, $result->center->latitude);
        $this->assertSame('Paris, Ile-de-France', $result->label);
    }

    public function test_from_array_with_nullable_fields(): void
    {
        $result = GeocodeResult::fromArray([
            'geoLevel' => 'region',
            'regionName' => 'Ile-de-France',
            'regionCode' => '11',
            'label' => 'Ile-de-France',
        ]);

        $this->assertSame(GeoLevel::Region, $result->geoLevel);
        $this->assertNull($result->cityName);
        $this->assertNull($result->inseeCode);
        $this->assertNull($result->boundingBox);
        $this->assertNull($result->center);
        $this->assertSame([], $result->postCode);
    }

    public function test_from_array_list(): void
    {
        $results = GeocodeResult::fromArrayList([
            [
                'geoLevel' => 'city',
                'regionName' => 'Ile-de-France',
                'regionCode' => '11',
                'cityName' => 'Paris',
                'inseeCode' => '75056',
                'label' => 'Paris',
            ],
            [
                'geoLevel' => 'department',
                'regionName' => 'Ile-de-France',
                'regionCode' => '11',
                'departmentName' => 'Paris',
                'departmentCode' => '75',
                'label' => 'Paris (75)',
            ],
        ]);

        $this->assertCount(2, $results);
        $this->assertSame(GeoLevel::City, $results[0]->geoLevel);
        $this->assertSame(GeoLevel::Department, $results[1]->geoLevel);
    }
}
