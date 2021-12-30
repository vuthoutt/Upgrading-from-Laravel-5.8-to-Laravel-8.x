<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\ComplianceProgramme;
use Prettus\Repository\Eloquent\BaseRepository;

class ComplianceProgrammeRepository extends BaseRepository {

    function model()
    {
        return ComplianceProgramme::class;
    }

    public function getAllProgrammes($system_id, $limit,$request) {
        if($request->has('q')) {
            $builder = $this->model
                ->where('system_id', $system_id);
            if (isset($request->q) && !empty($request->q)) {
                $condition_raw = "( `name` LIKE '%" . $request->q . "%'
                                    OR `reference` LIKE '%" . $request->q . "%' )";
                $builder->whereRaw($condition_raw);
            }
            return $builder->paginate($limit);
        }
        return ComplianceProgramme::where('system_id', $system_id)->paginate($limit);
    }

    public function searchProgramme($q){
        // property privilege
        return $this->model->whereRaw("(name LIKE '%$q%' OR reference LIKE '%$q%')")
            ->where('decommissioned','=',0)
            ->orderBy('reference','asc')->limit(LIMIT_SEARCH)->get();
    }

    public function listProgrammeProperty($property_id, $system_id) {
        if(!$system_id){
            return $this->model->where(['property_id'=>$property_id])->orderBy('name')->get();
        }
        return $this->model->where(['property_id'=>$property_id,'system_id' => $system_id])->orderBy('name')->get();
    }
}
