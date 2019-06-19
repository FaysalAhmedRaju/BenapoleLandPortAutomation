<?php

namespace App\Models\Assessment;

use Illuminate\Database\Eloquent\Model;

class AssessmentDocument extends Model
{
    protected $table = 'assessment_documents';
    public $timestamps = false;

    const MANIFEST_ID = 'manifest_id';
    const DOCUMENT_NAME = 'document_name';
    const DOCUMENT_CHARGE = 'document_charge';
    const PARTIAL_STATUS = 'partial_status';
    const NUMBER_OF_DOCUMENT ='number_of_document';
    const CREATED_AT ='created_at';
    const CREATED_BY ='created_by';




    protected $fillable = [

        self::MANIFEST_ID,
        self::DOCUMENT_NAME,
        self::DOCUMENT_CHARGE,
        self::PARTIAL_STATUS,
        self::NUMBER_OF_DOCUMENT,
        self::CREATED_AT,
        self::CREATED_BY,


    ];

    public $ownFields = [

        self::MANIFEST_ID,
        self::DOCUMENT_NAME,
        self::DOCUMENT_CHARGE,
        self::PARTIAL_STATUS,
        self::NUMBER_OF_DOCUMENT,
        self::CREATED_AT,
        self::CREATED_BY,

    ];

}
