<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TierItemPosition extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'tier_list_id',
        'tier_row_id',
        'tier_item_id',
        'position',
    ];

    public function tierList(): BelongsTo
    {
        return $this->belongsTo(TierList::class);
    }

    public function tierRow(): BelongsTo
    {
        return $this->belongsTo(TierRow::class);
    }

    public function tierItem(): BelongsTo
    {
        return $this->belongsTo(TierItem::class);
    }
}
