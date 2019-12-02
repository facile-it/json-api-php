<?php

declare(strict_types=1);

namespace Facile\JsonApiPhp\Serialization;

interface JsonApiDeserializerInterface extends JsonApiInterface
{
    /**
     * @param string $jsonApiString
     *
     * @return string
     */
    public function deserialize(string $jsonApiString): string;

    /**
     * @param string $jsonApiString
     *
     * @return string
     */
    public function __invoke(string $jsonApiString): string;
}
