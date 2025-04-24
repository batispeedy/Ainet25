<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStockLog extends Model
{

    protected $table = 'view_product_stock_logs';
    protected $primaryKey = null;
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'log_type',
        'log_id',
        'product_id',
        'registered_by_user_id',
        'quantity_changed',
    ];
}
