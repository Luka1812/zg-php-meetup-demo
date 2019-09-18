<?php

namespace App\Services\Business;

use App\AMQP\Common\AMQPConsumeMessageInterface;

class TestSendService implements AMQPSendMessageInterface
{
    /**
     * @param array $message
     * @return array
     */
    public function process(array $message) : array
    {
        // TODO: Implement process() method.
    }
}