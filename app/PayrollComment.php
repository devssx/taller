<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayrollComment extends Model
{
    public $fillable = ['week', 'user_id', 'comment', 'total'];
}
