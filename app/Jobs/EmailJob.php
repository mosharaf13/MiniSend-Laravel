<?php

namespace App\Jobs;

use App\Contracts\AttachmentStorage;
use App\Contracts\EmailHandler;
use App\Contracts\EmailStorage;
use App\Models\EmailRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public EmailRequest $emailRequest,
        public int $emailIdInStorage,
        public EmailHandler $emailHandler,
        public EmailStorage $emailStorage,
    ) {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->emailHandler->send($this->emailRequest);
        $this->emailStorage->changeStatusToSent($this->emailIdInStorage);
    }


    public function failed()
    {
        $this->emailStorage->changeStatusToFailed($this->emailIdInStorage);
        Log::error(
            "Couldn't send email from " . $this->emailRequest->getFrom() . " to " . $this->emailRequest->getTo()
        );
    }
}
