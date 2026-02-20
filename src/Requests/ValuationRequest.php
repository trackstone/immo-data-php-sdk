<?php

declare(strict_types=1);

namespace ImmoData\Sdk\Requests;

use ImmoData\Sdk\Enums\Condition;
use ImmoData\Sdk\Enums\Dpe;
use ImmoData\Sdk\Enums\RealtyType;

final readonly class ValuationRequest
{
    public function __construct(
        public float $longitude,
        public float $latitude,
        public RealtyType $realtyType,
        public int $nbRooms,
        public float $livingArea,
        public ?Condition $condition = null,
        public ?float $landArea = null,
        public ?int $bathrooms = null,
        public ?int $constructionYear = null,
        public ?Dpe $dpe = null,
        public ?int $level = null,
        public ?int $floor = null,
        public ?bool $pool = null,
        public ?bool $cellar = null,
        public ?bool $niceView = null,
        public ?bool $parking = null,
        public ?bool $elevator = null,
        public ?bool $patio = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $params = [
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'realtyType' => $this->realtyType->value,
            'nbRooms' => $this->nbRooms,
            'livingArea' => $this->livingArea,
        ];

        if ($this->condition !== null) {
            $params['condition'] = $this->condition->value;
        }
        if ($this->landArea !== null) {
            $params['landArea'] = $this->landArea;
        }
        if ($this->bathrooms !== null) {
            $params['bathrooms'] = $this->bathrooms;
        }
        if ($this->constructionYear !== null) {
            $params['constructionYear'] = $this->constructionYear;
        }
        if ($this->dpe !== null) {
            $params['dpe'] = $this->dpe->value;
        }
        if ($this->level !== null) {
            $params['level'] = $this->level;
        }
        if ($this->floor !== null) {
            $params['floor'] = $this->floor;
        }
        if ($this->pool !== null) {
            $params['pool'] = $this->pool;
        }
        if ($this->cellar !== null) {
            $params['cellar'] = $this->cellar;
        }
        if ($this->niceView !== null) {
            $params['niceView'] = $this->niceView;
        }
        if ($this->parking !== null) {
            $params['parking'] = $this->parking;
        }
        if ($this->elevator !== null) {
            $params['elevator'] = $this->elevator;
        }
        if ($this->patio !== null) {
            $params['patio'] = $this->patio;
        }

        return $params;
    }
}
