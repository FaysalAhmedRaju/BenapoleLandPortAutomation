<?php

namespace App\Models\Leave;

use App\Models\Employee\Employee;
use App\Models\ProjectModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class LeaveApplication extends ProjectModel
{
    protected $table = 'leave_applications';

    const LEAVE_ID='leave_id';
    const EMPLOYEE_ID='employee_id';
    const FROM='from';
    const TO='to';
    const APPLIED_ON='applied_on';
    const GRANTED_ON='granted_on';
    const REJECTED_ON='rejected_on';
    const LEAVE_DAYS='leave_days';
    const APPLICATION_COPY='application_copy';
    const REASON='reason';
    const STATUS='status';


    protected $fillable=[
        self::LEAVE_ID,
        self::EMPLOYEE_ID,
        self::FROM,
        self::TO,
        self::APPLIED_ON,
        self::GRANTED_ON,
        self::REJECTED_ON,
        self::LEAVE_DAYS,
        self::APPLICATION_COPY,
        self::REASON,
        self::STATUS,
    ];

    protected $ownFields=[
        self::LEAVE_ID,
        self::EMPLOYEE_ID,
        self::FROM,
        self::TO,
        self::APPLIED_ON,
        self::GRANTED_ON,
        self::REJECTED_ON,
        self::LEAVE_DAYS,
        self::APPLICATION_COPY,
        self::REASON,
        self::STATUS,
    ];


    public function leave(){
        return $this->belongsTo(Leave::class);
    }
    public function employee(){
        return $this->belongsTo(Employee::class);
    }

}
