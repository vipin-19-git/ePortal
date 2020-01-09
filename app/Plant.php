<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    //
     public $table = "plants";
     public function comapny()
    {
        return $this->belongsTo('App\Company');
    }
}
