<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $fillable = ['week', 'workshop_id', 'comment', 'total'];
}
