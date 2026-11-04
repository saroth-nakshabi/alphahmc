<?php

namespace Tests\Unit;

use App\Support\Seo;
use PHPUnit\Framework\TestCase;

class SeoDescriptionTest extends TestCase
{
    public function test_stored_description_is_used_as_written(): void
    {
        $text = str_repeat('word ', 40) . 'end.';
        $this->assertSame(trim($text), Seo::description($text, 'fallback'));
    }

    public function test_fallback_is_cut_at_a_word_boundary_without_ellipsis(): void
    {
        $body = '<p>' . str_repeat('healthcare ', 30) . '</p>';
        $out = Seo::description(null, $body);
        $this->assertLessThanOrEqual(155, mb_strlen($out));
        $this->assertStringEndsWith('healthcare', $out);
        $this->assertStringNotContainsString('...', $out);
    }

    public function test_short_fallback_is_kept_whole_and_tags_are_removed(): void
    {
        $this->assertSame('Short & clear.', Seo::description('  ', '<p>Short &amp; clear.</p>'));
        $this->assertSame('', Seo::description(null, null));
    }
}
