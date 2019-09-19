<?php

namespace App\AMQP\Services\Business;

use App\AMQP\Common\AMQPSendMessageInterface;

class TestSendService implements AMQPSendMessageInterface
{
    /**
     * @param array $message
     * @return array
     */
    public function process(array $message) : array
    {
        // TODO: implement business logic.

        return $message;
    }
}