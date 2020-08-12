<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordManager extends Model
{
    public $fillable = ['name', 'workshop_id', 'password'];
}
