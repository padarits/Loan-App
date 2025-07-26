<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\TransportDocumentLine;
use App\Observers\TransportDocumentLineObserver;
use App\Models\TransportDocument;
use App\Observers\TransportDocumentObserver;

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
        //
        TransportDocumentLine::observe(TransportDocumentLineObserver::class);
        TransportDocument::observe(TransportDocumentObserver::class);
    }
}
