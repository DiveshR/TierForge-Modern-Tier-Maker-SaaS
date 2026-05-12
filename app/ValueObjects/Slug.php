<?php

declare(strict_types=1);

namespace App\ValueObjects;

use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Represents a validated, URL-safe slug.
 *
 * Automatically normalises input to lowercase with hyphens.
 * Throws when the result would be empty (e.g. input was all special chars).
 *
 * @example new Slug('My Tier List!') → 'my-tier-list'
 * @example new Slug('  ') → throws InvalidArgumentException
 */
final class Slug
{
    private readonly string $value;

    public function __construct(string $raw)
    {
        $normalised = Str::slug($raw);

        if ($normalised === '') {
            throw new InvalidArgumentException(
                "Cannot create a slug from \"{$raw}\" — result is empty after normalisation."
            );
        }

        $this->value = $normalised;
    }

    /** Returns the normalised slug (e.g. 'my-tier-list'). */
    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
