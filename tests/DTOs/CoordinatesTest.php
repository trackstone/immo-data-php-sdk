<?php

declare(strict_types=1);

namespace ImmoData\Sdk\Tests\DTOs;

use ImmoData\Sdk\DTOs\BoundingBox;
use ImmoData\Sdk\DTOs\Coordinates;
use ImmoData\Sdk\DTOs\GeoJsonPolygon;
use PHPUnit\Framework\TestCase;

final class CoordinatesTest extends TestCase
{
    public function test_coordinates_from_array(): void
    {
        $coords = Coordinates::fromArray([2.3488, 48.8534]);

        $this->assertSame(2.3488, $coords->longitude);
        $this->assertSame(48.8534, $coords->latitude);
    }

    public function test_bounding_box_from_array(): void
    {
        $bbox = BoundingBox::fromArray([2.22, 48.81, 2.47, 48.90]);

        $this->assertSame(2.22, $bbox->minLongitude);
        $this->assertSame(48.81, $bbox->minLatitude);
        $this->assertSame(2.47, $bbox->maxLongitude);
        $this->assertSame(48.90, $bbox->maxLatitude);
    }

    public function test_geojson_polygon_from_array(): void
    {
        $polygon = GeoJsonPolygon::fromArray([
            'type' => 'Polygon',
            'coordinates' => [[[2.22, 48.81], [2.47, 48.90], [2.22, 48.81]]],
        ]);

        $this->assertSame('Polygon', $polygon->type);
        $this->assertCount(1, $polygon->coordinates);
        $this->assertCount(3, $polygon->coordinates[0]);
    }
}
