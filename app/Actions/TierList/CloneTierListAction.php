<?php

declare(strict_types=1);

namespace App\Actions\TierList;

use App\Contracts\Repositories\TierListRepositoryContract;
use App\DTOs\TierListData;
use App\Models\TierList;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Single-responsibility action: deep-clone a TierList.
 *
 * Clone scope:
 *   ✅ New TierList record (new UUID, new slug, same content)
 *   ✅ All TierRows (same labels, colours, order)
 *   ✅ All TierItemPositions (same placements)
 *   ❌ TierItems are NOT duplicated — they are shared catalogue entries
 *   ❌ Media is NOT re-uploaded — positions reference the original items
 *
 * The entire operation runs inside a DB transaction so a failure at any step
 * leaves the database in its original state (no orphaned rows).
 */
final class CloneTierListAction
{
    public function __construct(
        private readonly TierListRepositoryContract $repository,
    ) {}

    public function handle(TierList $source, string $newOwnerId): TierList
    {
        return DB::transaction(function () use ($source, $newOwnerId): TierList {
            // 1. Load the full structure if not already loaded.
            $source->loadMissing(['rows', 'rows.itemPositions', 'tags']);

            // 2. Create the cloned list.
            $cloneData = TierListData::fromArray([
                'user_id'     => $newOwnerId,
                'title'       => $source->title . ' (Copy)',
                'slug'        => Str::slug($source->title) . '-' . Str::random(6),
                'description' => $source->description,
                'category'    => $source->category,
                'is_public'   => false, // clones start as drafts
                'metadata'    => $source->metadata,
            ]);

            $clone = $this->repository->create($cloneData);

            // 3. Clone rows and their item positions.
            foreach ($source->rows as $row) {
                $clonedRow = $clone->rows()->create([
                    'label'       => $row->label,
                    'color'       => $row->color,
                    'order_index' => $row->order_index,
                ]);

                foreach ($row->itemPositions as $position) {
                    $clone->itemPositions()->create([
                        'tier_row_id'  => $clonedRow->id,
                        'tier_item_id' => $position->tier_item_id,
                        'position'     => $position->position,
                    ]);
                }
            }

            // 4. Sync tags (pivot, not cloned).
            $clone->tags()->sync($source->tags->pluck('id')->all());

            return $clone->refresh();
        });
    }
}
