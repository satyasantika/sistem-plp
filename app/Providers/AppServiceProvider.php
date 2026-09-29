<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Map;
use App\DataTables\FormDataTable;
use App\Http\Controllers\Configuration\FormController;
use Illuminate\Support\Facades\URL;
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
        // Saat di-deploy di bawah subpath (mis. https://supportfkip.unsil.ac.id/plp) lewat reverse proxy,
        // request yang sampai ke container tidak membawa prefix /plp. Paksa URL dasar mengikuti APP_URL
        // agar route(), url(), asset(), dan redirect menghasilkan /plp/... bukan /...
        $appUrl = rtrim((string) config('app.url'), '/');
        if (parse_url($appUrl, PHP_URL_PATH)) {
            URL::forceRootUrl($appUrl);
        }
        if (str_starts_with($appUrl, 'https://')) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            $availableYears = Map::availableYearsForUser(auth()->user());
            $activeYear = Map::activeYear(auth()->user());
            $view->with(compact('activeYear', 'availableYears'));
        });
    }
}
