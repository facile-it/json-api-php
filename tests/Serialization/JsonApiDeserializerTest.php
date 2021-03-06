<?php

declare(strict_types=1);

namespace Facile\JsonApiPhp\Tests\Serialization;

use Facile\JsonApiPhp\Serialization\JsonApiDeserializer;
use Facile\JsonApiPhp\Serialization\JsonApiDeserializerInterface;
use PHPUnit\Framework\TestCase;

class JsonApiDeserializerTest extends TestCase
{
    /** @var JsonApiDeserializerInterface */
    private $jsonApiDeserializer;

    public function setUp(): void
    {
        $this->jsonApiDeserializer = new JsonApiDeserializer();
    }

    /**
     * @dataProvider jsonApiString
     *
     * @param string $jsonApiString
     * @param string $expectedJsonApiString
     */
    public function testDeserialize(string $expectedJsonApiString, string $jsonApiString): void
    {
        $this->assertJsonStringEqualsJsonString(
            $expectedJsonApiString,
            $this->jsonApiDeserializer->deserializeToString($jsonApiString)
        );
    }

    /**
     * @return array
     */
    public function jsonApiString(): array
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
