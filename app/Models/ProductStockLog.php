<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStockLog extends Model
{
    /**
     * The view associated with the model.
     */
    protected $table = 'view_product_stock_logs';

    /**
     * The primary key is non-incrementing and not an integer.
     */
    protected $primaryKey = null;
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'log_type',
        'log_id',
        'product_id',
        'registered_by_user_id',
        'quantity_changed',
    ];
}
