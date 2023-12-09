<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Comment::class => CommentPolicy::class,
        Event::class => EventPolicy::class,
        Notification::class => NotificationPolicy::class,
        Organization::class => OrganizationPolicy::class,
        ReportComment::class => ReportCommentPolicy::class,
        ReportEvent::class => ReportEventPolicy::class,
        User::class => UserPolicy::class,
        VoteComment::class => VoteCommentPolicy::class,    
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
