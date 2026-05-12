<?php

declare(strict_types=1);

namespace App\DTOs;

/**
 * Data Transfer Object for User profile update operations.
 *
 * Deliberately excludes password/email changes — those are security-sensitive
 * operations handled by dedicated Actions with their own DTOs.
 */
final readonly class UserProfileData
{
    public function __construct(
        public readonly string  $name,
        public readonly ?string $avatarPath = null,
        public readonly ?string $bio        = null,
    ) {}

    /**
     * @param  array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name:       $data['name'],
            avatarPath: $data['avatar_path'] ?? null,
            bio:        $data['bio']         ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'name'        => $this->name,
            'avatar_path' => $this->avatarPath,
            'bio'         => $this->bio,
        ], fn (mixed $v): bool => $v !== null);
    }
}
