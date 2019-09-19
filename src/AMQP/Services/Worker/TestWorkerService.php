<?php

namespace App\AMQP\Services\Worker;

use App\AMQP\Common\AMQPService;
use App\AMQP\Services\Business\TestConsumeService;

class TestWorkerService
{
    /**
     * The TestConsumeService instance
     *
     * @var \App\AMQP\Services\Business\TestConsumeService
     */
    private $testService;

    /**
     * Create a new console command instance.
     *
     * @param \App\AMQP\Services\Business\TestConsumeService $testService
     */
    public function __construct(TestConsumeService $testService)
    {
        $this->testService = $testService;
    }

    /**
     * Consume message
     *
     * @throws \Throwable
     */
    public function handle()
    {
        $AMQPService = new AMQPService();

        $AMQPService->setQueueName("queue-name");
        $AMQPService->setExchangeName("exchange-name");
        $AMQPService->setExchangeType("exchange-type");
        $AMQPService->setConsumerTag("consumer-tag");
        $AMQPService->open();

        $AMQPService->consume($this->testService);

        $AMQPService->closeChannel();
        $AMQPService->closeConnection();
    }
}
