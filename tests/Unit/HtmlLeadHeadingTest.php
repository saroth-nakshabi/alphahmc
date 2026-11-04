<?php

namespace Tests\Unit;

use App\Support\Html;
use PHPUnit\Framework\TestCase;

class HtmlLeadHeadingTest extends TestCase
{
    public function test_opening_h3_becomes_h2_with_class(): void
    {
        $this->assertSame(
            "<h2 class=\"x\">Plan your clinic</h2>\n<p>Lead <h3>keep</h3></p>",
            Html::leadHeadingToH2("<h3>Plan your clinic</h3>\n<p>Lead <h3>keep</h3></p>", 'x')
        );
    }

    public function test_h3_inside_wrapper_divs_is_promoted(): void
    {
        $this->assertSame(
            "<div>\n<h2 class=\"x\" id=\"a\">Title</h2>\n<div>Text</div></div>",
            Html::leadHeadingToH2("<div>\n<h3 id=\"a\">Title</h3>\n<div>Text</div></div>", 'x')
        );
    }

    public function test_existing_class_is_kept(): void
    {
        $this->assertSame('<h2 class="x big">T</h2>', Html::leadHeadingToH2('<h3 class="big">T</h3>', 'x'));
    }

    public function test_blocks_not_opening_with_h3_are_unchanged(): void
    {
        foreach (['<p>Text</p><h3>Later</h3>', '<h2>Already</h2>', '<div><span>T</span></div><h3>L</h3>', '', '<h4>T</h4>'] as $html) {
            $this->assertSame($html, Html::leadHeadingToH2($html, 'x'));
        }
        $this->assertSame('', Html::leadHeadingToH2(null, 'x'));
    }
}
