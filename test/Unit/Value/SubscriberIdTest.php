<?php

declare(strict_types=1);

namespace GSteel\Listless\Convert\Test\Unit\Value;

use GSteel\Listless\Convert\Value\SubscriberId;
use PHPUnit\Framework\TestCase;

class SubscriberIdTest extends TestCase
{
    public function testThatIntegerValueIsPreserved(): void
    {
        $state = SubscriberId::fromInteger(5);
        self::assertSame(5, $state->toInteger());
    }

    public function testEquality(): void
    {
        self::assertTrue(SubscriberId::fromInteger(1)->isEqualTo(SubscriberId::fromInteger(1)));
        self::assertFalse(SubscriberId::fromInteger(1)->isEqualTo(SubscriberId::fromInteger(2)));
    }
}
