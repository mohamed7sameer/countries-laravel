<?php

namespace Mohamed7sameer\CountriesLaravel\Package;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Mohamed7sameer\Countries\Package\Countries as CountriesService;
use Mohamed7sameer\Countries\Package\Data\Repository;
use Mohamed7sameer\Countries\Package\Services\Cache\Service as Cache;
use Mohamed7sameer\Countries\Package\Services\Config;
use Mohamed7sameer\Countries\Package\Services\Helper;
use Mohamed7sameer\Countries\Package\Services\Hydrator;
use Mohamed7sameer\CountriesLaravel\Package\Console\Commands\Update;
use Mohamed7sameer\CountriesLaravel\Package\Facade as CountriesFacade;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    protected $defer = true;

    /**
     * Configure package paths.
     */
    protected function configurePaths()
    {
        $this->publishes([
            $this->getPackageConfigFile() => config_path('countries.php'),
        ], 'config');
    }

    /**
     * Get the package config file path.
     *
     * @return string
     */
    protected function getPackageConfigFile()
    {
        return __DIR__.'/../config/countries.php';
    }

    /**
     * Merge configuration.
     */
    protected function mergeConfig()
    {
        $this->mergeConfigFrom(
            $this->getPackageConfigFile(), 'countries'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('countries.validation.enabled')) {
            $this->addValidators();
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->configurePaths();

        $this->mergeConfig();

        $this->registerService();

        $this->registerUpdateCommand();

        if (config('countries.routes.enabled')) {
            $this->registerRoutes();
        }
    }

    /**
     * Register routes.
     */
    protected function registerRoutes()
    {
        Route::get(
            '/mohamed7sameer/countries/flag/file/{cca3}.svg',
            [
                'name' => 'mohamed7sameer.countries.flag.file',
                'uses' => '\Mohamed7sameer\CountriesLaravel\Package\Http\Controllers\Flag@file',
            ]
        );

        Route::get(
            '/mohamed7sameer/countries/flag/download/{cca3}.svg',
            [
                'name' => 'mohamed7sameer.countries.flag.download',
                'uses' => '\Mohamed7sameer\CountriesLaravel\Package\Http\Controllers\Flag@download',
            ]
        );
    }

    /**
     * Register the service.
     */
    protected function registerService()
    {
        $this->app->singleton('mohamed7sameer.countries', function () {
            $hydrator = new Hydrator($config = new Config(config()));

            $cache = new Cache($config, app(config('countries.cache.service')));

            $helper = new Helper($config);

            $repository = new Repository($cache, $hydrator, $helper, $config);

            $hydrator->setRepository($repository);

            return new CountriesService($config, $cache, $helper, $hydrator, $repository);
        });
    }

    /**
     * Add validators.
     */
    protected function addValidators()
    {
        foreach (config('countries.validation.rules') as $ruleName => $countryAttribute) {
            if (is_int($ruleName)) {
                $ruleName = $countryAttribute;
            }

            Validator::extend($ruleName, function ($attribute, $value) use ($countryAttribute) {
                return ! CountriesFacade::where($countryAttribute, $value)->isEmpty();
            }, 'The :attribute must be a valid '.$ruleName.'.');
        }
    }

    /**
     * Register update command.
     */
    protected function registerUpdateCommand()
    {
        $this->app->singleton($command = 'countries.update.command', function () {
            return new Update();
        });

        $this->commands($command);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'mohamed7sameer.countries',
            'countries.update.command',
        ];
    }
}
