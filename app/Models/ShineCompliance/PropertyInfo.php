<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class PropertyInfo extends ModelBase
{
    protected $table = 'tbl_property_info';

    protected $fillable = [
        'property_id',
        'flat_number',
        'building_name',
        'street_number',
        'street_name',
        'address1',
        'address2',
        'address3',
        'address4',
        'address5',
        'town',
        'postcode',
        'country',
        'telephone',
        'mobile',
        'email',
        'app_contact',
        'team1',
        'team2',
        'team3',
        'team4',
        'team5',
        'team6',
        'team7',
        'team8',
        'team9',
        'team10',
        'estate',
        'deleted_by',
        'created_by',
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    public function propertyInfoUser() {
        return $this->belongsTo('App\Models\ShineCompliance\User','team1');
    }

    public function getTeamAttribute() {
        $contact = [];
        for ($i=1; $i <= 10; $i++) {
            $name = 'team'. $i;
            if (!is_null($this->attributes[$name]) and $this->attributes[$name] != 0 ) {
                $contact[] = $this->attributes[$name];
            }
        }
        return $contact;
    }

        // out ->list_address
    public function getlistAddressAttribute() {
        $adress = [];
        for ($i=1; $i <= 5; $i++) {
            $name = 'address'. $i;
            if (isset($this->attributes[$name]) and !is_null($this->attributes[$name])) {
                $adress[] = $this->attributes[$name];
            }
        }
        return implode(", ", $adress);
    }

}
