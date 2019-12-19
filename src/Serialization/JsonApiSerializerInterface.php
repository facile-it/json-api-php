<?php

declare(strict_types=1);

namespace Facile\JsonApiPhp\Serialization;

use RuntimeException;

interface JsonApiSerializerInterface extends JsonApiInterface
{
    /**
     * @param array $elements
     * @param bool $flattenedRelationships
     *
     * @return array
     */
    public function serialize(array $elements, bool $flattenedRelationships = self::DEFAULT_FLATTENED_RELATIONSHIPS): array;

    /**
     * @param string $jsonString
     * @param bool $flattenedRelationships
     *
     * @throws RuntimeException
     *
     * @return string
     */
    public function serializeString(string $jsonString, bool $flattenedRelationships = self::DEFAULT_FLATTENED_RELATIONSHIPS): string;

    /**
     * @param array $elements
     * @param bool $flattenedRelationships
     *
     * @return array
     */
    public function __invoke(array $elements, bool $flattenedRelationships = self::DEFAULT_FLATTENED_RELATIONSHIPS): array;
}
