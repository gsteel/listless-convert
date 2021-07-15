<?php

declare(strict_types=1);

namespace GSteel\Listless\Convert\Test\Unit\Value;

use DateTimeImmutable;
use DateTimeZone;
use GSteel\Listless\Convert\Exception\AssertionFailed;
use GSteel\Listless\Convert\Value\Form;
use GSteel\Listless\Convert\Value\FormId;
use GSteel\Listless\Json;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
    public function testThatAFormCanBeHydratedFromAJsonPayload(): void
    {
        $examplePayload = <<<JSON
            {
                "id":123,
                "name":"Landing page",
                "created_at":"2021-01-01T12:34:56.000Z",
                "type":"hosted",
                "embed_js":"https://some-domain.ck.page/some-uid/index.js",
                "embed_url":"https://some-domain.ck.page/some-uid",
                "archived":false,
                "uid":"some-uid"
            }
            JSON;

        $form = Form::fromArray(Json::decodeToArray($examplePayload));
        self::assertTrue(FormId::fromInteger(123)->isEqualTo($form->listId()));
        self::assertTrue($form->listId()->isEqualTo($form->id()));

        $expect = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2021-01-01 12:34:56', new DateTimeZone('UTC'));
        self::assertEquals($expect, $form->createdAt());
    }

    /** @return array<string, array{0: mixed[]}> */
    public function invalidPayloadProvider(): array
    {
        return [
            'Missing ID' => [
                [
                    'name' => 'Foo',
                    'created_at' => '2021-01-01T12:34:56.000Z',
                ],
            ],
            'String ID' => [
                [
                    'id' => 'Goats',
                    'name' => 'Foo',
                    'created_at' => '2021-01-01T12:34:56.000Z',
                ],
            ],
            'Missing Name' => [
                [
                    'id' => 123,
                    'created_at' => '2021-01-01T12:34:56.000Z',
                ],
            ],
            'Integer Name' => [
                [
                    'id' => 123,
                    'name' => 42,
                    'created_at' => '2021-01-01T12:34:56.000Z',
                ],
            ],
            'Missing Date' => [
                [
                    'id' => 123,
                    'name' => 'Goats',
                ],
            ],
            'Invalid Date' => [
                [
                    'id' => 123,
                    'name' => 'Goats',
                    'created_at' => 'Nuts',
                ],
            ],
            'Non string Date' => [
                [
                    'id' => 123,
                    'name' => 'Goats',
                    'created_at' => 9,
                ],
            ],
        ];
    }

    /**
     * @param mixed[] $payload
     *
     * @dataProvider invalidPayloadProvider
     */
    public function testPayloadAssertions(array $payload): void
    {
        $this->expectException(AssertionFailed::class);
        Form::fromArray($payload);
    }
}
