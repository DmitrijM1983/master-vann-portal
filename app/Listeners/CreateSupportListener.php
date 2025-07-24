<?php

namespace App\Listeners;

use App\Events\CreateSupportEvent;
use App\Mail\SupportMail;
use Illuminate\Support\Facades\Mail;

readonly class CreateSupportListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {}

    /**
     * Handle the event.
     */
    public function handle(CreateSupportEvent $event): void
    {
        if ($event->user != null) {
            Mail::to('dmitrij.m.183@yandex.ru')->send(new SupportMail($event->support, $event->user));
        } else {
            Mail::to('dmitrij.m.183@yandex.ru')->send(new SupportMail($event->support));
        }
    }
}
