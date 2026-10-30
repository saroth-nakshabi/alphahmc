<?php

namespace App\Support;

use App\Models\Reviewer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Reviewer, review date (and for blogs the service page link) on the admin forms of services, categories,
 * packages and blogs (brief "alphahmc-reviewers-and-linking"). The form partial is
 * dashboard/partials/reviewer-fields; nothing changes when a form does not send the fields.
 * Rule from the SEO agent: change reviewed_at only when the reviewer has re-read the page after an edit.
 */
class ReviewerFields
{
    public static function validate(Request $request, bool $withServiceLink = false): void
    {
        if (!$request->has('reviewer')) {
            return;
        }

        $request->validate(array_filter([
            'reviewer'     => ['nullable', 'string', Rule::in(Reviewer::byKey()->keys()->all())],
            'reviewed_at'  => ['nullable', 'date', 'before_or_equal:today', 'required_with:reviewer'],
            'service_link' => $withServiceLink ? ['nullable', 'string', 'max:255', 'regex:#^/(services|service-category|service-packages)/[a-z0-9-]+$#'] : null,
        ]), [
            'reviewed_at.required_with'   => 'Add the date the reviewer signed the page off, or clear the reviewer.',
            'reviewed_at.before_or_equal' => 'The review date cannot be in the future.',
            'service_link.regex'          => 'Use a site path such as /services/dha-license-healthcare-professionals-dubai.',
        ]);
    }

    public static function apply(Request $request, Model $page, bool $withServiceLink = false): void
    {
        if (!$request->has('reviewer')) {
            return;
        }

        $page->reviewer = $request->input('reviewer') ?: null;
        $page->reviewed_at = $page->reviewer ? ($request->input('reviewed_at') ?: null) : null;
        if ($withServiceLink) {
            $page->service_link = $request->input('service_link') ?: null;
        }
        if ($page->isDirty(['reviewer', 'reviewed_at', 'service_link'])) {
            $page->save();
        }
    }
}
