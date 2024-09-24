<?php

namespace App\Listeners;

use App\Events\EmailScheduled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendEmailJob;

class LogScheduledEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EmailScheduled $event)
    {
        Log::info('Email scheduled to be sent to: ' . $event->email);
        Log::info('Message body: ' . $event->messageBody);

        SendEmailJob::dispatch($event->email, $event->messageBody);
    }
}
