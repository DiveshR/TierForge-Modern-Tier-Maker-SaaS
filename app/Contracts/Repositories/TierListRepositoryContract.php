<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\DTOs\TierListData;
use App\Models\TierList;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Contract for all TierList persistence operations.
 *
 * Why an interface here?
 * ─────────────────────
 * The Service layer depends on this contract, NOT on the concrete Eloquent
 * repository. This means:
 *   1. Tests can inject an InMemory implementation — no database required.
 *   2. We can swap to a different ORM or data source without changing the Service.
 *   3. Larastan / static analysis can verify that every implementation is complete.
 *
 * Segregation: this interface deliberately stays focused on TierList. It does
 * NOT include methods for TierRows or TierItems — those live in their own contracts.
 */
interface TierListRepositoryContract
{
    /**
     * Persist a new TierList from a DTO and return the hydrated model.
     */
    public function create(TierListData $data): TierList;

    /**
     * Find a single TierList by primary key. Returns null when not found.
     */
    public function findById(string $id): ?TierList;

    /**
     * Find a TierList by its URL slug. Returns null when not found.
     */
    public function findBySlug(string $slug): ?TierList;

    /**
     * Apply arbitrary attribute updates and return the refreshed model.
     *
     * @param  array<string, mixed> $attributes
     */
    public function update(TierList $tierList, array $attributes): TierList;

    /**
     * Soft-delete the TierList. Returns true on success.
     */
    public function delete(TierList $tierList): bool;

    /**
     * Paginated feed of all public tier lists, newest first.
     */
    public function paginatePublic(int $perPage = 15): LengthAwarePaginator;

    /**
     * Paginated list of tier lists owned by a specific user.
     */
    public function paginateForUser(string $userId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Eager-load a TierList with its full structure (rows + item positions + items).
     * Used by the editor view — single query trip via with().
     */
    public function findWithFullStructure(string $id): ?TierList;

    /**
     * Return all TierLists belonging to a user — no pagination, for exports.
     */
    public function allForUser(string $userId): Collection;
}
