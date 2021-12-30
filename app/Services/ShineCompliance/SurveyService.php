<?php

namespace App\Services\ShineCompliance;

use App\Helpers\CommonHelpers;
use App\Jobs\SendSampleEmail;
use App\Models\Survey;
use App\Repositories\ShineCompliance\SurveyRepository;
use App\Repositories\ShineCompliance\PropertyRepository;
use App\Repositories\ShineCompliance\SurveyDateRepository;
use App\Repositories\ShineCompliance\SurveyInfoRepository;
use App\Repositories\ShineCompliance\SurveySettingRepository;
use App\Repositories\ShineCompliance\ItemRepository;
use App\Repositories\ShineCompliance\AreaRepository;
use App\Repositories\ShineCompliance\LocationRepository;
use App\Repositories\ShineCompliance\SitePlanDocumentRepository;
use App\Repositories\ShineCompliance\PublishedSurveyRepository;
use App\Repositories\ShineCompliance\SampleCertificateRepository;
use App\Repositories\ShineCompliance\PublishedWorkRequestRepository;
use App\Repositories\ShineCompliance\SurveyAnswerRepository;
use App\Repositories\ShineCompliance\SampleRepository;
use Illuminate\Support\Collection;
use App\Models\Project;
use Carbon\Carbon;

class SurveyService{

    private $surveyRepository;
    private $propertyRepository;
    private $surveyDateRepository;
    private $surveySettingRepository;
    private $surveyInfoRepository;
    private $itemRepository;
    private $areaRepository;
    private $locationRepository;
    private $sitePlanDocumentRepository;
    private $publishedSurveyRepository;
    private $sampleCertificateRepository;
    private $publishedWorkRequestRepository;
    private $sampleRepository;
    private $surveyAnswerRepository;

    public function __construct(SurveyRepository $surveyRepository,
                                PropertyRepository $propertyRepository,
                                SurveyDateRepository $surveyDateRepository,
                                SurveyInfoRepository $surveyInfoRepository,
                                SurveySettingRepository $surveySettingRepository,
                                ItemRepository $itemRepository,
                                AreaRepository $areaRepository,
                                LocationRepository $locationRepository,
                                SitePlanDocumentRepository $sitePlanDocumentRepository,
                                PublishedSurveyRepository $publishedSurveyRepository,
                                SampleCertificateRepository $sampleCertificateRepository,
                                PublishedWorkRequestRepository $publishedWorkRequestRepository,
                                SurveyAnswerRepository $surveyAnswerRepository,
                                SampleRepository $sampleRepository
    ){
        $this->surveyRepository = $surveyRepository;
        $this->propertyRepository = $propertyRepository;
        $this->surveyDateRepository = $surveyDateRepository;
        $this->surveyInfoRepository = $surveyInfoRepository;
        $this->surveySettingRepository = $surveySettingRepository;
        $this->itemRepository = $itemRepository;
        $this->areaRepository = $areaRepository;
        $this->locationRepository = $locationRepository;
        $this->sitePlanDocumentRepository = $sitePlanDocumentRepository;
        $this->publishedSurveyRepository = $publishedSurveyRepository;
        $this->sampleCertificateRepository = $sampleCertificateRepository;
        $this->publishedWorkRequestRepository = $publishedWorkRequestRepository;
        $this->sampleRepository = $sampleRepository;
        $this->surveyAnswerRepository = $surveyAnswerRepository;
    }

    public function getAllSurveyByProperty($property_id,$decommission = 0){
        return $this->surveyRepository->getAllSurveyByProperty($property_id,$decommission);
    }

    public function getFindSurvey($survey_id){
        return $this->surveyRepository->getFindSurvey($survey_id);
    }
    public function getSurvey($survey_id){
        return $this->surveyRepository->getSurvey($survey_id);
    }

    public function updateSurvey($survey_id,$data){
        $survey = $this->surveyRepository->getFindSurvey($survey_id);
        if (!is_null($survey)) {
            try {
                $surveyUpdate = $survey->update($data);
                return CommonHelpers::successResponse('Survey Updated Successfully!',$surveyUpdate);
            } catch (\Exception $e) {
                return CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Failed to update! Please try again');
            }
        } else {
            return CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Survey not found!');
        }
    }

    public function updateSurveyDate($survey_id,$data = []){
            $surveyDate = $this->surveyDateRepository->where('survey_id', $survey_id);
            if (!is_null($surveyDate)) {
                try {
                    $surveyUpdate = $surveyDate->update($data);
                    return CommonHelpers::successResponse('Survey Updated Successfully!',$surveyUpdate);
                } catch (\Exception $e) {
                    return CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Failed to update! Please try again');
                }
            } else {
                return CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Survey not found!');
            }
        }

    public function getSurveyPublish($relation = [],$survey_id){
        return $this->surveyRepository->getSurveyPublish($relation,$survey_id);
    }

    public function getApprovalSurvey($user_id = null)
    {
        return $this->surveyRepository->getApprovalSurvey($user_id);
    }

    public function getPropertyFromSurvey($survey_id){
        return $this->surveyRepository->getPropertyFromSurvey($survey_id);
    }

    public function getSitePlanDocumentbySurvey($property_id,$category){
        return $this->sitePlanDocumentRepository->getSitePlanDocumentbySurvey($property_id,$category);
    }

    public function getActiveSamplesTable($property_id,$survey_id){
        return $this->surveyRepository->getActiveSamplesTable($property_id,$survey_id);
    }

    public function listOverdueSurveys($type, $limit){
        return $this->surveyRepository->listOverdueSurveys($type, $limit);
    }

    public function listOverdueAudits($type, $limit){
        return $this->surveyRepository->listOverdueAudits($type, $limit);
    }

    public function getOverdueSurveySites2($datacentreRisk, $clientID = 0, $page = 0, $limit = 2000) {
        return $this->surveyRepository->getOverdueSurveySites2($datacentreRisk, $clientID, $page, $limit);
    }

    public function missingSurvey($request, $client_id = 0, $limit = 500, $offset = 0){
        //sorting
        $order_by = "p.`created_at` DESC";
        if(isset($request->order[0]['column']) && isset($request->order[0]['dir'])){
            $index = $request->order[0]['column'];
            $column = $request->columns[$index]['data'] ?? '';
            $dir = $request->order[0]['dir'];
            if($column && $dir){
                $order_by = " p.".$column. " $dir";
            }
        }
        $search = "";
        if(isset($request->search['value']) && !empty($request->search['value'])){
            $text_search = $request->search['value'];
            $search = " AND ( p.`name` LIKE '%$text_search%' OR p.`reference` LIKE '%$text_search%' OR p.`property_reference` LIKE '%$text_search%')";
        }
        return $this->surveyRepository->missingSurvey($client_id, $limit, $offset, $order_by, $search);
    }

    public function missingAssessment($request, $client_id = 0, $limit = 500, $offset = 0){
        //sorting
        $order_by = "p.`created_at` DESC";
        if(isset($request->order[0]['column']) && isset($request->order[0]['dir'])){
            $index = $request->order[0]['column'];
            $column = $request->columns[$index]['data'] ?? '';
            $dir = $request->order[0]['dir'];
            if($column && $dir){
                $order_by = " p.".$column. " $dir";
            }
        }
        $search = "";
        if(isset($request->search['value']) && !empty($request->search['value'])){
            $text_search = $request->search['value'];
            $search = " AND ( p.`name` LIKE '%$text_search%' OR p.`reference` LIKE '%$text_search%' OR p.`property_reference` LIKE '%$text_search%')";
        }
        return $this->surveyRepository->missingAssessment($client_id, $limit, $offset, $order_by, $search);
    }

    public function countMissingAssessment(){
        $return = $this->surveyRepository->missingAssessment(0, 1, 0, "p.`created_at` DESC");
        return $return[1];
    }

    public function countMissingSurvey(){
        $return = $this->surveyRepository->missingSurvey(0, 1, 0, "p.`created_at` DESC");
        return $return[1];
    }

    public function getSurveyByType($client_id, $type, $user_id = null) {

        if ($client_id != 1) {
            if($type == MANAGEMENT_SURVEY_PARTIAL){
                $surveys1 = Survey::with('project', 'property', 'surveyDate','clients','surveySetting')
                    ->whereHas('surveySetting', function($query) {
                        return $query->where('is_require_r_and_d_elements', 1);
                    })
                    ->where([
                        'client_id' => $client_id,
                        'survey_type' => 1
                    ])
                    ->where('status','!=',COMPLETED_SURVEY_STATUS)
                    ->where('decommissioned', 0);
                if (!empty($user_id)) {
                    $surveys1 = $surveys1->where(function ($query) use ($user_id) {
                        $query->where(['lead_by' => $user_id]);
                        $query->orWhere(['second_lead_by' => $user_id]);
                        $query->orWhere(['quality_id' => $user_id]);
                    });
                }
                $surveys1 = $surveys1->get();
            } else {
                $surveys1 = Survey::with('project', 'property', 'surveyDate','clients','surveySetting')
                    ->where([
                        'client_id' => $client_id,
                        'survey_type' => $type
                    ])
                    ->where('status','!=',COMPLETED_SURVEY_STATUS)
                    ->where('decommissioned', 0);
                if (!empty($user_id)) {
                    $surveys1 = $surveys1->where(function ($query) use ($user_id) {
                        $query->where(['lead_by' => $user_id]);
                        $query->orWhere(['second_lead_by' => $user_id]);
                        $query->orWhere(['quality_id' => $user_id]);
                    });
                }
                $surveys1 = $surveys1->get();
            }
            $project_ids = Project::whereRaw("FIND_IN_SET('$client_id', REPLACE(checked_contractors, ' ', ''))")->where('status', '!=' , 5)->orderBy('id','desc')->pluck('id')->toArray();

            $surveys2 = Survey::with('project','surveyDate','publishedSurvey','project', 'property', 'surveyDate','clients','surveySetting')
                ->where('decommissioned', 0)
                ->where('status','!=',COMPLETED_SURVEY_STATUS)
                ->where('survey_type', $type)
                ->whereIn('project_id', $project_ids);
            if (!empty($user_id)) {
                $surveys2 = $surveys2->where(function ($query) use ($user_id) {
                    $query->where(['lead_by' => $user_id]);
                    $query->orWhere(['second_lead_by' => $user_id]);
                    $query->orWhere(['quality_id' => $user_id]);
                });
            }
            $surveys2 = $surveys2->get();

            $survey = $surveys1->merge($surveys2);
        } else {
            // property privilege
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();
            if ($type == MANAGEMENT_SURVEY_PARTIAL){
                $survey = Survey::with('project', 'property', 'surveyDate','clients','surveySetting')
                    ->whereHas('surveySetting', function($query) {
                        return $query->where('is_require_r_and_d_elements', 1);
                    })
                    ->where([
                    'survey_type' => 1
                ])->where('status','!=',COMPLETED_SURVEY_STATUS)->where('decommissioned', 0)
                    ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id');
                if (!empty($user_id)) {
                    $survey = $survey->where(function ($query) use ($user_id) {
                        $query->where(['lead_by' => $user_id]);
                        $query->orWhere(['second_lead_by' => $user_id]);
                        $query->orWhere(['quality_id' => $user_id]);
                    });
                }
                $survey = $survey->get();
            }else{
                $survey = Survey::with('project', 'property', 'surveyDate','clients','surveySetting')->where([
                    'survey_type' => $type
                ])->where('status','!=',COMPLETED_SURVEY_STATUS)->where('decommissioned', 0)
                    ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id');
                if (!empty($user_id)) {
                    $survey = $survey->where(function ($query) use ($user_id) {
                        $query->where(['lead_by' => $user_id]);
                        $query->orWhere(['second_lead_by' => $user_id]);
                        $query->orWhere(['quality_id' => $user_id]);
                    });
                }
                $survey = $survey->get();
            }
        }
        return $survey;
    }

    public function getPropertySurvey($property_areas){
        $dataProperty = [];
        foreach ($property_areas as $key => $area) {
            $dataTmp['area'] =  $area;
            $dataTmp['locations'] = [];
            if (!is_null($area->locations)) {
                foreach ($area->locations as $key => $location) {
                    $location->title = $this->getLocationTreeDes($location);
                    $dataTmp['locations'][$key]['location'] = $location;
                    $dataTmp['locations'][$key]['items'] = [];
                    if (!is_null($location->items)) {
                        foreach ($location->items as $key1 => $item) {
                            $item->productDebris = $this->getItemTreeDes($item);
                            $dataTmp['locations'][$key]['items'][] = $item;
                        }
                    }
                }
            }
            $dataProperty[] = $dataTmp;
        }
        return $dataProperty ?? [];
    }

    public function getLocationTreeDes($location) {
        $description = $location->location_reference. ' - ' .$location->description;
        $description .= ($location->state == LOCATION_STATE_INACCESSIBLE) ? " ( inaccessible room/location and " : " ( ";
        $description .= ((!is_null($location->items)) ? count($location->items) : 0) . ' Items )';
        return $description;
    }

    public function getItemTreeDes($item) {
        return $item->reference . " - OAS " . sprintf("%02d",$item->total_risk). ' ' . ($item->productDebrisView->product_debris ?? '');
    }

    public function createSurvey($property_id,$data, $survey_id = null) {

        if (!empty($data)) {

            $dataSurvey = [
                'client_id' => \CommonHelpers::checkArrayKey($data,'clientKey'),
                'property_id' => $property_id,
                'survey_type' => \CommonHelpers::checkArrayKey($data,'surveyType'),
                'project_id' => \CommonHelpers::checkArrayKey($data,'projectKey'),
                'decommissioned' => 0,
                'lead_by' => \CommonHelpers::checkArrayKey($data,'leadBy'),
                'second_lead_by' => \CommonHelpers::checkArrayKey($data,'secondLeadBy'),
                'surveyor_id' => \CommonHelpers::checkArrayKey($data,'surveyor'),
                'second_surveyor_id' => \CommonHelpers::checkArrayKey($data,'secondSurveyor'),
                'cad_tech_id' => $data['cad_tech'] ?? '',
                'quality_id' => \CommonHelpers::checkArrayKey($data,'qualityKey'),
                'analyst_id' => \CommonHelpers::checkArrayKey($data,'analystKey'),
                'consultant_id' => \CommonHelpers::checkArrayKey($data,'consultantKey'),
                'is_locked' => 0,
                'work_stream' => \CommonHelpers::checkArrayKey($data,'work_stream'),
                'cost' => \CommonHelpers::checkArrayKey($data,'cost'),
                'created_by' => \Auth::user()->id,
            ];

            $dataSurveyDate = [
                'due_date' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'due-date')),
                'surveying_start_date' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'sv-start-date')),
                'surveying_finish_date' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'sv-finish-date')),
                'published_date' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'published-date')),
                'completed_date' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'completed-date')),
                'sample_sent_to_lab_date' => \CommonHelpers::toTimeStamp2(\CommonHelpers::checkArrayKey($data, 'sample-sent-to-lab-date')),
                'created_at' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'date')),
            ];

            $dataSurveySetting = [
                'is_require_priority_assessment' => (isset($data['priorityAssessmentRequired'])) ? 1 : 0,
                'is_require_construction_details' => (isset($data['constructionDetailsRequired'])) ? 1 : 0,
                'is_require_location_void_investigations' => (isset($data['locationVoidInvestigationsRequired'])) ? 1 : 0,
                'is_require_location_construction_details' =>(isset($data['locationConstructionDetailsRequired'])) ? 1 : 0,
                'is_require_photos' => (isset($data['photosRequired'])) ? 1 : 0,
                'is_require_license_status' => (isset($data['licenseStatusRequired'])) ? 1 : 0,
                'is_require_r_and_d_elements' => (isset($data['RDinManagementAllowed'])) ? 1 : 0,
                'is_property_plan_photo' => (isset($data['propertyPlanPhoto'])) ? 1 : 0,
                'external_laboratory' => (isset($data['external_laboratory'])) ? 1 : 0,
                'ceiling_void' => (isset($data['ceiling_void'])) ? 1 : 0,
                'floor_void' => (isset($data['floor_void'])) ? 1 : 0,
                'cavities' => (isset($data['cavities'])) ? 1 : 0,
                'risers' => (isset($data['risers'])) ? 1 : 0,
                'ducting' => (isset($data['ducting'])) ? 1 : 0,
                'boxing' => (isset($data['boxing'])) ? 1 : 0,
                'pipework' => (isset($data['pipework'])) ? 1 : 0,
            ];
            $dataSurveyInfo = [];

            try {
                // create survey
                if (is_null($survey_id)) {
                    $dataSurvey['status'] = 1;
                    $survey = $this->surveyRepository->create($dataSurvey);
                    $reference = $this->getSurveyReferenceText($survey->survey_type, $survey->id);
                    $this->surveyRepository->where('id', $survey->id)->update(['reference'=> $reference]);
                    $dataSurveyDate['started_date'] = time();

                    $message = 'Survey Created Successfully!';
                    if (isset($data['constructionDetailsRequired'])) {
                        $propertyData = $this->propertyRepository->getProperty($property_id,'propertySurvey');
                        $propertyData = [
                            'programmeType'     => $propertyData->propertySurvey->programme_type,
                            'programmetypemore'     => $propertyData->propertySurvey->programme_type_other,
                            'PrimaryUse'        => $propertyData->propertySurvey->asset_use_primary,
                            'primaryusemore'    => $propertyData->propertySurvey->asset_use_primary_other,
                            'SecondaryUse'      => $propertyData->propertySurvey->asset_use_secondary,
                            'secondaryusemore'  => $propertyData->propertySurvey->asset_use_secondary_other,
                            'constructionAge' => $propertyData->propertySurvey->construction_age,
                            'constructionType' => $propertyData->propertySurvey->construction_type,
                            'sizeFloors' => $propertyData->propertySurvey->size_floors,
                            'sizeStaircases' => $propertyData->propertySurvey->size_staircases,
                            'sizeLifts' => $propertyData->propertySurvey->size_lifts,
                            'electricalMeter' => $propertyData->propertySurvey->electrical_meter,
                            'gasMeter' => $propertyData->propertySurvey->gas_meter,
                            'loftVoid' => $propertyData->propertySurvey->loft_void,
                            'sizeNetArea' => $propertyData->propertySurvey->size_net_area,
                            'sizeGrossArea' => $propertyData->propertySurvey->size_gross_area,
                            'sizeComments' => $propertyData->propertySurvey->size_comments,
                        ];
                        $dataSurveyInfo['property_data'] = json_encode($propertyData);
                    }

                    //log audit
                    $comment = \Auth::user()->full_name  . " add new survey "  . $reference;
                    \CommonHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_ADD, $reference, $survey->property_id ,$comment, 0 ,$survey->property_id);
                    // update survey
                } else {
                    $reference = $this->getSurveyReferenceText($dataSurvey['survey_type'], $survey_id);
                    $dataSurvey['reference'] = $reference;
                    $this->surveyRepository->where('id', $survey_id)->update($dataSurvey);
                    $survey =  $this->surveyRepository->where('id', $survey_id)->first();
                    $message = 'Survey Updated Successfully!';

                    //log audit
                    $comment = \Auth::user()->full_name  . " edited survey "  . $survey->reference;
                    \CommonHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_EDIT, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);
                }

                // update linked project
//                $project_id = \CommonHelpers::checkArrayKey($data,'projectKey');
//                $project = Project::find($project_id);
//                if (!is_null($project)) {
//                    $survey_ids = explode(",",$project->survey_id);
//
//                    if (!in_array($survey->id, $survey_ids)) {
//                        array_push($survey_ids,$survey->id);
//                        $survey_project = implode(",",$survey_ids);
//                        Project::where('id', $project->id)->update(['survey_id' => $survey_project]);
//                    }
//                }

                if ($survey) {

                    $this->surveyDateRepository->updateOrCreate(['survey_id' => $survey->id],$dataSurveyDate);
                    $this->surveyInfoRepository->updateOrCreate(['survey_id' => $survey->id],$dataSurveyInfo);
                    $this->surveySettingRepository->updateOrCreate(['survey_id' => $survey->id],$dataSurveySetting);
                }
                return $response = \CommonHelpers::successResponse($message, $survey);
            } catch (\Exception $e) {
                return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to create or update survey. Please try again!');
            }
        }
    }

    public function getSurveyReferenceText($survey_type, $survey_id) {

        switch ($survey_type) {
            case 1:
                $reference = "MS".$survey_id;
                break;
            case 2:
                $reference = "FS".$survey_id;
                break;
            case 3:
                $reference = "RR".$survey_id;
                break;
            case 4:
                $reference = "DS".$survey_id;
                break;

            case 5:
                $reference = "MSP".$survey_id;
                break;

            case 6:
                $reference = "BS".$survey_id;
                break;

            default:
                $reference = '';
                break;
        }

        return $reference;
    }

    public function cloneRegisterData($area_ids, $location_ids, $item_ids, $survey_id) {
        $areaInserted = [];
        $locationInserted = [];
        try {
            \DB::beginTransaction();

            if (!is_null($item_ids)) {
                $item_ids = $this->convertString2ArrayInt($item_ids);
                // lock item
                foreach ($item_ids as $item_id) {
                    $item = $this->itemRepository->where('id', $item_id)->first();
                    // if area already inserted
                    if (in_array($item->area_id, $areaInserted)) {
                        $areaOldRecordId = $this->areaRepository->where('id', $item->area_id)->first()->record_id;
                        $cloneAreaSurvey =  $this->areaRepository->where('record_id', $areaOldRecordId)->where('survey_id', $survey_id)->first();
                        if (is_null($cloneAreaSurvey)) {
                            continue;
                        } else {
                            $cloneArea =  $cloneAreaSurvey->id;
                        }
                    } else {
                        $cloneArea = $this->cloneArea($item->area_id, $survey_id);
                        $areaInserted[] = $item->area_id;
                    }
                    //  if location already inserted
                    if (in_array($item->location_id, $locationInserted)) {
                        $locationOldRecordId = $this->locationRepository->where('id', $item->location_id)->first()->record_id;
                        $cloneLocationSurvey = $this->locationRepository->where('record_id', $locationOldRecordId)->where('survey_id', $survey_id)->first();
                        if (is_null($cloneLocationSurvey)) {
                            continue;
                        } else {
                            $cloneLocation =  $cloneLocationSurvey->id;
                        }
                    } else {
                        $cloneLocation = $this->cloneLocation($item->location_id, $survey_id, $cloneArea );
                        $locationInserted[] = $item->location_id;
                    }
                    $cloneItem = $this->cloneItem($item_id, $cloneLocation, $cloneArea, $survey_id);
                }
            }

            $locationInserted = array_unique($locationInserted);
            if (!is_null($location_ids)) {
                $location_ids = $this->convertString2ArrayInt($location_ids);
                $location_ids = array_diff($location_ids,$locationInserted);
                $location_ids = array_unique($location_ids);

                foreach ($location_ids as $location_id) {
                    $location = $this->locationRepository->where('id', $location_id)->first();
                    // if area already inserted
                    if (in_array($location->area_id, $areaInserted)) {
                        $areaOldRecordId = $this->areaRepository->where('id', $location->area_id)->first()->record_id;
                        $cloneAreaSurvey = $this->areaRepository->where('record_id', $areaOldRecordId)->where('survey_id', $survey_id)->first();
                        // skip if null area
                        if (is_null($cloneAreaSurvey)) {
                            continue;
                        } else {
                            $cloneArea =  $cloneAreaSurvey->id;
                        }
                    } else {
                        $cloneArea = $this->cloneArea($location->area_id, $survey_id);
                        $areaInserted[] = $location->area_id;
                    }

                    $cloneLocation = $this->cloneLocation($location->id, $survey_id, $cloneArea );
                }
            }

            $areaInserted = array_unique($areaInserted);
            if (!is_null($area_ids)) {
                $area_ids = $this->convertString2ArrayInt($area_ids);
                $area_ids = array_diff($area_ids,$areaInserted);
                $area_ids = array_unique($area_ids);

                foreach ($area_ids as $area_id) {
                    $cloneArea = $this->cloneArea($area_id, $survey_id);
                }
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
        }
    }

    public function cloneArea($areaId, $surveyId, $approval = false) {
        $area = $this->areaRepository->find($areaId);
        $dataArea = [
            "reference" => $area->reference ,
            "record_id" => $area->record_id ,
            "version" => $area->version + 1,
            "property_id" => $area->property_id ,
            "survey_id" => $surveyId ,
            "is_locked" => 0 ,
            "description" => $area->description ,
            "area_reference" => $area->area_reference ,
            "decommissioned" => $area->decommissioned ,
            // 'decommissioned_reason'         => $area->decommissioned_reason ,
            'not_assessed'                  => $area->not_assessed ,
            'not_assessed_reason'           => $area->not_assessed_reason,
            'created_by' => $area->created_by
        ];
        if ($approval) {
            //unlock area
            // Area::where('id', $areaId)->update(['is_locked' => AREA_UNLOCKED]);
            \DB::update("Update tbl_area set is_locked = 0 where id = $areaId");
        } else {
            //lock area
            // Area::where('id', $areaId)->update(['is_locked' => AREA_LOCKED]);
            \DB::update("Update tbl_area set is_locked = 1 where id = $areaId");
        }
        //duplicate area
        $newArea = $this->areaRepository->create($dataArea);

        return $newArea->id;
    }
    public function cloneLocation($locationId, $surveyId, $areaId, $approval = false) {

        $location = $this->locationRepository->with('locationInfo', 'locationVoid', 'locationConstruction','shineDocumentStorage')->find($locationId);
        $dataNewLocation = [
            'area_id'                      => $areaId ?? 0,
            'survey_id'                    => $surveyId,
            'property_id'                  => $location->property_id,
            'is_locked'                    => 0,
            'state'                        => $location->state,
            'decommissioned'               => $location->decommissioned,
            // 'decommissioned_reason'         => $location->decommissioned_reason ,
            'not_assessed'                  => $location->not_assessed ,
            'not_assessed_reason'           => $location->not_assessed_reason,
            'version'                      => $location->version + 1,
            'description'                  => $location->description,
            'location_reference'           => $location->location_reference,
            'reference'                     => $location->reference,
            'record_id'                     => $location->record_id,
            'created_by'                    => $location->created_by
        ];
        // not update timestamp
        if ($approval) {
            //unlock location
            // Location::where('id', $locationId)->update(['is_locked' => LOCATION_UNLOCKED]);
            \DB::update("Update tbl_location set is_locked = 0 where id = $locationId");
        } else {
            //lock location
            // Location::where('id', $locationId)->update(['is_locked' => LOCATION_LOCKED]);
            \DB::update("Update tbl_location set is_locked = 1 where id = $locationId");
        }

        //duplicate location
        $newLocation = $this->locationRepository->create($dataNewLocation);
        // Location::where('id', $newLocation->id)->update(['reference' => 'RL'.$newLocation->id]);
        //duplicate location relation
        $this->checkMultipleRelations($location->locationInfo, $newLocation->id, 'location');
        $this->checkMultipleRelations($location->locationVoid, $newLocation->id, 'location');
        $this->checkMultipleRelations($location->locationConstruction, $newLocation->id, 'location');
        $this->checkMultipleRelations($location->shineDocumentStorage, $newLocation->id, 'file');

        return $newLocation->id;
    }

    public function cloneItem($itemId, $locationId, $areaId, $surveyId, $approval = false, $decommissioned = false) {
        $relation = [
            'itemInfo',
            'AsbestosTypeValue',
            'ExtentValue',
            'ProductDebrisTypeValue',
            'ActionRecommendationValue',
            'AdditionalInformationValue',
            'SampleCommentValue',
            'SpecificLocationValue',
            'AccessibilityVulnerabilityValue',
            'LicensedNonLicensedValue',
            'UnableToSampleValue',
            'ItemNoAccessValue',
            'NoACMCommentsValue',
            'PriorityAssessmentRiskValue',
            'MaterialAssessmentRiskValue',
            'SampleIdValue',
            'SubSampleIdValue',
            'shineDocumentStorage'
        ];
        $item =  $this->itemRepository->getFindItem($relation,$itemId);

        $dataNewItem = [
            'area_id'                       => $areaId ,
            'survey_id'                     => $surveyId ,
            'property_id'                   => $item->property_id ,
            'location_id'                   => $locationId ,
            'state'                         => $item->state,
            'version'                       => $item->version + 1 ,
            'is_locked'                     => 0 ,
            'total_risk'                    => $item->total_risk ,
            'total_mas_risk'                => $item->total_mas_risk ,
            'total_pas_risk'                => $item->total_pas_risk ,
            'decommissioned'                => ($decommissioned) ? 1 : $item->decommissioned ,
            // 'decommissioned_reason'         => $item->decommissioned_reason ,
            'not_assessed'                  => $item->not_assessed ,
            'not_assessed_reason'           => $item->not_assessed_reason,
            'name'                          => $item->name ,
            'record_id'                     => $item->record_id ,
            'reference'                     => $item->reference,
            'created_by'                    => $item->created_by
        ];

        if ($approval) {
            //unlock item
            // Item::where('id', $itemId)->update(['is_locked' => ITEM_UNLOCKED]);
            \DB::update("Update tbl_items set is_locked = 0 where id = $itemId");
        } else {
            //lock item
            // Item::where('id', $itemId)->update(['is_locked' => ITEM_LOCKED]);
            \DB::update("Update tbl_items set is_locked = 1 where id = $itemId");
        }

        //duplicate item
        $newItem = $this->itemRepository->create($dataNewItem);
        // Item::where('id', $newItem->id)->update(['reference' => 'IN'.$newItem->id]);
        // duplicate item relation
        $this->checkMultipleRelations($item->itemInfo, $newItem->id);
        $this->checkMultipleRelations($item->AsbestosTypeValue, $newItem->id);
        $this->checkMultipleRelations($item->ExtentValue, $newItem->id);
        $this->checkMultipleRelations($item->ProductDebrisTypeValue, $newItem->id);
        $this->checkMultipleRelations($item->ActionRecommendationValue, $newItem->id);
        $this->checkMultipleRelations($item->AdditionalInformationValue, $newItem->id);
        $this->checkMultipleRelations($item-> SampleCommentValue, $newItem->id);
        $this->checkMultipleRelations($item-> SpecificLocationValue, $newItem->id);
        $this->checkMultipleRelations($item->AccessibilityVulnerabilityValue, $newItem->id);
        $this->checkMultipleRelations($item-> LicensedNonLicensedValue, $newItem->id);
        $this->checkMultipleRelations($item->UnableToSampleValue, $newItem->id);
        $this->checkMultipleRelations($item->ItemNoAccessValue, $newItem->id);
        $this->checkMultipleRelations($item->NoACMCommentsValue, $newItem->id);
        $this->checkMultipleRelations($item->PriorityAssessmentRiskValue, $newItem->id);
        $this->checkMultipleRelations($item->MaterialAssessmentRiskValue, $newItem->id);
        $this->checkMultipleRelations($item-> SampleIdValue, $newItem->id);
        $this->checkMultipleRelations($item-> SubSampleIdValue, $newItem->id);
        $this->checkMultipleRelations($item->shineDocumentStorage, $newItem->id, 'file');

        return $newItem->id;
    }

    public function checkMultipleRelations($relations, $newRelationId, $type = 'item') {
        if (!is_null($relations)) {
            if($relations instanceof Collection) {
                foreach ($relations as $relation) {
                    $this->replicateRelation($relation, $newRelationId, $type);
                }
            } else {
                $this->replicateRelation($relations, $newRelationId, $type);
            }
        }
    }

    public function replicateRelation($relation, $newRelationId, $type) {
        $newRelation = $relation->replicate();
        if ($type == 'item') {
            $newRelation->item_id = $newRelationId;
        } elseif($type == 'file') {
            $newRelation->object_id = $newRelationId;
        } else {
            $newRelation->location_id = $newRelationId;
        }
        $newRelation->save();
    }

    public function convertString2ArrayInt($string) {
        $result_array = [];
        $string = explode(',', $string);

        foreach ($string as $each_number) {
            $result_array[] = (int) $each_number;
        }
        return $result_array;
    }

    public function getSurveyPlans($property_id, $survey_id) {
        $plans =  $this->sitePlanDocumentRepository->getSitePlanbySurvey($property_id,$survey_id);
        return is_null($plans) ? [] : $plans;
    }

    public function getSurveyorsNotes($property_id, $survey_id) {
        $survey_or_notes =  $this->sitePlanDocumentRepository->getSurveyorsNotes($property_id,$survey_id);
        return is_null($survey_or_notes) ? [] : $survey_or_notes;
    }

    public function decommissionSurvey($survey_id) {

        $survey = $this->surveyRepository->find($survey_id);
        $areas = $this->areaRepository->where('survey_id', $survey_id)->get();
        $locations = $this->locationRepository->where('survey_id', $survey_id)->get();
        $items = $this->itemRepository->where('survey_id', $survey_id)->get();
        try {
            if ($survey->decommissioned == SURVEY_DECOMMISSION) {
                $this->surveyRepository->where('id', $survey_id)->update(['decommissioned' => SURVEY_UNDECOMMISSION]);
                $this->areaRepository->where('survey_id', $survey_id)->update(['decommissioned' => AREA_UNDECOMMISSION]);
                $this->locationRepository->where('survey_id', $survey_id)->update(['decommissioned' => LOCATION_UNDECOMMISSION]);
                $this->itemRepository->where('survey_id', $survey_id)->update(['decommissioned' => ITEM_UNDECOMMISSION]);

                //log audit
                if (!is_null($areas)) {
                    foreach ($areas as $area) {
                        $comment = \Auth::user()->full_name . " Recommission Area ".$area->area_reference." by Recommission Survey " . $survey->reference;
                        \CommonHelpers::logAudit(AREA_TYPE, $area->id, AUDIT_ACTION_RECOMMISSION, $area->area_reference, $area->survey_id, $comment , 0 , $area->property_id ?? 0);
                    }
                }

                if (!is_null($locations)) {
                    foreach ($locations as $location) {
                        $comment = \Auth::user()->full_name . " Recommission Location ".$location->location_reference." by Recommission Survey " . $survey->reference;
                        \CommonHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_RECOMMISSION, $location->location_reference, $location->survey_id ,$comment, 0 ,$location->property_id);
                    }
                }
                if (!is_null($items)) {
                    foreach ($items as $item) {
                        $comment = \Auth::user()->full_name . " Recommission Item ".$item->reference." by Recommission Survey " . $survey->reference;
                        \CommonHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_RECOMMISSION, $item->reference, $item->survey_id ,$comment, 0 ,$item->property_id);
                    }
                }
                $comment = \Auth::user()->full_name  . " recommission survey "  . $survey->reference;
                \CommonHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_RECOMMISSION, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);
                return \CommonHelpers::successResponse('Survey Recommissioned successfully!');
            } else {
                $this->surveyRepository->where('id', $survey_id)->update(['decommissioned' => SURVEY_DECOMMISSION]);
                $this->areaRepository->where('survey_id', $survey_id)->update(['decommissioned' => AREA_DECOMMISSION]);
                $this->locationRepository->where('survey_id', $survey_id)->update(['decommissioned' => LOCATION_DECOMMISSION]);
                $this->itemRepository->where('survey_id', $survey_id)->update(['decommissioned' => ITEM_DECOMMISSION]);

                // unlock register data
                // Unlock register areas
                \DB::update("UPDATE tbl_area a,
                                             (
                                                SELECT
                                                    record_id
                                                FROM
                                                    tbl_area
                                                WHERE
                                                    id != record_id
                                                AND survey_id = $survey_id
                                            ) a2
                                            SET a.is_locked = 0
                                            WHERE
                                                a.record_id = a2.record_id
                                            AND a.survey_id = 0");

                // Unlock register items
                \DB::update("UPDATE tbl_items a,
                                             (
                                                SELECT
                                                    record_id
                                                FROM
                                                    tbl_items
                                                WHERE
                                                    id != record_id
                                                AND survey_id = $survey_id
                                            ) a2
                                            SET a.is_locked = 0
                                            WHERE
                                                a.record_id = a2.record_id
                                            AND a.survey_id = 0 ");

                // Unlock register locations
                \DB::update("UPDATE tbl_location a,
                                             (
                                                SELECT
                                                    record_id
                                                FROM
                                                    tbl_location
                                                WHERE
                                                    id != record_id
                                                AND survey_id = $survey_id
                                            ) a2
                                            SET a.is_locked = 0
                                            WHERE
                                                a.record_id = a2.record_id
                                            AND a.survey_id = 0");

                //log audit
                if (!is_null($areas)) {
                    foreach ($areas as $area) {
                        $comment = \Auth::user()->full_name . " Decommission Area ".$area->area_reference." by Decommission Survey " . $survey->reference;
                        \CommonHelpers::logAudit(AREA_TYPE, $area->id, AUDIT_ACTION_DECOMMISSION, $area->area_reference, $area->survey_id, $comment , 0 , $area->property_id ?? 0);
                    }
                }

                if (!is_null($locations)) {
                    foreach ($locations as $location) {
                        $comment = \Auth::user()->full_name . " Decommission Location ".$location->location_reference." by Decommission Survey " . $survey->reference;
                        \CommonHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_DECOMMISSION, $location->location_reference, $location->survey_id ,$comment, 0 ,$location->property_id);
                    }
                }
                if (!is_null($items)) {
                    foreach ($items as $item) {
                        $comment = \Auth::user()->full_name . " Decommission Item ".$item->reference." by Decommission Survey " . $survey->reference;
                        \CommonHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_DECOMMISSION, $item->reference, $item->survey_id ,$comment, 0 ,$item->property_id);
                    }
                }
                $comment = \Auth::user()->full_name  . " decommission survey "  . $survey->reference;
                \CommonHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_DECOMMISSION, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);
                return \CommonHelpers::successResponse('Survey Decommissioned successfully!');
            }
        } catch (\Exception $e) {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to Decommission/Recommission Survey. Please try again!');
        }
    }
    public function lockSurvey($survey_id){
        $this->surveyRepository->where('id', $survey_id)->update(['is_locked' => 1]);
        // Area::where('survey_id', $survey_id)->update(['is_locked'=> 1]);
        // Location::where('survey_id', $survey_id)->update(['is_locked'=> 1]);
        // Item::where('survey_id', $survey_id)->update(['is_locked'=> 1]);
        \DB::update("Update tbl_area set is_locked = 1 where survey_id = $survey_id");
        \DB::update("Update tbl_location set is_locked = 1 where survey_id = $survey_id");
        \DB::update("Update tbl_items set is_locked = 1 where survey_id = $survey_id");
    }

    public function sendSurvey($survey_id) {
        $survey =  $this->surveyRepository->find($survey_id);
        try {
            $this->surveyRepository->where('id', $survey_id)->update([
                'is_locked' => SURVEY_LOCKED,
                'status' => 2,
            ]);
            $dataSurveyDate['sent_out_date'] = Carbon::now()->timestamp;
            if (is_null($survey->surveyDate->started_date)) {
                $dataSurveyDate['sent_out_date'] = Carbon::now()->timestamp;
            }
            $this->surveyDateRepository->where('survey_id', $survey_id)->update($dataSurveyDate);
            $this->lockSurvey($survey_id);

            //log audit
            $comment = \Auth::user()->full_name  . " send survey "  . $survey->reference ." to surveyor " . \CommonHelpers::getUserFullname($survey->surveyor_id);
            \CommonHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_SEND, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);

            $can_send_email = false;
            if ($survey->client_id == 1) {
                // External Laboratory = Yes
                if (($survey->surveySetting->external_laboratory ?? '') == 1) {
                    // Linked Project = Linked to a Survey Only Project created by the Work Request Feature with the Workflow Dropdown = Tersus Group.
                    if (isset($survey->project) and !is_null($survey->project)) {
                        $project_type =  $survey->project->project_type ?? 0;
                        $project_status = $survey->project->status ?? 0;
                        $work_request = $survey->project->workRequest ?? null;
                        $work_flow = $work_request->work_flow ?? 0;
                        if (($project_type == PROJECT_SURVEY_ONLY) and !is_null($work_request) and ($work_flow == WORK_FLOW_HACKNEY) and ($survey->status != COMPLETED_SURVEY_STATUS) and ($project_status != PROJECT_COMPLETE_STATUS)) {
                            $can_send_email = true;
                        }
                    }
                }
            }
            // check permission send email
            if ($can_send_email) {
                //send mail queue
                \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendClientEmailNotification(
                    $survey->client->name ?? '',
                    SURVEY_SENT_BACK_TO_DEVICE_EMAILTYPE,
                    $survey->surveyor_id,
                    $survey->property->property_reference ?? '',
                    $survey->property->name ?? '',
                    $survey->property->propertyInfo->pblock ?? '',
                    $survey->property ?? '',
                    $survey->reference ?? '',
                    \Auth::user()->clients->name ?? ''
                ));

            }

            return $response = \CommonHelpers::successResponse('Survey Sent successfully!');
        } catch (\Exception $e) {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to Send Survey. Please try again!');
        }
    }

    public function createPublishedSurvey($data_ps){
        return $this->publishedSurveyRepository->createPublishedSurvey($data_ps);
    }

    public function getSampleCertificateBySurvey($relation = [], $suvery_id){
        return $this->sampleCertificateRepository->getSampleCertificateBySurvey($relation, $suvery_id);
    }

    public function getSitePlanDocumentBySurveyPDF($relation = [], $suvery_id){
        return $this->sitePlanDocumentRepository->getSitePlanDocumentBySurveyPDF($relation, $suvery_id);
    }

    public function getPublishedSurvey($id){
        return $this->publishedSurveyRepository->getPublishedSurvey($id);
    }

    public function getPublishedWorkRequest($id){
        return $this->publishedWorkRequestRepository->getPublishedWorkRequest($id);
    }

    public function updateSampleIsReal($check, $uncheck) {
        try {
            $this->sampleRepository->whereIn('id',$check)->update(['is_real' => 1]);
            $this->sampleRepository->whereIn('id',$uncheck)->update(['is_real' => 0]);
            return \CommonHelpers::successResponse('Update Sample successful!');
        } catch (\Exception $e) {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Update Sample Fail!');
        }
    }

    public function emailSample($data){
        $survey_id = $data['assess_id'];
        $survey = $this->surveyRepository->where('id', $survey_id)->first();
        $datasampleEmail = [
            'survey_id' => $survey_id ?? '',
            'client_id' => $survey->property->client_id ?? '',
            "survey_reference" => $survey->reference ?? '',
            "contractor_name" => $survey->client->name ?? '',
            "block_reference" => $survey->property->pblock ?? '',
            "property_uprn" => $survey->property->reference ?? '',
            "property_name" => $survey->property->name ?? '',
            "postcode" => $survey->property->propertyInfo->postcode ?? '',
            "domain" => \Config::get('app.url')
        ];
        if($data['sample_email'] == "sample"){
            if(isset($survey->cad_tech_id) and $survey->cad_tech_id != NULL){
                $cad_tech_id = $survey->cad_tech_id;

                $datasampleEmail['subject'] = 'Sample Results Updated';
                \Queue::pushOn(SURVEY_APPROVAL_EMAIL_QUEUE,new SendSampleEmail($datasampleEmail,EMAIL_SAMPLE_EMAIL_QUEUE, $cad_tech_id));

                return $response = \CommonHelpers::successResponse('Send Email successful!');
            }else{
                return $response = \CommonHelpers::failResponse(STATUS_FAIL,'CAD not found. Please try again');
            }
        }
        if($data['sample_email'] == 'cad') {

            $consultant_id = $survey->consultant_id ?? 0;
            $surveyor_id = $survey->surveyor_id ?? 0;

            $datasampleEmail['subject'] = 'CAD Drawing Completed';
            \Queue::pushOn(SURVEY_APPROVAL_EMAIL_QUEUE, new SendSampleEmail($datasampleEmail, EMAIL_CAD_EMAIL_QUEUE, $consultant_id, $surveyor_id));
            return $response = \CommonHelpers::successResponse('Send Email successful!');
        }else{
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to Send Email. Please try again!');
        }
    }

    public function getSurveyInfomationData($survey_id, $type) {
        $survey = $this->surveyInfoRepository->where('survey_id', $survey_id)->first();
        if (is_null($survey)) {
            $response = \CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Survey not found! Please try again');
        }
        $data = [];

        switch ($type) {
            case 'executive-summary-table':
                $data['title'] = 'Executive Summary';
                $data['name'] = 'executive_summary';
                $data['data'] = $survey->executive_summary;
                break;

            case 'limitations-table':
                $data['title'] = 'Limitations';
                $data['name'] = 'limitations';
                $data['data'] = $survey->limitations;
                break;

            case 'method-table':
                $data['title'] = 'Method';
                $data['name'] = 'method';
                $data['data'] = $survey->method;
                break;

            case 'objectives-scope-table':
                $data['title'] = 'Objective Scope';
                $data['name'] = 'objectives_scope';
                $data['data'] = $survey->objectives_scope;
                break;

            default:
                $data['title'] = 'Executives Summary';
                $data['name'] = 'executive_summary';
                $data['data'] = $survey->executive_summary;
                break;
        }

        return $data;
    }

    public function updateSurveyInfo($id, $data, $image = null) {
        $surveyInfo = $this->surveyInfoRepository->where('survey_id', $id)->first();
        $survey =  $this->surveyRepository->find($id);
        //log audit
        $comment = \Auth::user()->full_name  . " updated survey "  . $survey->reference . " property information";
        \CommonHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_EDIT, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);
        \CommonHelpers::changeSurveyStatus($id);
        if (!is_null($surveyInfo)) {
            try {
                $surveyInfo = $surveyInfo->update($data);
                if (!is_null($image)) {
                    $savePropertyImage = \CommonHelpers::saveFileShineDocumentStorage($image, $id, PROPERTY_SURVEY_IMAGE);
                    $savePropertyImage;
                }
                return \CommonHelpers::successResponse('Survey Information Updated Succesfully!',$surveyInfo);
            } catch (\Exception $e) {
                return \CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Failed to Update Survey Information. Please try again!');
            }
        } else {
            return \CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Survey not found!');
        }
    }

    public function updateMethodQuestion($survey_id, $data) {
        if (!is_null($data) and count($data) > 0) {
            $questions = $data['question'];
            $answers = $data['answer'];
            $comments = $data['comment'];

            \CommonHelpers::changeSurveyStatus($survey_id);
            foreach ($questions as $key => $question) {
                $dataSurveyAnswer = [
                    'survey_id' => $survey_id,
                    'question_id' => $question,
                    'answer_id' => $answers[$key],
                    'comment' => $comments[$key],
                ];
                try {
                    $update = $this->surveyAnswerRepository->updateOrCreate(['survey_id' => $survey_id, 'question_id' => $question], $dataSurveyAnswer);
                    $response = \CommonHelpers::successResponse('Method/Questionnaire Updated Successful!');
                } catch (\Exception $e) {
                    $response = \CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Failed to Update Method/Questionnaire. Please try again!');
                }
            }
            if (isset($data['question-other']) and isset($data['other-answer'])) {
                $questionOther = $data['question-other'];
                $otherAnswer = $data['other-answer'];
                foreach ($questionOther as $key => $questionOtherData) {
                    $dataSurveyOtherAnswer = [
                        'survey_id' => $survey_id,
                        'question_id' => $questionOtherData,
                        'answer_id' => 0,
                        'answerOther' => $otherAnswer[$key],
                        'comment' => $comments[$key],
                    ];

                    try {
                        $update = $this->surveyAnswerRepository->updateOrCreate(['survey_id' => $survey_id, 'question_id' => $questionOtherData], $dataSurveyOtherAnswer);
                        $response = \CommonHelpers::successResponse('Method/Questionnaire Updated Successful!');

                    } catch (\Exception $e) {
                        $response = \CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Failed to Update Method/Questionnaire. Please try again!');
                    }
                }
            }
            return $response;
        }
    }

    public function updateOrCreatePropertyPlan($data, $id = null) {

        if (!empty($data)) {
            // SitePlanDocument
            $dataPlan = [
                "property_id" => \CommonHelpers::checkArrayKey3($data,'property_id'),
                "name" => \CommonHelpers::checkArrayKey3($data,'name'),
                "plan_reference" => \CommonHelpers::checkArrayKey($data,'description'),
                "survey_id" => \CommonHelpers::checkArrayKey3($data,'assess_id'),
                "type" => \CommonHelpers::checkArrayKey3($data,'type'),
                "category" => \CommonHelpers::checkArrayKey3($data,'category'),

                "added" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'plan_date')),
            ];

            $type_log = ($data['assess_id'] == 0) ? SITE_PLAN_DOCUMENT_TYPE : SURVEY_PLAN_DOCUMENT_TYPE;

            try {
                \DB::beginTransaction();
                if (is_null($id)) {
                    $dataPlan["added_by"] = \Auth::user()->id;
                    $plan = $this->sitePlanDocumentRepository->create($dataPlan);
                    if ($plan) {
                        if ($plan->survey_id == 0) {
                            $planRef = "PP" . $plan->id;
                        } else{
                            $planRef = "SP" . $plan->id;
                        }
                        $this->sitePlanDocumentRepository->where('id', $plan->id)->update(['reference' => $planRef]);
                        //log audit
                        $comment = \Auth::user()->full_name . " add new plan " . $plan->name;
                        \CommonHelpers::logAudit($type_log, $plan->id, AUDIT_ACTION_ADD, $planRef, $dataPlan['survey_id'], $comment, 0 , $dataPlan['property_id']);
                    }
                    $response = CommonHelpers::successResponse('Upload plan document successfully !');
                } else {
                    $this->sitePlanDocumentRepository->where('id', $id)->update($dataPlan);
                    $plan = $this->sitePlanDocumentRepository->where('id', $id)->first();
                    $response = CommonHelpers::successResponse('Update plan document successfully !');

                    //log audit
                    $comment = \Auth::user()->full_name . " edited plan " . $plan->name;
                    \CommonHelpers::logAudit($type_log, $plan->id, AUDIT_ACTION_EDIT, $plan->reference, $dataPlan['survey_id'], $comment, 0 , $dataPlan['property_id']);
                }

                if (isset($data['document'])) {
                    $saveLocationImage = \CommonHelpers::saveFileShineDocumentStorage($data['document'], $plan->id, PLAN_FILE,\CommonHelpers::checkArrayKey3($data,'survey_id'));
                    $dataUpdateImg = [
                        'document_present' => 1,
                    ];
                    $this->sitePlanDocumentRepository->where('id', $plan->id)->update($dataUpdateImg);
                }
                \DB::commit();
                return $response;
            } catch (\Exception $e) {
                \Log::debug($e);
                \DB::rollBack();
                return CommonHelpers::failResponse(STATUS_FAIL,'Failed to upload plan document. Please try again !');
            }
        }
    }

    public function getSampleById($property_id, $survey_id, $sample_id){
        $data_sample = $this->surveyRepository->getSampleById($property_id,$survey_id,$sample_id);
        return $data_sample ?? [];
    }

    public function updateSample($sample_id, $description, $comment)
    {
        if (is_array($comment)) {
            $comment = implode(",", $comment);
        }
        $updateSample = $this->itemRepository->updateSample($sample_id, $description, $comment);
        $updateSample = Sample::where('id', $sample_id)->update(['description' => $description, 'comment_other' => $comment]);
        if ($updateSample) {
            return \CommonHelpers::successResponse('Sample Updated Successfully!');
        } else {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL, 'Sample Updated Fail!');
        }
    }

    public function getRejectedSurvey($user_id = null)
    {
        return $this->surveyRepository->getRejectedSurvey($user_id);
    }
}
