<?php

namespace App\Providers;

use App\Services\DatagridFilterService;
use Illuminate\Support\ServiceProvider;

class DatagridFilterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(DatagridFilterService::class, function($app) {
            return new DatagridFilterService();
        });
    }
}
