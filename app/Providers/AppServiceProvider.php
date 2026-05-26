<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Map;
use App\DataTables\FormDataTable;
use App\Http\Controllers\Configuration\FormController;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(FormController::class)
            ->needs(FormDataTable::class)
            ->give(fn () => new FormDataTable());

        // Daftar FormItem kini di-resolve manual di FormItemController (scoped per Form).
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            $availableYears = Map::availableYearsForUser(auth()->user());
            $activeYear = Map::activeYear(auth()->user());
            $view->with(compact('activeYear', 'availableYears'));
        });
    }
}
