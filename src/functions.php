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
            return $valid && is_int($key);
        },
        true
    );
}
