<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Repositories\TierItemRepositoryContract;
use App\Contracts\Repositories\TierListRepositoryContract;
use App\Contracts\Repositories\UserRepositoryContract;
use App\Contracts\Services\TierListServiceContract;
use App\Repositories\TierItemRepository;
use App\Repositories\TierListRepository;
use App\Repositories\UserRepository;
use App\Services\TierListService;
use Illuminate\Support\ServiceProvider;

/**
 * Application Service Provider.
 *
 * ─── SERVICE CONTAINER BINDINGS ─────────────────────────────────────────────
 *
 * WHY BIND CONTRACTS HERE?
 * ─────────────────────────
 * This is the Dependency Inversion Principle (D in SOLID) in action.
 * Instead of every Service doing `new TierListRepository()`, Laravel's IoC
 * container resolves the correct concrete class automatically when any class
 * type-hints a Contract interface.
 *
 * FLOW:
 *   Controller constructor type-hints TierListServiceContract
 *     → Container sees the binding below
 *       → Injects TierListService (which in turn has its own deps resolved)
 *
 * SWAPPING FOR TESTS:
 *   In a Feature/Unit test, call:
 *     $this->app->instance(TierListRepositoryContract::class, new FakeTierListRepository());
 *   …and the entire stack will use the fake — no database needed.
 *
 * SINGLETON vs BIND:
 *   We use `bind()` (not `singleton()`) for repositories because each HTTP
 *   request should get a fresh instance — no shared state between requests.
 *   Services are also bound, not singletons, for the same reason.
 * ────────────────────────────────────────────────────────────────────────────
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * Explicit bindings: Contract → Concrete implementation.
     * Laravel resolves nested dependencies automatically via the container.
     */
    public function register(): void
    {
        // ── Repositories ──────────────────────────────────────────────────────
        $this->app->bind(
            abstract: TierListRepositoryContract::class,
            concrete: TierListRepository::class,
        );

        $this->app->bind(
            abstract: TierItemRepositoryContract::class,
            concrete: TierItemRepository::class,
        );

        $this->app->bind(
            abstract: UserRepositoryContract::class,
            concrete: UserRepository::class,
        );

        // ── Services ──────────────────────────────────────────────────────────
        $this->app->bind(
            abstract: TierListServiceContract::class,
            concrete: TierListService::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
