<?php

namespace Properos\Commerce\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Properos\Base\Classes\Theme;

class SubscriptionExpired implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $user;
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;

        // \Mail::send('themes.'.Theme::get().'.emails.subscription-expired', ['user'=>$user], function ($message) use ($user) {
        //     $message->to($user->email);
        //     $message->subject(env('APP_NAME', 'Properos'). ' - Expired Subscription');
        // });
    }
}
