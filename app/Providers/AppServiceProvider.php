<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        if (env('APP_ENV') == 'local') {
            //URL::forceSchema('https');

           // $this->setEnvironmentValue("APP_URL","http://localhost:8000");
        }elseif(env('APP_ENV') == 'ngrok') {


            //URL::forceRootUrl(env("APP_URL"));
            //$this->setEnvironmentValue("APP_URL",env("API_URL"));
        }
        URL::forceRootUrl(env("APP_URL"));

        //URL::forceSchema("http");
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }

}
