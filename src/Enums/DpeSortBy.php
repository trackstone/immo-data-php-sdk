<?php

declare(strict_types=1);

namespace ImmoData\Enums;

enum DpeSortBy: string
{
    case Date = 'date';
    case LivingArea = 'livingArea';
    case EnergyConsFinal = 'energyConsFinal';
}
