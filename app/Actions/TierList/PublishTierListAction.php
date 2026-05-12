<?php

declare(strict_types=1);

namespace App\Actions\TierList;

use App\Contracts\Repositories\TierListRepositoryContract;
use App\Events\TierListPublished;
use App\Models\TierList;

/**
 * Single-responsibility action: publish a TierList.
 *
 * Publishing is separated from updating because it has its own side-effects:
 *   1. Sets `is_public = true`.
 *   2. Dispatches the `TierListPublished` domain event, which triggers:
 *      - Meilisearch indexing (async via queue).
 *      - Notification to followers (future feature).
 *
 * By keeping this in its own Action, we can test the publish flow — including
 * event dispatch — without exercising the full update path.
 */
final class PublishTierListAction
{
    public function __construct(
        private readonly TierListRepositoryContract $repository,
    ) {}

    public function handle(TierList $tierList): TierList
    {
        $tierList = $this->repository->update($tierList, ['is_public' => true]);

        TierListPublished::dispatch($tierList);

        return $tierList;
    }
}
