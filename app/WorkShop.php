<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkShop extends Model
{
    public $fillable = ['code', 'consec', 'name', 'address', 'account', 'phone', 'sucursal1', 'sucursal2'];
}
