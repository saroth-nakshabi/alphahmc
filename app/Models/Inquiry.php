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
        'message',
        'status',
        'reply_history',
    ];

    protected $casts = [
        'reply_history' => 'array',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}