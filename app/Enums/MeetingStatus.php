<?php

namespace App\Enums;

enum MeetingStatus: string
{
    case PENDING = 'Pending';
    case ACCEPTED = 'Accepted';
    case DECLINED = 'Declined';
    case CANCELLED = 'Cancelled';
    case EXPIRED = 'Expired';
}