<?php

namespace App\AMQP\Common;

interface AMQPSendMessageInterface
{
    /**
     * Process the message
     *
     * @param array $message
     * @return array (indexed)
     */
    public function process(array $message) : array;
}