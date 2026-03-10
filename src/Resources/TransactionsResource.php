<?php

declare(strict_types=1);

namespace ImmoData\Resources;

use ImmoData\DTOs\TransactionList;
use ImmoData\HttpClient\HttpClientInterface;
use ImmoData\Requests\TransactionsRequest;

final class TransactionsResource
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
    ) {}

    public function search(TransactionsRequest $request): TransactionList
    {
        $response = $this->httpClient->get('/v1/transactions', $request->toArray());

        return TransactionList::fromArray($response);
    }
}
