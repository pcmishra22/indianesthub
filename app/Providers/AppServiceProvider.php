<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

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
        // The whole app (admin/AdminKit, builder panel, dealer panel, frontend)
        // is styled with Bootstrap, not Tailwind. Laravel's built-in default
        // pagination view is "tailwind", which renders <svg class="w-5 h-5">
        // previous/next arrows sized purely by Tailwind utility classes.
        // Since Tailwind CSS isn't loaded anywhere in this app, those classes
        // do nothing and the SVGs fall back to their native (huge) size —
        // that's the "big pagination arrow" bug across the admin panel.
        // Bootstrap 5's pagination view uses plain text chevrons + standard
        // .page-link classes, so it renders correctly everywhere ->links()
        // is called without touching every single blade file.
        Paginator::defaultView('vendor.pagination.bootstrap-5');
        Paginator::defaultSimpleView('vendor.pagination.simple-bootstrap-5');
    }
}
