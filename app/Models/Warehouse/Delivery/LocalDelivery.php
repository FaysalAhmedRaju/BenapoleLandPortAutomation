<?php

namespace App\Models\Warehouse\Delivery;

use Illuminate\Database\Eloquent\Model;
use App\Models\Manifest;

class LocalDelivery extends Model
{
    protected $table='truck_deliverys';


    public function manifest() {
        return $this->belongsTo(Manifest::Class,'manf_id');
    }
}
