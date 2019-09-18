<?php

namespace App\AMQP\Common;

use Exception;
use Throwable;

class AMQPException extends Exception
{
    /**
     * The custom exception constructor.
     *
     * @param string $message
     * @param \Throwable $previous
     * @param int $code
     *
     * @return void
     */
    public function __construct($message, Throwable $previous = null, $code = 0)
    {
        parent::__construct($message, $previous, $code);
    }
}
