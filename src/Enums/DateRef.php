<?php

declare(strict_types=1);

namespace ImmoData\Enums;

enum DateRef: string
{
    case Listed = 'listed';
    case Removed = 'removed';
}
