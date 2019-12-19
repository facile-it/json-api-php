<?php

declare(strict_types=1);

namespace Facile\JsonApiPhp\Serialization;

trait JsonApiSerializerDeserializerTrait
{
    /**
     * @param mixed $element
     *
     * @return bool
     */
    abstract protected static function isReference($element): bool;

    /**
     * @param mixed $element
     * @param bool $all
     *
     * @return bool
     */
    private static function isArrayOfReference($element, bool $all = true): bool
    {
        if (false === is_array($element)) {
            return false;
        }

        if (true === empty($element)) {
            return false;
        }

        return array_reduce(
            $element,
            static function ($valid, $item) use ($all): bool {
                $isReference = static::isReference($item);
                if (true === $all) {
                    return true === $valid && true === $isReference;
                }

                return true === $valid || true === $isReference;
            },
            $all
        );
    }
}
