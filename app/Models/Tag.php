<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Tag Eloquent Model.
 *
 * Simple taxonomy model — tags are shared across all tier lists.
 * Many-to-many via the `tier_list_tags` pivot table.
 *
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string $created_at
 * @property string $updated_at
 */
final class Tag extends Model
{
    use HasFactory, HasUuids;

    public bool $incrementing = false;
    protected string $keyType = 'string';

    protected $fillable = [
        'name',
        'slug',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function tierLists(): BelongsToMany
    {
        return $this->belongsToMany(TierList::class, 'tier_list_tags');
    }
}
