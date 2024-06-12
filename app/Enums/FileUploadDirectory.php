<?php

namespace App\Enums;

enum FileUploadDirectory: string
{
    case UPLOADS = 'uploads';
    case ATTENDEES = 'attendees';
}