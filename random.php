<?php
declare(strict_types=1);

/**
 * Generate a random string, using a cryptographically secure 
 * pseudorandom number generator (random_int)
 * 
 * Requires PHP 8.3 or newer. The keyspace is treated as bytes (use ASCII).
 * 
 * @param int $length      How many characters do we want?
 * @param string $keyspace A string of all possible characters
 *                         to select from
 * @return string
 */
function random_str(
    int $length,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
    if ($length < 1) {
        throw new InvalidArgumentException('$length must be a positive integer');
    }
    $str = '';
    $max = strlen($keyspace) - 1;
    if ($max < 1) {
        throw new InvalidArgumentException('$keyspace must be at least two characters long');
    }
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}
