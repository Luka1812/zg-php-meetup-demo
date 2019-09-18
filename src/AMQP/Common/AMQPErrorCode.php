<?php

namespace App\AMQP\Common;

final class AMQPErrorCode
{
    const ERR_WRONG_EXCHANGE_TYPE       = 'ERR_WRONG_EXCHANGE_TYPE';
    const ERR_MISSING_QUEUE_NAME        = 'ERR_MISSING_QUEUE_NAME';
    const ERR_INIT_FAILED               = 'ERR_INIT_FAILED';
    const ERR_EMPTY_PAYLOAD             = 'ERR_EMPTY_PAYLOAD';
    const ERR_SERVICE_PROCESSING_FAILED = 'ERR_SERVICE_PROCESSING_FAILED';
}
