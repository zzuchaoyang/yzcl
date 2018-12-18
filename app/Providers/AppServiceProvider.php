<?php

namespace App\Providers;

use App\Fx\Message\SMSSender;
use App\GraphQL\Types\TypeRegistry;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use PhpSms;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // 设置默认 string 长度，兼容 mysql 低版本问题
        Schema::defaultStringLength(190);
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        //
        // 短信工具
        $this->app->bind('sms-sender', function () {
            // 开启队列短信
            PhpSms::queue(false);

            return new SMSSender(PhpSms::make());
        });

        $this->app->singleton('type-registry', function() {
            return new TypeRegistry();
        });
    }
}
