<?php

declare(strict_types=1);

namespace App\Enums;

use App\Util\BetterEnumFromTrait;

enum Ratings: string
{
    use BetterEnumFromTrait;

    case COMMUNICATION = 'communication';
    case QUALITY_OF_WORK = 'quality_of_work';
    case VALUE = 'value';
}
