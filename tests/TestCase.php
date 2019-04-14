<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use TestDatabaseSeeder;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;

    public function setUp() : void
    {
        parent::setUp();

        $this->runDatabaseMigrations();

        Artisan::call('db:seed');

        Artisan::call('db:seed', [
            '--class' => TestDatabaseSeeder::class,
        ]);
    }
}
