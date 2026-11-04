<?php

namespace Tests\Unit;

use App\Support\Html;
use PHPUnit\Framework\TestCase;

class HtmlBodyHeadingsTest extends TestCase
{
    public function test_h3_body_moves_up_one_level_keeping_its_look(): void
    {
        $this->assertSame(
            '<h2 class="h3">A</h2><p>x</p><h3 class="h4" id="b">B</h3>',
            Html::bodyHeadingsFrom2('<h3>A</h3><p>x</p><h4 id="b">B</h4>')
        );
    }

    public function test_h4_body_moves_up_two_levels_and_keeps_classes(): void
    {
        $this->assertSame('<h2 class="h4 big">A</h2>', Html::bodyHeadingsFrom2('<h4 class="big">A</h4>'));
    }

    public function test_h2_body_is_unchanged(): void
    {
        $html = '<h2>A</h2><h3>B</h3><h2>C</h2>';
        $this->assertSame($html, Html::bodyHeadingsFrom2($html));
    }

    public function test_h1_in_body_renders_as_h2(): void
    {
        $this->assertSame('<h2 class="h1">A</h2><h3 class="h2">B</h3>', Html::bodyHeadingsFrom2('<h1>A</h1><h2>B</h2>'));
    }

    public function test_inner_gap_is_closed(): void
    {
        $this->assertSame('<h2>A</h2><h3 class="h5">B</h3><h2>C</h2>', Html::bodyHeadingsFrom2('<h2>A</h2><h5>B</h5><h2>C</h2>'));
    }

    public function test_body_without_headings_is_unchanged(): void
    {
        $this->assertSame('<p>Hello</p>', Html::bodyHeadingsFrom2('<p>Hello</p>'));
        $this->assertSame('', Html::bodyHeadingsFrom2(null));
    }
}
