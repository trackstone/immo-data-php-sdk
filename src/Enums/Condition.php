<?php

declare(strict_types=1);

namespace ImmoData\Sdk\Enums;

enum Condition: int
{
    case ToRenovate = -1;
    case Standard = 0;
    case Excellent = 1;
}
