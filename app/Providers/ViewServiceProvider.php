<?php

namespace App\Providers;

use App\Models\CompanyInfo;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // View::share('companyInfo', CompanyInfo::first());
        View::composer('*', function ($view) {
            $view->with('companyInfo', CompanyInfo::first());
        });
    }
}
