<?php

namespace App\Models\Cnf;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;

class Cnf extends Model
{
    protected $table = 'cnf_details';
    public $timestamps = false;




    public function ports() {
        return $this->belongsToMany('App\Port');
    }
}
