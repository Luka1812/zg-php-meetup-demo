<?php

namespace Tests\Feature\Api;

use Symfony\Component\HttpFoundation\Response;
use App\Enums\ErrorCodes\UserErrorCode;

class UserValidationTest extends TestCase
{
    /**
     * The API endpoint to test
     *
     * @var string
     */
    protected $endpoint = '/api/users.register';

    /** @test */
    public function registerUserExpectSuccessResponse()
    {
        $data = [
            'username' => 'vorever',
            'email'    => 'john.doe@hotmail.com',
        ];

        $response = $this->json('POST', $this->endpoint, $data);

        $response->assertStatus(Response::HTTP_CREATED);

        $response->assertJsonFragment([
            'username' => $data['username']
        ]);
    }

    /** @test */
    public function registerUserSetInvalidEmailExpectBadRequest()
    {
        $data = [
            'username' => 'vorever',
            'email'    => 'invalid-email',
        ];

        $response = $this->json('POST', $this->endpoint, $data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertJsonFragment([
            'code' => UserErrorCode::ERR_INVALID_EMAIL
        ]);
    }
}

