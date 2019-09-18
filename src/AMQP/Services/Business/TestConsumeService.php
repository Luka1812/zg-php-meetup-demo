<?php

namespace App\Services\Business;

use App\AMQP\Common\AMQPConsumeMessageInterface;
use PhpAmqpLib\Message\AMQPMessage;

class TestConsumeService implements AMQPConsumeMessageInterface
{
    /**
     * @param AMQPMessage $message
     */
    public function process(AMQPMessage $message)
    {
        $data = json_decode($message->getBody(), true);

        // TODO: validate data.

        // TODO: implement business logic.
    }
}