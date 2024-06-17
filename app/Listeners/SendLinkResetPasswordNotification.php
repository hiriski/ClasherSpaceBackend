<?php

namespace App\Listeners;

use App\Events\EventSendResetPasswordLink;
use App\Mail\ResetPasswordLinkInstruction;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendLinkResetPasswordNotification
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
    public function handle(EventSendResetPasswordLink $event): void
    {
        try {
            Mail::to($event->data['email'])->send(new ResetPasswordLinkInstruction([
                'email' => $event->data['email'],
                'link'  => $event->data['link'],
            ]));
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
