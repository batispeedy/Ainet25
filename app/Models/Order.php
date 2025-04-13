<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'member_id', 'status', 'nif', 'total', 'shipping_cost',
        'delivery_address', 'cancel_reason'
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
