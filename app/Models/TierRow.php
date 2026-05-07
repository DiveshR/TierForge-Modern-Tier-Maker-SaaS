<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TierRow extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'tier_list_id',
        'label',
        'color',
        'order_index',
    ];

    public function tierList(): BelongsTo
    {
        return $this->belongsTo(TierList::class);
    }

    public function itemPositions(): HasMany
    {
        return $this->hasMany(TierItemPosition::class);
    }
}
