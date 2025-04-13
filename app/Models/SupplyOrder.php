<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'registered_by_user_id',
        'status',
        'quantity',
        'custom',
    ];

    protected $casts = [
        'custom' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by_user_id');
    }
}
