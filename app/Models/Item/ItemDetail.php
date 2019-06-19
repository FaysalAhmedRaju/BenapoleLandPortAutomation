<?php

namespace App\Models\Item;

use App\Models\ProjectModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\Manifest;

class ItemDetail extends ProjectModel
{
    protected $table = 'item_details';
    public $timestamps = false;

    const MANF_ID = 'manf_id';
    const ITEM_CODE_ID = 'item_Code_id';
    const ITEM_TYPE = 'item_type';
    const ITEM_QUANTITY = 'item_quantity';
    const YARD_SHED = 'yard_shed';
    const GOODS_ID = 'goods_id';
    const DANGEROUS = 'dangerous';
    const PORT_ID = 'port_id';


    protected $fillable = [
        self::MANF_ID,
        self::ITEM_CODE_ID,
        self::ITEM_TYPE,
        self::ITEM_QUANTITY,
        self::YARD_SHED,
        self::GOODS_ID,
        self::DANGEROUS,
        self::PORT_ID,

    ];

    public $ownFields = [
        self::MANF_ID,
        self::ITEM_CODE_ID,
        self::ITEM_TYPE,
        self::ITEM_QUANTITY,
        self::YARD_SHED,
        self::GOODS_ID,
        self::DANGEROUS,
        self::PORT_ID,
    ];

    public function manifest()
    {
        return $this->belongsTo(Manifest::Class,'manf_id');
    }


}
