<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Media Eloquent Model.
 *
 * Polymorphic media store — images, thumbnails, and assets for any model
 * that uses the `mediable` morph relationship (TierItem, User avatar, etc.)
 *
 * @property string      $id
 * @property string      $mediable_id
 * @property string      $mediable_type
 * @property string      $file_path
 * @property string      $file_name
 * @property string      $mime_type
 * @property int         $size
 * @property array|null  $custom_properties
 * @property string      $created_at
 * @property string      $updated_at
 */
final class Media extends Model
{
    use HasFactory, HasUuids;

    public bool $incrementing = false;
    protected string $keyType = 'string';

    protected $fillable = [
        'mediable_id',
        'mediable_type',
        'file_path',
        'file_name',
        'mime_type',
        'size',
        'custom_properties',
    ];

    protected function casts(): array
    {
        return [
            'custom_properties' => 'array',
            'size'              => 'integer',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }
}
