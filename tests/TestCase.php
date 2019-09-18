<?php

namespace Tests\Feature\Api\Nextpertise;

use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Set up the test
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('doctrine:migrations:refresh');
        $this->artisan('db:seed');
    }
}
