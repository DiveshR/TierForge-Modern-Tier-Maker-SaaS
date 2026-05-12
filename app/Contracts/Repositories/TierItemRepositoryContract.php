<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\DTOs\TierItemData;
use App\Models\TierItem;
use App\Models\TierItemPosition;
use Illuminate\Database\Eloquent\Collection;

/**
 * Contract for TierItem and TierItemPosition persistence.
 *
 * Handles both the catalogue item (TierItem) and its placement on a list
 * (TierItemPosition) — kept in one contract because position is meaningless
 * without the item and they always change together.
 */
interface TierItemRepositoryContract
{
    /**
     * Create a new catalogue item.
     */
    public function create(TierItemData $data): TierItem;

    /**
     * Find a single item by primary key.
     */
    public function findById(string $id): ?TierItem;

    /**
     * Update item attributes and return the refreshed model.
     *
     * @param  array<string, mixed> $attributes
     */
    public function update(TierItem $tierItem, array $attributes): TierItem;

    /**
     * Delete an item (hard delete — items are catalogue entries, not user content).
     */
    public function delete(TierItem $tierItem): bool;

    /**
     * Return all items that are positioned on a given TierList.
     */
    public function allForTierList(string $tierListId): Collection;

    /**
     * Bulk-upsert position records for an entire tier list.
     * Used by ReorderTierItemsAction — done atomically in a transaction.
     *
     * @param  array<int, array{tier_list_id: string, tier_row_id: string, tier_item_id: string, position: int}> $positions
     */
    public function syncPositions(string $tierListId, array $positions): void;

    /**
     * Place a single item into a specific row at a specific position.
     */
    public function placeItem(
        string $tierListId,
        string $tierRowId,
        string $tierItemId,
        int    $position,
    ): TierItemPosition;
}
