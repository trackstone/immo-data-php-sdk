<?php

declare(strict_types=1);

namespace ImmoData\Sdk\Enums;

enum GeoLevel: string
{
    case Region = 'region';
    case Department = 'department';
    case City = 'city';
    case District = 'district';
    case Street = 'street';
    case Address = 'address';
}
