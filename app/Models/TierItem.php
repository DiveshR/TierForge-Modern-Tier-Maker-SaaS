<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * TierItem Eloquent Model.
 *
 * Represents a catalogue entry — an item that CAN be placed on tier lists.
 * TierItems are shared resources: the same item can appear on multiple lists
 * at different positions. Placement is tracked via TierItemPosition.
 *
 * @property string      $id
 * @property string      $name
 * @property string|null $description
 * @property array|null  $metadata
 * @property string      $created_at
 * @property string      $updated_at
 */
final class TierItem extends Model
{
    use HasFactory, HasUuids;

    public bool $incrementing = false;
    protected string $keyType = 'string';

    protected $fillable = [
        'name',
        'description',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function itemPositions(): HasMany
    {
        return $this->hasMany(TierItemPosition::class);
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
