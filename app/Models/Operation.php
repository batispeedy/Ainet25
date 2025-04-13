<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $fillable = [
        'card_id', 'value', 'type', 'payment_type', 'payment_reference'
    ];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
