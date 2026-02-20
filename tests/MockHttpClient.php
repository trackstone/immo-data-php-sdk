<?php

declare(strict_types=1);

namespace ImmoData\Sdk\Tests;

use ImmoData\Sdk\HttpClient\HttpClientInterface;
use RuntimeException;

final class MockHttpClient implements HttpClientInterface
{
    /** @var array<string, array<string, mixed>> */
    private array $responses = [];

    /** @var array{path: string, query: array<string, mixed>}[] */
    private array $requests = [];

    public function mockGet(string $path, array $response): self
    {
        $this->responses[$path] = $response;

        return $this;
    }

    public function get(string $path, array $query = []): array
    {
        $this->requests[] = ['path' => $path, 'query' => $query];

        if (!isset($this->responses[$path])) {
            throw new RuntimeException("No mock response configured for path: {$path}");
        }

        return $this->responses[$path];
    }

    public function getLastRequest(): ?array
    {
        return $this->requests[count($this->requests) - 1] ?? null;
    }

    public function getRequests(): array
    {
        return $this->requests;
    }
}
