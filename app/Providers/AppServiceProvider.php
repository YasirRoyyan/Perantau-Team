<?php

namespace App\Providers;

use App\Console\Commands\ServeCommand as StableServeCommand;
use App\Models\NavigationItem;
use Illuminate\Foundation\Console\ServeCommand as FrameworkServeCommand;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->app->singleton(FrameworkServeCommand::class, StableServeCommand::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('partials.nav', function ($view) {
            $view->with('navItems', NavigationItem::activeOrFallback());
        });
    }
}
