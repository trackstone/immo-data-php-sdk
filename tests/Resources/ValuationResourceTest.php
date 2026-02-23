<?php

declare(strict_types=1);

namespace ImmoData\Tests\Resources;

use ImmoData\DTOs\ValuationResult;
use ImmoData\Enums\RealtyType;
use ImmoData\Requests\ValuationRequest;
use ImmoData\Resources\ValuationResource;
use ImmoData\Tests\MockHttpClient;
use PHPUnit\Framework\TestCase;

final class ValuationResourceTest extends TestCase
{
    public function test_estimate_sends_correct_request(): void
    {
        $http = new MockHttpClient();
        $http->mockGet('/v1/valuation', [
            'mainValuation' => 485000,
            'upperValuation' => 530000,
            'lowerValuation' => 440000,
            'confidence' => 85,
        ]);

        $resource = new ValuationResource($http);

        $request = new ValuationRequest(
            longitude: 2.3488,
            latitude: 48.8534,
            realtyType: RealtyType::Apartment,
            nbRooms: 3,
            livingArea: 65.0,
        );

        $result = $resource->estimate($request);

        $this->assertInstanceOf(ValuationResult::class, $result);
        $this->assertSame(485000.0, $result->mainValuation);
        $this->assertSame(85, $result->confidence);

        $lastRequest = $http->getLastRequest();
        $this->assertSame('/v1/valuation', $lastRequest['path']);
        $this->assertSame(2.3488, $lastRequest['query']['longitude']);
        $this->assertSame('apartment', $lastRequest['query']['realtyType']);
    }
}
