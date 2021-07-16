<?php

declare(strict_types=1);

namespace GSteel\Listless\ConvertKit\Test\Unit\Value;

use DateTimeImmutable;
use DateTimeZone;
use Generator;
use GSteel\Listless\ConvertKit\Exception\AssertionFailed;
use GSteel\Listless\ConvertKit\Value\Subscriber;
use GSteel\Listless\ConvertKit\Value\SubscriberId;
use GSteel\Listless\ConvertKit\Value\SubscriberState;
use GSteel\Listless\Value\EmailAddress;
use PHPUnit\Framework\TestCase;

use function array_keys;

class SubscriberTest extends TestCase
{
    /** @return array<string, mixed> */
    public function validPayload(): array
    {
        return [
            'id' => 1,
            'email_address' => 'me@example.com',
            'created_at' => '2021-01-01T12:34:56.000Z',
            'state' => 'active',
        ];
    }

    public function testAccessorsReturnTheExpectedValues(): void
    {
        $subscriber = Subscriber::fromArray($this->validPayload());
        self::assertTrue(SubscriberId::fromInteger(1)->isEqualTo($subscriber->id()));
        self::assertTrue(EmailAddress::fromString('me@example.com')->isEqualTo($subscriber->email()));
        self::assertTrue(SubscriberState::active()->equals($subscriber->state()));
        $expect = DateTimeImmutable::createFromFormat(
            'Y-m-d H:i:s',
            '2021-01-01 12:34:56',
            new DateTimeZone('UTC')
        );
        self::assertEquals($expect, $subscriber->createdAt());
    }

    /** @return array<string, array{0: string, 1:mixed}> */
    public function invalidPayloadProvider(): array
    {
        return [
            'Missing ID' => ['id', null],
            'String ID' => ['id', 'Foo'],
            'Missing Email' => ['email_address', null],
            'Integer Email' => ['email_address', 1],
            'Missing Date' => ['created_at', null],
            'Invalid Date' => ['created_at', 'Nuts'],
            'Non string Date' => ['created_at', 9],
            'Missing State' => ['state', null],
            'Integer State' => ['state', 1],
            'Invalid State' => ['state', 'foo'],
        ];
    }

    /**
     * @param mixed $value
     *
     * @dataProvider invalidPayloadProvider
     */
    public function testPayloadAssertions(string $key, $value): void
    {
        $payload = $this->validPayload();
        /** @psalm-suppress MixedAssignment */
        $payload[$key] = $value;
        $this->expectException(AssertionFailed::class);
        Subscriber::fromArray($payload);
    }

    /** @return Generator<string, array{0:string}> */
    public function keyProvider(): Generator
    {
        foreach (array_keys($this->validPayload()) as $key) {
            yield $key => [$key];
        }
    }

    /** @dataProvider keyProvider */
    public function testUnsetRequirementsCauseAssertionFailures(string $key): void
    {
        $payload = $this->validPayload();
        unset($payload[$key]);
        $this->expectException(AssertionFailed::class);
        Subscriber::fromArray($payload);
    }
}
