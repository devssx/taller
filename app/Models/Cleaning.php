<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cleaning extends Model
{
    public $fillable = ['user_id', 'start', 'cleaning', 'breakfast_start', 'breakfast_end', 'lunch_start', 'lunch_end', 'done', 'comment'];
}
