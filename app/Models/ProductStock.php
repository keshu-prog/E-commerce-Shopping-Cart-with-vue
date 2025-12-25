<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'quantity_available',
        'low_stock_threshold',
    ];

    protected $casts = [
        'quantity_available' => 'integer',
        'low_stock_threshold' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
