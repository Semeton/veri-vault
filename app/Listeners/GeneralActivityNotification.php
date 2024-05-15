<?php

namespace App\Listeners;

use App\Events\GeneralActivity;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GeneralActivityNotification
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
    public function handle(GeneralActivity $event): void
    {
        //
    }
}
