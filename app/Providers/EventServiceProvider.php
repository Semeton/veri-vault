<?php

namespace App\Providers;

use App\Events\ChatActivity;
use App\Events\ChatRequest;
use App\Events\NewUserRegistration;
use App\Listeners\ChatRequestNotification;
use App\Listeners\HandleNewUserRegistration;
use App\Listeners\SendChatActivityNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [SendEmailVerificationNotification::class],
        NewUserRegistration::class => [HandleNewUserRegistration::class],
        ChatRequest::class => [ChatRequestNotification::class],
        ChatActivity::class => [SendChatActivityNotification::class],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
