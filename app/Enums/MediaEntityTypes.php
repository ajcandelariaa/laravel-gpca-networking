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
    case SPEAKER_PFP = 'speaker_pfp';
    case SPEAKER_COVER_PHOTO = 'speaker_cover_photo';

    // SPONSORS
    case SPONSOR_LOGO = 'sponsor_logo';
    case SPONSOR_BANNER = 'sponsor_banner';

    // EXHIBITORS
    case EXHIBITOR_LOGO = 'exhibitor_logo';
    case EXHIBITOR_BANNER = 'exhibitor_banner';

    // MRPS
    case MEETING_ROOM_PARTNER_LOGO = 'meeting_room_partner_logo';
    case MEETING_ROOM_PARTNER_BANNER = 'meeting_room_partner_banner';

    // MPS
    case MEDIA_PARTNER_LOGO = 'media_partner_logo';
    case MEDIA_PARTNER_BANNER = 'media_partner_banner';

    // FEATURES
    case FEATURE_LOGO = 'feature_logo';
    case FEATURE_BANNER = 'feature_banner';
}