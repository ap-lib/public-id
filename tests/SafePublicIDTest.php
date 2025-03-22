<?php declare(strict_types=1);

namespace AP\PublicID\Tests;

use AP\PublicID\SafePublicID;
use PHPUnit\Framework\TestCase;

final class SafePublicIDTest extends TestCase
{
    public function testUse()
    {
        $safePublicId = new SafePublicID(902734092);

        $digits_divider = 2;

        $original_id = 1000;

        $public_id = $safePublicId->encode(
            original: $original_id,
            digits_divider: $digits_divider
        );

        $decoded_original_id = $safePublicId->decode(
            public: $public_id,
            digits_divider: $digits_divider
        );

        $this->assertEquals($decoded_original_id, $original_id);

        $public_id_2 = $safePublicId->encode(
            original: $original_id,
            digits_divider: $digits_divider
        );

        $this->assertEquals($public_id, $public_id_2);
    }

    public function testBrutforse(): void
    {
        $safePublicId = new SafePublicID(902734092);

        for ($digits = 1; $digits <= 2; $digits++) {
            for ($i = 0; $i < 100000; $i++) {
                $pub = $safePublicId->encode($i, $digits);
                $dec = $safePublicId->decode($pub, $digits);
                $this->assertEquals($i, $dec);
            }
        }
    }
}
