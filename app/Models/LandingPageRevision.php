<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** A full snapshot of a landing page (copy, SEO fields and hero image) taken on every admin save. */
class LandingPageRevision extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['landing_page_id', 'user_id', 'user_name', 'note', 'snapshot'];

    protected $casts = ['snapshot' => 'array'];

    public function landingPage()
    {
        return $this->belongsTo(LandingPage::class);
    }
}
