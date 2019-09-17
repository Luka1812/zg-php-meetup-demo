<?php

namespace App\Services\AMQP;

use App\Support\AMQP\AMQPService;
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
     * Notify process engine
     *
     * @param \App\Mapper\Mapper $mapper
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function handle(Mapper $mapper): void
    {
        $AMQPService = new AMQPService();
        $AMQPService->setQueueName("TEST");
        $AMQPService->setExchangeName("TEST");
        $AMQPService->setExchangeType("TEST");
        $AMQPService->setConsumerTag("TEST");
        $AMQPService->open();

        $AMQPService->send($this->testSendService, $mapper->toArray());

        $AMQPService->closeChannel();
        $AMQPService->closeConnection();
    }
}
