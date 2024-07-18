<?php

namespace App\Enums;

enum NotificationTypes: string
{
    case NEW_ACCOUNT = 'New account';
    case SESSION = 'Session';
    case PASSWORD_CHANGE = 'Password updated';
}