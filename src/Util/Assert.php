<?php

declare(strict_types=1);

namespace GSteel\Listless\Convert\Util;

use GSteel\Listless\Convert\Exception\AssertionFailed;

final class Assert extends \Webmozart\Assert\Assert
{
    /**
     * @param string $message
     * @psalm-param string $message
     *
     * @psalm-return no-return
     *
     * @throws AssertionFailed
     *
     * @psalm-pure
     */
    protected static function reportInvalidArgument($message): void // phpcs:ignore
    {
        throw new AssertionFailed($message);
    }
}
