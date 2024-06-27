<?php

namespace App\Enums;

enum PassTypes: string
{
    case FULL_MEMBER = 'fullMember';
    case MEMBER = 'member';
    case NON_MEMBER = 'nonMember';
}