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
        // Every admin page (and several frontend pages) calls the bare
        // ->links() helper, which without this falls back to Laravel's raw
        // "pagination::default" view. That view renders the prev/next
        // arrows as plain, unstyled <a> tags with NO Bootstrap classes
        // (no `page-item` / `page-link`), so the ‹ › glyphs render at the
        // browser's default font-size — much bigger than the rest of the
        // compact admin UI. Switching the app-wide default to Bootstrap 5
        // makes every ->links() call use the properly classed & sized
        // pagination::bootstrap-5 view instead, without having to touch
        // every individual blade file.
        Paginator::useBootstrapFive();
    }
}
