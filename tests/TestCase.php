<?php

namespace iMemento\Clients\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use iMemento\Clients\ServiceProvider;

class TestCase extends OrchestraTestCase
{

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class
        ];
    }
}
