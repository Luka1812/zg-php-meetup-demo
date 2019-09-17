<?php

namespace App\Support\AMQP;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use Exception;

class AMQPService
{
    /**
     * @var \PhpAmqpLib\Connection\AMQPStreamConnection
     */
    private $connection;

    /**
     * @var \PhpAmqpLib\Channel\AMQPChannel
     */
    private $channel;

    /**
     * @var string
     */
    private $queueName = null;

    /**
     * @var string
     */
    private $exchangeName = "";

    /**
     * @var string
     */
    private $routingKey = "";

    /**
     * @var string
     */
    private $exchangeType = null;

    /**
     * @var string
     */
    private $consumerTag = null;

    /**
     * @var array
     */
    private $exchangeTypes = [
        'direct'  => AMQPExchangeType::DIRECT,
        'topic'   => AMQPExchangeType::TOPIC,
        'headers' => AMQPExchangeType::HEADERS,
        'fanout'  => AMQPExchangeType::FANOUT,
    ];

    /**
     * Set queue name
     *
     * @param string $queueName
     * @return void
     */
    public function setQueueName(string $queueName) : void
    {
        $this->queueName = $queueName;
    }

    /**
     * Set exchange name
     *
     * @param string $exchangeName
     * @return void
     */
    public function setExchangeName(string $exchangeName) : void
    {
        $this->exchangeName = $exchangeName;
    }

    /**
     * Set routing key
     *
     * @param string $routingKey
     * @return void
     */
    public function setRoutingKey(string $routingKey) : void
    {
        $this->routingKey = $routingKey;
    }

    /**
     * Set exchange name
     *
     * @param string $exchangeType
     * @return void
     *
     * @throws AMQPException
     */
    public function setExchangeType(string $exchangeType) : void
    {
        if (in_array($exchangeType, $this->exchangeTypes)) {
            $this->exchangeType = $exchangeType;
        } else {
            throw new AMQPException("Test error message");
        }
    }

    /**
     * Set consumer tag
     *
     * @param string $consumerTag
     * @return void
     */
    public function setConsumerTag(string $consumerTag) : void
    {
        $this->consumerTag = $consumerTag;
    }

    /**
     * Check if connection is open
     *
     * @return bool
     */
    public function isOpenConnection() : bool
    {
        return $this->connection ? true : false;
    }

    /**
     * Check if channel is open
     *
     * @return bool
     */
    public function isOpenChannel() : bool
    {
        return $this->channel ? true : false;
    }

    /**
     * Init the AMQP process, start connection, open the channel, declare queue and exchange type
     *
     * @return void
     *
     * @throws \Throwable
     */
    private function init() : void
    {
        try {
            $this->connection = new AMQPStreamConnection(
               //set host
               //set port
               //set user
               //set pass
               //set vhosts
            );

            $this->channel = $this->connection->channel();

            if (! $this->queueName) {
                throw new AMQPException(AMQPErrorCode::ERR_MISSING_QUEUE_NAME);
            }

            $this->channel->queue_declare($this->queueName, false, true, false, false);

            if (! $this->exchangeType) {
                $this->exchangeType = AMQPExchangeType::DIRECT;
            }

            if ($this->exchangeName) {
                $this->channel->exchange_declare($this->exchangeName, $this->exchangeType, false, true, false);

                $this->channel->queue_bind($this->queueName, $this->exchangeName);
            }
        } catch (AMQPException $AMQPException) {
            throw $AMQPException;
        } catch(Exception $e) {
            throw new AMQPException(AMQPErrorCode::ERR_INIT_FAILED);
        }
    }

    /*
     * Open AMQP connection
     *
     * @return void
     */
    public function open() : void
    {
        if (! $this->isOpenConnection()) {
            $this->init();
        }
    }

    /**
     * Send message
     *
     * @param AMQPSendMessageInterface $service
     * @param array $payload
     * @return void
     *
     * @throws AMQPException
     */
    public function send(AMQPSendMessageInterface $service, array $payload) : void
    {
        if (! $this->isOpenConnection()) {
            throw new AMQPException(AMQPErrorCode::ERR_INIT_FAILED);
        }

        if (empty($payload)) {
            throw new AMQPException(AMQPErrorCode::ERR_EMPTY_PAYLOAD);
        }

        try {
            $results = $service->process($payload);
        } catch (Exception $exception) {
            throw new AMQPException(AMQPErrorCode::ERR_SERVICE_PROCESSING_FAILED);
        }


        if (empty($results)) {
            throw new AMQPException(AMQPErrorCode::ERR_EMPTY_PAYLOAD);
        }

        foreach ($results as $result) {
            $message = new AMQPMessage(json_encode($result),
                [
                    'content_type'  => 'application/json',
                    'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                    'priority'      => in_array($priority, range(1, 10)) ? $priority : 1
                ]);

            $this->channel->basic_publish($message, $this->exchangeName, $this->routingKey);
        }
    }

    /**
     * Consume message
     *
     * @param \App\Contracts\AMQPConsumeMessageInterface $service
     *
     * @return void
     *
     * @throws AMQPException
     * @throws Exception
     */
    public function consume(AMQPConsumeMessageInterface $service) : void
    {
        if (! $this->isOpenConnection()) {
            throw new AMQPException(AMQPErrorCode::ERR_INIT_FAILED);
        }

        $consumeCallback = function ($message) use ($service) {
            try {
                $service->process($message);

                $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
            } catch (Exception $exception) {
                // TODO: Log exception in message log table.

                $message->delivery_info['channel']->basic_nack($message->delivery_info['delivery_tag']);
            }

            if ($message->body === 'quit') {
                $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
            }
        };

        if (! $this->consumerTag) {
            $this->consumerTag = 'default-consumer';
        }

        $this->channel->basic_consume($this->queueName, $this->consumerTag, false, false, false, false, $consumeCallback);

        $shutdownCallback = function ($channel, $connection) {
            $channel->close();
            $connection->close();
        };

        register_shutdown_function($shutdownCallback, $this->channel, $this->connection);

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    /**
     * Close AMQP channel
     *
     * @return void
     */
    public function closeChannel() : void
    {
        if ($this->isOpenChannel()) {
            $this->channel->close();
            $this->channel = null;
        }
    }

    /**
     * Close AMQP connection
     *
     * @return void
     * @throws \Throwable
     */
    public function closeConnection() : void
    {
        if ($this->isOpenConnection()) {
            $this->connection->close();
            $this->connection = null;
        }
    }
}
