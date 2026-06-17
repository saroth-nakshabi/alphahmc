<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'address',
        'description',
        'what_we_do',
        'sort_order',
        'google_location',
    ];

    /**
     * Resolve the Google Maps embed src URL from the stored value.
     * Accepts either a full <iframe ... src="..."> embed snippet or a bare embed URL.
     * Returns null when nothing usable is stored.
     */
    public function getMapEmbedSrcAttribute(): ?string
    {
        $val = trim((string) $this->google_location);
        if ($val === '') {
            return null;
        }
        if (preg_match('/src\s*=\s*["\']([^"\']+)["\']/i', $val, $m)) {
            return $m[1];
        }
        return $val;
    }
}