<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\LocationInfo;
use Prettus\Repository\Eloquent\BaseRepository;

class LocationInfoRepository extends BaseRepository
{

    public function model()
    {
        return LocationInfo::class;
    }

    public function updateLocationInfo($record_id,$data){
        return $this->model->where('location_id', $record_id)->update($data);
    }
}
