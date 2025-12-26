<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $casts = [
        'images' => 'array',
    ];
    protected $appends = ['image_urls'];
    protected $fillable = [
        'sku',
        'name',
        'description',
        'price',
        'status',
    ];

    public function stock()
    {
        return $this->hasOne(ProductStock::class, 'product_id', 'id');
    }

    public function getImageUrlsAttribute()
    {
        if (empty($this->images) || !is_array($this->images)) {
            return [
                asset('storage/product/pink-handbags.jpg')
            ];
        }

        return collect($this->images)->map(function ($image) {
            return asset('storage/product/' . $image);
        })->toArray();
    }
}


