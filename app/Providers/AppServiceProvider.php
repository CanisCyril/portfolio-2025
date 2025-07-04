<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Contracts\Payments\PaymentServiceInterface;
use App\Services\Payments\StripePaymentService;

use App\Contracts\ProductSearchServiceInterface;
use App\Services\ProductSearchService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentServiceInterface::class, StripePaymentService::class);
        $this->app->bind(ProductSearchServiceInterface::class, ProductSearchService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
