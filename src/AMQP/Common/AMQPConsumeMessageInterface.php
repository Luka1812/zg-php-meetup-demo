<?php

namespace App\AMQP\Common;

use PhpAmqpLib\Message\AMQPMessage;

interface AMQPConsumeMessageInterface
{
    /**
     * Process the message
     *
     * @param \PhpAmqpLib\Message\AMQPMessage $message
     */
    public function process(AMQPMessage $message);
}