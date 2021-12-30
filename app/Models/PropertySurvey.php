<?php

namespace App\Models;

use App\Models\ModelBase;
use App\Models\ShineCompliance\DropdownDataProperty;
use App\Models\ShineCompliance\PropertyInfoDropdownData;

class PropertySurvey extends ModelBase
{
    protected $table = 'tbl_property_survey';

    protected $fillable = [
        "property_id",
        "programme_type",
        "programme_type_other",
        "asset_use_primary",
        "asset_use_primary_other",
        "asset_use_secondary",
        "asset_use_secondary_other",
        "property_status",
        "property_occupied",
        "construction_age",
        "construction_type",
        "size_floors",
        "size_floors_other",
        "size_staircases",
        "size_staircases_other",
        "size_lifts",
        "size_lifts_other",
        "size_net_area",
        "electrical_meter",
        "gas_meter",
        "loft_void",
        "size_bedrooms",
        "listed_building",
        "listed_building_other",
        "parking_arrangements",
        "parking_arrangements_other",
        "nearest_hospital",
        "restrictions_limitations",
        "unusual_features",
        "size_gross_area",
        "size_comments",

    ];

    public function propertyProgrammeType() {
        return $this->hasOne('App\Models\PropertyProgrammeType','id','programme_type');
    }

    public function getUsePrimaryDispAttribute()
    {
        if ($this->attributes['asset_use_primary']) {
            $usePrimary = DropdownDataProperty::find($this->attributes['asset_use_primary']);
            if ($usePrimary && $usePrimary->other) {
                return $this->attributes['asset_use_primary_other'];
            } else {
                return $usePrimary->description ?? '';
            }
        }

        return $this->attributes['asset_use_primary'];
    }

    public function getUseSecondaryDispAttribute()
    {
        if ($this->attributes['asset_use_secondary']) {
            $useSecondary = DropdownDataProperty::find($this->attributes['asset_use_secondary']);
            if ($useSecondary && $useSecondary->other) {
                return $this->attributes['asset_use_secondary_other'];
            } else {
                return $useSecondary->description ?? '';
            }
        }

        return $this->attributes['asset_use_secondary'];
    }

    public function getPropertyStatusDispAttribute()
    {
        if ($this->attributes['property_status']) {
            return PropertyInfoDropdownData::find($this->attributes['property_status'])->description ?? '';
        }

        return $this->attributes['property_status'];
    }

    public function getPropertyOccupiedDispAttribute()
    {
        if ($this->attributes['property_occupied']) {
            return PropertyInfoDropdownData::find($this->attributes['property_occupied'])->description ?? '';
        }

        return $this->attributes['property_occupied'];
    }

    public function getListedBuildingDispAttribute()
    {
        if ($this->attributes['listed_building']) {
            $listedBuilding = PropertyInfoDropdownData::find($this->attributes['listed_building']);
            if ($listedBuilding && $listedBuilding->other) {
                return $this->attributes['listed_building_other'];
            } else {
                return $listedBuilding->description ?? '';
            }
        }

        return $this->attributes['listed_building'];
    }

    public function getParkingArrangementsDispAttribute()
    {
        if ($this->attributes['parking_arrangements']) {
            $parkingArrangements = PropertyInfoDropdownData::find($this->attributes['parking_arrangements']);
            if ($parkingArrangements && $parkingArrangements->other) {
                return $this->attributes['parking_arrangements_other'];
            } else {
                return $parkingArrangements->description ?? '';
            }
        }

        return $this->attributes['parking_arrangements'];
    }
}
