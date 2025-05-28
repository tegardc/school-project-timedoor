<?php

namespace App\Providers;

use App\Helpers\ResponseHelper;
use Illuminate\Support\ServiceProvider;

class ResponseHelperServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('response.helper', function ($app) {
            return new ResponseHelper();
        });
    }
}
