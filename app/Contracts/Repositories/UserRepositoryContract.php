<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\DTOs\UserProfileData;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Contract for User persistence operations.
 *
 * Deliberately narrow — authentication and password changes are handled by
 * Laravel's built-in mechanisms; this contract covers only profile operations
 * that the application layer controls.
 */
interface UserRepositoryContract
{
    /**
     * Find a user by primary key. Returns null when not found.
     */
    public function findById(string $id): ?User;

    /**
     * Find a user by email address. Returns null when not found.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Apply profile data and return the refreshed user model.
     */
    public function updateProfile(User $user, UserProfileData $data): User;

    /**
     * Soft-delete the user account. Returns true on success.
     */
    public function delete(User $user): bool;

    /**
     * Paginate all active (non-deleted) users — used in the admin panel.
     */
    public function paginate(int $perPage = 20): LengthAwarePaginator;
}
