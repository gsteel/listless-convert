<?php

declare(strict_types=1);

namespace ListInterop\ConvertKit\Test\Unit\Value;

use DateTimeImmutable;
use DateTimeZone;
use Generator;
use ListInterop\ConvertKit\Exception\AssertionFailed;
use ListInterop\ConvertKit\Value\Form;
use ListInterop\ConvertKit\Value\FormId;
use ListInterop\Json;
use PHPUnit\Framework\TestCase;

use function array_keys;

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

        $expect = DateTimeImmutable::createFromFormat(
            'Y-m-d H:i:s',
            '2021-01-01 12:34:56',
            new DateTimeZone('UTC')
        );
        self::assertEquals($expect, $form->createdAt());
    }

    /** @return array<string, mixed> */
    public function validPayload(): array
    {
        return [
            'id' => 1,
            'name' => 'Foo',
            'created_at' => '2021-01-01T12:34:56.000Z',
        ];
    }

    /** @return array<string, array{0: string, 1:mixed}> */
    public function invalidPayloadProvider(): array
    {
        return [
            'Missing ID' => ['id', null],
            'String ID' => ['id', 'Foo'],
            'Missing Name' => ['name', null],
            'Integer Name' => ['name', 1],
            'Missing Date' => ['created_at', null],
            'Invalid Date' => ['created_at', 'Nuts'],
            'Non string Date' => ['created_at', 9],
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
        Form::fromArray($payload);
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
        Form::fromArray($payload);
    }
}
