<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NetworkEvent extends Model
{
    protected $fillable = [
        'source_ip',
        'destination_ip',
        'source_port',
        'destination_port',
        'protocol',
        'event_type',
        'severity',
        'description',
        'status',
        'detected_at',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
    ];
}