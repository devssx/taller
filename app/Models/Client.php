<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    public $fillable = [
        'name', 'phonenumber','phonenumber2', 'address', 'reference', 'email', 'information', 'details', 'reminder', 'reminder_date', 'workshop_id'
    ];
}
