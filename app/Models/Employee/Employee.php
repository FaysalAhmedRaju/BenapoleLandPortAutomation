<?php

namespace App\Models\Employee;

use App\Models\Leave\Leave;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    public function leave(){
        return $this->belongsTo(Leave::class);
    }

    public function applications(){
        return $this->belongsToMany(Leave::class,'leave_applications','employee_id','leave_id')->withPivot('from','to','leave_days','applied_on','granted_on','status','application_copy','reason')
            ->as('application')
            ->withTimestamps();
    }
}
