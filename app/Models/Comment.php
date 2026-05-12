<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Comment Eloquent Model.
 *
 * Supports infinite nesting via self-referential parent_id.
 * Soft-deleted so moderation can restore comments and audit history.
 *
 * @property string      $id
 * @property string      $user_id
 * @property string      $tier_list_id
 * @property string|null $parent_id
 * @property string      $content
 * @property string|null $deleted_at
 * @property string      $created_at
 * @property string      $updated_at
 */
final class Comment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public bool $incrementing = false;
    protected string $keyType = 'string';

    protected $fillable = [
        'user_id',
        'tier_list_id',
        'parent_id',
        'content',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tierList(): BelongsTo
    {
        return $this->belongsTo(TierList::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
