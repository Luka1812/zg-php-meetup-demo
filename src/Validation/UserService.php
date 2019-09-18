<?php

namespace App\Services;

use App\Validations\UserValidation;

class UserService
{
    public function register(UserValidation $validation, array $data)
    {
        try {
            $validation->validate($data);
        } catch (ValidationException $exception) {
            // use $exception->errors() to log, report or show/dump error messages, vorever
        }

        // continue with your business logic
    }
}
