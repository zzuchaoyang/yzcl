<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot()
    {
        //

        parent::boot();

        //Route::model('customer', Customer::class);
    }

    /**
     * Define the routes for the application.
     */
    public function map()
    {
        if (file_exists(base_path('routes/t.php'))) {
            Route::namespace($this->namespace)->middleware('web')
                ->group(base_path('routes/t.php'));
        }

        $this->mapSupplierRoutes();

        // 本地测试 路由
        Route::group([
            'domain'     => config('app.url'),
            'namespace'  => $this->namespace,
            'middleware' => 'web',
        ], function ($router) {
            if (is_file(base_path('routes/t.php'))) {
                require base_path('routes/t.php');
            }
        });
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapSupplierRoutes()
    {
        Route::group([
            'domain'     => config('app.url'),
            'namespace'  => $this->namespace,
            'middleware' => 'web',
        ], function ($router) {
            require __DIR__.'/../../routes/supplier.php';
        });
    }
}
