<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\EmailScheduled' => [
            'App\Listeners\LogScheduledEmail',
        ],
    ];

    /**
     * Register services.
     */
    public function register() : void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot() : void
    {
        parent::boot(); // Ensure parent boot method is called

        // You can add any custom boot logic if needed
    }
}
