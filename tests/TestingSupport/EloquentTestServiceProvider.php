<?php

namespace Konsulting\Laravel\Sorting\Tests\TestSupport;

use Illuminate\Support\ServiceProvider;

class EloquentTestServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }
}
