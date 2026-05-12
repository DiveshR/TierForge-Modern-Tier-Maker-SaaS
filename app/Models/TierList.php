<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * TierList Eloquent Model.
 *
 * RESPONSIBILITY: Relationships and casts ONLY.
 * All creation / mutation / query logic lives in TierListRepository.
 *
 * @property string      $id
 * @property string      $user_id
 * @property string      $title
 * @property string      $slug
 * @property string|null $description
 * @property string|null $category
 * @property bool        $is_public
 * @property array|null  $metadata
 * @property string|null $deleted_at
 * @property string      $created_at
 * @property string      $updated_at
 */
final class TierList extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public bool $incrementing = false;
    protected string $keyType = 'string';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'category',
        'is_public',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'metadata'  => 'array',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rows(): HasMany
    {
        return $this->hasMany(TierRow::class)->orderBy('order_index');
    }

    public function itemPositions(): HasMany
    {
        return $this->hasMany(TierItemPosition::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'tier_list_tags');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class, 'likeable_id')
            ->where('likeable_type', self::class);
    }
}
