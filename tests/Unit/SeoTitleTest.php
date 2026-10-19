<?php

namespace Tests\Unit;

use App\Support\Seo;
use PHPUnit\Framework\TestCase;

class SeoTitleTest extends TestCase
{
    public function test_uses_meta_title_when_filled(): void
    {
        $this->assertSame('DHA License Consultants', Seo::title('DHA License Consultants', 'Fallback Name'));
    }

    public function test_falls_back_to_name_when_meta_title_empty(): void
    {
        $this->assertSame('Fallback Name', Seo::title('', 'Fallback Name'));
        $this->assertSame('Fallback Name', Seo::title(null, 'Fallback Name'));
        $this->assertSame('Fallback Name', Seo::title('   ', 'Fallback Name'));
    }

    public function test_trims_at_word_boundary_to_60_characters(): void
    {
        $long = 'Healthcare Professional Licensing in Saudi Arabia, Qatar, Oman and the United Arab Emirates';

        // Cut after "Qatar," (57 chars, next word would pass 60), trailing comma dropped.
        $this->assertSame('Healthcare Professional Licensing in Saudi Arabia, Qatar', Seo::title($long, 'x'));
    }

    public function test_keeps_a_title_of_exactly_60_characters(): void
    {
        $sixty = str_repeat('a', 25) . ' ' . str_repeat('b', 34);
        $this->assertSame(60, mb_strlen($sixty));
        $this->assertSame($sixty, Seo::title($sixty, 'x'));
    }

    public function test_strips_html_and_collapses_whitespace(): void
    {
        $this->assertSame('Clinic Setup and Licensing', Seo::title("<b>Clinic</b>  Setup\n and Licensing", 'x'));
    }

    public function test_appends_brand_only_when_it_fits(): void
    {
        $this->assertSame(
            'How to Get a Healthcare License in the UAE | Alpha Health Group',
            Seo::title('How to Get a Healthcare License in the UAE', 'x', 'Alpha Health Group', 70)
        );
        // 55 characters + " | Alpha Health Group" would exceed 60, so no brand.
        $this->assertSame(
            'How to Get a Healthcare License in the UAE (2026 Guide)',
            Seo::title('How to Get a Healthcare License in the UAE (2026 Guide)', 'x', 'Alpha Health Group')
        );
        $this->assertSame('Short Title | Alpha Health Group', Seo::title('Short Title', 'x', 'Alpha Health Group'));
    }

    public function test_does_not_double_the_brand(): void
    {
        $this->assertSame(
            'Qatar DHP Medical License Support | Alpha Health Group',
            Seo::title('Qatar DHP Medical License Support | Alpha Health Group', 'x', 'Alpha Health Group')
        );
    }
}
