<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\TierList\CloneTierListAction;
use App\Actions\TierList\CreateTierListAction;
use App\Actions\TierList\PublishTierListAction;
use App\Actions\TierList\UpdateTierListAction;
use App\Contracts\Repositories\TierListRepositoryContract;
use App\Contracts\Services\TierListServiceContract;
use App\DTOs\TierListData;
use App\Models\TierList;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * TierList Service — Orchestration Layer.
 *
 * WHAT THE SERVICE DOES:
 * ──────────────────────
 * The service is the chef of the kitchen. It:
 *   1. Receives a DTO from the Controller.
 *   2. Decides WHICH Action(s) to call and in what order.
 *   3. Handles cross-cutting concerns: authorization checks, logging,
 *      event sequencing, and rollback strategies.
 *   4. Returns a model (or collection) to the Controller.
 *
 * WHAT THE SERVICE DOES NOT DO:
 * ──────────────────────────────
 * ❌ Knows about HTTP (no Request, Response, or status codes).
 * ❌ Contains SQL queries (delegates to Actions → Repository).
 * ❌ Validates user input (that's the Form Request's job).
 *
 * SCALABILITY NOTE:
 * ─────────────────
 * As features grow, new capabilities are added by injecting more Actions,
 * never by expanding existing Action classes. The Service method signatures
 * stay stable — only the orchestration inside them evolves.
 */
final class TierListService implements TierListServiceContract
{
    public function __construct(
        private readonly CreateTierListAction       $createAction,
        private readonly UpdateTierListAction       $updateAction,
        private readonly PublishTierListAction      $publishAction,
        private readonly CloneTierListAction        $cloneAction,
        private readonly TierListRepositoryContract $repository,
    ) {}

    public function create(TierListData $data): TierList
    {
        return $this->createAction->handle($data);
    }

    public function update(TierList $tierList, TierListData $data): TierList
    {
        return $this->updateAction->handle($tierList, $data);
    }

    public function publish(TierList $tierList): TierList
    {
        return $this->publishAction->handle($tierList);
    }

    public function clone(TierList $source, string $newOwnerId): TierList
    {
        return $this->cloneAction->handle($source, $newOwnerId);
    }

    public function delete(TierList $tierList): bool
    {
        return $this->repository->delete($tierList);
    }

    public function publicFeed(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginatePublic($perPage);
    }

    public function userFeed(string $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginateForUser($userId, $perPage);
    }
}
