<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    public $fillable = ['week', 'number', 'start', 'end', 'user_id', 'type', 'total_week', 'comission', 'discount', 'salary', 'total', 'workshop_id'];
}
