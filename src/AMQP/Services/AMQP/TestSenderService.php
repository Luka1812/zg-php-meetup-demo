<?php

namespace App\Services\AMQP;

use App\AMQP\Common\AMQPService;
use App\Services\Business\TestSendService;
use App\Mapper\Mapper;

class TestSenderService
{
    /**
     * The ProcessEngineNotificationService instance
     *
     * @var \App\Services\Business\TestSendService
     */
    private $testSendService;

    /**
     * Service constructor.
     *
     * @param \App\Services\Business\TestSendService $testSendService
     *
     * @return void
     */
    public function __construct(TestSendService $testSendService)
    {
        $this->testSendService = $testSendService;
    }

    /**
     * Send message
     *
     * @param array $data
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function handle(array $data): void
    {
        $AMQPService = new AMQPService();
        $AMQPService->setQueueName("TEST");
        $AMQPService->setExchangeName("TEST");
        $AMQPService->setExchangeType("TEST");
        $AMQPService->setConsumerTag("TEST");
        $AMQPService->open();

        $AMQPService->send($this->testSendService, $data);

        $AMQPService->closeChannel();
        $AMQPService->closeConnection();
    }
}
