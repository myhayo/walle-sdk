<?php

namespace Myhayo\Walle\Providers;

use Illuminate\Support\ServiceProvider;
use Myhayo\Walle\WalleService;

class WalleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // 单例绑定服务
        $this->app->singleton('walle', function ($app) {
            return new WalleService($app['config']);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/walle.php' => config_path('walle.php'), // 发布配置文件到 laravel 的config 下
        ]);
    }
}
