<?php

namespace IMemento\SDK\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use IMemento\SDK\ServiceProvider;

class TestCase extends OrchestraTestCase
{

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class
        ];
    }
}
