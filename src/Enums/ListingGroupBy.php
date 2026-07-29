<?php

declare(strict_types=1);

namespace ImmoData\Enums;

enum ListingGroupBy: string
{
    case Month = 'month';
    case Quarter = 'quarter';
    case Year = 'year';
    case DpeRating = 'dpeRating';
    case NumberOfRooms = 'numberOfRooms';
    case SquareMeterPrice = 'squareMeterPrice';
    case Price = 'price';
    case DaysOnMarket = 'daysOnMarket';
    case LivingArea = 'livingArea';
}
