<?php

namespace Sonover\Docs;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class DocumentationServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->registerResources();
        $this->defineAssetPublishing();
    }

    /**
     * Register the Documenation routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
            'domain' => config('documentation.domain', null),
            'prefix' => config('documentation.path'),
            'namespace' => 'Sonover\Docs\Http\Controllers',
            'middleware' => config('documentation.middleware', 'web'),
        ], function () {
            Route::get('/', 'DocumentationController@showRootPage');
            Route::get('{version}/{page?}', 'DocumentationController@show');
        });
    }

    /**
     * Register the Documenation resources.
     *
     * @return void
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'documentation');
    }

    /**
     * Define the asset publishing configuration.
     *
     * @return void
     */
    public function defineAssetPublishing()
    {
        $this->publishes([
            DOCS_PATH . '/public' => public_path('vendor/documentation'),
        ], 'documentation-assets');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (!defined('DOCS_PATH')) {
            define('DOCS_PATH', realpath(__DIR__ . '/../'));
        }

        $paths = config('view.paths');
        $paths[] = resource_path('docs');
        config()->set('view.paths', $paths);

        $this->configure();
        $this->offerPublishing();
    }

    /**
     * Setup the configuration for Documenation.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/documentation.php',
            'documentation'
        );
    }

    /**
     * Setup the resource publishing groups for Documenation.
     *
     * @return void
     */
    protected function offerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/documentation.php' => config_path('documentation.php'),
            ], 'documentation-config');
        }
    }
}
