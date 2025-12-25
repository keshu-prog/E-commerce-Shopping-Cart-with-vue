<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'type',
        'recipient',
        'payload',
        'sent_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'sent_at' => 'datetime',
    ];



    public static function log(
        string $type,
        string $recipient,
        array $payload
    ): self {
        return self::create([
            'type' => $type,
            'recipient' => $recipient,
            'payload' => $payload,
            'sent_at' => now(),
        ]);
    }
}
