<?php

declare(strict_types=1);

namespace GSteel\Listless\ConvertKit\Value;

use DateTimeImmutable;
use DateTimeInterface;
use GSteel\Listless\ConvertKit\Util\Assert;
use GSteel\Listless\ListId;
use GSteel\Listless\MailingList;

final class Form implements MailingList
{
    private FormId $id;
    private string $name;
    private DateTimeImmutable $createdAt;

    private function __construct(
        FormId $id,
        string $name,
        DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->createdAt = $createdAt;
    }

    /**
     * @param array<array-key, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        $keys = ['id', 'name', 'created_at'];
        foreach ($keys as $key) {
            Assert::keyExists($payload, $key);
        }

        Assert::integer($payload['id']);
        Assert::string($payload['name']);
        Assert::string($payload['created_at']);

        $date = DateTimeImmutable::createFromFormat(DateTimeInterface::RFC3339_EXTENDED, $payload['created_at']);
        Assert::isInstanceOf($date, DateTimeImmutable::class);

        return new self(
            FormId::fromInteger($payload['id']),
            $payload['name'],
            $date
        );
    }

    public function id(): FormId
    {
        return $this->id;
    }

    public function listId(): ListId
    {
        return $this->id;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
