<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'mediable_id',
        'mediable_type',
        'file_path',
        'file_name',
        'mime_type',
        'size',
        'custom_properties',
    ];

    protected $casts = [
        'custom_properties' => 'json',
    ];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }
}
