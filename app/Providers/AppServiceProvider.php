<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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
        Builder::macro('getToApi', function (Request $request): LengthAwarePaginator|Collection {
            if ($request->has('no_pagination')) {
                return $this->get();
            }

            return $this->paginate($request->get('per_page') ?? config('app.pagination.per_page'));
        });

        Collection::macro('list', function (string $keyField, string $labelField): SupportCollection {
            /** @var Collection $this */
            return $this->map(function ($item) use ($keyField, $labelField) {
                return [
                    'key' => data_get($item, $keyField),
                    'label' => data_get($item, $labelField),
                ];
            });
        });
    }
}
