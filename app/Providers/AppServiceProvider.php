<?php

namespace App\Providers;

use App\Services\SeoService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SeoService::class, fn() => new SeoService());
    }

    public function boot(): void
    {
        // Share a default $seo with EVERY view (prevents "Undefined variable $seo")
        View::share('seo', new SeoService());

        // Register our custom pagination view as the DEFAULT
        // so ->links() and ->links('vendor.pagination.tailwind') both work.
        Paginator::defaultView('pagination.tailwind');
        Paginator::defaultSimpleView('pagination.tailwind');
    }
}
