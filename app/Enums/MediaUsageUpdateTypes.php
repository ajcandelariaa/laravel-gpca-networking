<?php

namespace App\Enums;

enum MediaUsageUpdateTypes: string
{
    case ADD_ONLY = 'addOnly';
    case REMOVED_ONLY = 'removedOnly';
    case REMOVED_THEN_ADD = 'removedThenAdd';
}