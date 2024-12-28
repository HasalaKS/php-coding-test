<?php

namespace App\Jobs;

use App\Mail\SupportTicketCreatedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendCreatedTicketEmailJob implements ShouldQueue
{
    use Queueable, Dispatchable;

    public $supportTicket;

    /**
     * Create a new job instance.
     */
    public function __construct($supportTicket)
    {
        $this->supportTicket = $supportTicket;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->supportTicket->customer_email)->send(new SupportTicketCreatedMail($this->supportTicket));
    }
}
