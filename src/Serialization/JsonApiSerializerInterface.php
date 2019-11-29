<?php

declare(strict_types=1);

namespace Facile\JsonApiPhp\Serialization;

interface JsonApiSerializerInterface extends JsonApiInterface
{
    /**
     * @param string $jsonString
     *
     * @return string|bool
     */
    public function serialize(string $jsonString);

    /**
     * @param string $jsonString
     *
     * @return string|bool
     */
    public function __invoke(string $jsonString);
}
