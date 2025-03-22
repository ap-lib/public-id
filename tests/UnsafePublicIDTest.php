<?php declare(strict_types=1);

namespace AP\PublicID\Tests;

use AP\PublicID\UnsafePublicID;
use PHPUnit\Framework\TestCase;

final class UnsafePublicIDTest extends TestCase
{
    public function testUse()
    {
        $digits_divider = 2;

        $original_id = 1000;

        $public_id = UnsafePublicID::encode(
            original: $original_id,
            digits_divider: $digits_divider
        );

        $decoded_original_id = UnsafePublicID::decode(
            public: $public_id,
            digits_divider: $digits_divider
        );

        $this->assertEquals($decoded_original_id, $original_id);
    }

    public function testBrutforse(): void
    {
        for ($digits = 1; $digits <= 2; $digits++) {
            for ($i = 0; $i < 100000; $i++) {
                $pub = UnsafePublicID::encode($i, $digits);
                $dec = UnsafePublicID::decode($pub, $digits);
                $this->assertEquals($i, $dec);
            }
        }
    }
}
