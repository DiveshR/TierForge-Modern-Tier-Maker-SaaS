<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryContract;
use App\DTOs\UserProfileData;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Eloquent implementation of the User repository.
 *
 * Deliberately thin — password hashing, email verification, and token
 * management are handled by Laravel's authentication scaffolding.
 * This repository only owns profile-level data operations.
 */
final class UserRepository implements UserRepositoryContract
{
    public function findById(string $id): ?User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function updateProfile(User $user, UserProfileData $data): User
    {
        $user->update($data->toArray());

        return $user->refresh();
    }

    public function delete(User $user): bool
    {
        return (bool) $user->delete();
    }

    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return User::query()
            ->latest()
            ->paginate($perPage);
    }
}
