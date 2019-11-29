<?php

declare(strict_types=1);

namespace Facile\JsonApiPhp\Serialization;

interface JsonApiDeserializerInterface extends JsonApiInterface
{
    /**
     * @param string $jsonApiString
     *
     * @return string|bool
     */
    public function deserialize(string $jsonApiString);

    /**
     * @param string $jsonApiString
     *
     * @return string|bool
     */
    public function __invoke(string $jsonApiString);
}
