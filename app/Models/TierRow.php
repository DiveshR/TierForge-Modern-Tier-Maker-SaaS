<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * TierRow Eloquent Model.
 *
 * Represents a single labelled row in a tier list (e.g. "S", "A", "B").
 * The `color` is stored as a raw hex string in the DB; validated via
 * the ColorHex ValueObject when building TierRowData DTOs.
 *
 * @property string $id
 * @property string $tier_list_id
 * @property string $label
 * @property string $color
 * @property int    $order_index
 * @property string $created_at
 * @property string $updated_at
 */
final class TierRow extends Model
{
    use HasFactory, HasUuids;

    public bool $incrementing = false;
    protected string $keyType = 'string';

    protected $fillable = [
        'tier_list_id',
        'label',
        'color',
        'order_index',
    ];

    protected function casts(): array
    {
        return [
            'order_index' => 'integer',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function tierList(): BelongsTo
    {
        return $this->belongsTo(TierList::class);
    }

    public function itemPositions(): HasMany
    {
        return $this->hasMany(TierItemPosition::class)->orderBy('position');
    }
}
