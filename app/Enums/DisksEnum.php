<?php

namespace App\Enums;

enum DisksEnum: string
{
    case LOCAL = 'local';
    case PUBLIC = 'public';
    case PRIVATE = 'private';
}
