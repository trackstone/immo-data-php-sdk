<?php

declare(strict_types=1);

namespace ImmoData\DTOs;

use ImmoData\Enums\TxRealtyType;
use ImmoData\Enums\TxType;

final readonly class Transaction
{
    /**
     * @param TransactionLot[] $lot
     */
    public function __construct(
        public string $txId,
        public string $txDate,
        public TxType $txType,
        public int $price,
        public int $squareMeterPrice,
        public TxRealtyType $realtyType,
        public TransactionAttributes $attributes,
        public array $lot,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            txId: $data['txId'],
            txDate: $data['txDate'],
            txType: TxType::from($data['txType']),
            price: $data['price'],
            squareMeterPrice: $data['squareMeterPrice'],
            realtyType: TxRealtyType::from($data['realtyType']),
            attributes: TransactionAttributes::fromArray($data['attributes']),
            lot: array_map(fn(array $l) => TransactionLot::fromArray($l), $data['lot']),
        );
    }
}
