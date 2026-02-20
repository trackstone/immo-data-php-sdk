<?php

declare(strict_types=1);

namespace ImmoData\Sdk\Resources;

use ImmoData\Sdk\DTOs\ValuationResult;
use ImmoData\Sdk\HttpClient\HttpClientInterface;
use ImmoData\Sdk\Requests\ValuationRequest;

final class ValuationResource
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
    ) {}

    public function estimate(ValuationRequest $request): ValuationResult
    {
        $response = $this->httpClient->get('/v1/valuation', $request->toArray());

        return ValuationResult::fromArray($response);
    }
}
