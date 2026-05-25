<?php

namespace Tests\Unit;

use App\Services\PlaceholderReplacer;
use PHPUnit\Framework\TestCase;

class PlaceholderReplacerTest extends TestCase
{
    public function test_replaces_known_keys(): void
    {
        $r = new PlaceholderReplacer;

        $out = $r->replace('Hi {{user_name}}, code {{ otp_code }}', [
            'user_name' => 'Jane',
            'otp_code' => '123456',
        ]);

        $this->assertSame('Hi Jane, code 123456', $out);
    }

    public function test_leaves_unknown_placeholders(): void
    {
        $r = new PlaceholderReplacer;

        $out = $r->replace('{{known}} and {{unknown}}', ['known' => 'X']);

        $this->assertSame('X and {{unknown}}', $out);
    }
}
