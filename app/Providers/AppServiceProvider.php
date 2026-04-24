<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Map;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot()
    {
        View::composer('*', function ($view) {
            $availableYears = Map::availableYearsForUser(auth()->user());
            $activeYear = Map::activeYear(auth()->user());
            $view->with(compact('activeYear', 'availableYears'));
        });
    }
}
