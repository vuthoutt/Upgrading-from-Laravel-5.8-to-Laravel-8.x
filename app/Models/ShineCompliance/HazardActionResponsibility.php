<?php


namespace App\Models\ShineCompliance;


use Illuminate\Database\Eloquent\Model;

class HazardActionResponsibility extends Model
{
    protected $table = 'cp_hazard_action_responsibilities';

    protected $fillable = [
        'description',
        'parent_id',
        'order',
        'other',
    ];

    public function parents() {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }
}
