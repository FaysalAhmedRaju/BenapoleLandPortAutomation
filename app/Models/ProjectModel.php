<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProjectModel extends Model
{

    const PORT_ID = 'port_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function __construct(array $attributes = []){
        $this->{self::PORT_ID} = (int)session()->get('PORT_ID');
        parent::__construct($attributes);

    }


//    public function setPortIdAttribute($value)
//    {
//        $this->attributes[self::PORT_ID] = session()->get(PORT_ID);
//
//    }
    public function setCreatedAtAttribute($value)
    {
        $this->attributes[self::CREATED_AT] = Carbon::now();

    }
    public function setUpdatedAtAttribute($value)
    {
        $this->attributes[self::UPDATED_AT] = $value?$value:Carbon::now();

    }


    public function newQuery()
    {
        $port_id = (int)session()->get('PORT_ID');
        $builder = $this->newQueryWithoutScopes();
        $tableName = $builder->getModel()->getTable();
        $final = $builder->where($tableName . '.port_id', $port_id);

        return $final;
    }

}
