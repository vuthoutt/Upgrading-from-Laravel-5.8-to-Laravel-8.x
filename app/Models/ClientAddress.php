<?php

namespace App\Models;

use App\Models\ModelBase;

class ClientAddress extends ModelBase
{
    protected $table = 'tbl_client_address';

    protected $fillable = [
        'client_id',
        'address1',
        'address2',
        'address3',
        'address4',
        'address5',
        'postcode',
        'country',
        'telephone',
        'mobile',
        'fax',
    ];
    // out ->list_address
    public function getlistAddressAttribute() {
        $address = [];
        for ($i=1; $i <= 5; $i++) {
            $name = 'address'. $i;
            if (isset($this->attributes[$name]) and !is_null($this->attributes[$name])) {
                $address[] = $this->attributes[$name];
            }
        }
        $address[] = $this->attributes['postcode'];
        return implode(", ", array_filter($address));
    }
}
