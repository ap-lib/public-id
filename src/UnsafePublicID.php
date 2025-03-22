<?php

namespace AP\PublicID;

/**
 * Encodes numeric IDs into less predictable integers to hide system scale and client-specific patterns.
 * Not intended for security—just to keep public IDs compact and opaque.
 *
 * Note: If clients know this encoding is used, they may reverse-engineer ID ranges or estimate overall volume.
 * Use SafePublicID for stronger obfuscation.
 */
class UnsafePublicID
{
    /**
     * Encodes a numeric ID into a less predictable public-facing integer.
     *
     * The resulting number has a length of: original ID length + [$digits_divider, 2 * $digits_divider]
     *
     * @param int $original             Original numeric ID to encode.
     * @param int $digits_divider  Number of digits used to split and disguise the original ID.
     * @param string $secret       Optional secret salt to vary the encoding, useful for different environments.
     *
     * @return int Encoded public-facing integer.
     */
    public static function encode(int $original, int $digits_divider, string $secret = ""): int
    {
        $b = pow(10, $digits_divider - 1);
        $d = crc32($original . $secret) % (9 * $b) + $b;
        return (int)(sprintf("%d%0{$digits_divider}d%d", $d, $original % $d, floor($original / $d)));
    }

    /**
     * Decodes a previously encoded public-facing integer back to the original ID.
     *
     * @param int $public          The encoded public integer.
     * @param int $digits_divider  Must match the one used during encoding.
     *
     * @return int Original decoded ID.
     */
    public static function decode(int $public, int $digits_divider): int
    {
        $public = (string)$public;
        return (int)substr($public, $digits_divider * 2)
            * (int)substr($public, 0, $digits_divider)
            + (int)substr($public, $digits_divider, $digits_divider);
    }
}