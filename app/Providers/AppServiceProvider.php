<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        // Eigen, framework-vrije paginatie-weergave (Laravel default is voor Tailwind,
        // dat hier niet geladen is -> gaf gigantische pijlen + dubbele knoppen).
        Paginator::defaultView('vendor.pagination.admin');
    }
}
