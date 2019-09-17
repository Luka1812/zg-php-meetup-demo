<?php

namespace App\Services\Business;

use App\Support\AMQP\AMQPConsumeMessageInterface;
use PhpAmqpLib\Message\AMQPMessage;
use App\Support\Json;

class TestConsumeService implements AMQPConsumeMessageInterface
{
    public function process(AMQPMessage $message)
    {
        $data = Json::decode($message->getBody());

        // TODO: validate data.

        // TODO: implement business logic.
    }
}