<?php

namespace App\Console\Commands\Test;

use App\Services\AMQP\TestWorkerService;
use Exception;

class TestWorkerCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:worker';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test worker which handles incoming messages.';

    /**
     * The TestWorkerService instance
     *
     * @var \App\Services\OneView\TestWorkerService
     */
    private $testWorkerService;

    /**
     * Create a new console command instance.
     *
     * @param \App\Services\OneView\TestWorkerService $testWorkerService
     * @return void
     */
    public function __construct(TestWorkerService $testWorkerService)
    {
        $this->testWorkerService = $testWorkerService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \Throwable
     */
    public function handle() : void
    {
        // TODO: Do something.

        try {
            $this->testWorkerService->handle();
        } catch (Exception $exception) {
            // TODO: Log exception message (to file, database, send message to email/slack).
        }

        // TODO: Do something else.

        return;
    }
}