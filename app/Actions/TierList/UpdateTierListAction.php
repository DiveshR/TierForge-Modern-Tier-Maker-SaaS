<?php

declare(strict_types=1);

namespace App\Actions\TierList;

use App\Contracts\Repositories\TierListRepositoryContract;
use App\DTOs\TierListData;
use App\Models\TierList;

/**
 * Single-responsibility action: update an existing TierList.
 *
 * Receives the already-fetched model and a DTO of the desired new state.
 * Does not re-fetch the model — the Service is responsible for that.
 * Does not validate data — the Form Request does that.
 */
final class UpdateTierListAction
{
    public function __construct(
        private readonly TierListRepositoryContract $repository,
    ) {}

    public function handle(TierList $tierList, TierListData $data): TierList
    {
        return $this->repository->update($tierList, $data->toArray());
    }
}
