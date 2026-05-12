<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\TierList;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Domain event: a TierList has been published (made public).
 *
 * Dispatched by PublishTierListAction after the DB update.
 * Listeners registered in EventServiceProvider react asynchronously:
 *   - IndexTierListInSearch  → pushes to Meilisearch via queue.
 *   - (future) NotifyFollowers → sends push notifications.
 *
 * SerializesModels ensures the model is safely queued as an ID reference,
 * not a full serialised object — preventing stale data in queue workers.
 */
final class TierListPublished
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly TierList $tierList,
    ) {}
}
