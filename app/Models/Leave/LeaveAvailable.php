<?php

namespace App\Models\Leave;

use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Model;

class LeaveAvailable extends Model
{
    protected $table = 'leave_availables';

    const LEAVE_ID = 'leave_id';
    const EMPLOYEE_ID = 'employee_id';
    const REMAINING = 'remaining';


    protected $fillable = [
        self::LEAVE_ID,
        self::EMPLOYEE_ID,
        self::REMAINING,
    ];

    protected $ownFields = [
        self::LEAVE_ID,
        self::EMPLOYEE_ID,
        self::REMAINING,
    ];

    public function leave(){
        return $this->belongsTo(Leave::class);
    }
    public function employee(){
        return $this->belongsTo(Employee::class);
    }

}
