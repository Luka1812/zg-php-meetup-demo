<?php

namespace App\AMQP\Listeners;

use App\AMQP\Events\NotifySomeoneEvent;
use App\AMQP\Common\AMQPService;
use App\AMQP\Services\Business\TestSendService;

class SendNotificationToSomeoneListener
{
    /**
     * @var \App\AMQP\Services\Business\TestSendService
     */
    private $service;

    /**
     * Create the event listener.
     *
     * @param \App\AMQP\Services\Business\TestSendService $service
     * @return void
     */
    public function __construct(TestSendService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle the event.
     *
     * @param  \App\AMQP\Events\NotifySomeoneEvent $event
     * @return void
     * @throws
     */
    public function handle(NotifySomeoneEvent $event)
    {
        $data = $event->getData();

        $AMQPService = new AMQPService();
        $AMQPService->setQueueName("queue-name");
        $AMQPService->setExchangeName("exchange-name");
        $AMQPService->setExchangeType("exchange-type");
        $AMQPService->setConsumerTag("consumer-tag");
        $AMQPService->open();

        $AMQPService->send($this->service, $data);

        $AMQPService->closeChannel();
        $AMQPService->closeConnection();
    }
}