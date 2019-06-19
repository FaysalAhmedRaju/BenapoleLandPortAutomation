<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WeighbrideUsr extends Model
{
    protected $table='weighbridge_users';
  
    public function user(){
       return $this->belongsTo('App/User');
    }
}
