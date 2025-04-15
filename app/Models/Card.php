<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'id',
        'card_number',
        'balance',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id'); // local key = id do cartão, foreign key = id do user
    }
}
