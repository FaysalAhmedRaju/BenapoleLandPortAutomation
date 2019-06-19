<?php

namespace App\Listeners;

use App\Events\UserSaving as UserSavingEvent;

class UserSaving
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\UserSaving $event
     * @return mixed
     */
    public function handle(UserSavingEvent $event)
    {
//        app('log')->info($event->user);
        \Log::info($event->user);
    }
}