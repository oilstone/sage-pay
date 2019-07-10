<?php

/** @noinspection PhpUndefinedFunctionInspection */

namespace Oilstone\SagePay\Integrations\Laravel;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Oilstone\SagePay\SagePay;

/**
 * Class ServiceProvider
 * @package Oilstone\SagePay\Integrations\Laravel
 */
class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../../../config/sage-pay.php';

        $this->mergeConfigFrom($configPath, 'sage-pay');

        $this->app->bind(SagePay::class, function () {
            return new SagePay(config('sage-pay'));
        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../../../config/sage-pay.php';

        $this->publishes([$configPath => $this->getConfigPath()], 'config');
    }

    /**
     * Get the config path
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return config_path('sage-pay.php');
    }

    /**
     * Publish the config file
     *
     * @param  string $configPath
     */
    protected function publishConfig($configPath)
    {
        $this->publishes([$configPath => config_path('sage-pay.php')], 'config');
    }
}