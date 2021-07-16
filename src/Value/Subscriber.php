<?php

declare(strict_types=1);

namespace GSteel\Listless\ConvertKit\Value;

use DateTimeImmutable;
use DateTimeInterface;
use GSteel\Listless\ConvertKit\Util\Assert;
use GSteel\Listless\Value\EmailAddress;

/**
 * @psalm-immutable
 */
final class Subscriber
{
    private SubscriberId $id;
    private EmailAddress $email;
    private DateTimeImmutable $createdAt;
    private SubscriberState $state;

    private function __construct(
        SubscriberId $id,
        EmailAddress $emailAddress,
        DateTimeImmutable $createdAt,
        SubscriberState $state
    ) {
        $this->id = $id;
        $this->email = $emailAddress;
        $this->createdAt = $createdAt;
        $this->state = $state;
    }

    /**
     * @param array<array-key, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        $keys = ['id', 'email_address', 'created_at', 'state'];
        foreach ($keys as $key) {
            Assert::keyExists($payload, $key);
        }

        $state = $payload['state'];

        Assert::integer($payload['id']);
        Assert::string($payload['email_address']);
        Assert::string($payload['created_at']);

        $date = DateTimeImmutable::createFromFormat(DateTimeInterface::RFC3339_EXTENDED, $payload['created_at']);
        Assert::isInstanceOf($date, DateTimeImmutable::class);

        Assert::true(SubscriberState::isValid($state));
        /** @psalm-var string $state */

        return new self(
            SubscriberId::fromInteger($payload['id']),
            EmailAddress::fromString($payload['email_address']),
            $date,
            new SubscriberState($state)
        );
    }

    public function id(): SubscriberId
    {
        return $this->id;
    }

    public function email(): EmailAddress
    {
        return $this->email;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function state(): SubscriberState
    {
        return $this->state;
    }
}
