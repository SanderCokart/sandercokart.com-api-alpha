<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;

enum DisksEnum: string
{
    use InvokableCases;

    case LOCAL = 'local';
    case PUBLIC = 'public';
    case PRIVATE = 'private';
}
