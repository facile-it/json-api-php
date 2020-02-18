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
            $this->jsonApiSerializer->serializeString($jsonString)
        );
    }

    /**
     * @return array
     */
    public function jsonString(): array
    {
        return [
            [
                json_encode([
                    '_type' => 'a',
                    '_id' => 1,
                    'A' => [
                        '_type' => 'b',
                        '_id' => 1,
                        'B' => ['_type' => 'c', '_id' => 1]
                    ]
                ]),
                json_encode([
                    'data' => [
                        'type' => 'a',
                        'id' => '1',
                        'relationships' => [
                            'A' => ['data' => ['type' => 'b', 'id' => '1']]
                        ]
                    ],
                    'included' => [
                        [
                            'type' => 'b',
                            'id' => '1',
                            'relationships' => [
                                'B' => ['data' => ['type' => 'c', 'id' => '1']]
                            ]
                        ],
                        ['type' => 'c', 'id' => '1']
                    ]
                ])
            ],
            [
                json_encode([
                    '_type' => 'a',
                    '_id' => 1,
                    'A' => [
                        '_type' => 'b',
                        '_id' => 1,
                        'B' => [['_type' => 'c', '_id' => 1]]
                    ]
                ]),
                json_encode([
                    'data' => [
                        'type' => 'a',
                        'id' => '1',
                        'relationships' => [
                            'A' => ['data' => ['type' => 'b', 'id' => '1']]
                        ]
                    ],
                    'included' => [
                        [
                            'type' => 'b',
                            'id' => '1',
                            'relationships' => [
                                'B' => ['data' => [['type' => 'c', 'id' => '1']]]
                            ]
                        ],
                        ['type' => 'c', 'id' => '1']
                    ]
                ])
            ],
            [
                file_get_contents(__DIR__ . '/../raw.json'),
                file_get_contents(__DIR__ . '/../json-api.json'),
            ],
            [
                '[' . file_get_contents(__DIR__ . '/../raw.json') . ']',
                '{"data":[' . json_encode(json_decode(file_get_contents(__DIR__ . '/../json-api.json'), true)['data']) . ']}'
            ]
        ];
    }
}
