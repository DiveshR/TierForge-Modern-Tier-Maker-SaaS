<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\TierItem\ReorderTierItemsAction;
use App\Contracts\Repositories\TierItemRepositoryContract;
use App\DTOs\TierItemData;
use App\Models\TierItem;
use App\Models\TierItemPosition;
use App\Models\TierList;
use Illuminate\Database\Eloquent\Collection;

/**
 * TierItem Service — Orchestration Layer.
 *
 * Coordinates item catalogue operations and position management.
 * Reorder is the most performance-critical operation:
 *   - Delegates entirely to ReorderTierItemsAction → TierItemRepository::syncPositions()
 *   - Two DB round-trips regardless of list size (delete + bulk insert).
 */
final class TierItemService
{
    public function __construct(
        private readonly ReorderTierItemsAction     $reorderAction,
        private readonly TierItemRepositoryContract $repository,
    ) {}

    public function create(TierItemData $data): TierItem
    {
        return $this->repository->create($data);
    }

    public function update(TierItem $tierItem, TierItemData $data): TierItem
    {
        return $this->repository->update($tierItem, $data->toArray());
    }

    public function delete(TierItem $tierItem): bool
    {
        return $this->repository->delete($tierItem);
    }

    /**
     * @param  array<int, array{tier_row_id: string, tier_item_id: string, position: int}> $positions
     */
    public function reorder(TierList $tierList, array $positions): void
    {
        $this->reorderAction->handle($tierList, $positions);
    }

    public function placeItem(
        string $tierListId,
        string $tierRowId,
        string $tierItemId,
        int    $position,
    ): TierItemPosition {
        return $this->repository->placeItem($tierListId, $tierRowId, $tierItemId, $position);
    }

    public function allForTierList(string $tierListId): Collection
    {
        return $this->repository->allForTierList($tierListId);
    }
}
