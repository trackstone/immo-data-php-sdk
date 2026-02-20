<?php

declare(strict_types=1);

namespace ImmoData\Sdk\Tests\DTOs;

use ImmoData\Sdk\DTOs\ValuationResult;
use PHPUnit\Framework\TestCase;

final class ValuationResultTest extends TestCase
{
    public function test_from_array(): void
    {
        $result = ValuationResult::fromArray([
            'mainValuation' => 485000,
            'upperValuation' => 530000,
            'lowerValuation' => 440000,
            'confidence' => 85,
        ]);

        $this->assertSame(485000.0, $result->mainValuation);
        $this->assertSame(530000.0, $result->upperValuation);
        $this->assertSame(440000.0, $result->lowerValuation);
        $this->assertSame(85, $result->confidence);
    }

    public function test_from_array_casts_types(): void
    {
        $result = ValuationResult::fromArray([
            'mainValuation' => '485000',
            'upperValuation' => '530000',
            'lowerValuation' => '440000',
            'confidence' => '85',
        ]);

        $this->assertSame(485000.0, $result->mainValuation);
        $this->assertSame(85, $result->confidence);
    }
}
