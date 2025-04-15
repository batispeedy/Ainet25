<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $fillable = [
        'card_id',
        'type',
        'value',
        'date', 
        'credit_type',
        'debit_type',
        'payment_type',
        'payment_reference',
        'order_id',
    ];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
