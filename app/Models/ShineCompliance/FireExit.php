<?php


namespace App\Models\ShineCompliance;


use App\Models\ModelBase;

class FireExit extends ModelBase
{
    protected $table = 'cp_fire_exits';

    protected $fillable = [
        'record_id',
        'is_locked',
        'name',
        'reference',
        'type',
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

    public function fireExistPhotoShineDocumentStorage() {
        return $this->hasOne('App\Models\ShineCompliance\ShineDocumentStorage','object_id', 'id')->where('type',FIRE_EXIT_PHOTO);
    }

    public function reasonDecommission()
    {
        return $this->hasOne(DecommissionReason::class, 'id', 'reason_decommissioned');
    }

    // Getter/Setter
    public function getTypeDispAttribute()
    {
        switch ($this->attributes['type']) {
            case FIRE_EXIT_TYPE_FINAL:
                return 'Final Exit';
            case FIRE_EXIT_TYPE_STOREY:
                return 'Storey Exit';
        }

        return '';
    }

    public function getIsFireAttribute() {
        return true;
    }
}
