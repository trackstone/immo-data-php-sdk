<?php

declare(strict_types=1);

namespace ImmoData\Enums;

enum ListingStat: string
{
    case Mean = 'mean';
    case Count = 'count';
    case Percentile = 'percentile';
}
