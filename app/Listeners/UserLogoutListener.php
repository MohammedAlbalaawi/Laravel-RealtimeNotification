<?php

namespace App\Listeners;

use App\Events\UserSessionChangedEvent;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserLogoutListener
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
    public function handle(Logout $event): void
    {
        broadcast(new UserSessionChangedEvent("{$event->user->name} is OFFLINE",'danger'));
    }
}
