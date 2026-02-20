<?php

declare(strict_types=1);

namespace ImmoData\Sdk;

use ImmoData\Sdk\HttpClient\GuzzleHttpClient;
use ImmoData\Sdk\HttpClient\HttpClientInterface;
use ImmoData\Sdk\Resources\GeocodeResource;
use ImmoData\Sdk\Resources\GeoResource;
use ImmoData\Sdk\Resources\MarketResource;
use ImmoData\Sdk\Resources\ValuationResource;

final class ImmoDataClient
{
    private HttpClientInterface $httpClient;
    private ?ValuationResource $valuation = null;
    private ?GeocodeResource $geocode = null;
    private ?GeoResource $geo = null;
    private ?MarketResource $market = null;

    public function __construct(
        string $apiKey,
        string $baseUrl = 'https://api.immo-data.fr',
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->httpClient = $httpClient ?? new GuzzleHttpClient($apiKey, $baseUrl);
    }

    public function valuation(): ValuationResource
    {
        return $this->valuation ??= new ValuationResource($this->httpClient);
    }

    public function geocode(): GeocodeResource
    {
        return $this->geocode ??= new GeocodeResource($this->httpClient);
    }

    public function geo(): GeoResource
    {
        return $this->geo ??= new GeoResource($this->httpClient);
    }

    public function market(): MarketResource
    {
        return $this->market ??= new MarketResource($this->httpClient);
    }
}
