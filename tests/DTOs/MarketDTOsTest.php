<?php

declare(strict_types=1);

namespace ImmoData\Tests\DTOs;

use ImmoData\DTOs\CurrentPrice;
use ImmoData\DTOs\PriceHistory;
use PHPUnit\Framework\TestCase;

final class MarketDTOsTest extends TestCase
{
    public function test_price_history_from_array(): void
    {
        $history = PriceHistory::fromArray([
            'metric' => 'sqm_price',
            'data' => [
                ['period' => '2024-01', 'value' => 10234.5],
                ['period' => '2024-02', 'value' => 10300.0],
            ],
        ]);

        $this->assertSame('sqm_price', $history->metric);
        $this->assertCount(2, $history->data);
        $this->assertSame('2024-01', $history->data[0]->period);
        $this->assertSame(10234.5, $history->data[0]->value);
        $this->assertSame('2024-02', $history->data[1]->period);
        $this->assertSame(10300.0, $history->data[1]->value);
    }

    public function test_current_price_from_array(): void
    {
        $price = CurrentPrice::fromArray([
            'metric' => 'sqm_price',
            'value' => 10500.75,
        ]);

        $this->assertSame('sqm_price', $price->metric);
        $this->assertSame(10500.75, $price->value);
    }

    public function test_current_price_casts_string_value(): void
    {
        $price = CurrentPrice::fromArray([
            'metric' => 'sqm_price',
            'value' => '9800',
        ]);

        $this->assertSame(9800.0, $price->value);
    }
}
