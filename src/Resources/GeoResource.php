<?php

declare(strict_types=1);

namespace ImmoData\Resources;

use ImmoData\DTOs\City;
use ImmoData\DTOs\Department;
use ImmoData\DTOs\District;
use ImmoData\DTOs\Region;
use ImmoData\DTOs\Subdistrict;
use ImmoData\HttpClient\HttpClientInterface;

final class GeoResource
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
    ) {}

    public function region(string $code): Region
    {
        $response = $this->httpClient->get("/v1/geo/regions/{$code}");

        return Region::fromArray($response);
    }

    public function department(string $code): Department
    {
        $response = $this->httpClient->get("/v1/geo/departments/{$code}");

        return Department::fromArray($response);
    }

    public function city(string $inseeCode): City
    {
        $response = $this->httpClient->get("/v1/geo/cities/{$inseeCode}");

        return City::fromArray($response);
    }

    public function district(string $code): District
    {
        $response = $this->httpClient->get("/v1/geo/districts/{$code}");

        return District::fromArray($response);
    }

    public function subdistrict(string $code): Subdistrict
    {
        $response = $this->httpClient->get("/v1/geo/subdistricts/{$code}");

        return Subdistrict::fromArray($response);
    }
}
