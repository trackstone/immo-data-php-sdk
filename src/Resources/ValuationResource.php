<?php

declare(strict_types=1);

namespace ImmoData\Resources;

use ImmoData\DTOs\ValuationResult;
use ImmoData\HttpClient\HttpClientInterface;
use ImmoData\Requests\ValuationRequest;

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
