<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Validation\CustomValidation;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->validator->resolver(function ($translator, $data, $rules, $messages) {
            return new CustomValidation($translator, $data, $rules, $messages);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
