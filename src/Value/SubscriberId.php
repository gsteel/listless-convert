<?php

declare(strict_types=1);

namespace GSteel\Listless\Convert\Value;

/**
 * @psalm-immutable
 */
final class SubscriberId
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

    public function toInteger(): int
    {
        return $this->id;
    }

    public function isEqualTo(SubscriberId $other): bool
    {
        return $this->id === $other->id;
    }
}
