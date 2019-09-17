<?php

namespace App\Services\AMQP;

use App\Support\AMQP\AMQPService;
use App\Services\Business\TestConsumeService;

class TestWorkerService
{
    /**
     * The TestConsumeService instance
     *
     * @var \App\Services\Business\TestConsumeService
     */
    private $testService;

    /**
     * Create a new console command instance.
     *
     * @param \App\Services\Business\TestConsumeService $testService
     */
    public function __construct(TestConsumeService $testService)
    {
        $this->testService = $testService;
    }

    /**
     * Process the message
     *
     * @throws \Throwable
     */
    public function handle()
    {
        $AMQPService = new AMQPService();

        $AMQPService->setQueueName("TEST");
        $AMQPService->setExchangeName("TEST");
        $AMQPService->setExchangeType("TEST");
        $AMQPService->setConsumerTag("TEST");
        $AMQPService->open();

        $AMQPService->consume($this->testService);

        $AMQPService->closeChannel();
        $AMQPService->closeConnection();
    }
}
