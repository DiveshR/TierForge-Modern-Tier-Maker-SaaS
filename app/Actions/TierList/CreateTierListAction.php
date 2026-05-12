<?php

declare(strict_types=1);

namespace App\Actions\TierList;

use App\Contracts\Repositories\TierListRepositoryContract;
use App\DTOs\TierListData;
use App\Models\TierList;

/**
 * Single-responsibility action: create one TierList.
 *
 * Why an Action instead of putting this in the Service?
 * ─────────────────────────────────────────────────────
 * An Action does exactly ONE thing. This makes it:
 *  - Unit-testable in complete isolation (mock only the repository).
 *  - Reusable — the clone action, an import command, or a Filament form can
 *    all call this without duplicating creation logic.
 *  - Easy to decorate with logging, auditing, or rate-limiting without
 *    touching the Service.
 *
 * The Service calls this Action; the Action calls the Repository.
 * The Controller never calls the Action directly.
 */
final class CreateTierListAction
{
    public function __construct(
        private readonly TierListRepositoryContract $repository,
    ) {}

    public function handle(TierListData $data): TierList
    {
        return $this->repository->create($data);
    }
}
