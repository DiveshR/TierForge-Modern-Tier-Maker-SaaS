<?php

declare(strict_types=1);

namespace App\Actions\TierItem;

use App\Contracts\Repositories\TierItemRepositoryContract;
use App\Models\TierList;

/**
 * Single-responsibility action: atomically reorder all items on a TierList.
 *
 * The payload is the new canonical state of all positions.
 * This action does NOT diff — it replaces all positions in one atomic
 * transaction via the repository's syncPositions() method.
 *
 * Why replace-all instead of diff-and-patch?
 * ───────────────────────────────────────────
 * Drag-and-drop reorder sends the full new state anyway (from the UI).
 * Diff-and-patch adds complexity (missed diffs, race conditions on concurrent
 * edits) for no throughput benefit — the delete + bulk-insert is 2 queries
 * regardless of how many items moved.
 *
 * @example
 *   $action->handle($tierList, [
 *       ['tier_row_id' => 'uuid-A', 'tier_item_id' => 'uuid-1', 'position' => 0],
 *       ['tier_row_id' => 'uuid-B', 'tier_item_id' => 'uuid-2', 'position' => 0],
 *   ]);
 */
final class ReorderTierItemsAction
{
    public function __construct(
        private readonly TierItemRepositoryContract $repository,
    ) {}

    /**
     * @param  array<int, array{tier_row_id: string, tier_item_id: string, position: int}> $positions
     */
    public function handle(TierList $tierList, array $positions): void
    {
        $this->repository->syncPositions($tierList->id, $positions);
    }
}
