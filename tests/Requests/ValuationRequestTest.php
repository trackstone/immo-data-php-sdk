<?php

declare(strict_types=1);

namespace ImmoData\Tests\Requests;

use ImmoData\Enums\Condition;
use ImmoData\Enums\Dpe;
use ImmoData\Enums\RealtyType;
use ImmoData\Requests\ValuationRequest;
use PHPUnit\Framework\TestCase;

final class ValuationRequestTest extends TestCase
{
    public function test_create_with_required_fields(): void
    {
        $request = new ValuationRequest(
            longitude: 2.3488,
            latitude: 48.8534,
            realtyType: RealtyType::Apartment,
            nbRooms: 3,
            livingArea: 65.0,
        );

        $this->assertSame(2.3488, $request->longitude);
        $this->assertSame(48.8534, $request->latitude);
        $this->assertSame(RealtyType::Apartment, $request->realtyType);
        $this->assertSame(3, $request->nbRooms);
        $this->assertSame(65.0, $request->livingArea);
    }

    public function test_to_array_with_required_fields_only(): void
    {
        $request = new ValuationRequest(
            longitude: 2.3488,
            latitude: 48.8534,
            realtyType: RealtyType::Apartment,
            nbRooms: 3,
            livingArea: 65.0,
        );

        $this->assertSame([
            'longitude' => 2.3488,
            'latitude' => 48.8534,
            'realtyType' => 'apartment',
            'nbRooms' => 3,
            'livingArea' => 65.0,
        ], $request->toArray());
    }

    public function test_with_all_optional_fields(): void
    {
        $request = new ValuationRequest(
            longitude: 2.3488,
            latitude: 48.8534,
            realtyType: RealtyType::House,
            nbRooms: 5,
            livingArea: 120.0,
            condition: Condition::Excellent,
            landArea: 500.0,
            bathrooms: 2,
            constructionYear: 1990,
            dpe: Dpe::C,
            level: 2,
            floor: 1,
            pool: true,
            cellar: true,
            niceView: true,
            parking: true,
            elevator: true,
            patio: true,
        );

        $array = $request->toArray();

        $this->assertSame('house', $array['realtyType']);
        $this->assertSame(1, $array['condition']);
        $this->assertSame(500.0, $array['landArea']);
        $this->assertSame(2, $array['bathrooms']);
        $this->assertSame(1990, $array['constructionYear']);
        $this->assertSame('C', $array['dpe']);
        $this->assertSame(2, $array['level']);
        $this->assertSame(1, $array['floor']);
        $this->assertTrue($array['pool']);
        $this->assertTrue($array['cellar']);
        $this->assertTrue($array['niceView']);
        $this->assertTrue($array['parking']);
        $this->assertTrue($array['elevator']);
        $this->assertTrue($array['patio']);
    }

    public function test_to_array_excludes_null_values(): void
    {
        $request = new ValuationRequest(
            longitude: 2.3488,
            latitude: 48.8534,
            realtyType: RealtyType::Apartment,
            nbRooms: 3,
            livingArea: 65.0,
            bathrooms: 1,
        );

        $array = $request->toArray();

        $this->assertArrayHasKey('bathrooms', $array);
        $this->assertArrayNotHasKey('condition', $array);
        $this->assertArrayNotHasKey('landArea', $array);
        $this->assertArrayNotHasKey('pool', $array);
    }

    public function test_optional_fields_default_to_null(): void
    {
        $request = new ValuationRequest(
            longitude: 2.3488,
            latitude: 48.8534,
            realtyType: RealtyType::Apartment,
            nbRooms: 3,
            livingArea: 65.0,
        );

        $this->assertNull($request->condition);
        $this->assertNull($request->landArea);
        $this->assertNull($request->bathrooms);
        $this->assertNull($request->pool);
    }

    public function test_boolean_fields_can_be_false(): void
    {
        $request = new ValuationRequest(
            longitude: 2.3488,
            latitude: 48.8534,
            realtyType: RealtyType::Apartment,
            nbRooms: 3,
            livingArea: 65.0,
            pool: false,
        );

        $this->assertFalse($request->pool);
        $this->assertFalse($request->toArray()['pool']);
    }
}
