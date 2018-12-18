<?php

namespace App\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Toplan\PhpSms\Sms;

class SendReminderSms extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $sms;

    /**
     * Create a new job instance.
     *
     * @param Sms $sms
     */
    public function __construct(Sms $sms)
    {
        $this->sms = $sms;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->sms->send();
    }
}
