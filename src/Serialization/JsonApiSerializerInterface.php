<?php

declare(strict_types=1);

namespace Facile\JsonApiPhp\Serialization;

interface JsonApiSerializerInterface extends JsonApiInterface
{
    /**
     * @param string $jsonString
     *
     * @return array
     */
    public function serialize(string $jsonString): array;

    /**
     * @param string $jsonString
     *
     * @return string
     */
    public function serializeToString(string $jsonString): string;

    /**
     * @param string $jsonString
     *
     * @return array
     */
    public function __invoke(string $jsonString): array;
}
