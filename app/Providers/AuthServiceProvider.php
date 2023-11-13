<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Laboratory;
use App\Models\PreRegistration;
use App\Models\Role;
use App\Models\User;
use App\Policies\LaboratoryPolicy;
use App\Policies\PreRegistrationPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        PreRegistration::class=>PreRegistrationPolicy::class,
        Laboratory::class=>LaboratoryPolicy::class,
        Role::class=>RolePolicy::class,
        User::class=>UserPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
