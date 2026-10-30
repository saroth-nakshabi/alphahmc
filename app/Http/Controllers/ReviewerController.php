<?php

namespace App\Http\Controllers;

use App\Models\Reviewer;

/** Reviewer bio pages at /team/{key} (brief "alphahmc-reviewers-and-linking", 30 Oct 2026). */
class ReviewerController extends Controller
{
    public function show(string $key)
    {
        $reviewer = Reviewer::where('key', $key)->firstOrFail();

        return view('front.reviewer', compact('reviewer'));
    }
}
