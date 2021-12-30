<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class AdminTool extends ModelBase
{
    protected $table = 'tbl_admin_tool';

    protected $fillable = [
        "id",
        "action",
        "input",
        "result",
        "roll_back",
        "created_at",
        "created_by",
        "updated_at",
    ];


    public function user() {
        return $this->hasOne('App\User','id','created_by');
    }

    public function getDescriptionAttribute(){
        $input = json_decode($this->attributes['input']);
        if (!is_null($input)) {
            return $input->description ?? '';
        } else {
            return '';
        }
    }

    public function getReasonAttribute(){
        $input = json_decode($this->attributes['input']);
        if (!is_null($input)) {
            return $input->reason ?? '';
        } else {
            return '';
        }
    }
}
