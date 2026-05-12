<?php

declare(strict_types=1);

namespace App\DTOs;

use Illuminate\Support\Str;

/**
 * Data Transfer Object for TierList create / update operations.
 *
 * Readonly — once instantiated the data is immutable.
 * All layers (Controller → Service → Action → Repository) speak this type.
 */
final readonly class TierListData
{
    public function __construct(
        public readonly string  $userId,
        public readonly string  $title,
        public readonly string  $slug,
        public readonly ?string $description,
        public readonly ?string $category,
        public readonly bool    $isPublic,
        public readonly ?array  $metadata = null,
    ) {}

    /**
     * Factory: build from a validated Form Request payload.
     *
     * @param  array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            userId:      $data['user_id'],
            title:       $data['title'],
            slug:        $data['slug'] ?? Str::slug($data['title']),
            description: $data['description'] ?? null,
            category:    $data['category']    ?? null,
            isPublic:    (bool) ($data['is_public'] ?? false),
            metadata:    $data['metadata']    ?? null,
        );
    }

    /**
     * Hydrate from an authenticated request — sets user_id automatically.
     *
     * @param  array<string, mixed> $validated
     */
    public static function fromRequest(array $validated, string $userId): self
    {
        return new self(
            userId:      $userId,
            title:       $validated['title'],
            slug:        $validated['slug'] ?? Str::slug($validated['title']),
            description: $validated['description'] ?? null,
            category:    $validated['category']    ?? null,
            isPublic:    (bool) ($validated['is_public'] ?? false),
            metadata:    $validated['metadata']    ?? null,
        );
    }

    /**
     * Serialise back to a plain array for Eloquent mass-assignment.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'user_id'     => $this->userId,
            'title'       => $this->title,
            'slug'        => $this->slug,
            'description' => $this->description,
            'category'    => $this->category,
            'is_public'   => $this->isPublic,
            'metadata'    => $this->metadata,
        ];
    }
}
