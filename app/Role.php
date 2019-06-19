<?php

namespace App;
use App\User;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class Role extends Model
{
    protected $table='roles';

    const NAME = 'name';
    const DASHBOARD_ROUTE = 'dashboard_route';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $primaryKey='id';
    public $timestamps=false;


    protected $fillable = [
        self::NAME,
        self::DASHBOARD_ROUTE,
        self::CREATED_AT,
        self::UPDATED_AT,

    ];


    public $ownFields = [

        self::NAME,
        self::DASHBOARD_ROUTE,
        self::CREATED_AT,
        self::UPDATED_AT,
    ];


    public function users(){
       return $this->hasMany('App/User','role_id','id');
    }

    public function groupAccess()
    {
        return $this->hasMany(GroupAccess::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class,'dashboard_route');
    }









        public function roleList()
    {
        $list=[];

        foreach ($this->all() as $k=>$role){
            $list[$role->id]=$role->name;
        }

        return $list;
    }






}
