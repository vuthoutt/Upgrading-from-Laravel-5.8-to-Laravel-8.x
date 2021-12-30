<?php

namespace App\Services\ShineCompliance;

use App\Models\ShineCompliance\Area;
use App\Repositories\ShineCompliance\AreaRepository;
use App\Repositories\ShineCompliance\LocationRepository;
use App\Repositories\ShineCompliance\ItemRepository;
use App\Services\ShineCompliance\ItemService;

class AreaService{

    private $areaRepository;

    public function __construct(AreaRepository $areaRepository,
                                LocationRepository $locationRepository,
                                ItemService $itemService,
                                ItemRepository $itemRepository){
        $this->areaRepository = $areaRepository;
        $this->locationRepository = $locationRepository;
        $this->itemRepository = $itemRepository;
        $this->itemService = $itemService;
    }

    public function getAllArea($property_id, $request){
        return $this->areaRepository->getAllArea($property_id, $request, $pagination = 9);
    }

    public function listRegisterAreaProperty($property_id, $relations = []){
        return $this->areaRepository->listRegisterAreaProperty($property_id, $relations);
    }

    public function decodeData($role_data){
        return json_decode(json_encode($role_data));;
    }
    public function updateArea($id, $data) {

        $area = Area::where('id', $id)->first();
        if (!is_null($area)) {
            try {
                \DB::beginTransaction();
                $data['updated_by'] = \Auth::user()->id;
                $areaUpdate = $area->update($data);

                // log audit
               \ComplianceHelpers::logAudit(AREA_TYPE, $area->id, AUDIT_ACTION_EDIT, $area->reference, $area->survey_id, null , 0 , $area->project->property_id ?? 0);
                if ($area->survey_id == 0) {
                    \CommonHelpers::isRegisterUpdated($area->property_id);
                } else {
                    \CommonHelpers::changeSurveyStatus($area->survey_id);
                }
                \DB::commit();
                return $response = \CommonHelpers::successResponse('Area/Floor Updated  Successfully!', $id);
            } catch (\Exception $e) {
                \Log::debug($e);
                \DB::rollBack();
                return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to Updated Area/floor. Please try again!');
            }
        } else {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Can not find area. Please try again!');
        }
    }

    public function createArea($data) {

        try {
            \DB::beginTransaction();
            $area = Area::create($data);
            if ($area) {
                $refArea = "AF" . $area->id;
                Area::where('id', $area->id)->update(['record_id' => $area->id, 'reference' => $refArea, 'created_by' => \Auth::user()->id]);
                // log audit
               \ComplianceHelpers::logAudit(AREA_TYPE, $area->id, AUDIT_ACTION_ADD, $refArea, $area->survey_id, null , 0 , $area->property_id ?? 0);

                if ($area->survey_id == 0) {
                    \CommonHelpers::isRegisterUpdated($area->property_id);
                } else {
                    \CommonHelpers::changeSurveyStatus($area->survey_id);
                }
            }
            \DB::commit();
            return $response = \CommonHelpers::successResponse('Area/Floors Created Successfully!', $area->id);
        } catch (\Exception $e) {
            \Log::debug($e);
            \DB::rollBack();
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to Create Area/floor. Please try again!');
        }

    }

    public function getDropDownReasonArea(){
        return $this->areaRepository->getDropDownReasonArea();
    }


    public function getAreaDetail($area_id,$relation = []){
        return $this->areaRepository->getAreaDetail($area_id,$relation);
    }

    public function decommissionArea($area_id, $reason)
    {
        $area = $this->areaRepository->getFindArea($area_id);
        $locations = $this->locationRepository->getLocationArea($area_id);
        $items = $this->itemRepository->getItemArea($area_id);
        try {
            \DB::beginTransaction();
            if ($area->decommissioned == AREA_DECOMMISSION) {
                $area_de = $this->areaRepository->recommissionArea($area_id, $reason);
                $locations_de = $this->locationRepository->recommissionArea($area_id, $reason);
                $items_de = $this->itemRepository->recommissionArea($area_id, $reason);
                // store comment history
                \CommentHistory::storeDeccomissionHistory('recommission', 'area', $area_id, $reason, $area->survey->reference ?? null);
                // log audit
                if (!is_null($locations)) {
                    foreach ($locations as $location) {
                        $comment = \Auth::user()->full_name . " Recommission Location ".$location->reference." by Recommission Area " . $area->reference;
                        \ComplianceHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_RECOMMISSION, $location->reference, $location->survey_id ,$comment, 0 ,$location->property_id);
                    }
                }
                if (!is_null($items)) {
                    foreach ($items as $item) {
                        $comment = \Auth::user()->full_name . " Recommission Item ".$item->reference." by Recommission Area " . $area->reference;
                        \ComplianceHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_RECOMMISSION, $item->reference, $item->survey_id ,$comment, 0 ,$item->property_id);
                    }
                }
                \ComplianceHelpers::logAudit(AREA_TYPE, $area->id, AUDIT_ACTION_RECOMMISSION, $area->reference, $area->survey_id, null , 0 , $area->project->property_id ?? 0);


                $response = \CommonHelpers::successResponse('Area/floor Recommissioned Successfully!');
            } else {
                $area_de = $this->areaRepository->decommissionArea($area_id, $reason);
                $locations_de = $this->locationRepository->decommissionArea($area_id);
                $items_de = $this->itemRepository->decommissionArea($area_id);


                // store comment history
                \CommentHistory::storeDeccomissionHistory('decommission', 'area', $area_id, $reason, $area->survey->reference ?? null);
                // log audit
                if (!is_null($locations)) {
                    foreach ($locations as $location) {
                        $comment = \Auth::user()->full_name . " Decommission Location ".$location->reference." by Decommission Area " . $area->reference;
                        \ComplianceHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_DECOMMISSION, $location->reference, $location->survey_id ,$comment, 0 ,$location->property_id);
                    }
                }
                if (!is_null($items)) {
                    foreach ($items as $item) {
                        $comment = \Auth::user()->full_name . " Decommission Item ".$item->reference." by Decommission Area " . $area->reference;
                        \ComplianceHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_DECOMMISSION, $item->reference, $item->survey_id ,$comment, 0 ,$item->property_id);
                    }
                }
                \ComplianceHelpers::logAudit(AREA_TYPE, $area->id, AUDIT_ACTION_DECOMMISSION, $area->reference, $area->survey_id, null , 0 , $area->property_id ?? 0);
                $response = \CommonHelpers::successResponse('Area/floor Decommissioned Successfully!');
            }
            \DB::commit();
            return $response;
        } catch (\Exception $e) {
            \Log::debug($e);
            \DB::rollBack();
            return $response = \CommonHelpers::failResponse(STATUS_FAIL, 'Failed to Decommission/Recommission Area/floor. Please try again!');
        }
    }

    public function findArea($id){
        return $this->areaRepository->find($id);
    }

    public function getAssessmentArea($assess_id, $property_id,$relations = []) {
        return $this->areaRepository->getAssessmentArea($assess_id, $property_id, $relations);
    }

    public function getRegisterAssessmentArea($property_id, $assess_id) {
        return $this->areaRepository->getRegisterAssessmentArea($property_id, $assess_id);
    }

    public function getPropertyArea($id, $decommissioned = 0) {
        $areas = $this->areaRepository->getPropertyArea($id,$decommissioned);
        if (!is_null($areas)) {
            foreach ($areas as $value) {
                $value->area_reference = strip_tags($value->area_reference);
                $value->description = strip_tags($value->description);
            }
        }

        return is_null($areas) ? [] : $areas;
    }

    public function getAreaPaginationCustomize($survey_id, $decommissioned, $property_id) {
        $area = $this->areaRepository->getAreaPaginationCustomize($survey_id, $decommissioned = 0, $property_id,$other_condition = TRUE);
        return !$area->isEmpty() ? $area : [];
    }

    public function getSurveyArea($survey_id,$decommissioned = 0) {
        $area = $this->areaRepository->getSurveyArea($survey_id, $decommissioned);
        return !$area->isEmpty() ? $area : [];
    }

    public function getAreaSurveyDetail($area_id,$survey,$property_id,$request){
        $pagination = [];
        $survey_id = $survey->id ?? "";
        $areaData = $this->getAreaDetail($area_id,'decommissionedReason');
        if (!$areaData) {
            abort(404);
        }
        //check privilege
        if (\CommonHelpers::isSystemClient()) {
            // property permission and register tab permission
            // if (!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $areaData->property_id) || !\CompliancePrivilege::checkPermission(SURVEYS_PROP_VIEW_PRIV)) {
            //     abort(404);
            // }
        }
        // only pagination for some case
        if(isset($request->position)){
            $list_areas = $this->getAreaPaginationCustomize($survey_id, $areaData->decommissioned, $property_id);
            //set path
            $pagination = \CommonHelpers::setPathPagination($request, $list_areas, 'area', $areaData->id);
        }

        $dataTab = $this->locationRepository->getAreaLocation($area_id, $survey_id);
        $dataDecommisstionTab = $this->locationRepository->getAreaLocation($area_id, $survey_id, 1);
        $items = $this->itemRepository->with('area', 'location','itemInfo','productDebrisView','decommissionedReason')->where('property_id', $areaData->property_id)->where('survey_id', $survey_id)->where('area_id', $area_id)->get();

        $dataSummary = $this->itemService->getRegisterSurveySummary($items,'surveyarea', $property_id, $areaData->survey_id , $area_id);

        $dataDecommisstionItems = $items->where('decommissioned', ITEM_DECOMMISSION)->all();
        $breadcrumb_name = 'survey_area_asbestos';
        $breadcrumb_data = $areaData;

        //log audit
        $comment = \Auth::user()->full_name  . " viewed Survey Area/Floor "  . $areaData->reference .' on ' . $survey->reference . ' on ' . $survey->property->name;
        \CommonHelpers::logAudit(AREA_TYPE, $areaData->id, AUDIT_ACTION_VIEW, $areaData->reference, $areaData->survey_id ,$comment, 0 ,$areaData->property_id);
        $data = [
            'items' => $items,
            'areaData' => $areaData,
            'pagination' => $pagination,
            'dataTab' => $dataTab,
            'dataDecommisstionTab' => $dataDecommisstionTab,
            'dataSummary' => $dataSummary,
            'dataDecommisstionItems' => $dataDecommisstionItems,
            'breadcrumb_name' => $breadcrumb_name,
            'breadcrumb_data' => $breadcrumb_data
        ];
        return $data;
    }
}
