<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * ActivityLog Eloquent Model.
 *
 * Append-only audit trail. Never soft-deleted — historical integrity.
 * The `properties` JSONB column stores before/after snapshots of changed
 * attributes, enabling full audit replay.
 *
 * @property string      $id
 * @property string|null $user_id
 * @property string      $event
 * @property string|null $subject_id
 * @property string|null $subject_type
 * @property array|null  $properties
 * @property string|null $ip_address
 * @property string      $created_at
 * @property string      $updated_at
 */
final class ActivityLog extends Model
{
    use HasFactory, HasUuids;

    public bool $incrementing = false;
    protected string $keyType = 'string';

    protected $fillable = [
        'user_id',
        'event',
        'subject_id',
        'subject_type',
        'properties',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'properties' => 'array',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}
