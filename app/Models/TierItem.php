<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class TierItem extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'json',
    ];

    public function itemPositions(): HasMany
    {
        return $this->hasMany(TierItemPosition::class);
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
