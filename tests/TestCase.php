<?php

namespace Mohamed7sameer\CountriesLaravel\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            \Illuminate\Cache\CacheServiceProvider::class,
            \Mohamed7sameer\CountriesLaravel\Package\ServiceProvider::class,
        ];
    }
}
