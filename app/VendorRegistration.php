<?php

namespace App;

  use Illuminate\Notifications\Notifiable;
    use Illuminate\Foundation\Auth\User as Authenticatable;
class VendorRegistration  extends Authenticatable
      
{
  use Notifiable;
  

        protected $guard = 'vendors';
     public $timestamps = false;
      protected $fillable = [
        'mobile', 'password'
    ];

  public function BusinessType()
  {
      //return $this->belongsTo('App\Business_types');
       return $this->hasOne('App\Business_types', 'code','business_type');
  }
   public function City()
  {
     return $this->hasOne('App\Cities', 'id','city');
  }
  public function State()
  {
      return $this->hasOne('App\States', 'state_Code','state');
  }
 public function Country()
  {
     return $this->hasOne('App\Countries', 'country_Code','country');
  }
  public function getAuthPassword()
    {
     return $this->password;
    }
 
}
