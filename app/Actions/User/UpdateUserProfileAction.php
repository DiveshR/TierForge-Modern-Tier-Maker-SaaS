<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Contracts\Repositories\UserRepositoryContract;
use App\DTOs\UserProfileData;
use App\Models\User;

/**
 * Single-responsibility action: update a user's profile fields.
 *
 * Scoped only to display-name, avatar, and bio.
 * Email changes and password resets are intentionally separate actions
 * because they require additional verification steps (email confirmation,
 * current-password check) which belong in their own action classes.
 */
final class UpdateUserProfileAction
{
    public function __construct(
        private readonly UserRepositoryContract $repository,
    ) {}

    public function handle(User $user, UserProfileData $data): User
    {
        return $this->repository->updateProfile($user, $data);
    }
}
