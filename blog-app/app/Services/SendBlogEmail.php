<?php

namespace App\Services;

use App\Jobs\SendEmailJob;
use App\Models\User;
use App\Models\EmailScheduler;
use App\Events\EmailScheduled;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SendBlogEmail
{
    public static function sendEmail()
    {
        $emailData = EmailScheduler::where('email_send_time', '<=', Carbon::now())
            ->where('is_send', 0)
            ->first();

        if ($emailData) {

            $emailData->is_send = 1;
            $emailData->save();

            // Fetch the admin user
            $admin = User::where('user_type', 1)->first();

            // Dispatch the email job
            SendEmailJob::dispatch($admin->email, $emailData->body);
        }
    }
}
