<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //
    public $table = "companies";
    public function plants()
    {
        return $this->hasMany('App\Plant');
    }
}
