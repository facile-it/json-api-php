<?php

declare(strict_types=1);

namespace Facile\JsonApiPhp\Tests\Unit\Serialization;

use Facile\JsonApiPhp\Serialization\JsonApiSerializer;
use Facile\JsonApiPhp\Serialization\JsonApiSerializerInterface;
use PHPUnit\Framework\TestCase;

class JsonApiSerializerTest extends TestCase
{
    /** @var JsonApiSerializerInterface */
    private $jsonApiSerializer;

    public function setUp()
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
        $this->assertEquals(
            $expectedJsonString,
            $this->jsonApiSerializer->serialize($jsonString)
        );
    }

    /**
     * @return array
     */
    public function jsonString(): array
    {
        return [
            [
                '[
   {
      "a":1,
      "b":{
         "_type":"a",
         "_id":1,
         "a":1,
         "b":{
            "_type":"b",
            "_id":1,
            "a":1
         }
      },
      "c":{
         "_id":2,
         "bb":{
            "_type":"b",
            "_id":1,
            "a":1
         }
      },
      "d":{
         "_type":"a",
         "_id":2,
         "a":1
      },
      "e":[
         {
            "_type":"type",
            "_id":1,
            "ccc":{
               "_type":"type",
               "_id":2,
               "ddd":2
            }
         },
         {
            "_type":"type",
            "_id":3
         }
      ],
      "f":[

      ]
   }
]',
                '{
    "data": [
        {
            "attributes": {
                "a": 1,
                "c": {
                    "_id": 2
                },
                "f": []
            },
            "relationships": {
                "b": {
                    "data": {
                        "type": "a",
                        "id": "1"
                    }
                },
                "c": {
                    "data": {
                        "bb": {
                            "type": "b",
                            "id": "1"
                        }
                    }
                },
                "d": {
                    "data": {
                        "type": "a",
                        "id": "2"
                    }
                },
                "e": {
                    "data": [
                        {
                            "type": "type",
                            "id": "1"
                        },
                        {
                            "type": "type",
                            "id": "3"
                        }
                    ]
                }
            }
        }
    ],
    "included": [
        {
            "type": "b",
            "id": "1",
            "attributes": {
                "a": 1
            }
        },
        {
            "type": "a",
            "id": "1",
            "attributes": {
                "a": 1
            },
            "relationships": {
                "b": {
                    "data": {
                        "type": "b",
                        "id": "1"
                    }
                }
            }
        },
        {
            "type": "a",
            "id": "2",
            "attributes": {
                "a": 1
            }
        },
        {
            "type": "type",
            "id": "2",
            "attributes": {
                "ddd": 2
            }
        },
        {
            "type": "type",
            "id": "1",
            "relationships": {
                "ccc": {
                    "data": {
                        "type": "type",
                        "id": "2"
                    }
                }
            }
        },
        {
            "type": "type",
            "id": "3"
        }
    ]
}',
            ],
        ];
    }
}
