<?php

declare(strict_types=1);

namespace ImmoData\Enums;

enum ListingMetric: string
{
    case SquareMeterPrice = 'squareMeterPrice';
    case Price = 'price';
    case DaysOnMarket = 'daysOnMarket';
    case LivingArea = 'livingArea';
    case NumberOfRooms = 'numberOfRooms';
}
