<?php

namespace App\Enums\ErrorCodes;

final class UserErrorCode extends Enum
{
    const ERR_EMPTY_USERNAME = 'ERR_EMPTY_USERNAME';
    const ERR_EMPTY_EMAIL    = 'ERR_EMPTY_EMAIL';
    const ERR_INVALID_EMAIL  = 'ERR_INVALID_EMAIL';
}
