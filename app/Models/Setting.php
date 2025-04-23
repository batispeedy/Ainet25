<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // Table is singular “settings” by convention
    protected $table = 'settings';

    // We only care about the one row, no timestamps for this example
    public $timestamps = false;

    protected $fillable = ['membership_fee'];
}
