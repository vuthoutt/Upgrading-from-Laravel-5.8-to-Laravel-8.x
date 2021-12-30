<?php

namespace App\Repositories\ShineCompliance;
use App\Models\DropdownDataLocation;
use App\Models\ShineCompliance\DecommissionComment;
use App\Models\ShineCompliance\DropdownDataArea;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\ShineCompliance\Area;

class AreaRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */

    function model()
    {
        return Area::class;
    }

    public function getAllArea($id, $request, $pagination) {
        if($request->has('q') || $request->has('status') || $request->has('status')){
            //todo add identified_risks | system_type | equipment_types filter
            $builder = $this->model->with('reasonArea')->where('property_id', $id)->where('survey_id', 0);
            if(isset($request->q) && !empty($request->q)){
                $condition_raw = "( `description` LIKE '%" . $request->q . "%'
                                    OR `area_reference` LIKE '%" . $request->q . "%'
                                    OR `reference` LIKE '%" . $request->q . "%' )";
                $builder->whereRaw($condition_raw);
            }

            if(isset($request->accessibility)){
                $condition = explode(",", $request->accessibility);
                if(count($condition) > 0){
                    $builder->whereIn('state', $condition);
                }
            }

            if(isset($request->status)){
                $condition = explode(",", $request->status);
                if(count($condition) > 0){
                    $builder->whereIn('decommissioned', $condition);
                }
            }


            return $builder->paginate($pagination);
        }
        return $this->model->with('reasonArea')->where('property_id', $id)->where('survey_id', 0)->orderBy('decommissioned')->paginate($pagination);
    }

    public function listRegisterAreaProperty($property_id, $relations){
        return $this->model->with($relations)->where([
            'property_id' => $property_id,
            'survey_id' => 0,
            'assess_id' => COMPLIANCE_ASSESSMENT_REGISTER,
            'decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED
        ])->get();
    }

    public function getDropDownReasonArea() {
        return DropdownDataArea::where('dropdown_area_id', AREA_INACCESSBILE_DROPDOWN_ID)->get();
    }

    public function decommission($property_id, $type){
        return $this->model->where('property_id',$property_id)->update(['decommissioned' => $type]);
    }

    public function getAreaDetail($area_id,$relation){
        return $this->model->with($relation)->where('id',$area_id)->first();
    }

    public function getFindArea($area_id){
        return $this->model->find($area_id);
    }

    public function decommissionArea($area_id, $reason){
        return $this->model->where('id', $area_id)->update(['decommissioned' => AREA_DECOMMISSION,'decommissioned_reason' => $reason]);;
    }

    public function recommissionArea($area_id, $reason){
        return $this->model->where('id', $area_id)->update(['decommissioned' => AREA_UNDECOMMISSION,'decommissioned_reason' => $reason]);;
    }

    public function findDecommissionComment($comment_id){
        return DecommissionComment::find($comment_id);
    }


    public function searchArea($q, $survey_id = 0){
        // property privilege
        return $this->model->whereRaw("(area_reference LIKE '%$q%' OR reference LIKE '%$q%')")
            ->where('survey_id','=',$survey_id)->where('decommissioned','=',0)
            ->orderBy('area_reference','asc')->limit(LIMIT_SEARCH)->get();
    }

    public function getAssessmentArea($assess_id, $property_id, $relations){
        return $this->model->with($relations)
            ->where(function ($query) use ($assess_id, $property_id){
                $query->where(['assess_id' => $assess_id, 'decommissioned' => AREA_UNDECOMMISSION]);
            })
            ->orWhere(function ($query) use ($assess_id, $property_id){
                $query->where(['assess_id' => COMPLIANCE_ASSESSMENT_REGISTER, 'survey_id' => 0, 'property_id' => $property_id,'decommissioned' => AREA_UNDECOMMISSION]);
            })
            ->get();
    }

    public function getRegisterAssessmentArea($property_id, $assess_id){
        $register_area =  $this->model->where([
            'property_id' => $property_id,
            'assess_id' => 0,
            'survey_id' => 0,
            'decommissioned' => AREA_UNDECOMMISSION
        ])->get();
        $assessment_area = collect([]);
        if ($assess_id != 0) {
            $assessment_area = $this->model->where(['assess_id' => $assess_id, 'decommissioned' => AREA_UNDECOMMISSION])->get();
        }

        $register_area = $register_area->merge($assessment_area);

        return $register_area;
    }

    public function getRegisterAreaByRecordId($record_id)
    {
        $result = $this->model->where('survey_id', 0)
                            ->where('assess_id', 0)
                            ->where('record_id', $record_id)
                            ->first();
        if ($result) {
            return $result->id;
        } else {
            return 0;
        }
    }


    public function getAssessmentRegisterAreaId($assess_id) {
        $areas = Area::where('assess_id', $assess_id)->pluck('record_id')->toArray();

        $register_area_ids = Area::whereIn('record_id', $areas)
                                    ->where('assess_id',0)
                                    ->where('survey_id',0)
                                    ->where('decommissioned',0)
                                    ->pluck('id')->toArray();
        return $register_area_ids;
    }

    public function getPropertyArea($id,$decommissioned){
        return $this->model->with('locations','locations.items','locations.items.productDebrisView')->where('property_id', $id)->where('survey_id', 0)
            ->where('decommissioned', $decommissioned)
            ->oldest('reference')
            ->oldest('description')->get();
    }

    public function getAreaPaginationCustomize($survey_id, $decommissioned = 0, $property_id,$other_condition) {
        // page is position in list data in view
        return $area = $this->model->with('decommissionedReason')
                ->where(['survey_id' => $survey_id, 'property_id' => $property_id, 'decommissioned' => $decommissioned])
                ->whereRaw($other_condition)->get();
    }

    public function getSurveyArea($id, $decommissioned) {
        $areas = $this->model->where('survey_id', $id)
            ->where('decommissioned', $decommissioned)
            ->oldest('reference')
            ->oldest('description')->get();
        return is_null($areas) ? [] : $areas;
    }
}
