<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
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
        JsonResource::withoutWrapping();

        // bind custom
        $this->app->bind(
            \Illuminate\Pagination\LengthAwarePaginator::class,
            \App\Http\Resources\Common\BasePaginatorResource::class,
        );
    }
}
