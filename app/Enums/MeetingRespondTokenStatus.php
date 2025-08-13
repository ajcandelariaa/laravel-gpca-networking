<?php

namespace App\Enums;

enum MeetingRespondTokenStatus: string
{
    case ACTIVE = 'Active';
    case USED = 'Used';
    case EXPIRED = 'Expired';
}