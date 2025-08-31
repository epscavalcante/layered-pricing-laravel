<?php

namespace App\Providers;

use App\Repositories\LayerModelRepository;
use App\Repositories\PriceModelRepository;
use App\Repositories\ProductModelRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Src\Domain\Repositories\LayerRepository;
use Src\Domain\Repositories\PriceRepository;
use Src\Domain\Repositories\ProductRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            abstract: ProductRepository::class,
            concrete: ProductModelRepository::class
        );

        $this->app->singleton(
            abstract: LayerRepository::class,
            concrete: LayerModelRepository::class
        );

        $this->app->singleton(
            abstract: PriceRepository::class,
            concrete: PriceModelRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(! $this->app->isProduction());
    }
}
