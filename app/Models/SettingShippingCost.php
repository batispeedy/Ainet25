<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingShippingCost extends Model
{
    // Define explicit table name if it does not follow Laravel convention
    protected $table = 'settings_shipping_costs';

    // Mass assignable attributes
    protected $fillable = [
        'min_value_threshold',
        'max_value_threshold',
        'shipping_cost',
    ];

    // Disable timestamps if not using created_at/updated_at
    public $timestamps = true;
}
