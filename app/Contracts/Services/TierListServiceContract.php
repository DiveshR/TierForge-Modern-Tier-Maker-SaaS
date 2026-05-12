<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\DTOs\TierListData;
use App\Models\TierList;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Service contract for the TierList domain.
 *
 * The Service layer orchestrates Actions and Repositories. Controllers depend
 * on this contract, not on the concrete Service class — enabling mocking in
 * Feature tests without a real database.
 */
interface TierListServiceContract
{
    /**
     * Create a new tier list, dispatch post-creation events, return model.
     */
    public function create(TierListData $data): TierList;

    /**
     * Update an existing tier list's attributes.
     */
    public function update(TierList $tierList, TierListData $data): TierList;

    /**
     * Mark a tier list as published. Dispatches TierListPublished event.
     */
    public function publish(TierList $tierList): TierList;

    /**
     * Clone a tier list (rows + item positions) under the requesting user.
     */
    public function clone(TierList $source, string $newOwnerId): TierList;

    /**
     * Soft-delete a tier list.
     */
    public function delete(TierList $tierList): bool;

    /**
     * Paginated public feed.
     */
    public function publicFeed(int $perPage = 15): LengthAwarePaginator;

    /**
     * Paginated list of tier lists for a specific user.
     */
    public function userFeed(string $userId, int $perPage = 15): LengthAwarePaginator;
}
