<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'short_description',
        'image',
        'whatsapp',
        'available_modes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Returns true if the given mode (call / email / whatsapp) is enabled for this agent.
    // Defaults to all enabled when the column is null/empty.
    public function hasMode(string $mode): bool
    {
        if (empty($this->available_modes)) return true;
        return in_array($mode, explode(',', $this->available_modes));
    }
}
