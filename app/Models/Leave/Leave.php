<?php

namespace App\Models\Leave;

use App\Models\Employee\Employee;
use App\Models\ProjectModel;
use Illuminate\Database\Eloquent\Model;

class Leave extends ProjectModel
{
    protected $table = 'leaves';



    const TYPE = 'type';
    const NAME = 'name';
    const LEAVE_DETAILS = 'leave_details';
    const MAX_DAYS = 'max_days';


    protected $fillable = [
        self::TYPE,
        self::LEAVE_DETAILS,
        self::MAX_DAYS
    ];

    protected $ownFields = [
        self::TYPE,
        self::LEAVE_DETAILS,
        self::MAX_DAYS
    ];

    public function employees(){
        return $this->belongsToMany(Employee::class,'leave_availables')->withTimestamps();
    }
    public function applications()
    {
        return $this->belongsToMany(LeaveApplication::class,'leave_applications','leave_id','employee_id');
    }
}
