<?php

declare(strict_types=1);

namespace ImmoData\Tests;

use ImmoData\ImmoDataClient;
use ImmoData\Resources\GeocodeResource;
use ImmoData\Resources\GeoResource;
use ImmoData\Resources\MarketResource;
use ImmoData\Resources\ValuationResource;
use PHPUnit\Framework\TestCase;

final class ImmoDataClientTest extends TestCase
{
    public function test_returns_resource_instances(): void
    {
        $client = new ImmoDataClient(
            apiKey: 'test-key',
            httpClient: new MockHttpClient(),
        );

        $this->assertInstanceOf(ValuationResource::class, $client->valuation());
        $this->assertInstanceOf(GeocodeResource::class, $client->geocode());
        $this->assertInstanceOf(GeoResource::class, $client->geo());
        $this->assertInstanceOf(MarketResource::class, $client->market());
    }

    public function test_returns_same_resource_instance(): void
    {
        $client = new ImmoDataClient(
            apiKey: 'test-key',
            httpClient: new MockHttpClient(),
        );

        $valuation1 = $client->valuation();
        $valuation2 = $client->valuation();

        $this->assertSame($valuation1, $valuation2);
    }

    public function test_creates_guzzle_client_by_default(): void
    {
        // Should not throw — verifies default GuzzleHttpClient instantiation
        $client = new ImmoDataClient(apiKey: 'test-key');

        $this->assertInstanceOf(ValuationResource::class, $client->valuation());
    }
}
