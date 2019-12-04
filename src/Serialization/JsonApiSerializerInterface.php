<?php

declare(strict_types=1);

namespace Facile\JsonApiPhp\Serialization;

use RuntimeException;

interface JsonApiSerializerInterface extends JsonApiInterface
{
    /**
     * @param array $elements
     *
     * @return array
     */
    public function serialize(array $elements): array;

    /**
     * @param string $jsonString
     *
     * @throws RuntimeException
     *
     * @return string
     */
    public function serializeString(string $jsonString): string;

    /**
     * @param array $elements
     *
     * @return array
     */
    public function __invoke(array $elements): array;
}
