<?php

declare(strict_types=1);

namespace Facile\JsonApiPhp\Serialization;

interface JsonApiSerializerInterface extends JsonApiInterface
{
    /**
     * @param string $jsonString
     *
     * @return string
     */
    public function serialize(string $jsonString): string;

    /**
     * @param string $jsonString
     *
     * @return string
     */
    public function __invoke(string $jsonString): string;
}
