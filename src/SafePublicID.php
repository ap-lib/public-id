<?php

namespace AP\PublicID;

/**
 * Adds a masking layer on top of UnsafePublicID to further obscure patterns.
 * Useful for hiding client ranges or overall system volume while keeping IDs short and reversible.
 *
 * The bitmask provides a good level of protection against reverse-engineering,
 * even if the encoding method is known.
 */
readonly class SafePublicID
{
    private string $maskBin;
    private string $secret;

    /**
     * @param int $mask Bitmask used to obscure the encoded integer
     */
    public function __construct(int $mask)
    {
        $this->maskBin = decbin($mask);
        $this->secret  = substr((string)$mask, 0, 2);
    }

    /**
     * Applies a bitmask transformation to obfuscate or reverse-obfuscate an integer.
     *
     * @param int $number Number to transform.
     *
     * @return int Masked or unmasked number.
     */
    private function applyMask(int $number): int
    {
        $maskNeedLen = strlen(decbin($number)) - 1;
        return $number ^ bindec(substr(
                str_repeat(
                    $this->maskBin,
                    $maskNeedLen % strlen($this->maskBin)
                ),
                0,
                $maskNeedLen
            ));
    }

    /**
     * Encodes a numeric ID into a public-facing integer with masking.
     *
     * @param int $original Original ID.
     * @param int $digits_divider Same as in UnsafePublicID.
     *
     * @return int Masked encoded ID.
     */
    public function encode(int $original, int $digits_divider): int
    {
        return $this->applyMask(
            UnsafePublicID::encode(
                $original,
                $digits_divider,
                $this->secret
            )
        );
    }

    /**
     * Decodes a masked public-facing integer back to the original ID.
     *
     * @param int $public Masked encoded ID.
     * @param int $digits_divider Must match the one used during encoding.
     *
     * @return int Original ID.
     */
    public function decode(int $public, int $digits_divider): int
    {
        return UnsafePublicID::decode(
            $this->applyMask($public),
            $digits_divider
        );
    }
}