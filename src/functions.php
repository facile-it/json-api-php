<?php

declare(strict_types=1);

/**
 * @param array $keys
 * @param array $haystack
 *
 * @return bool
 */
function array_keys_exists(array $keys, array $haystack): bool
{
    return count(array_diff_key(array_flip($keys), $haystack)) === 0;
}

/**
 * @param array $haystack
 *
 * @return bool
 */
function is_a_real_array(array $haystack): bool
{
    return array_reduce(
        array_keys($haystack),
        static function (bool $valid, $key): bool {
            return true === $valid && true === is_int($key);
        },
        true
    );
}


/**
 * @param array $haystack
 * @param bool $all
 *
 * @return bool
 */
function is_array_of_array(array $haystack, bool $all = false): bool
{
    return array_reduce(
        array_values($haystack),
        static function (bool $valid, $key) use ($all): bool {
            $isArray = true === is_array($key);
            if (true === $all) {
                return true === $valid && true === $isArray;
            }

            return true === $valid || true === $isArray;
        },
        $all
    );
}
