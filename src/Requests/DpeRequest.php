<?php

declare(strict_types=1);

namespace ImmoData\Requests;

use ImmoData\Enums\Dpe;
use ImmoData\Enums\DpeSortBy;
use ImmoData\Enums\GeoLevel;
use ImmoData\Enums\RealtyType;
use ImmoData\Enums\SortOrder;

final readonly class DpeRequest
{
    /**
     * @param Dpe[]|null $dpeRating
     * @param Dpe[]|null $gesRating
     * @param RealtyType[]|null $realtyType
     */
    public function __construct(
        public ?string $code = null,
        public ?GeoLevel $geoLevel = null,
        public ?float $latitude = null,
        public ?float $longitude = null,
        public ?int $radius = null,
        public ?array $dpeRating = null,
        public ?array $gesRating = null,
        public ?array $realtyType = null,
        public ?int $livingAreaMin = null,
        public ?int $livingAreaMax = null,
        public ?int $constructionYearMin = null,
        public ?int $constructionYearMax = null,
        public ?float $energyConsFinalMin = null,
        public ?float $energyConsFinalMax = null,
        public ?float $energyConsPrimaryMin = null,
        public ?float $energyConsPrimaryMax = null,
        public ?string $dateMin = null,
        public ?string $dateMax = null,
        public DpeSortBy $sortBy = DpeSortBy::Date,
        public SortOrder $sortOrder = SortOrder::Desc,
        public int $size = 20,
        public ?string $searchAfter = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $params = [
            'sortBy' => $this->sortBy->value,
            'sortOrder' => $this->sortOrder->value,
            'size' => $this->size,
        ];

        if ($this->code !== null) {
            $params['code'] = $this->code;
        }
        if ($this->geoLevel !== null) {
            $params['geoLevel'] = $this->geoLevel->value;
        }
        if ($this->latitude !== null) {
            $params['latitude'] = $this->latitude;
        }
        if ($this->longitude !== null) {
            $params['longitude'] = $this->longitude;
        }
        if ($this->radius !== null) {
            $params['radius'] = $this->radius;
        }
        if ($this->dpeRating !== null) {
            $params['dpeRating'] = implode(',', array_map(fn(Dpe $r) => $r->value, $this->dpeRating));
        }
        if ($this->gesRating !== null) {
            $params['gesRating'] = implode(',', array_map(fn(Dpe $r) => $r->value, $this->gesRating));
        }
        if ($this->realtyType !== null) {
            $params['realtyType'] = implode(',', array_map(fn(RealtyType $t) => $t->value, $this->realtyType));
        }
        if ($this->livingAreaMin !== null) {
            $params['livingAreaMin'] = $this->livingAreaMin;
        }
        if ($this->livingAreaMax !== null) {
            $params['livingAreaMax'] = $this->livingAreaMax;
        }
        if ($this->constructionYearMin !== null) {
            $params['constructionYearMin'] = $this->constructionYearMin;
        }
        if ($this->constructionYearMax !== null) {
            $params['constructionYearMax'] = $this->constructionYearMax;
        }
        if ($this->energyConsFinalMin !== null) {
            $params['energyConsFinalMin'] = $this->energyConsFinalMin;
        }
        if ($this->energyConsFinalMax !== null) {
            $params['energyConsFinalMax'] = $this->energyConsFinalMax;
        }
        if ($this->energyConsPrimaryMin !== null) {
            $params['energyConsPrimaryMin'] = $this->energyConsPrimaryMin;
        }
        if ($this->energyConsPrimaryMax !== null) {
            $params['energyConsPrimaryMax'] = $this->energyConsPrimaryMax;
        }
        if ($this->dateMin !== null) {
            $params['dateMin'] = $this->dateMin;
        }
        if ($this->dateMax !== null) {
            $params['dateMax'] = $this->dateMax;
        }
        if ($this->searchAfter !== null) {
            $params['searchAfter'] = $this->searchAfter;
        }

        return $params;
    }
}
