<?php

namespace App\Enums;

enum PasswordChangedBy: string
{
    case ADMIN = 'admin';
    case ATTENDEE = 'attendee';
}