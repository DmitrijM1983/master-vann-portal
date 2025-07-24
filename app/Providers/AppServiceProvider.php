<?php

namespace App\Providers;

use App\Events\CreateSupportEvent;
use App\Listeners\CreateSupportListener;
use App\Services\ConnectionService;
use App\Services\IConnectionService;
use App\Services\IMasterService;
use App\Services\MasterService;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IMasterService::class, MasterService::class);
        $this->app->bind(IConnectionService::class, ConnectionService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Подтверждение электронной почты')
                ->line('Для подтверждения электронной почты нажмите на кнопку.')
                ->action('Подтвердить электронную почту', $url);
        });

        Event::listen(
            CreateSupportEvent::class,
            CreateSupportListener::class,
        );
    }
}
