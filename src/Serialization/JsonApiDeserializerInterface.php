<?php

declare(strict_types=1);

namespace Facile\JsonApiPhp\Serialization;

interface JsonApiDeserializerInterface extends JsonApiInterface
{
    /**
     * @param string $jsonApiString
     *
     * @return array
     */
    public function deserialize(string $jsonApiString): array;

    /**
     * @param string $jsonApiString
     *
     * @return string
     */
    public function deserializeToString(string $jsonApiString): string;

    /**
     * @param string $jsonApiString
     *
     * @return array
     */
    public function __invoke(string $jsonApiString): array;
}
