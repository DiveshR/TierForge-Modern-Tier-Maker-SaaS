<?php

declare(strict_types=1);

namespace App\DTOs;

use App\ValueObjects\ColorHex;

/**
 * Data Transfer Object for TierRow create / update operations.
 *
 * The `color` field is stored as a raw string in the DB but validated as a
 * ColorHex ValueObject inside this DTO, ensuring invalid colours never reach
 * the persistence layer.
 */
final readonly class TierRowData
{
    public function __construct(
        public readonly string   $tierListId,
        public readonly string   $label,
        public readonly ColorHex $color,
        public readonly int      $orderIndex,
    ) {}

    /**
     * @param  array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            tierListId:  $data['tier_list_id'],
            label:       $data['label'],
            color:       new ColorHex($data['color'] ?? '#808080'),
            orderIndex:  (int) ($data['order_index'] ?? 0),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'tier_list_id' => $this->tierListId,
            'label'        => $this->label,
            'color'        => $this->color->value(),
            'order_index'  => $this->orderIndex,
        ];
    }
}
