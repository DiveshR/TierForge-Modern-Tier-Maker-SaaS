<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Like Eloquent Model.
 *
 * Polymorphic — the same table handles likes on TierLists, Comments, etc.
 * Uniqueness is enforced at the DB level: (user_id, likeable_id, likeable_type).
 *
 * @property string $id
 * @property string $user_id
 * @property string $likeable_id
 * @property string $likeable_type
 * @property string $created_at
 * @property string $updated_at
 */
final class Like extends Model
{
    use HasFactory, HasUuids;

    public bool $incrementing = false;
    protected string $keyType = 'string';

    protected $fillable = [
        'user_id',
        'likeable_id',
        'likeable_type',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }
}
