<?php

namespace App\Services\Business;

use App\Support\AMQP\AMQPSendMessageInterface;

class TestSendService implements AMQPSendMessageInterface
{
    public function process(array $message) : array
    {
        // TODO: Implement process() method.
    }
}