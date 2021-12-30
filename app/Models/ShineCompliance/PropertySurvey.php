<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class PropertySurvey extends ModelBase
{
    protected $table = 'tbl_property_survey';

    protected $fillable = [
        "property_id",
        "property_status",
        "property_occupied",
        "programme_type",
        "programme_type_other",
        "asset_use_primary",
        "asset_use_primary_other",
        "asset_use_secondary",
        "asset_use_secondary_other",
        "construction_age",
        "construction_type",
        "listed_building",
        "listed_building_other",
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
        "size_gross_area",
        "parking_arrangements",
        "parking_arrangements_other",
        "nearest_hospital",
        "restrictions_limitations",
        "unusual_features",
        "size_comments",
        "evacuation_strategy",
        "fra_overall",
        'stairs',
        'stairs_other',
        'floors',
        'floors_other',
        'wall_construction',
        'wall_construction_other',
        'wall_finish',
        'wall_finish_other',
        'floors_above',
        'floors_above_other',
        'floors_below',
        'floors_below_other',
        'property_type',

    ];

    public function propertyProgrammeType() {
        return $this->hasOne('App\Models\ShineCompliance\PropertyProgrammeType','id','programme_type');
    }

    // Getter/Setter
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

        return '';
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

        return '';
    }

    public function getPropertyStatusDispAttribute()
    {
        if ($this->attributes['property_status']) {
            return PropertyInfoDropdownData::find($this->attributes['property_status'])->description ?? '';
        }

        return !empty($this->attributes['property_status']) ?? '';
    }

    public function getPropertyOccupiedDispAttribute()
    {
        if ($this->attributes['property_occupied']) {
            return PropertyInfoDropdownData::find($this->attributes['property_occupied'])->description ?? '';
        }

        return !empty($this->attributes['property_occupied']) ?? '';
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

        return !empty($this->attributes['listed_building']) ?? '';
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

        return !empty($this->attributes['parking_arrangements']) ?? '';
    }

    public function getStairsDispAttribute()
    {

        if ($this->attributes['stairs']) {
            $dropdownIds = explode(",",$this->attributes['stairs']);
            $dropdownDescriptions = PropertyInfoDropdownData::whereIn('id', $dropdownIds)->get();
            $description = [];
            if (!is_null($dropdownDescriptions)) {
                foreach ($dropdownDescriptions as $dropdownDescription) {
                    if ($dropdownDescription->other == 0) {
                        $description[] = $dropdownDescription->description;
                    } else {
                        $description[] = $this->attributes['stairs_other'];;
                    }
                }
                return implode(", ",$description);
            } else {
                return '';
            }
        }

        return '';
    }

    public function getFloorsDispAttribute()
    {
        if ($this->attributes['floors']) {
            $dropdownIds = explode(",",$this->attributes['floors']);
            $dropdownDescriptions = PropertyInfoDropdownData::whereIn('id', $dropdownIds)->get();
            $description = [];
            if (!is_null($dropdownDescriptions)) {
                foreach ($dropdownDescriptions as $dropdownDescription) {
                    if ($dropdownDescription->other == 0) {
                        $description[] = $dropdownDescription->description;
                    } else {
                        $description[] = $this->attributes['floors_other'];;
                    }
                }
                return implode(", ",$description);
            } else {
                return '';
            }
        }

        return '';
    }

    public function getWallConstructionDispAttribute()
    {
        if ($this->attributes['wall_construction']) {
            $dropdownIds = explode(",",$this->attributes['wall_construction']);
            $dropdownDescriptions = PropertyInfoDropdownData::whereIn('id', $dropdownIds)->get();
            $description = [];
            if (!is_null($dropdownDescriptions)) {
                foreach ($dropdownDescriptions as $dropdownDescription) {
                    if ($dropdownDescription->other == 0) {
                        $description[] = $dropdownDescription->description;
                    } else {
                        $description[] = $this->attributes['wall_construction_other'];;
                    }
                }
                return implode(", ",$description);
            } else {
                return '';
            }
        }

        return '';
    }

    public function getWallFinishDispAttribute()
    {
        if ($this->attributes['wall_finish']) {
            $dropdownIds = explode(",",$this->attributes['wall_finish']);
            $dropdownDescriptions = PropertyInfoDropdownData::whereIn('id', $dropdownIds)->get();
            $description = [];
            if (!is_null($dropdownDescriptions)) {
                foreach ($dropdownDescriptions as $dropdownDescription) {
                    if ($dropdownDescription->other == 0) {
                        $description[] = $dropdownDescription->description;
                    } else {
                        $description[] = $this->attributes['wall_finish_other'];;
                    }
                }
                return implode(", ",$description);
            } else {
                return '';
            }
        }
        return '';
    }

    public function getEvacuationStrategyDispAttribute()
    {
        if ($this->attributes['evacuation_strategy']) {
            return PropertyInfoDropdownData::find($this->attributes['evacuation_strategy'])->description ?? '';
        }

        return !empty($this->attributes['evacuation_strategy']) ?? '';
    }

    public function getFraOverallDispAttribute()
    {
        if ($this->attributes['fra_overall']) {
            return PropertyInfoDropdownData::find($this->attributes['fra_overall'])->description ?? '';
        }

        return !empty($this->attributes['fra_overall']) ?? '';
    }
}
