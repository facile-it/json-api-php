<?php

declare(strict_types=1);

namespace Facile\JsonApiPhp\Tests\Serialization;

use Facile\JsonApiPhp\Serialization\JsonApiSerializer;
use Facile\JsonApiPhp\Serialization\JsonApiSerializerInterface;
use PHPUnit\Framework\TestCase;

class JsonApiSerializerTest extends TestCase
{
    /** @var JsonApiSerializerInterface */
    private $jsonApiSerializer;

    public function setUp(): void
    {
        $this->jsonApiSerializer = new JsonApiSerializer();
    }

    /**
     * @dataProvider jsonString
     *
     * @param string $jsonString
     * @param string $expectedJsonString
     */
    public function testSerialize(string $jsonString, string $expectedJsonString): void
    {
        $this->assertJsonStringEqualsJsonString(
            $expectedJsonString,
            $this->jsonApiSerializer->serializeString(
                $jsonString,
                JsonApiSerializerInterface::DEFAULT_FLATTENED_RELATIONSHIPS,
                true
            )
        );
    }

    /**
     * @return array
     */
    public function jsonString(): array
    {
        $rawString = file_get_contents(__DIR__ . '/../raw.json');
        $jsonApiString = file_get_contents(__DIR__ . '/../json-api.json');
        $jsonApi = json_decode($jsonApiString, true);

        return [
            [
                json_encode([
                    '_type' => 'a',
                    '_id' => 1,
                    'A' => [
                        '_type' => 'b',
                        '_id' => 1,
                        'B' => ['_type' => 'c', '_id' => 1],
                    ],
                ]),
                json_encode([
                    'data' => [
                        'type' => 'a',
                        'id' => '1',
                        'relationships' => [
                            'A' => ['data' => ['type' => 'b', 'id' => '1']],
                        ],
                    ],
                    'included' => [
                        [
                            'type' => 'b',
                            'id' => '1',
                            'relationships' => [
                                'B' => ['data' => ['type' => 'c', 'id' => '1']],
                            ],
                        ],
                        ['type' => 'c', 'id' => '1'],
                    ],
                ]),
            ],
            [
                json_encode([
                    '_type' => 'a',
                    '_id' => 1,
                    'A' => [
                        '_type' => 'b',
                        '_id' => 1,
                        'B' => [['_type' => 'c', '_id' => 1]],
                    ],
                ]),
                json_encode([
                    'data' => [
                        'type' => 'a',
                        'id' => '1',
                        'relationships' => [
                            'A' => ['data' => ['type' => 'b', 'id' => '1']],
                        ],
                    ],
                    'included' => [
                        [
                            'type' => 'b',
                            'id' => '1',
                            'relationships' => [
                                'B' => ['data' => [['type' => 'c', 'id' => '1']]],
                            ],
                        ],
                        ['type' => 'c', 'id' => '1'],
                    ],
                ]),
            ],
            [$rawString, $jsonApiString],
            [
                '[' . $rawString . ']',
                json_encode([
                    'data' => [$jsonApi['data']],
                    'included' => $jsonApi['included'],
                ]),
            ],
        ];
    }
}
