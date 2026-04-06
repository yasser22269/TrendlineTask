<?php

namespace App\Providers;

use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Repositories\Contracts\PlanPriceRepositoryInterface;
use App\Repositories\Contracts\PlanRepositoryInterface;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use App\Repositories\Eloquent\PaymentRepository;
use App\Repositories\Eloquent\PlanPriceRepository;
use App\Repositories\Eloquent\PlanRepository;
use App\Repositories\Eloquent\SubscriptionRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PlanRepositoryInterface::class,
            PlanRepository::class);
        $this->app->bind(SubscriptionRepositoryInterface::class,
            SubscriptionRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class,
            PaymentRepository::class);
        $this->app->bind(PlanPriceRepositoryInterface::class,
            PlanPriceRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function ($request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
