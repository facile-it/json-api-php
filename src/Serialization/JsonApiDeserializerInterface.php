<?php

declare(strict_types=1);

namespace Facile\JsonApiPhp\Serialization;

use RuntimeException;

interface JsonApiDeserializerInterface extends JsonApiInterface
{
    /**
     * @param array $elements
     * @param bool $flattenedRelationships
     *
     * @return array
     */
    public function deserialize(array $elements, bool $flattenedRelationships = self::DEFAULT_FLATTENED_RELATIONSHIPS): array;

    /**
     * @param string $jsonApiString
     * @param bool $flattenedRelationships
     *
     * @throws RuntimeException
     *
     * @return string
     */
    public function deserializeToString(string $jsonApiString, bool $flattenedRelationships = self::DEFAULT_FLATTENED_RELATIONSHIPS): string;

    /**
     * @param array $elements
     * @param bool $flattenedRelationships
     *
     * @return array
     */
    public function __invoke(array $elements, bool $flattenedRelationships = self::DEFAULT_FLATTENED_RELATIONSHIPS): array;
}
