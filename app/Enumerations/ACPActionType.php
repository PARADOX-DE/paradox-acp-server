<?php

namespace App\Enumerations;

use Illuminate\Validation\Rules\Enum;

class ACPActionType extends Enum
{
    const KICK                      = 1;
    const WARN                      = 2;
    const SUSPEND                   = 3;
    const CALL_TO_SUPPORT           = 4;
    const RELOAD_CONTAINER_PLAYER   = 5;
    const RELOAD_CONTAINER_VEHICLE  = 6;
}
