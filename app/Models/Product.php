<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'category_id', 'price', 'stock', 'description', 'photo',
        'discount', 'discount_min_qty', 'stock_lower_limit', 'stock_upper_limit'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
