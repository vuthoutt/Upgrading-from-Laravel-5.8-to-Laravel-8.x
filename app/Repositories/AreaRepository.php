<?php
namespace App\Repositories;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Area;
use App\Models\Location;
use App\Models\Item;

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

    public function createArea($data) {
        try {
            $area = Area::create($data);
            if ($area) {
                $refArea = "AF" . $area->id;
                Area::where('id', $area->id)->update(['record_id' => $area->id, 'reference' => $refArea, 'created_by' => \Auth::user()->id]);
                // log audit
                \CommonHelpers::logAudit(AREA_TYPE, $area->id, AUDIT_ACTION_ADD, $area->area_reference, $area->survey_id, null , 0 , $area->project->property_id ?? 0);

                if ($area->survey_id == 0) {
                    \CommonHelpers::isRegisterUpdated($area->property_id);
                } else {
                    \CommonHelpers::changeSurveyStatus($area->survey_id);
                }
            }
            return $response = \CommonHelpers::successResponse('Area/Floors Added  Successfully!', $area->id);
        } catch (\Exception $e) {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to create area. Please try again!');
        }

    }

    public function updateArea($id, $data) {
        $area = Area::where('id', $id)->first();
        if (!is_null($area)) {
            try {
                $data['updated_by'] = \Auth::user()->id;
                $areaUpdate = $area->update($data);

                // log audit
                \CommonHelpers::logAudit(AREA_TYPE, $area->id, AUDIT_ACTION_EDIT, $area->area_reference, $area->survey_id, null , 0 , $area->project->property_id ?? 0);
                if ($area->survey_id == 0) {
                    \CommonHelpers::isRegisterUpdated($area->property_id);
                } else {
                    \CommonHelpers::changeSurveyStatus($area->survey_id);
                }
                return $response = \CommonHelpers::successResponse('Area/Floors Updated  Successfully!', $id);
            } catch (\Exception $e) {
                return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Can not update area. Please try again!');
            }
        } else {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Can not find area. Please try again!');
        }
    }

    public function getArea($id) {
        $area = Area::with('decommissionedReason')->where('id', $id)->first();
        return !is_null($area) ? $area : [];
    }

    public function getAreaPaginationCustomize($survey_id, $decommissioned = 0, $property_id,$other_condition = TRUE) {
        // page is position in list data in view
        $area = Area::with('decommissionedReason')
            ->where(['survey_id' => $survey_id, 'property_id' => $property_id, 'decommissioned' => $decommissioned])
            ->whereRaw($other_condition)->get();
        return !$area->isEmpty() ? $area : [];
    }

    public function getAreaOperative($property, $risk_type_one, $risk_type_two){
        // get only not decommission areas
        $areas = Area::with('itemACM', 'locationInaccessible')
            ->where(['decommissioned'=> 0, 'property_id' => $property->id, 'survey_id' => 0])->orderBy('area_reference')->orderBy('area_reference')->orderBy('description')
                ->get();

        if(count($areas)){
            foreach ($areas as $area){
                if(!$risk_type_two){
                    if(!is_null($area->itemACM)){
                        $area->area_operative = ['bg_color'=>'#f2dede','border_color'=>'#eed3d7','text_color'=>'#b94a48','text'=>'Asbestos Present'];
                    } else {
                        $first_inacc_void_location =  DB::select("SELECT lv.* from tbl_location_void lv
                                                            JOIN tbl_location l ON lv.location_id = l.id
                                                            LEFT JOIN tbl_area a ON l.area_id = a.id
                                                            WHERE a.id = $area->id AND l.decommissioned = 0 AND l.survey_id = 0 AND
                                                            (
                                                                FIND_IN_SET(1108, lv.ceiling)
                                                                OR FIND_IN_SET(1453, lv.floor)
                                                                OR FIND_IN_SET(1216, lv.cavities)
                                                                OR FIND_IN_SET(1280, lv.risers)
                                                                OR FIND_IN_SET(1344, lv.ducting)
                                                                OR FIND_IN_SET(1733, lv.boxing)
                                                                OR FIND_IN_SET(1606, lv.pipework)
                                                            ) LIMIT 1");
                        if(!is_null($area->locationInaccessible) || $first_inacc_void_location || !is_null($area->itemInaccACM)){
                            $area->area_operative = ['bg_color'=>'#f2dede','border_color'=>'#eed3d7','text_color'=>'#b94a48','text'=>'Asbestos Presumed'];
                        } else {
                            $area->area_operative = ['bg_color'=>'#dff0d8','border_color'=>'#d6e9c6','text_color'=>'#468847','text'=>'No Asbestos Detected'];
                        }
                    }
                } else {
                    $area->area_operative = ['bg_color'=>'#dff0d8','border_color'=>'#d6e9c6','text_color'=>'#468847','text'=>'Property Build in or After 2000'];
                }
            }
        }
        //$areaShineRef = implode(', ', array_filter([$this_site->name , $list['reference'], $list['description']]));
        return $areas;

    }

    public function getAreaLocation($id, $survey_id, $decommissioned = 0) {

    }

    public function decommissionArea($area_id, $reason) {
        $area = Area::find($area_id);
        $locations =  Location::where('area_id', $area_id)->get();
        $items =  Item::where('area_id', $area_id)->get();
        try {
            if ($area->decommissioned == AREA_DECOMMISSION) {
                Area::where('id', $area_id)->update(['decommissioned' => AREA_UNDECOMMISSION,'decommissioned_reason' => $reason]);
                Location::where('area_id', $area_id)->update(['decommissioned' => LOCATION_UNDECOMMISSION,'decommissioned_reason' => $reason]);
                Item::where('area_id', $area_id)->update(['decommissioned' => ITEM_UNDECOMMISSION,'decommissioned_reason' => $reason]);

                // store comment history
                \CommentHistory::storeDeccomissionHistory('recommission','area', $area_id, $reason, $area->survey->reference ?? null);
                // log audit
                if (!is_null($locations)) {
                    foreach ($locations as $location) {
                       $comment = \Auth::user()->full_name . " Recommission Location ".$location->location_reference." by Recommission Area " . $area->area_reference;
                       \CommonHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_RECOMMISSION, $location->location_reference, $location->survey_id ,$comment, 0 ,$location->property_id);
                    }
                }
                if (!is_null($items)) {
                    foreach ($items as $item) {
                       $comment = \Auth::user()->full_name . " Recommission Item ".$item->reference." by Recommission Area " . $area->area_reference;
                       \CommonHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_RECOMMISSION, $item->reference, $item->survey_id ,$comment, 0 ,$item->property_id);
                    }
                }
                \CommonHelpers::logAudit(AREA_TYPE, $area->id, AUDIT_ACTION_RECOMMISSION, $area->area_reference, $area->survey_id, null , 0 , $area->project->property_id ?? 0);
                //update and send email
                if ($area->survey_id == 0) {
                    \CommonHelpers::isRegisterUpdated($area->property_id);
                } else {
                    \CommonHelpers::changeSurveyStatus($area->survey_id);
                }

                return \CommonHelpers::successResponse('Area/floor Recommissioned Successfully!');
            } else {
                Area::where('id', $area_id)->update(['decommissioned' => AREA_DECOMMISSION,'decommissioned_reason' => $reason]);
                Location::where('area_id', $area_id)->update(['decommissioned' => LOCATION_DECOMMISSION]);
                Item::where('area_id', $area_id)->update(['decommissioned' => ITEM_DECOMMISSION]);

                //update and send email
                if ($area->survey_id == 0) {
                    \CommonHelpers::isRegisterUpdated($area->property_id);
                } else {
                    \CommonHelpers::changeSurveyStatus($area->survey_id);
                }

                // store comment history
                \CommentHistory::storeDeccomissionHistory('decommission','area', $area_id, $reason, $area->survey->reference ?? null);
                // log audit
                    if (!is_null($locations)) {
                    foreach ($locations as $location) {
                       $comment = \Auth::user()->full_name . " Decommission Location ".$location->location_reference." by Decommission Area " . $area->area_reference;
                       \CommonHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_DECOMMISSION, $location->location_reference, $location->survey_id ,$comment, 0 ,$location->property_id);
                    }
                }
                if (!is_null($items)) {
                    foreach ($items as $item) {
                       $comment = \Auth::user()->full_name . " Decommission Item ".$item->reference." by Decommission Area " . $area->area_reference;
                       \CommonHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_DECOMMISSION, $item->reference, $item->survey_id ,$comment, 0 ,$item->property_id);
                    }
                }
                \CommonHelpers::logAudit(AREA_TYPE, $area->id, AUDIT_ACTION_DECOMMISSION, $area->area_reference, $area->survey_id, null , 0 , $area->property_id ?? 0);
                return \CommonHelpers::successResponse('Area/floor Decommissioned Successfully!');
            }
        } catch (\Exception $e) {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Update Area Fail!');
        }
    }

    public function searchArea($q, $survey_id = 0){
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

        return $this->model->whereRaw("(area_reference LIKE '%$q%' OR reference LIKE '%$q%')")
            ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
            ->where('survey_id','=',$survey_id)->where('decommissioned','=',0)
            ->orderBy('area_reference','asc')->limit(LIMIT_SEARCH)->get();
    }

    public function decommissionAreaReason($area_id, $reason) {
        try {
            Area::where('id', $area_id)->update(['decommissioned_reason' => $reason]);
            $area = Area::find($area_id);
            \CommonHelpers::logAudit(AREA_TYPE, $area_id, AUDIT_ACTION_DECOMMISSION_REASON, $area->area_reference, $area->survey_id, null , 0 , $area->property_id ?? 0);
            return \CommonHelpers::successResponse('Area/floor Reason Updated Successfully!');
        } catch (\Exception $e) {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,' Area/floor Reason Updated Fail!');
        }
    }

}
