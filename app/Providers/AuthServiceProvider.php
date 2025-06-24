<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Notice;
use App\Models\Person;
use App\Models\Service;
use App\Policies\ActivityPolicy;
use App\Policies\NoticePolicy;
use App\Policies\PersonPolicy;
use App\Policies\ServicePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Activitylog\Models\Activity;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Activity::class => ActivityPolicy::class,
        Service::class => ServicePolicy::class,
        Person::class => PersonPolicy::class,
        Notice::class => NoticePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
