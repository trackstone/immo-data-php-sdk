<?php

declare(strict_types=1);

namespace ImmoData\Enums;

enum GeoLevel: string
{
    case Region = 'region';
    case Department = 'department';
    case City = 'city';
    case District = 'district';
    case Subdistrict = 'subdistrict';
    case Street = 'street';
    case Parcel = 'parcel';
    case Address = 'address';
}
