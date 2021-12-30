<?php


namespace App\Models\ShineCompliance;


use App\Models\ModelBase;

class VehicleParking extends ModelBase
{
    protected $table = 'cp_vehicle_parking';

    protected $fillable = [
        'record_id',
        'is_locked',
        'reference',
        'name',
        'property_id',
        'assess_id',
        'area_id',
        'location_id',
        'decommissioned',
        'accessibility',
        'reason_na',
        'reason_na_other',
        'comment',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assess_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function reasonNotAccessible()
    {
        return $this->hasOne(EquipmentDropdownData::class, 'id', 'reason_na');
    }

    public function reasonDecommission()
    {
        return $this->hasOne(DecommissionReason::class, 'id', 'reason_decommissioned');
    }

    public function vehicleParkingPhotoShineDocumentStorage() {
        return $this->hasOne('App\Models\ShineCompliance\ShineDocumentStorage','object_id', 'id')->where('type',VEHICLE_PARKING_PHOTO);
    }
}
