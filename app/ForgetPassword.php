<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForgetPassword extends Model
{

    //
public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }
}
