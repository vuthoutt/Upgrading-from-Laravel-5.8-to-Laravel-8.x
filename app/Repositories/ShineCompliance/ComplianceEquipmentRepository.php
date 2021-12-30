<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\ComplianceEquipment;
use App\Models\ShineCompliance\ComplianceEquipmentType;
use Prettus\Repository\Eloquent\BaseRepository;

class ComplianceEquipmentRepository extends BaseRepository {

    function model()
    {
        return ComplianceEquipment::class;
    }

    public function getAllEquipments($property_id, $limit) {
        return ComplianceEquipment::where('property_id', $property_id)->paginate($limit);
    }

    public function getAllEquipmentsInSystem($system_id, $limit) {
        return ComplianceEquipment::where('system_id', $system_id)->paginate($limit);
    }

    public function getAllEquipmentType() {
        return ComplianceEquipmentType::all();
    }

    public function searchEquipment($q){
        // property privilege
        return $this->model->whereRaw("(name LIKE '%$q%' OR reference LIKE '%$q%')")
            ->where('decommissioned','=',0)
            ->orderBy('reference','asc')->limit(LIMIT_SEARCH)->get();
    }
    //assess_id = 0
    public function listEquimentProperty($property_id, $system_id) {
        if(!$system_id){
            return $this->model->where(['property_id'=>$property_id])->orderBy('name')->get();
        }
        return $this->model->where(['property_id'=>$property_id,'system_id' => $system_id])->orderBy('name')->get();
    }
}
