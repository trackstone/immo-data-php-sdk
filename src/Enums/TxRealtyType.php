<?php

declare(strict_types=1);

namespace ImmoData\Enums;

enum TxRealtyType: string
{
    case House = 'house';
    case Apartment = 'apartment';
    case Land = 'land';
    case Commercial = 'commercial';
    case Annex = 'annex';
    case Multiple = 'multiple';
}
