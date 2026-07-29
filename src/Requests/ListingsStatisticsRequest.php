<?php

declare(strict_types=1);

namespace ImmoData\Requests;

use ImmoData\Enums\Condition;
use ImmoData\Enums\DateRef;
use ImmoData\Enums\Dpe;
use ImmoData\Enums\GeoLevel;
use ImmoData\Enums\ListingGroupBy;
use ImmoData\Enums\ListingMetric;
use ImmoData\Enums\ListingStat;
use ImmoData\Enums\MarketType;
use ImmoData\Enums\RealtyType;

final readonly class ListingsStatisticsRequest
{
    /**
     * @param ListingMetric[] $metrics
     * @param ListingStat[] $stats
     * @param int[]|null $percentiles
     * @param array<int|float>|null $bounds
     * @param Dpe[]|null $dpeRating
     */
    public function __construct(
        public array $metrics,
        public array $stats,
        public RealtyType $realtyType,
        public ?array $percentiles = null,
        public ?ListingGroupBy $groupBy = null,
        public ?array $bounds = null,
        public ?string $code = null,
        public ?GeoLevel $geoLevel = null,
        public ?float $latitude = null,
        public ?float $longitude = null,
        public ?int $radius = null,
        public MarketType $marketType = MarketType::Sales,
        public ?DateRef $dateRef = null,
        public ?string $dateMin = null,
        public ?string $dateMax = null,
        public ?bool $isActive = null,
        public ?bool $isNew = null,
        public ?Condition $condition = null,
        public ?int $priceMin = null,
        public ?int $priceMax = null,
        public ?int $daysOnMarketMin = null,
        public ?int $daysOnMarketMax = null,
        public ?int $sqmPriceMin = null,
        public ?int $sqmPriceMax = null,
        public ?int $livingAreaMin = null,
        public ?int $livingAreaMax = null,
        public ?int $roomsMin = null,
        public ?int $roomsMax = null,
        public ?int $bedroomsMin = null,
        public ?int $bedroomsMax = null,
        public ?int $floorMin = null,
        public ?int $floorMax = null,
        public ?int $landAreaMin = null,
        public ?int $landAreaMax = null,
        public ?array $dpeRating = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $params = [
            'metrics' => implode(',', array_map(fn(ListingMetric $m) => $m->value, $this->metrics)),
            'stats' => implode(',', array_map(fn(ListingStat $s) => $s->value, $this->stats)),
            'realtyType' => $this->realtyType->value,
            'marketType' => $this->marketType->value,
        ];

        if ($this->percentiles !== null) {
            $params['percentiles'] = implode(',', $this->percentiles);
        }
        if ($this->groupBy !== null) {
            $params['groupBy'] = $this->groupBy->value;
        }
        if ($this->bounds !== null) {
            $params['bounds'] = implode(',', $this->bounds);
        }
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
        if ($this->dateRef !== null) {
            $params['dateRef'] = $this->dateRef->value;
        }
        if ($this->dateMin !== null) {
            $params['dateMin'] = $this->dateMin;
        }
        if ($this->dateMax !== null) {
            $params['dateMax'] = $this->dateMax;
        }
        if ($this->isActive !== null) {
            $params['isActive'] = $this->isActive ? 'true' : 'false';
        }
        if ($this->isNew !== null) {
            $params['isNew'] = $this->isNew ? 'true' : 'false';
        }
        if ($this->condition !== null) {
            $params['condition'] = $this->condition->value;
        }
        if ($this->priceMin !== null) {
            $params['priceMin'] = $this->priceMin;
        }
        if ($this->priceMax !== null) {
            $params['priceMax'] = $this->priceMax;
        }
        if ($this->daysOnMarketMin !== null) {
            $params['daysOnMarketMin'] = $this->daysOnMarketMin;
        }
        if ($this->daysOnMarketMax !== null) {
            $params['daysOnMarketMax'] = $this->daysOnMarketMax;
        }
        if ($this->sqmPriceMin !== null) {
            $params['sqmPriceMin'] = $this->sqmPriceMin;
        }
        if ($this->sqmPriceMax !== null) {
            $params['sqmPriceMax'] = $this->sqmPriceMax;
        }
        if ($this->livingAreaMin !== null) {
            $params['livingAreaMin'] = $this->livingAreaMin;
        }
        if ($this->livingAreaMax !== null) {
            $params['livingAreaMax'] = $this->livingAreaMax;
        }
        if ($this->roomsMin !== null) {
            $params['roomsMin'] = $this->roomsMin;
        }
        if ($this->roomsMax !== null) {
            $params['roomsMax'] = $this->roomsMax;
        }
        if ($this->bedroomsMin !== null) {
            $params['bedroomsMin'] = $this->bedroomsMin;
        }
        if ($this->bedroomsMax !== null) {
            $params['bedroomsMax'] = $this->bedroomsMax;
        }
        if ($this->floorMin !== null) {
            $params['floorMin'] = $this->floorMin;
        }
        if ($this->floorMax !== null) {
            $params['floorMax'] = $this->floorMax;
        }
        if ($this->landAreaMin !== null) {
            $params['landAreaMin'] = $this->landAreaMin;
        }
        if ($this->landAreaMax !== null) {
            $params['landAreaMax'] = $this->landAreaMax;
        }
        if ($this->dpeRating !== null) {
            $params['dpeRating'] = implode(',', array_map(fn(Dpe $r) => $r->value, $this->dpeRating));
        }

        return $params;
    }
}
