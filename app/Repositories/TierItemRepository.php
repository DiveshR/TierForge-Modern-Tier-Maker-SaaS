<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\TierItemRepositoryContract;
use App\DTOs\TierItemData;
use App\Models\TierItem;
use App\Models\TierItemPosition;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Eloquent implementation of the TierItem repository.
 *
 * Owns all queries relating to:
 *   - The TierItem catalogue (the items themselves)
 *   - TierItemPosition (where each item sits on a list)
 *
 * The syncPositions() method uses an atomic DB transaction with upsert()
 * to guarantee that a reorder either fully succeeds or fully rolls back.
 * This prevents the "partial reorder" data corruption bug common in naive
 * loop-based implementations.
 */
final class TierItemRepository implements TierItemRepositoryContract
{
    public function create(TierItemData $data): TierItem
    {
        return TierItem::create($data->toArray());
    }

    public function findById(string $id): ?TierItem
    {
        return TierItem::find($id);
    }

    public function update(TierItem $tierItem, array $attributes): TierItem
    {
        $tierItem->update($attributes);

        return $tierItem->refresh();
    }

    public function delete(TierItem $tierItem): bool
    {
        return (bool) $tierItem->delete();
    }

    public function allForTierList(string $tierListId): Collection
    {
        return TierItem::query()
            ->whereHas('itemPositions', fn ($q) => $q->where('tier_list_id', $tierListId))
            ->with(['media', 'itemPositions' => fn ($q) => $q->where('tier_list_id', $tierListId)])
            ->get();
    }

    public function syncPositions(string $tierListId, array $positions): void
    {
        DB::transaction(function () use ($tierListId, $positions): void {
            // Wipe existing positions for this list, then bulk-insert the new order.
            // Upsert keeps it to 2 DB round-trips regardless of list size.
            TierItemPosition::where('tier_list_id', $tierListId)->delete();

            TierItemPosition::insert(
                array_map(
                    static fn (array $pos): array => [
                        'tier_list_id' => $tierListId,
                        'tier_row_id'  => $pos['tier_row_id'],
                        'tier_item_id' => $pos['tier_item_id'],
                        'position'     => $pos['position'],
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ],
                    $positions
                )
            );
        });
    }

    public function placeItem(
        string $tierListId,
        string $tierRowId,
        string $tierItemId,
        int    $position,
    ): TierItemPosition {
        return TierItemPosition::updateOrCreate(
            [
                'tier_list_id' => $tierListId,
                'tier_item_id' => $tierItemId,
            ],
            [
                'tier_row_id' => $tierRowId,
                'position'    => $position,
            ]
        );
    }
}
