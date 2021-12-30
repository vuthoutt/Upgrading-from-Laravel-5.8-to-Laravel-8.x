<?php


namespace App\Models\ShineCompliance;


use App\Models\ModelBase;

class PropertyVulnerableOccupant extends ModelBase
{
    protected $table = 'tbl_property_vulnerable_occupants';

    protected $fillable = [
        'id',
        'property_id',
        'avg_vulnerable_occupants',
        'max_vulnerable_occupants',
        'last_vulnerable_occupants',
        'vulnerable_occupant_type',
        'description',
    ];

    public function vulnerableOccupantTypes()
    {
        return $this->belongsToMany(VulnerableOccupantType::class, 'tbl_property_vulnerable_occupant_type', 'vulnerable_occupant_id', 'vulnerable_occupant_type_id');
    }
}
