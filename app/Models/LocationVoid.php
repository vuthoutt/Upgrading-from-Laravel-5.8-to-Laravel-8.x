<?php

namespace App\Models;

use App\Models\ModelBase;

class LocationVoid extends ModelBase
{
    protected $table = 'tbl_location_void';

    protected $fillable = [
        'location_id',
        'ceiling_other',
        'ceiling',
        'cavities_other',
        'cavities',
        'risers_other',
        'risers',
        'ducting_other',
        'ducting',
        'boxing_other',
        'boxing',
        'pipework_other',
        'pipework',
        'floor_other',
        'floor'
    ];
    protected $hidden = ['location_id','id'];

    public function getStateTextAttribute()
    {
        if ($this->attributes['state'] == "noasbestos") {
            return "No ACM";
        }
        return ucfirst($this->attributes['state']);
    }

    public function getCeilingVoidOperativeAttribute()
    {
        $celing_void = explode(",",$this->attributes['ceiling'])[0];
        return $celing_void == 1062 ? "Accessible" : ($celing_void == 1108 ? "Inaccessible" : "");
    }
    public function getFloorVoidOperativeAttribute()
    {
        $floor_void = explode(",",$this->attributes['floor'])[0];
//        dd($floor_void, 123);
        return $floor_void == 1407 ? "Accessible" : ($floor_void == 1453 ? "Inaccessible" : "");
    }

    public function getCativiesOperativeAttribute()
    {
        $cativies = explode(",",$this->attributes['cavities'])[0];
        return $cativies == 1170 ? "Accessible" : ($cativies == 1216 ? "Inaccessible" : "");
    }

    public function getRisersOperativeAttribute()
    {
        $risers = explode(",",$this->attributes['risers'])[0];
        return $risers == 1234 ? "Accessible" : ($risers == 1280 ? "Inaccessible" : "");
    }

    public function getDuctingOperativeAttribute()
    {
        $ducting = explode(",",$this->attributes['ducting'])[0];
        return $ducting == 1298 ? "Accessible" : ($ducting == 1344 ? "Inaccessible" : "");
    }

    public function getBoxingOperativeAttribute()
    {
        $boxing = explode(",",$this->attributes['boxing'])[0];
        return $boxing == 1687 ? "Accessible" : ($boxing == 1733 ? "Inaccessible" : "");
    }

    public function getPipeworkOperativeAttribute()
    {
        $pipework = explode(",",$this->attributes['pipework'])[0];
        return $pipework == 1560 ? "Accessible" : ($pipework == 1606 ? "Inaccessible" : "");
    }


}
