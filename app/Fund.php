<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    public $fillable = ['week', 'workshop_id', 'amount', 'expense', 'balance'];
}
