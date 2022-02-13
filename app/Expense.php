<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public $fillable = ['week', 'workshop_id', 'type', 'concept', 'amount', 'iva', 'total', 'bill'];
}
