<?php

namespace InnoSoft\FileManager;

use Illuminate\Support\ServiceProvider;

class FileManagerServiceProvider extends ServiceProvider
{
    protected $name = 'filemanager';
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/views', $this->name);
        $this->loadTranslationsFrom(__DIR__.'/lang', $this->name);
        
        $this->publishes([
            // public
//              __DIR__.'/assets/src/css' => resource_path('assets/css'),
//              __DIR__.'/assets/src/js' => resource_path('assets/js'),
            __DIR__.'/assets/dist/' => public_path('assets'),
            
            // config
            __DIR__.'/filemanager.php' => config_path('filemanager.php')
        ], $this->name);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        
    }
}
