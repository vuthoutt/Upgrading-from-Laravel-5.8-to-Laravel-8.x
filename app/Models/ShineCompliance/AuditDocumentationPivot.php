<?php


namespace App\Models\ShineCompliance;


use Illuminate\Database\Eloquent\Relations\Pivot;

class AuditDocumentationPivot extends Pivot
{
    public function answer() {
        return $this->belongsTo('App\Models\AuditAnswer');
    }
}

