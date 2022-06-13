<?php

namespace App\Jobs;

use App\Contracts\EmailHandler;
use App\Mail\PlainTextEmail;
use App\Models\EmailRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public EmailRequest $email,
        public int $emailIdInStorage,
        public EmailHandler $emailHandler
    ) {
        $this->connection = 'redis';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email->getTo())->send(
            new PlainTextEmail($this->email)
        );
        $this->emailHandler->changeStatusToSent($this->emailIdInStorage);
    }

    public function failed()
    {
        $this->emailHandler->changeStatusToFailed($this->emailIdInStorage);
        Log::error("Couldn't send email from " . $this->email->getFrom() . " to " . $this->email->getTo());
    }
}
