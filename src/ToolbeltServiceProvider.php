<?php

namespace Lsrur\Toolbelt;

use Illuminate\Support\ServiceProvider;

class ToolbeltServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('Toolbelt', function(){
            
            return new Toolbelt;
        });
    }
}
