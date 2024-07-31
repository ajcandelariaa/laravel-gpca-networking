<?php

namespace App\Enums;

enum NotificationTypes: string
{
    case SPEAKER_LIST = "SPKL";
    case SPEAKER_DETAILS = "SPKD";

    case SESSION_LIST = "SSL";
    case SESSION_DETAILS = "SSD";

    case SPONSOR_LIST = "SPSL";
    case SPONSOR_DETAILS = "SPSD";

    case EXHIBITOR_LIST = "EXHL";
    case EXHIBITOR_DETAILS = "EXHD";

    case MRP_LIST = "MRPL";
    case MRP_DETAILS = "MRPD";

    case MP_LIST = "MPL";
    case MP_DETAILS = "MPD";

    case FLOORPLAN_DETAILS = "FPD";

    case DELEGATE_FEEDBACK = "DF";

    case ATTENDEES_LIST = "AL";

    case ATTENDEE_PROFILE_DETAILS = "APD";

    case ATTENDEE_CHATS = "ACHS";
    case ATTENDEE_CHAT = "ACH";
}