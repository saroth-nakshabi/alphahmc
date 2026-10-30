<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'service_id',
        'landing_page_id',
        'facility_type',
        'emirate',
        'source',
        'message',
        'meeting_at',
        'status',
        'reply_history',
    ];

    protected $casts = [
        'reply_history' => 'array',
        'source'        => 'array',
        'meeting_at'    => 'datetime',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}