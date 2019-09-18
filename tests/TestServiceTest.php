<?php

namespace Tests\;

use App\Services\Business\TestSendService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Ramsey\Uuid\Uuid;
use App\Support\Validation\ValidationException;
use App\Exceptions\NotFoundException;
use App\Enums\Entities\MessageLogStatus;
use App\Enums\Entities\NextpertiseOrderStatus;

class TestServiceTest extends TestCase
{
    /**
     * The API endpoint to test
     *
     * @var string
     */
    protected $endpoint = '/api/test-endpoint';

    /**
     * TestConsumeService instance
     *
     * @var \App\Services\Business\TestSendService
     */
    private $service;

    /**
     * Test data
     *
     * @var array
     */
    private $data = [
        'order_status' => NextpertiseOrderStatus::TERMINATED,
    ];

    /**
     * Set up the test
     *
     * @return void
     *
     * @throws
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->data['uuid'] = config('app.test_process_uuid');

        $this->service = app()->make(TestSendService::class);
    }

    /** @test */
    public function setValidDataExpectHttpOkAndResponseContainsUuidAndDeliveryOrderContainsListUuidAndMessageLogContainsSuccessStatusAndNextpertiseOrderContainsTerminatedStatus()
    {
        $response = $this->json('POST', $this->endpoint, $this->data);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonFragment([
            'ack' => $this->data['uuid']
        ]);

        $messageLog = DB::table('message_log')
            ->where('identifier', $this->data['uuid'])
            ->first();

        $this->assertEquals(MessageLogStatus::SUCCESS, $messageLog->status);
    }

    /**
     * @test
     */
    public function passInvalidDataToNextpertiseOrderConnectionFailedServiceHandleMethodExpectValidationException()
    {
        $this->data['data']['order_status'] = 'xxxxx';

        $this->expectException(ValidationException::class);

        $this->service->process($this->data);
    }

    /** @test */
    public function setInvalidDataForBreakingTheValidationExpectHttpOkAndSeeErrorStatusInMessageLogTable()
    {
        $this->data['data']['order_status'] = 'xxxxx';

        $response = $this->json('POST', $this->endpoint, $this->data);

        $response->assertStatus(Response::HTTP_OK);

        $messageLog = DB::table('message_log')
            ->where('identifier', $this->data['uuid'])
            ->first();

        $this->assertEquals(MessageLogStatus::ERROR, $messageLog->status);
    }

    /**
     * @test
     */
    public function passInvalidListUuidToNextpertiseOrderConnectionFailedServiceHandleMethodExpectDeliveryNotFoundException()
    {
        $invalidListUuid = 'd7b6f945-886e-4693-a8f6-3fcfa214f01c';
        $this->data['listUuid'] = $invalidListUuid;

        $this->expectException(NotFoundException::class);

        $this->service->process($this->data);
    }

    /** @test */
    public function setInvalidListUuidDataExpectHttpOkAndSeeErrorStatusInMessageLogTable()
    {
        $invalidListUuid = 'd7b6f945-886e-4693-a8f6-3fcfa214f01c';
        $this->data['listUuid'] = $invalidListUuid;

        $response = $this->json('POST', $this->endpoint, $this->data);

        $response->assertStatus(Response::HTTP_OK);

        $messageLog = DB::table('message_log')
            ->where('identifier', $this->data['uuid'])
            ->first();

        $this->assertEquals(MessageLogStatus::ERROR, $messageLog->status);
    }
}