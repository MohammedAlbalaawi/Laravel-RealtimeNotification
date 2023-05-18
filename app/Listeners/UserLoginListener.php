<?php

namespace App\Listeners;

use App\Events\UserSessionChangedEvent;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserLoginListener
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
    public function handle(Login $event): void
    {
        broadcast(new UserSessionChangedEvent("{$event->user->name} is ONLINE",'success'));
    }
}
