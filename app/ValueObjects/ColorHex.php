<?php

declare(strict_types=1);

namespace App\ValueObjects;

use InvalidArgumentException;

/**
 * Represents a validated, normalised CSS hex colour.
 *
 * Immutable by design — once constructed the value can never change.
 * Use this whenever a hex colour crosses a layer boundary.
 *
 * @example new ColorHex('#ff2d20') → '#FF2D20'
 */
final class ColorHex
{
    private readonly string $value;

    public function __construct(string $value)
    {
        $normalised = strtoupper(trim($value));

        if (! preg_match('/^#[0-9A-F]{6}$/', $normalised)) {
            throw new InvalidArgumentException(
                "Invalid hex colour \"{$value}\". Expected 6-digit hex in the form #RRGGBB."
            );
        }

        $this->value = $normalised;
    }

    /** Returns the normalised uppercase hex string (e.g. '#FF2D20'). */
    public function value(): string
    {
        return $this->value;
    }

    /** Structural equality — two ColorHex are equal when their values are identical. */
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    /** Allows casting to string transparently wherever a string colour is required. */
    public function __toString(): string
    {
        return $this->value;
    }
}
