<?php

namespace App\Listeners;

use App\Events\FirstExampleEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FirstExampleListener
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
    public function handle(FirstExampleEvent $event): void
    {
        Log::debug("The user {$event->username} just performed {$event->action}.");
    }
}
