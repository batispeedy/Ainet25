<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'photo',
        'nif',
        'default_delivery_address',
        'default_payment_type',
        'default_payment_reference',
        'type',
        'blocked',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'blocked' => 'boolean',
    ];

    /*public function card()
    {
        return $this->hasOne(Card::class, 'user_id', 'id');
    }*/

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function supplyOrders()
    {
        return $this->hasMany(SupplyOrder::class, 'registered_by_user_id');
    }

    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class, 'registered_by_user_id');
    }
}
