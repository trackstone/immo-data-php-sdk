<?php

declare(strict_types=1);

namespace ImmoData\Enums;

enum SortBy: string
{
    case Date = 'date';
    case Price = 'price';
    case SquareMeterPrice = 'squareMeterPrice';
}
