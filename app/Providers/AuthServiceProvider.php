<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::tokensCan([
            'get' => 'get a list of categories available',
            'edit' => 'can edit categories available',
            'create' => 'can add new categories',
            'delete' => 'can delete categories',
        ]);

        Passport::routes();
		
        Passport::tokensExpireIn(now()->addHours(1));

        Passport::refreshTokensExpireIn(now()->addHours(1));

        Passport::personalAccessTokensExpireIn(now()->addHours(1));
    }
}
