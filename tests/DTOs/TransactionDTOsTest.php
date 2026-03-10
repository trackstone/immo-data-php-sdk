<?php

declare(strict_types=1);

namespace ImmoData\Tests\DTOs;

use ImmoData\DTOs\Transaction;
use ImmoData\DTOs\TransactionAttributes;
use ImmoData\DTOs\TransactionList;
use ImmoData\DTOs\TransactionLot;
use ImmoData\Enums\TxRealtyType;
use ImmoData\Enums\TxType;
use PHPUnit\Framework\TestCase;

final class TransactionDTOsTest extends TestCase
{
    private function sampleTransaction(): array
    {
        return [
            'txId' => 'tx-123',
            'txDate' => '2024-06-15',
            'txType' => 'sales',
            'price' => 350000,
            'squareMeterPrice' => 8750,
            'realtyType' => 'apartment',
            'attributes' => [
                'livingArea' => 40,
                'landArea' => null,
                'rooms' => 2,
            ],
            'lot' => [
                [
                    'parcelId' => '75114000AB0001',
                    'location' => [
                        'address' => [
                            'inseeCode' => '75114',
                            'departmentCode' => '75',
                            'districtCode' => '7511401',
                            'subdistrictCode' => '751140101',
                            'postCode' => '75014',
                            'cityName' => 'Paris',
                            'streetName' => 'Rue de la Paix',
                            'streetNumber' => '10',
                            'streetCode' => null,
                            'streetType' => null,
                            'streetSuffix' => null,
                            'addressId' => null,
                        ],
                        'geometry' => [
                            'type' => 'Point',
                            'coordinates' => [2.3308, 48.8698],
                        ],
                    ],
                    'realty' => [
                        [
                            'livingArea' => 40,
                            'landArea' => null,
                            'rooms' => 2,
                            'realtyType' => 'apartment',
                            'landType' => null,
                        ],
                    ],
                    'landArea' => 0,
                ],
            ],
        ];
    }

    public function test_transaction_from_array(): void
    {
        $tx = Transaction::fromArray($this->sampleTransaction());

        $this->assertSame('tx-123', $tx->txId);
        $this->assertSame('2024-06-15', $tx->txDate);
        $this->assertSame(TxType::Sales, $tx->txType);
        $this->assertSame(350000, $tx->price);
        $this->assertSame(8750, $tx->squareMeterPrice);
        $this->assertSame(TxRealtyType::Apartment, $tx->realtyType);
    }

    public function test_transaction_attributes(): void
    {
        $tx = Transaction::fromArray($this->sampleTransaction());

        $this->assertSame(40, $tx->attributes->livingArea);
        $this->assertNull($tx->attributes->landArea);
        $this->assertSame(2, $tx->attributes->rooms);
    }

    public function test_transaction_lot_and_location(): void
    {
        $tx = Transaction::fromArray($this->sampleTransaction());

        $this->assertCount(1, $tx->lot);
        $this->assertInstanceOf(TransactionLot::class, $tx->lot[0]);
        $this->assertSame('75114000AB0001', $tx->lot[0]->parcelId);
        $this->assertSame('Paris', $tx->lot[0]->location->address->cityName);
        $this->assertSame(2.3308, $tx->lot[0]->location->geometry->longitude);
        $this->assertSame(48.8698, $tx->lot[0]->location->geometry->latitude);
    }

    public function test_transaction_attributes_from_array_with_nulls(): void
    {
        $attrs = TransactionAttributes::fromArray([]);

        $this->assertNull($attrs->livingArea);
        $this->assertNull($attrs->landArea);
        $this->assertNull($attrs->rooms);
    }

    public function test_transaction_list_from_array(): void
    {
        $list = TransactionList::fromArray([
            'total' => 42,
            'size' => 20,
            'searchAfter' => 'cursor-abc',
            'data' => [$this->sampleTransaction()],
        ]);

        $this->assertSame(42, $list->total);
        $this->assertSame(20, $list->size);
        $this->assertSame('cursor-abc', $list->searchAfter);
        $this->assertCount(1, $list->data);
        $this->assertInstanceOf(Transaction::class, $list->data[0]);
    }

    public function test_transaction_list_null_search_after(): void
    {
        $list = TransactionList::fromArray([
            'total' => 5,
            'size' => 20,
            'searchAfter' => null,
            'data' => [],
        ]);

        $this->assertNull($list->searchAfter);
        $this->assertCount(0, $list->data);
    }
}
