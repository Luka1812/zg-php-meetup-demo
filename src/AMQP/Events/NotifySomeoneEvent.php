<?php

namespace App\AMQP\Events;

class NotifySomeoneEvent
{
    /**
     * @var array
     */
    private $data;

    /**
     * Create a new event instance.
     *
     * @param  array $data
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get event data
     * @return array
     */
    public function getData() : array
    {
        return $this->data;
    }
}