<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = '23810310084_products';

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'price',
        'stock_quantity',
        'image_path',
        'status',
        'discount_percent',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}