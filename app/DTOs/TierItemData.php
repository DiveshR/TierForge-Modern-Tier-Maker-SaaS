<?php

declare(strict_types=1);

namespace App\DTOs;

/**
 * Data Transfer Object for TierItem create / update operations.
 *
 * Items are independent catalogue entries — they carry a name, optional
 * description, and a JSONB metadata blob for extensible attributes (e.g.
 * release year, genre, image URL before media upload).
 */
final readonly class TierItemData
{
    public function __construct(
        public readonly string  $name,
        public readonly ?string $description,
        public readonly ?array  $metadata = null,
    ) {}

    /**
     * @param  array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name:        $data['name'],
            description: $data['description'] ?? null,
            metadata:    $data['metadata']    ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name'        => $this->name,
            'description' => $this->description,
            'metadata'    => $this->metadata,
        ];
    }
}
