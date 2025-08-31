<?php

namespace App\Providers;

use App\Queries\LayerQueryBuilder;
use App\Repositories\LayerModelRepository;
use App\Repositories\PriceModelRepository;
use App\Repositories\ProductModelRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Src\Application\Queries\LayerQuery;
use Src\Application\Repositories\LayerRepository;
use Src\Application\Repositories\PriceRepository;
use Src\Application\Repositories\ProductRepository;

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

        $this->app->singleton(
            abstract: LayerQuery::class,
            concrete: LayerQueryBuilder::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());
    }
}
