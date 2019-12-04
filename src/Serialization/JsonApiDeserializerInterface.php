<?php

declare(strict_types=1);

namespace Facile\JsonApiPhp\Serialization;

use RuntimeException;

interface JsonApiDeserializerInterface extends JsonApiInterface
{
    /**
     * @param array $elements
     *
     * @return array
     */
    public function deserialize(array $elements): array;

    /**
     * @param string $jsonApiString
     *
     * @throws RuntimeException
     *
     * @return string
     */
    public function deserializeToString(string $jsonApiString): string;

    /**
     * @param array $elements
     *
     * @return array
     */
    public function __invoke(array $elements): array;
}
