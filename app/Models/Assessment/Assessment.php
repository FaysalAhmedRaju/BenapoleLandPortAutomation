<?php

namespace App\Models\Assessment;

use App\ProjectModel;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{

    protected $table = 'assessments';
    public $timestamps = false;

    const MANIFEST_ID = 'manifest_id';
    const PARTIAL_STATUS = 'partial_status';
    const CREATED_BY = 'created_by';
    const CREATED_AT ='created_at';
    const WAREHOUSE_DETAILS = 'warehouse_details';


    protected $fillable = [

        self::MANIFEST_ID,
        self::PARTIAL_STATUS,
        self::CREATED_BY,
        self::CREATED_AT,
        self::WAREHOUSE_DETAILS,



    ];

    public $ownFields = [

        self::MANIFEST_ID,
        self::PARTIAL_STATUS,
        self::CREATED_BY,
        self::CREATED_AT,

    ];



}
