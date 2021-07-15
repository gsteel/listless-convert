<?php

declare(strict_types=1);

namespace GSteel\Listless\Convert\Value;

use GSteel\Listless\ListId;

/**
 * @psalm-immutable
 */
final class FormId implements ListId
{
    private int $id;

    private function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function fromInteger(int $id): self
    {
        return new self($id);
    }

    public function toString(): string
    {
        return (string) $this->id;
    }

    public function isEqualTo(ListId $other): bool
    {
        return $this->toString() === $other->toString();
    }
}
