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

        $root = dirname(dirname(__DIR__));
        $this->publishes([
            //发布配置文件
            $root . '/config/annotate.php' => config_path('annotate.php'),
        ]);
        //发布数据库
        $this->loadMigrationsFrom($root . '/migrations');
        //发布路由
        $this->loadRoutesFrom($root . '/routes/annotate.php');
        //发布视图
        $this->loadViewsFrom($root . '/resources/views', 'annotate');
        $this->publishes([
            $root . '/resources/views' => resource_path('views/vendor/annotate'),
        ]);
    }

    public function provides()
    {
        // 因为延迟加载 所以要定义 provides 函数 具体参考laravel 文档
        return ['annotate'];
    }
}
