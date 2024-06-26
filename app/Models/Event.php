<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'full_name',
        'short_name',
        'edition',
        'location',
        'description_html_text',
        'event_full_link',
        'event_short_link',
        'event_start_date',
        'event_end_date',

        'event_logo_media_id',
        'event_logo_inverted_media_id',
        'app_sponsor_logo_media_id',
        
        'event_splash_screen_media_id',
        'event_banner_media_id',
        'app_sponsor_banner_media_id',

        'login_html_text',
        'continue_as_guest_html_text',
        'forgot_password_html_text',

        'primary_bg_color',
        'secondary_bg_color',
        'primary_text_color',
        'secondary_text_color',

        'delegate_feedback_survey_link',
        'app_feedback_survey_link',
        'about_event_link',
        'venue_link',
        'press_releases_link',

        'year',

        'is_visible_in_the_app',
        'is_accessible_in_the_app',
    ];
}
