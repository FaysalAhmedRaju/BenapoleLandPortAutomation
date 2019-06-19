<?php

namespace App;

use App\Models\Employee\Employee;
use App\Models\Leave\Leave;
use Laravel\Passport\HasApiTokens;
use App\Models\Weighbridge\Weighbridge;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\Warehouse\ShedYard;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;



    protected $dispatchesEvents = [
        'saving' => \App\Events\UserSaving::class,
    ];

    protected $events = [
        'saving' => \App\Events\UserSaving::class
      /*  'saved' => UserSaved::class,
        'deleted' => UserDeleted::class,*/
    ];

    const NAME='name';
    const PHOTO='photo';
    const USERNAME='username';
    const PASSWORD='password';
    const ROLE_ID='role_id';
    const FATHER_NAME='father_name';
    const MOTHER_NAME='mother_name';
    const EMAIL='email';
    const ORG_ID='org_id';
    const ORG_TYPE_ID='org_type_id';
    const MOBILE='mobile';
    const PHONE='phone';
    const USER_STATUS='user_status';
    const DESIGNATION='designation';
    const DATE_OF_BIRTH='date_of_birth';
    const PRESENT_ADDRESS='present_address';
    const PERMANENT_ADDRESS='permanent_address';
    const JOIN_DATE='join_date';
    const NID_NO='nid_no';
    const NID_PHOTO='nid_photo';
    const PORT_ID='port_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::NAME,
        self::PHOTO,
        self::USERNAME,
        self::PASSWORD,
        self::ROLE_ID,
        self::FATHER_NAME,
        self::MOTHER_NAME,
        self::EMAIL,
        self::ORG_ID,
        self::MOBILE,
        self::PHONE,
        self::USER_STATUS,
        self::DESIGNATION,
        self::DATE_OF_BIRTH,
        self::PRESENT_ADDRESS,
        self::PERMANENT_ADDRESS,
        self::JOIN_DATE,
        self::NID_NO,
        self::NID_PHOTO,
        self::PORT_ID,

    ];

    public $ownFields = [

        self::NAME,
        self::PHOTO,
        self::USERNAME,
        self::PASSWORD,
        self::ROLE_ID,
        self::FATHER_NAME,
        self::MOTHER_NAME,
        self::EMAIL,
        self::ORG_ID,
        self::MOBILE,
        self::PHONE,
        self::USER_STATUS,
        self::DESIGNATION,
        self::DATE_OF_BIRTH,
        self::PRESENT_ADDRESS,
        self::PERMANENT_ADDRESS,
        self::JOIN_DATE,
        self::NID_NO,
        self::NID_PHOTO,
        self::PORT_ID,
    ];


/*    protected $dispatchesEvents = [
        'saving' => \App\Events\UserSaving::class,
    ];*/

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function setPasswordAttribute($value)
    {
      return  $this->attributes['password'] =  bcrypt($value);
    }
    /*public function getPasswordAttribute($value)
    {
      return  $this->attributes['password'] =  encrypt($value);
    }*/

    public function role() {
        return $this->hasOne('App\Role','id','role_id');
    }

   /* public function port() {
        return $this->belongsTo(Port::class);
    }*/

    public function ports() {
        return $this->belongsToMany(Port::class);
    }


    public function scales() {
        return $this->hasMany(Weighbridge::class);
    }


    public function shedYards()
    {
        return $this->belongsToMany(ShedYard::class);
    }

    public function portEmployee()
    {
        return $this->belongsTo(Employee::class,'port_employee_id');
    }

    public function weighbridges(){
        return $this->belongsToMany(Weighbridge::class,'weighbridge_users','user_id','scale_id');
    }

    public function portEmployeeLeave()
    {
        return $this->belongsToMany(Leave::class,'leave_applications');
    }




    private function checkIfUserHasRole($need_role) {
        return (strtolower($need_role)==strtolower($this->role->name)) ? true : null;
    }

    public function hasRole($roles) {
        if(is_array($roles)) {
            foreach ($roles as $need_role) {
                if($this->checkIfUserHasRole($need_role)) {
                    return true;
                }
            }
        } else {
            return $this->checkIfUserHasRole($roles);
        }
        return false;
    }



    public function leaveAdmin(){
       $leaveAdmin=[1,2,11];

        if (in_array(\Auth::user()->role_id,$leaveAdmin)){
            return true;
        }
        else{
            return false;
        }
    }
    public function is_admin(){
        if ($this->nur){
            return true;
        }
        else{
            return false;
        }
    }

}
