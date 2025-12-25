<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    const STATUS_PENDING  = 0;
    const STATUS_SUCCESS  = 1;
    const STATUS_FAILED   = 2;
    const STATUS_REFUNDED = 3;

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'payment_method',
        'transaction_id',
        'amount',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => 'integer',
        'paid_at' => 'datetime',
    ];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    public function isSuccessful(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }
}
