<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\TierListRepositoryContract;
use App\DTOs\TierListData;
use App\Models\TierList;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Eloquent implementation of the TierList repository.
 *
 * WHY THE REPOSITORY PATTERN?
 * ───────────────────────────
 * 1. **Testability**: The contract can be satisfied by an InMemory fake —
 *    services under test never touch the database.
 * 2. **Single Query Location**: All SQL for TierLists lives here. No raw
 *    queries scattered across Controllers or Services.
 * 3. **Eager-load Strategy**: Optimised with() calls are defined once and
 *    shared everywhere — no accidental N+1 queries.
 * 4. **Swappability**: Migrating from Eloquent to a CQRS read model only
 *    requires a new implementation of this interface.
 *
 * TRADEOFFS:
 * ──────────
 * • Extra abstraction layer — more files to navigate initially.
 * • Thin controllers/services mean slightly more boilerplate classes overall.
 * • Justified for a SaaS with a growing feature set: complexity is front-loaded
 *   here so every future feature is cheaper to build and safer to change.
 */
final class TierListRepository implements TierListRepositoryContract
{
    public function create(TierListData $data): TierList
    {
        return TierList::create($data->toArray());
    }

    public function findById(string $id): ?TierList
    {
        return TierList::find($id);
    }

    public function findBySlug(string $slug): ?TierList
    {
        return TierList::where('slug', $slug)->first();
    }

    public function update(TierList $tierList, array $attributes): TierList
    {
        $tierList->update($attributes);

        return $tierList->refresh();
    }

    public function delete(TierList $tierList): bool
    {
        return (bool) $tierList->delete();
    }

    public function paginatePublic(int $perPage = 15): LengthAwarePaginator
    {
        return TierList::query()
            ->where('is_public', true)
            ->with(['user:id,name', 'tags:id,name,slug'])
            ->latest()
            ->paginate($perPage);
    }

    public function paginateForUser(string $userId, int $perPage = 15): LengthAwarePaginator
    {
        return TierList::query()
            ->where('user_id', $userId)
            ->with(['tags:id,name,slug'])
            ->latest()
            ->paginate($perPage);
    }

    public function findWithFullStructure(string $id): ?TierList
    {
        return TierList::with([
            'rows',
            'rows.itemPositions',
            'rows.itemPositions.tierItem',
            'rows.itemPositions.tierItem.media',
            'tags',
            'user:id,name',
        ])->find($id);
    }

    public function allForUser(string $userId): Collection
    {
        return TierList::query()
            ->where('user_id', $userId)
            ->with(['tags:id,name,slug'])
            ->latest()
            ->get();
    }
}
