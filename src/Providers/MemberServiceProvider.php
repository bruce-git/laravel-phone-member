<?php
namespace Bruce\LaravelShopPhoneMember\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Arr;
use EasyWeChat\OfficialAccount\Application as OfficialAccount;
/**
 * 服务提供者
 */
class MemberServiceProvider extends ServiceProvider
{
    // member组件需要注入的中间件
    protected $routeMiddleware = [

         'wechat.oauth' => \Overtrue\LaravelWeChat\Middleware\OAuthAuthenticate::class,
    ];

    // 这是命令的注册注册地点
    protected $commands = [
        \Bruce\LaravelShopPhoneMember\Console\Commands\InstallCommand::class
    ];

    protected $middlewareGroups = [];


    // 模仿
    public function register()
    {
        // 注册组件路由
        $this->registerRoutes();

        $this->mergeConfigFrom(__DIR__.'/../Config/member.php', "phone.member");

        $this->registerRouteMiddleware();

        $this->registerPublishing();
    }
    public function boot()
    {
        $this->loadMigrations();
        $this->loadMemberAuthConfig();
        $this->commands($this->commands);

        $this->app->singleton("wechat.official_account.default", function ($laravelApp) {
            $app = new OfficialAccount(array_merge(config('wechat.defaults', []), config("wechat.official_account.default", [])));
            if (config('wechat.defaults.use_laravel_cache')) {
                $app['cache'] = $laravelApp['cache.store'];
            }
            $app['request'] = $laravelApp['request'];
            return $app;
        });
    }

    public function loadMigrations()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        }
    }
    protected function loadMemberAuthConfig()
    {
        config(Arr::dot(config('phone.member.wechat', []), 'wechat.'));
        config(Arr::dot(config('phone.member.auth', []), 'auth.'));
    }

    public function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../Config' => config_path('wap')], 'laravel-shop-phone-member');
        }
    }

    protected function registerRouteMiddleware()
    {
        foreach ($this->middlewareGroups as $key => $middleware) {
            $this->app['router']->middlewareGroup($key, $middleware);
        }

        foreach ($this->routeMiddleware as $key => $middleware) {
            $this->app['router']->aliasMiddleware($key, $middleware);
        }
    }

    private function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../Http/routes.php');
        });
    }

    private function routeConfiguration()
    {
        return [
            'namespace' => 'Bruce\LaravelShopPhoneMember\Http\Controllers',
            // 这是前缀
            'prefix' => 'phone/member',
            // 这是中间件
            'middleware' => 'web',
        ];
    }
}
