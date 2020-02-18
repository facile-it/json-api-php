<?php

declare(strict_types=1);

namespace Facile\JsonApiPhp\Serialization;

/**
 * Interface
 */
interface JsonApiSerializerInterface extends JsonApiInterface
{
    /**
     * @param array $elements
     * @param bool $flattenedRelationships
     * @param bool $sort
     *
     * @return array
     */
    public function serialize(array $elements, bool $flattenedRelationships = self::DEFAULT_FLATTENED_RELATIONSHIPS, bool $sort = false): array;

    /**
     * @param string $jsonString
     * @param bool $flattenedRelationships
     * @param bool $sort
     *
     * @return string
     */
    public function serializeString(string $jsonString, bool $flattenedRelationships = self::DEFAULT_FLATTENED_RELATIONSHIPS, bool $sort = false): string;

    /**
     * @param array $elements
     * @param bool $flattenedRelationships
     * @param bool $sort
     *
     * @return array
     */
    public function __invoke(array $elements, bool $flattenedRelationships = self::DEFAULT_FLATTENED_RELATIONSHIPS, bool $sort = false): array;
}
