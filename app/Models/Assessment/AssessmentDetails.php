<?php

namespace App\Models\Assessment;

use Illuminate\Database\Eloquent\Model;
use App\Models\SubHead;


class AssessmentDetails extends Model
{
    protected $table = 'assesment_details';
    public $timestamps = false;

    const MANIF_ID = 'manif_id';
    const SUB_HEAD = 'acc_sub_head';
    const UNIT = 'unit';
    const OTHER_UNIT = 'other_unit';
    const CHARGE_PER_UNIT = 'charge_per_unit';
    const TOTAL_CHARGE = 'tcharge';
    const PARTIAL_STATUS = 'partial_status';


    protected $fillable = [

        self::MANIF_ID,
        self::SUB_HEAD,
        self::UNIT,
        self::OTHER_UNIT,
        self::CHARGE_PER_UNIT,
        self::TOTAL_CHARGE,
        self::PARTIAL_STATUS,

    ];

    public $ownFields = [

        self::MANIF_ID,
        self::SUB_HEAD,
        self::UNIT,
        self::OTHER_UNIT,
        self::CHARGE_PER_UNIT,
        self::TOTAL_CHARGE,
        self::PARTIAL_STATUS,
    ];


    public function subHead()
    {
        return $this->belongsTo(SubHead::Class, 'sub_head_id');
    }

}
