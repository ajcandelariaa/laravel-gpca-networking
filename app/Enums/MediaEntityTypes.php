<?php

namespace App\Enums;

enum MediaEntityTypes: string
{
    // EVENTS
    case EVENT_LOGO = 'event_logo';
    case EVENT_LOGO_INVERTED = 'event_logo_inverted';
    case EVENT_APP_SPONSOR_LOGO = 'event_app_sponsor_logo';
    case EVENT_SPLASH_SCREEN = 'event_splash_screen';
    case EVENT_BANNER = 'event_banner';
    case EVENT_APP_SPONSOR_BANNER = 'event_app_sponsor_banner';

    // ATTENDEES
    case ATTENDEE_PFP = 'attendee_pfp';

    // SPEAKERS
    case SPEAKERS = 'speakers';

    // SPONSORS
    case SPONSORS = 'sponsors';

    // EXHIBITORS
    case EXHIBITORS = 'exhibitors';

    // MRPS
    case MEETING_ROOM_PARTNERS = 'meetingRoomPartners';

    // MPS
    case MEDIA_PARTNER_LOGO = 'media_partner_logo';
    case MEDIA_PARTNER_BANNER = 'media_partner_banner';

    // FEATURES
    case FEATURE_LOGO = 'feature_logo';
    case FEATURE_BANNER = 'feature_banner';
}