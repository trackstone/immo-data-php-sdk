<?php

declare(strict_types=1);

namespace ImmoData\HttpClient;

use ImmoData\Exceptions\ImmoDataException;

interface HttpClientInterface
{
    /**
     * @param array<string, mixed> $query
     * @return array<string, mixed>
     *
     * @throws ImmoDataException
     */
    public function get(string $path, array $query = []): array;
}
