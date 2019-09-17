<?php

namespace App\Support\AMQP;

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