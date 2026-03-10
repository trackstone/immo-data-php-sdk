<?php

declare(strict_types=1);

namespace ImmoData\Enums;

enum TxType: string
{
    case Sales = 'sales';
    case Vefa = 'vefa';
    case Adjudication = 'adjudication';
    case Expropriation = 'expropriation';
    case Exchange = 'exchange';
}
