<?php

namespace App\Enums;

enum MeetingReceiverType: string
{
    case ATTENDEE = 'Attendee';
    case EXHIBITOR = 'Exhibitor';
    case SPONSOR = 'Sponsor';
    case MEETING_ROOM_PARTNER = 'Meeting Room Partner';
}