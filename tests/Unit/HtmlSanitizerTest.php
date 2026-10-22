<?php

namespace Tests\Unit;

use App\Support\HtmlSanitizer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class HtmlSanitizerTest extends TestCase
{
    public function test_dangerous_markup_is_removed_and_allowed_tags_survive(): void
    {
        $out = HtmlSanitizer::clean('<p>Hi <b onclick="x()">there</b> <a href="https://dha.gov.ae" target="_blank">DHA</a></p>'
            . '<script>alert(1)</script><iframe src="https://x.example"></iframe><table><tr><td colspan="2">c</td></tr></table>');

        $this->assertStringNotContainsString('<script', $out);
        $this->assertStringNotContainsString('onclick', $out);
        $this->assertStringNotContainsString('<iframe', $out);
        $this->assertStringContainsString('<b>there</b>', $out);
        $this->assertStringContainsString('<a href="https://dha.gov.ae" target="_blank" rel="noopener">DHA</a>', $out);
        $this->assertStringContainsString('<td colspan="2">c</td>', $out);
    }

    public function test_arabic_text_survives(): void
    {
        $arabic = 'هذا نص عربي للاختبار، مع é و ü';

        $this->assertSame("<p>{$arabic}</p>", HtmlSanitizer::clean("<p>{$arabic}</p>"));
    }

    public function test_leading_h1_equal_to_the_title_is_removed_and_other_h1_become_h2(): void
    {
        $out = HtmlSanitizer::clean('<h1>DHA Licensing Guide</h1><p>a</p><h1>Next step</h1>', 'DHA licensing guide');

        $this->assertSame('<p>a</p><h2>Next step</h2>', $out);
    }

    public function test_confirm_marker_is_kept(): void
    {
        $this->assertStringContainsString('[confirm]', HtmlSanitizer::clean('<p>Fee is AED 500 [confirm]</p>'));
    }

    public static function schemeTricks(): array
    {
        return [
            ['java&#9;script:alert(1)'], ['&#32;javascript:alert(1)'], ['JaVaScRiPt:alert(1)'], ['&#x6A;avascript:alert(1)'],
            ['java&#10;script:'], ['data:text/html,x'], ['vbscript:x'], ['//evil.example'], ['&#106avascript:alert(1)'],
        ];
    }

    #[DataProvider('schemeTricks')]
    public function test_scheme_tricks_lose_their_href(string $href): void
    {
        $out = HtmlSanitizer::clean('<p><a href="' . $href . '">x</a></p>');

        $this->assertSame('<p><a>x</a></p>', $out);
    }

    public function test_images_need_https_or_a_site_path(): void
    {
        $out = HtmlSanitizer::clean('<img src="javascript:x"><img src="http://insecure.example/a.png"><img src="https://ok.example/a.png" onerror="x" alt="a"><img src="/uploads/b.jpg">');

        $this->assertSame('<img src="https://ok.example/a.png" alt="a"><img src="/uploads/b.jpg">', $out);
    }

    public function test_comments_cdata_and_unknown_elements(): void
    {
        $out = HtmlSanitizer::clean('<!-- x --><![CDATA[x]]><custom><em>kept</em></custom>');

        $this->assertSame('<em>kept</em>', $out);
    }

    public function test_styles_are_limited_to_alignment_and_span_colours(): void
    {
        $out = HtmlSanitizer::clean('<p style="text-align:center;color:red" class="c" id="i" data-x="1">a</p>'
            . '<span style="color:#ff0000;font-size:40px">b</span><span style="background:url(x)">c</span>');

        $this->assertSame('<p style="text-align: center">a</p><span style="color: #ff0000">b</span><span>c</span>', $out);
    }
}
