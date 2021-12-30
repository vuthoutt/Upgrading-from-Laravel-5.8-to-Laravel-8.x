<?php
namespace App\Repositories;
use App\Models\Item;
use App\Models\Property;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Location;
use App\Models\LocationInfo;
use App\Models\LocationVoid;
use App\Models\Survey;
use App\Models\Zone;
use App\Models\LocationConstruction;
use App\Models\DropdownLocation;
use App\Models\DropdownDataLocation;

class LocationRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Location::class;
    }

    public function getAreaLocation($area_id, $survey_id, $decommissioned = 0) {
        $locations = Location::with('allItems')->where(['area_id' => $area_id,'survey_id' => $survey_id, 'decommissioned' => $decommissioned])->get();
        return !is_null($locations) ? $locations : [];
    }

    public function getLocationDropdown($ids) {
        $locationDropdown= DropdownLocation::whereIn('id', $ids)->get();
        return !is_null($locationDropdown) ? $locationDropdown : [];
    }

    public function getDropdownById($id, $parent_id = 0) {
        $dropdowns = DropdownDataLocation::with('allChildrenAccounts')->where('dropdown_location_id', $id)
        ->where('parent_id', $parent_id)->where('decommissioned',0)->get();
        return !is_null($dropdowns) ? $dropdowns : [];
    }

    public function getLocation($id) {
        $location = Location::with('locationInfo', 'locationVoid', 'locationConstruction','decommissionedReason')->where('id', $id)->first();
        return !is_null($location) ? $location : [];
    }

    public function getLocationPaginationCustomize($survey_id, $decommissioned , $property_id, $area_id, $group_id ,$client_id = NULL, $type = NULL, $pagination_type = NULL) {
//        dd($survey_id, $decommissioned , $property_id, $area_id, $group_id ,$client_id , $type , $pagination_type );
        $list_condition = $this->getPaginationCondition($survey_id, $decommissioned , $property_id, $area_id, $group_id, $client_id, $type, $pagination_type);
//        dd($list_condition);
        $condition = count($list_condition['condition']) > 0 ? $list_condition['condition'] : TRUE;
        $other_condition = $list_condition['other_condition'] ? $list_condition['other_condition'] : TRUE ;
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();
        $location = Location::with('locationInfo', 'locationVoid', 'locationConstruction','decommissionedReason')
            ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
            ->where($condition)->whereRaw($other_condition)->get();
        return !$location->isEmpty() ? $location : [];
    }

    public function getLocationData($id) {
        $location = Location::with('locationInfo', 'locationVoid', 'locationConstruction','allItemACM', 'countItemACM')->where('id', $id)->first();
        return !is_null($location) ? $location : [];
    }

    private function getPaginationCondition($survey_id, $decommissioned , $property_id, $area_id, $group_id ,$client_id = NULL, $type = NULL, $pagination_type = NULL){
        $condition = [];
        $other_condition = '';
        if($type && $type == TYPE_INACCESS_ROOM_SUMMARY && $pagination_type){
            //common condition
            $condition['state'] = LOCATION_STATE_INACCESSIBLE;
            $condition['decommissioned'] = LOCATION_UNDECOMMISSION;
            switch ($pagination_type){
                case TYPE_CLIENT:
                    // $zone_ids = Zone::where(['client_id' => $client_id, 'parent_id' => 0])->get()->pluck('id')->toArray();
                    // // property privilege
                    // if (\CommonHelpers::isSystemClient()) {
                    //     $property_id_privs =  \CompliancePrivilege::getPermission(PROPERTY_PERMISSION);
                    // } else {
                    //     $property_id_privs =    \CompliancePrivilege::getPropertyContractorPermission();
                    // }
                    // $client_property_ids = Property::where(['client_id' => $client_id, 'decommissioned' => 0])
                    //     ->whereIn('zone_id', $zone_ids)
                    //     ->whereIn('id', $property_id_privs)
                    //     ->pluck('id')->toArray();

                    // if (count($client_property_ids)) {
                    //     $property_ids = implode(" ,",$client_property_ids);
                    //     $other_condition = "property_id IN($property_ids)";
                    // }
                    $condition['survey_id'] =  0;
                    break;
                case TYPE_ZONE:
                    // property privilege
                    // if (\CommonHelpers::isSystemClient()) {
                    //     $property_id_privs =  \CompliancePrivilege::getPermission(PROPERTY_PERMISSION);
                    // } else {
                    //     $property_id_privs =    \CompliancePrivilege::getPropertyContractorPermission();
                    // }

                    // $client_property_group = Property::with('propertyInfo')
                    //     ->where(['decommissioned' => 0, 'zone_id'=> $group_id])
                    //     ->whereIn('id', $property_id_privs)->pluck('id')->toArray();

                    // if (count($client_property_group)) {
                    //     $property_ids = implode(" ,",$client_property_group);
                    //     $other_condition = "property_id IN($property_ids)";
                    // }
                    $condition['survey_id'] =  0;
                    break;
                case TYPE_REPORT:
                    $condition['survey_id'] =  $survey_id;
                    break;
                case TYPE_PROPERTY:
                    $condition['survey_id'] =  0;
                    $condition['property_id'] =  $property_id;
                    break;
                case TYPE_AREA:
                    $condition['survey_id'] =  $survey_id;
                    $condition['area_id'] =  $area_id;
                    break;
            }
        } else {
            //normal
            if(isset($location_id)){
                $condition['location_id'] = $location_id;
            }
            if(isset($decommissioned)){
                $condition['decommissioned'] =  $decommissioned;
            }
            if(isset($survey_id)){
                $condition['survey_id'] =  $survey_id;
            }
            if(isset($property_id)){
                $condition['property_id'] =  $property_id;
            }
            if(isset($area_id)){
                $condition['area_id'] =  $area_id;
            }
        }
//        dd($condition, $other_condition);
        return ['condition' => $condition, 'other_condition' => $other_condition];
    }


    public function createLocation($data, $id = null) {
        if (!empty($data)) {
            // location void data process
            $ceilingVoid = isset($data['Ceiling-Void']) ? $data['Ceiling-Void'] : null;
            $ceilingVoidType = isset($data['Ceiling-Void-type']) ? $data['Ceiling-Void-type'] : null;
            $constructionCeilingVoid = \CommonHelpers::getMultiselectDataVoid($ceilingVoid , $ceilingVoidType);

            $cavities = isset($data['Cavities']) ? $data['Cavities'] : null;
            $cavitiesType = isset($data['Cavities-type']) ? $data['Cavities-type'] : null;
            $constructionCavities = \CommonHelpers::getMultiselectDataVoid($cavities , $cavitiesType);

            $risers = isset($data['Risers']) ? $data['Risers'] : null;
            $risersType = isset($data['Risers-type']) ? $data['Risers-type'] : null;
            $constructionRisers = \CommonHelpers::getMultiselectDataVoid($risers , $risersType);

            $ducting = isset($data['Ducting']) ? $data['Ducting'] : null;
            $ductingType = isset($data['Ducting-type']) ? $data['Ducting-type'] : null;
            $constructionDucting = \CommonHelpers::getMultiselectDataVoid($ducting , $ductingType);

            $floorVoid = isset($data['Floor-Void']) ? $data['Floor-Void'] : null;
            $floorVoidType = isset($data['Floor-Void-type']) ? $data['Floor-Void-type'] : null;
            $constructionFloorVoid = \CommonHelpers::getMultiselectDataVoid($floorVoid , $floorVoidType);

            $pipeWork = isset($data['Pipework']) ? $data['Pipework'] : null;
            $pipeWorkType = isset($data['Pipework-type']) ? $data['Pipework-type'] : null;
            $constructionPipework = \CommonHelpers::getMultiselectDataVoid($pipeWork , $pipeWorkType);

            $boxing = isset($data['Boxing']) ? $data['Boxing'] : null;
            $boxingType = isset($data['Boxing-type']) ? $data['Boxing-type'] : null;
            $constructionBoxing = \CommonHelpers::getMultiselectDataVoid($boxing , $boxingType);
            $reason_na  = \CommonHelpers::checkArrayKey($data,'reasons-na') ?? [];
            $survey = Survey::with('surveySetting')->where('id', \CommonHelpers::checkArrayKey($data,'survey_id'))->first();
            $dataLocation = [
                'area_id'                      => \CommonHelpers::checkArrayKey($data,'area_id'),
                'survey_id'                    => \CommonHelpers::checkArrayKey($data,'survey_id'),
                'property_id'                  => \CommonHelpers::checkArrayKey($data,'property_id'),
                'is_locked'                    => 0,
                'state'                        => \CommonHelpers::checkArrayKey($data,'location-state'),
                'version'                      => 1,
                'description'                  => \CommonHelpers::checkArrayKey($data,'description'),
                'location_reference'           => \CommonHelpers::checkArrayKey($data,'reference'),
                'not_assessed'                  => \CommonHelpers::checkArrayKey($data,'not_assessed'),
                'not_assessed_reason'                  => \CommonHelpers::checkArrayKey($data,'not_assessed_reason'),
            ];

            $dataLocationInfo = [
                'reason_inaccess_key'          => end($reason_na),
                'reason_inaccess_other'        => \CommonHelpers::checkArrayKey($data,'reasons-na-other'),
                'comments'                     => \CommonHelpers::checkArrayKey($data,'location-comment'),
            ];

            $dataLocationVoid = [];
            if (empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_location_void_investigations == ACTIVE) ) {
                $dataLocationVoid = [
                    'ceiling'      => $constructionCeilingVoid,
                    'ceiling_other' => \CommonHelpers::getLocationOther($data,'Ceiling-Void-type-other'),
                    'cavities'         => $constructionCavities,
                    'cavities_other'    => \CommonHelpers::getLocationOther($data,'Cavities-type-other'),
                    'risers'           => $constructionRisers,
                    'risers_other'      => \CommonHelpers::getLocationOther($data,'Risers-type-other'),
                    'ducting'          => $constructionDucting,
                    'ducting_other'     => \CommonHelpers::getLocationOther($data,'Ducting-type-other'),
                    'boxing'           => $constructionBoxing,
                    'boxing_other'      => \CommonHelpers::getLocationOther($data,'Boxing-type-other'),
                    'pipework'         => $constructionPipework,
                    'pipework_other'    => \CommonHelpers::getLocationOther($data,'Pipework-type-other'),
                    'floor'        => $constructionFloorVoid,
                    'floor_other'   => \CommonHelpers::getLocationOther($data,'Floor-Void-type-other'),
                ];
            }
            $dataLocationContruction = [];
            if (empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_location_construction_details == ACTIVE) ) {
                $dataLocationContruction = [
                    'ceiling'          => \CommonHelpers::getMultiselectDataContruction(isset($data['Ceiling']) ? $data['Ceiling'] : null),
                    'ceiling_other'     => \CommonHelpers::checkArrayKey($data,'Ceiling-other'),
                    'walls'            => \CommonHelpers::getMultiselectDataContruction(isset($data['Walls']) ? $data['Walls'] : null),
                    'walls_other'       => \CommonHelpers::checkArrayKey($data,'Walls-other'),
                    'doors'            => \CommonHelpers::getMultiselectDataContruction(isset($data['Doors']) ? $data['Doors'] : null),
                    'doors_other'       => \CommonHelpers::checkArrayKey($data,'Doors-other'),
                    'floor'            => \CommonHelpers::getMultiselectDataContruction(isset($data['Floor']) ? $data['Floor'] : null),
                    'floor_other'       => \CommonHelpers::checkArrayKey($data,'Floor-other'),
                    'windows'          => \CommonHelpers::getMultiselectDataContruction(isset($data['Windows']) ? $data['Windows'] : null),
                    'windows_other'     => \CommonHelpers::checkArrayKey($data,'Windows-other'),
                ];
            }
            try {
                if (is_null($id)) {
                    $dataLocation['decommissioned'] = 0;
                    $dataLocation['created_by'] = \Auth::user()->id;
                    $location = Location::create($dataLocation);
                    if ($location) {
                       $refLocation = "RL" . $location->id;
                       Location::where('id', $location->id)->update(['record_id' => $location->id, 'reference' => $refLocation]);
                       if (isset($data['location-photo'])) {
                            $saveLocationImage = \CommonHelpers::saveFileShineDocumentStorage($data['location-photo'], $location->id, LOCATION_IMAGE);
                       }
                       $dataLocationInfo['location_id'] = $location->id;
                       $dataLocationVoid['location_id'] = $location->id;
                       $dataLocationContruction['location_id'] = $location->id;
                       $locationInfox = LocationInfo::create($dataLocationInfo);
                       LocationVoid::create($dataLocationVoid);
                       LocationConstruction::create($dataLocationContruction);
                    }

                    //update and send email
                    if ($location->survey_id == 0) {
                        \CommonHelpers::isRegisterUpdated($location->property_id);
                    } else {
                        \CommonHelpers::changeSurveyStatus($location->survey_id);
                    }
                    //log audit
                    $comment_audit = \Auth::user()->full_name  . " added New Room/Location " . $refLocation .(isset($location->survey->reference) ? 'on '.$location->survey->reference : ''). ' on ' . ($location->property->name ?? '');
                    \CommonHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_ADD, $refLocation, $location->survey_id ,$comment_audit, 0 ,$location->property_id);
                    // store comment history
                    \CommentHistory::storeCommentHistory('location', $location->id, $dataLocationInfo['comments']);
                    return $response = \CommonHelpers::successResponse('Room/location Added Successfully!', $location);
                } else {
                    $dataLocation['updated_by'] = \Auth::user()->id;
                    $locationUpdate = Location::where('id', $id)->update($dataLocation);
                    if (isset($data['location-photo'])) {
                        $saveLocationImage = \CommonHelpers::saveFileShineDocumentStorage($data['location-photo'], $id, LOCATION_IMAGE);
                    }
                    LocationInfo::where('location_id', $id)->update($dataLocationInfo);
                    LocationVoid::where('location_id', $id)->update($dataLocationVoid);
                    LocationConstruction::where('location_id', $id)->update($dataLocationContruction);
                    $location =  Location::find($id);

                    //update and send email
                    if ($location->survey_id == 0) {
                        \CommonHelpers::isRegisterUpdated($location->property_id);
                    } else {
                        \CommonHelpers::changeSurveyStatus($location->survey_id);
                    }

                    // store comment history
                    \CommentHistory::storeCommentHistory('location', $location->id, $dataLocationInfo['comments']);

                    //log audit
                    $comment_audit = \Auth::user()->full_name  . " edited Room/Location " . $location->location_reference .(isset($location->survey->reference) ? 'on '.$location->survey->reference : ''). ' on ' . ($location->property->name ?? '');
                    \CommonHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_EDIT, $location->location_reference, $location->survey_id ,$comment_audit, 0 ,$location->property_id);
                    return $response = \CommonHelpers::successResponse('Room/location Updated Successfully!', $data['survey_id']);
                }
            } catch (\Exception $e) {
                return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to create location. Please try again!');
            }
        }
    }

    public function getLocationVoidId($data, $type = 'child') {
        if (!is_null($data) and $data !== '') {
            $data = explode(",",$data);
            if ($type == 'parent') {
                return $data[0];
            } elseif($type == 'child') {
                if (count($data) > 1) {
                    $tmp = array_shift($data);
                    return $data;
                }
                return '';
            }
        } else {
            return '';
        }
    }

    public function getLocationContructionId($data) {
        if (!is_null($data) and $data !== '') {
            $data = explode(",",$data);
            return $data;
        } else {
            return '';
        }
    }

    public function getLocationOperative($area, $property, $risk_type_one, $risk_type_two)
    {
        $locations = Location::with('allItemACM', 'countItemACM')
            ->where(['decommissioned'=> 0, 'area_id' => $area->id, 'survey_id' => 0])->orderBy('location_reference')->orderBy('description')
//            ->where('id',4851)
            ->get();
//        dd($locations);
        if(count($locations)){
            foreach ($locations as $location){
//                dd($location->allItemACM->count(), $location->allItemACM);
                if(!$risk_type_two){
                    if($location->allItemACM->count() > 0){
                        $location->location_operative = ['bg_color'=>'#f2dede','border_color'=>'#eed3d7','text_color'=>'#b94a48','text'=>'Asbestos Present'];
                    } else {
                        $first_inacc_void_location = DB::select("SELECT lv.* from tbl_location_void lv
                                                            JOIN tbl_location l ON lv.location_id = l.id
                                                            WHERE l.id = $location->id AND l.decommissioned = 0 AND l.survey_id = 0 AND
                                                            (
                                                                FIND_IN_SET(1108, lv.ceiling)
                                                                OR FIND_IN_SET(1453, lv.floor)
                                                                OR FIND_IN_SET(1216, lv.cavities)
                                                                OR FIND_IN_SET(1280, lv.risers)
                                                                OR FIND_IN_SET(1344, lv.ducting)
                                                                OR FIND_IN_SET(1733, lv.boxing)
                                                                OR FIND_IN_SET(1606, lv.pipework)
                                                            ) LIMIT 1");
                        if($location->state == LOCATION_STATE_INACCESSIBLE || $first_inacc_void_location || !$location->allInaccItemACM->isEmpty()){
                            $location->location_operative = ['bg_color'=>'#f2dede','border_color'=>'#eed3d7','text_color'=>'#b94a48','text'=>'Asbestos Presumed'];
                        } else {
                            $location->location_operative = ['bg_color'=>'#dff0d8','border_color'=>'#d6e9c6','text_color'=>'#468847','text'=>'No Asbestos Detected'];
                        }
                    }
                } else {
                    $location->location_operative = ['bg_color'=>'#dff0d8','border_color'=>'#d6e9c6','text_color'=>'#468847','text'=>'No Risk'];
                }
            }

        }
        return $locations;
    }

    public function getItemNoAcmSurvey($location_record_id){
        $return = [];
        $location_survey = Location::with('survey')
            ->where(['decommissioned'=> 0, 'record_id' => $location_record_id])
            ->where('survey_id', '>', 0)
//            ->where('id',4851)
            ->get();
        $arr_loction_ids = [];
        if(!$location_survey->isEmpty()){
            foreach ($location_survey as $location ){
                if(isset($location->survey) && !is_null($location->survey) && $location->survey->status == COMPLETED_SURVEY_STATUS){
                    $arr_loction_ids[] = $location->id;
                }
            }
        }

        if(count($arr_loction_ids) > 0){
            return Item::whereIn('location_id',$arr_loction_ids)->where(['decommissioned' => ITEM_UNDECOMMISSION,'state' => ITEM_NOACM_STATE])->get();
        }
        return [];
    }

    public function decommissionLocation($location_id, $reason) {
        $location = Location::find($location_id);
        $items =  Item::where('location_id', $location_id)->get();
        try {
            if ($location->decommissioned == LOCATION_DECOMMISSION) {
                Location::where('id', $location_id)->update(['decommissioned' => LOCATION_UNDECOMMISSION,'decommissioned_reason' => $reason]);
                Item::where('location_id', $location_id)->update(['decommissioned' => ITEM_UNDECOMMISSION,'decommissioned_reason' => $reason]);
                //update and send email
                if ($location->survey_id == 0) {
                    \CommonHelpers::isRegisterUpdated($location->property_id);
                } else {
                    \CommonHelpers::changeSurveyStatus($location->survey_id);
                }

                // store comment history
                \CommentHistory::storeDeccomissionHistory('recommission','location', $location_id, $reason, $location->survey->reference ?? null);

                //log audit
                if (!is_null($items)) {
                    foreach ($items as $item) {
                       $comment = \Auth::user()->full_name . " Recommission Item ".$item->reference." by Recommission Location " . $location->location_reference;
                       \CommonHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_RECOMMISSION, $item->reference, $item->survey_id ,$comment, 0 ,$item->property_id);
                    }
                }
                \CommonHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_RECOMMISSION, $location->location_reference, $location->survey_id ,null, 0 ,$location->property_id);
                return \CommonHelpers::successResponse('Room/location Recommissioned  Successfully!');
            } else {
                Location::where('id', $location_id)->update(['decommissioned' => LOCATION_DECOMMISSION,'decommissioned_reason' => $reason]);
                Item::where('location_id', $location_id)->update(['decommissioned' => ITEM_DECOMMISSION]);
                //update and send email
                if ($location->survey_id == 0) {
                    \CommonHelpers::isRegisterUpdated($location->property_id);
                } else {
                    \CommonHelpers::changeSurveyStatus($location->survey_id);
                }

                // store comment history
                \CommentHistory::storeDeccomissionHistory('decommission','location', $location_id, $reason, $location->survey->reference ?? null);
                //log audit
                //log audit
                if (!is_null($items)) {
                    foreach ($items as $item) {
                       $comment = \Auth::user()->full_name . " Decommission Item ".$item->reference." by Decommission Location " . $location->location_reference;
                       \CommonHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_DECOMMISSION, $item->reference, $item->survey_id ,$comment, 0 ,$item->property_id);
                    }
                }
                \CommonHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_DECOMMISSION, $location->location_reference, $location->survey_id ,null, 0 ,$location->property_id);
                return \CommonHelpers::successResponse('Room/location Decommissioned Successfully!');
            }
        } catch (\Exception $e) {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Update Location Fail!');
        }
    }

    public function decommissionLocationReason($location_id, $reason) {
        try {
            Location::where('id', $location_id)->update(['decommissioned_reason' => $reason]);
            $location = Location::find($location_id);
            \CommonHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_DECOMMISSION_REASON, $location->location_reference, $location->survey_id ,null, 0 ,$location->property_id);
            return \CommonHelpers::successResponse('Location Decommission Reason Updated Successfully!');
        } catch (\Exception $e) {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,' Location Decommission Reason Updated Fail!');
        }
    }

    public function getInaccessibleLocations($type, $id){
        switch ($type){
            case PROPERTY_REGISTER_PDF:
                $condition = ['property_id' => $id];
                break;
            case AREA_REGISTER_PDF:
                $condition = ['area_id' => $id];
                break;
            case LOCATION_REGISTER_PDF:
                $condition = ['id' => $id];
                break;
            default:
                $condition = ['property_id' => $id];
                break;
        }
        $data = Location::with('property',
            'area',
            'locationInfo')->where($condition)->where(['survey_id'=>0,'state'=>LOCATION_STATE_INACCESSIBLE, 'decommissioned' => 0])->orderBy('location_reference')->get();
        return $this->sortLocationRegister($data);
    }

    public function sortLocationRegister($data){
        $arr_tem = $list = [];
        foreach ($data as $l){
            $area_key = explode(" ", $l->area->area_reference ?? '')[0] . " " . $l->area->id;
            $location_key = explode(" ", $l->location_reference ?? '')[0] . " " . $l->id;
            $arr_tem[$area_key][$location_key] = $l;
        }

        if(count($arr_tem)){
            ksort($arr_tem);
            foreach ($arr_tem as $arr1) {
                ksort($arr1);
                if(count($arr1)){
                    foreach ($arr1 as $loc){
                        $list[] = $loc;
                    }
                }
            }
        }


        if ($list) {
            return $list;
        } else {
            return [];
        }
    }

    public function searchLocation($q, $survey_id = 0){
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

        return $this->model->whereRaw("(location_reference LIKE '%$q%' OR reference LIKE '%$q%')")
                    ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
                    ->where('survey_id','=',$survey_id)
                    ->where('decommissioned','=',0)->orderBy('location_reference','asc')
                    ->limit(LIMIT_SEARCH)->get();
    }

    public function hasInaccessibleVoid($location){
        if($location){
            if(in_array(1108,explode(",",$location->locationVoid->ceiling)) ||
                in_array(1453,explode(",",$location->locationVoid->floor)) ||
                in_array(1216,explode(",",$location->locationVoid->cavities)) ||
                in_array(1280,explode(",",$location->locationVoid->risers)) ||
                in_array(1344,explode(",",$location->locationVoid->ducting)) ||
                in_array(1733,explode(",",$location->locationVoid->boxing)) ||
                in_array(1606,explode(",",$location->locationVoid->pipework))
            ){
                return true;
            }
        }
        return false;
    }

    public function getViewOnlyNoRiskItems($location)
    {
        if ($location->id != $location->record_id) {
            $registerLocation = Location::where('record_id', $location->record_id)->where('survey_id', 0)
                                        ->where('assess_id', 0)->first();
            if ($registerLocation) {
                return Item::where('location_id', $registerLocation->id)->where('state', ITEM_NOACM_STATE)
                                            ->whereNotIn('record_id', function($query) use ($location){
                                                $query->select('record_id')->from('tbl_items')->where('location_id', $location->id);
                                            })->get();
            }
        }

        return [];
    }
}
