<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Models\Order\Order;
use App\Observers\Order\OrderObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
    ];

    protected $observers = [
        Order::class   => OrderObserver::class,
    ];

    /**
     * Register any events for your application.
     */
    public function boot()
    {
        parent::boot();

        $this->bootObserver();
    }

    public function bootObserver()
    {
        foreach ($this->observers as $modelName => $observerName) {
            $modelName::observe(new $observerName());
        }
    }
}
