<?php

namespace Annotate\Providers;

use Annotate\Console\Commands\AnnotateApiDocCommand;
use Annotate\Console\Commands\AnnotateApiTestCommand;
use Illuminate\Support\ServiceProvider;

class AnnotateServiceProvider extends ServiceProvider
{
    protected $defer = true; // 延迟加载服务

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AnnotateApiDocCommand::class,
                AnnotateApiTestCommand::class,
            ]);
        }
    }

    public function provides()
    {
        // 因为延迟加载 所以要定义 provides 函数 具体参考laravel 文档
        return ['annotate'];
    }
}
