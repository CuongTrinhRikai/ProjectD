<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
//        $this->registerPolicies();
//        Passport::routes();

        $this->registerPolicies();
        Passport::routes();

        Passport::tokensExpireIn(now()->addMonths(1));
        Passport::refreshTokensExpireIn(now()->addMonths(2));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

//        Passport::tokensExpireIn(Carbon::now()->addMinutes(1));
//        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
//        Passport::personalAccessTokensExpireIn(Carbon::now()->addMonths(6));
    }
}
