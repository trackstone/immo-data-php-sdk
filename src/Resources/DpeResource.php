<?php

declare(strict_types=1);

namespace ImmoData\Resources;

use ImmoData\DTOs\DpeList;
use ImmoData\HttpClient\HttpClientInterface;
use ImmoData\Requests\DpeRequest;

final class DpeResource
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
    ) {}

    public function search(DpeRequest $request): DpeList
    {
        $response = $this->httpClient->get('/v1/dpe', $request->toArray());

        return DpeList::fromArray($response);
    }
}
