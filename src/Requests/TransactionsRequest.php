<?php

declare(strict_types=1);

namespace ImmoData\Requests;

use ImmoData\Enums\GeoLevel;
use ImmoData\Enums\SortBy;
use ImmoData\Enums\SortOrder;
use ImmoData\Enums\TxRealtyType;
use ImmoData\Enums\TxType;

final readonly class TransactionsRequest
{
    /**
     * @param TxType[]|null $txType
     * @param TxRealtyType[]|null $realtyType
     */
    public function __construct(
        public ?string $code = null,
        public ?GeoLevel $geoLevel = null,
        public ?float $latitude = null,
        public ?float $longitude = null,
        public ?int $radius = null,
        public ?array $txType = null,
        public ?array $realtyType = null,
        public ?string $dateMin = null,
        public ?string $dateMax = null,
        public ?int $priceMin = null,
        public ?int $priceMax = null,
        public ?int $sqmPriceMin = null,
        public ?int $sqmPriceMax = null,
        public ?int $minRoom = null,
        public ?int $maxRoom = null,
        public ?int $livingAreaMin = null,
        public ?int $livingAreaMax = null,
        public ?int $landAreaMin = null,
        public ?int $landAreaMax = null,
        public SortBy $sortBy = SortBy::Date,
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
        if ($this->txType !== null) {
            $params['txType'] = implode(',', array_map(fn(TxType $t) => $t->value, $this->txType));
        }
        if ($this->realtyType !== null) {
            $params['realtyType'] = implode(',', array_map(fn(TxRealtyType $t) => $t->value, $this->realtyType));
        }
        if ($this->dateMin !== null) {
            $params['dateMin'] = $this->dateMin;
        }
        if ($this->dateMax !== null) {
            $params['dateMax'] = $this->dateMax;
        }
        if ($this->priceMin !== null) {
            $params['priceMin'] = $this->priceMin;
        }
        if ($this->priceMax !== null) {
            $params['priceMax'] = $this->priceMax;
        }
        if ($this->sqmPriceMin !== null) {
            $params['sqmPriceMin'] = $this->sqmPriceMin;
        }
        if ($this->sqmPriceMax !== null) {
            $params['sqmPriceMax'] = $this->sqmPriceMax;
        }
        if ($this->minRoom !== null) {
            $params['minRoom'] = $this->minRoom;
        }
        if ($this->maxRoom !== null) {
            $params['maxRoom'] = $this->maxRoom;
        }
        if ($this->livingAreaMin !== null) {
            $params['livingAreaMin'] = $this->livingAreaMin;
        }
        if ($this->livingAreaMax !== null) {
            $params['livingAreaMax'] = $this->livingAreaMax;
        }
        if ($this->landAreaMin !== null) {
            $params['landAreaMin'] = $this->landAreaMin;
        }
        if ($this->landAreaMax !== null) {
            $params['landAreaMax'] = $this->landAreaMax;
        }
        if ($this->searchAfter !== null) {
            $params['searchAfter'] = $this->searchAfter;
        }

        return $params;
    }
}
