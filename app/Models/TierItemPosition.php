<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * TierItemPosition Eloquent Model.
 *
 * The pivot-with-extras that records WHERE a TierItem sits on a TierList.
 * Contains: which list, which row, which item, and the integer position
 * within the row (0-indexed).
 *
 * Performance note: (tier_list_id, tier_row_id, position) has a composite
 * index defined in the migration for fast sort-order retrieval.
 *
 * @property string $id
 * @property string $tier_list_id
 * @property string $tier_row_id
 * @property string $tier_item_id
 * @property int    $position
 * @property string $created_at
 * @property string $updated_at
 */
final class TierItemPosition extends Model
{
    use HasFactory, HasUuids;

    public bool $incrementing = false;
    protected string $keyType = 'string';

    protected $fillable = [
        'tier_list_id',
        'tier_row_id',
        'tier_item_id',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'position' => 'integer',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function tierList(): BelongsTo
    {
        return $this->belongsTo(TierList::class);
    }

    public function tierRow(): BelongsTo
    {
        return $this->belongsTo(TierRow::class);
    }

    public function tierItem(): BelongsTo
    {
        return $this->belongsTo(TierItem::class);
    }
}
