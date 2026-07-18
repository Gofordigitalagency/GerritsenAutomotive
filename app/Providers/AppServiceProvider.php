<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

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

        // Forceer https-URL's in productie. Zonder dit genereert route()/url()
        // http-links (de app draait achter de nginx-proxy op Forge). Een POST
        // naar zo'n http-URL wordt door de server 301'd naar https, waarna de
        // browser 'm als GET opnieuw stuurt -> "GET method not supported" (405)
        // bij o.a. het als verkocht markeren. Alleen in productie, zodat lokaal
        // (http://...test) blijft werken.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
