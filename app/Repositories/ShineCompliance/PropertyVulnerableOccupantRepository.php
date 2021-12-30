<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\PropertyVulnerableOccupant;
use Prettus\Repository\Eloquent\BaseRepository;

class PropertyVulnerableOccupantRepository extends BaseRepository
{

    public function model()
    {
        return PropertyVulnerableOccupant::class;
    }
}
