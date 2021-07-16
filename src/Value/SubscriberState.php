<?php

declare(strict_types=1);

namespace GSteel\Listless\ConvertKit\Value;

use MyCLabs\Enum\Enum;

/**
 * @psalm-immutable
 * @extends Enum<string>
 */
final class SubscriberState extends Enum
{
    private const ACTIVE = 'active';

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }
}
