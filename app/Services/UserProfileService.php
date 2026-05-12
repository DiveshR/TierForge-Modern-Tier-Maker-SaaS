<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\User\UpdateUserProfileAction;
use App\Contracts\Repositories\UserRepositoryContract;
use App\DTOs\UserProfileData;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * UserProfile Service — Orchestration Layer.
 *
 * Coordinates all profile-related operations.
 * Deliberately scoped: password, email, and authentication changes
 * are NOT handled here — those are separate security-critical actions.
 */
final class UserProfileService
{
    public function __construct(
        private readonly UpdateUserProfileAction $updateAction,
        private readonly UserRepositoryContract  $repository,
    ) {}

    public function updateProfile(User $user, UserProfileData $data): User
    {
        return $this->updateAction->handle($user, $data);
    }

    public function findById(string $id): ?User
    {
        return $this->repository->findById($id);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->repository->findByEmail($email);
    }

    public function deleteAccount(User $user): bool
    {
        return $this->repository->delete($user);
    }

    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }
}
