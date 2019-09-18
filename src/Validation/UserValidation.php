<?php

namespace App\Validations;

use App\Enums\ErrorCodes\UserErrorCode;

class UserValidation extends Validation
{
    public function rules(): array
    {
        return [
            'username'  => ['required'],
            'email'     => ['required', 'email'],
        ];
    }

    public function errorCodes(): array
    {
        return [
            'username.required' => UserErrorCode::ERR_EMPTY_USERNAME,
            'email.required'    => UserErrorCode::ERR_EMPTY_EMAIL,
            'email.email'       => UserErrorCode::ERR_INVALID_EMAIL,
        ];
    }

    public function validationData(): array
    {
        return $this->data;
    }
}
