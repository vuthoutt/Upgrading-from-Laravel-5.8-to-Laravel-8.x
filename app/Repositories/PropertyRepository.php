<?php
namespace App\Repositories;
use App\Models\AssetClass;
use App\Models\CommunalArea;
use App\Models\Responsibility;
use App\Models\ServiceArea;
use App\Models\TenureType;
use App\Models\Ward;
use App\Models\AirTest;
use App\Models\AirTestCertificate;
use App\Models\Document;
use App\Models\SampleCertificate;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Property;
use App\Models\PropertyInfo;
use App\Models\PropertyDropdown;
use App\Models\PropertyType;
use App\Models\PropertySurvey;
use App\Models\PropertyDropdownTitle;
use App\Models\DropdownDataProperty;
use App\Models\PropertyProgrammeType;
use App\Models\SitePlanDocument;
use App\Models\HistoricDocCategory;
use App\Models\HistoricDoc;
use App\Models\Zone;
use App\Models\Area;
use App\Models\Location;
use App\Models\Item;
use App\Models\Survey;
use App\Models\Client;
use App\Models\Project;
use App\Models\ShineCompliance\Assessment;
use App\Models\ShineCompliance\ComplianceDocument;
use App\Models\ShineCompliance\PropertyInfoDropdown;
use App\Models\ShineCompliance\IncidentReportDocument;
use App\Models\AuditTrail;
use App\User;
use App\Helpers\CommonHelpers;
use Illuminate\Support\Facades\DB;

class PropertyRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Property::class;
    }

    /**
     * get prperty by id with relation
    * @return
     */
    public function getProperty($id) {
        return Property::with('propertyInfo', 'zone', 'propertyType', 'clients','sitePlanDocuments', 'historicalDocCategory.historicalDoc')->where('id', $id)->first();
    }

    /**
     * get property contact team
    * @return
     */
    public function getPropertyContacts($id) {
        $contact = PropertyInfo::where('property_id', $id)->first()->team ?? [];
        $contactUser = User::with('contact')->whereIn('id',$contact)->get();

        return is_null($contactUser) ? [] : $contactUser;
    }
    /**
     * get app contact
     * @return
     */
    public function getListContacts($id) {
        $list_contact = User::with('contact')->where('id',$id)->get();

        return is_null($list_contact) ? [] : $list_contact;
    }

    public function getPropertyPlans($id) {
        $propertyPlans = Property::with('sitePlanDocuments')->where('id', $id)->first();
        dd($propertyPlans);
    }

    // get all property areas with decomissioned status
    public function getPropertyArea($id, $decommissioned = 0) {
        $areas = Area::with('locations','locations.items','locations.items.productDebrisView')->where('property_id', $id)->where('survey_id', 0)
                    ->where('decommissioned', $decommissioned)
                    ->oldest('reference')
                    ->oldest('description')->get();
        if (!is_null($areas)) {
            foreach ($areas as $value) {
                $value->area_reference = strip_tags($value->area_reference);
                $value->description = strip_tags($value->description);
            }
        }
        return is_null($areas) ? [] : $areas;
    }

    // get all property areas with decomissioned status
    public function getPropertyLocation($id, $decommissioned = 0) {
        $location = Location::where('property_id', $id)->where('survey_id', 0)
                    ->where('decommissioned', $decommissioned)
                    ->oldest('reference')
                    ->oldest('description')->get();

        if (!is_null($location)) {
            foreach ($location as $value) {
                $value->location_reference = strip_tags($value->location_reference);
                $value->description = strip_tags($value->description);
            }
        }
        return is_null($location) ? [] : $location;
    }


    public function getPropertySurvey($id, $decommissioned = 0, $client_id = null) {
        if (!is_null($client_id)) {
            $user_id = \Auth::user()->id;
            $surveys = Survey::with('project','surveyDate','publishedSurvey')
                                ->where('property_id', $id)
                                 ->where('decommissioned', $decommissioned)
                                 ->whereRaw("(client_id = $client_id OR created_by = $user_id)")
                                 ->orderBy('id','desc')
                                 ->get();
        } else {
            $surveys = Survey::with('project','surveyDate','publishedSurvey')->where('property_id', $id)
                                            ->where('decommissioned', $decommissioned)
                                            ->orderBy('id','desc')
                                            ->get();
        }
        return is_null($surveys) ? [] : $surveys;
    }

    public function getPropertyAssessment($property_id)
    {
        return Assessment::where(['property_id' => $property_id, 'decommissioned' => 0,'classification' => 2])->orderBy('id', 'desc')->get();
    }

    public function getPropertyAudit($id, $decommissioned = 0, $client_id = null) {
        $builder = Audit::where('property_id', $id)
            ->where('decommissioned', $decommissioned)
            ->orderBy('id','desc');

        if ($client_id) {
            $builder->where('client_id', $client_id);
        }

        $audits = $builder->get();
        return is_null($audits) ? [] : $audits;

    }

    public function getPropertyProject($id) {
        $client_type = \Auth::user()->clients->client_type;
        $client_id = \Auth::user()->client_id;
        // missing role
        if ($client_type == 1) {
            $projects = Project::where('property_id', $id)->whereRaw("FIND_IN_SET('$client_id', REPLACE(contractors, ' ', ''))")->orderBy('id','desc')->get();
        } else {
            $projects = Project::where('property_id', $id)->whereIn('project_type', \CompliancePrivilege::getPermission(PROJECT_TYPE_PERMISSION))->orderBy('id','desc')->get();
        }
        return is_null($projects) ? [] : $projects;
    }

    public function getLinkedPropertyProject($id, $project_id = 0) {
        $client_type = \Auth::user()->clients->client_type;
        $client_id = \Auth::user()->client_id;
        // missing role
        if ($client_type == 1) {
            $projects = Project::where('property_id', $id)->whereRaw("FIND_IN_SET('$client_id', REPLACE(contractors, ' ', ''))")->whereRaw("id != $project_id")->orderBy('id','desc')->get();
        } else {
            $projects = Project::where('property_id', $id)->whereRaw("id != $project_id")->orderBy('id','desc')->get();
        }
        return is_null($projects) ? [] : $projects;
    }

    public function isWinnerSurveyContractor($property_id, $contractor_id) {
        $projectList = DB::select("SELECT `id` from `tbl_project`
                        WHERE `project_type` = 1
                            AND property_id = $property_id
                            AND FIND_IN_SET('$contractor_id', `checked_contractors`) ");
        if (count($projectList)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getListSizeFloor(){
        $result = [];
        for($i = 0 ; $i < 16; $i++){
            $tem_arr = [];
            $tem_arr['id'] = $i;
            $tem_arr['description'] = $i;
            $result[] = $tem_arr;
        }
        return $result;
    }

    public function getAllPropertyDropdownTitle() {
        return PropertyDropdownTitle::all();
    }

    public function getPropertyDropdownData($id) {
        $dropdowns = PropertyDropdown::where('dropdown_id', $id)->get();
        return is_null($dropdowns) ? [] : $dropdowns;
    }

    public function loadDropdownText($dropdown_property_id, $parent_id = 0) {
        $data =  DropdownDataProperty::where('dropdown_property_id', $dropdown_property_id)->where('parent_id', $parent_id)->where('decommissioned',0)->get();
        return is_null($data) ? [] : $data;
    }

    public function getAllRiskType() {
        $riskTypes = PropertyType::all();
        return $riskTypes;
    }

    public function getAllProgrammeType() {
        $types = PropertyProgrammeType::where('is_deleted', '!=', 1)->orderBy('order')->get();
        return $types;
    }

    public function getAllServiceArea() {
        return ServiceArea::all();
    }
    // for LBHC only
    public function getAllWard() {
        return Ward::all();
    }

    public function getAllTenureType() {
        return TenureType::orderBy('description')->get();
    }

    public function getAllCommunalArea() {
        return CommunalArea::where('is_deleted',0)->get();
    }

    public function getAllResponsibility() {
        return Responsibility::all();
    }

    public function getAllAssetClass($is_parent) {
        $condition = ['parent_id', '>', 0];
        if($is_parent){
            $condition = ['parent_id', '=', 0];
        }
        return AssetClass::where([$condition])->where('is_deleted', '!=', 1)->get();
    }

    public function getListClients() {
        $list = Client::whereIn('client_type', [2, 0])->get();
        return $list;
    }

    public function createProperty($data, $id = false) {
        if (!empty($data)) {

            $dataProperty = [
                'client_id' => \CommonHelpers::checkArrayKey($data,'client_id'),
                'zone_id' => \CommonHelpers::checkArrayKey($data,'zone_id'),
                'property_reference' => \CommonHelpers::checkArrayKey($data,'property_reference'),
                'name' => \CommonHelpers::checkArrayKey($data,'name'),
                'decommissioned' => 0,
                'comments' => \CommonHelpers::checkArrayKey($data,'size_comments'),
                'programme_phase' => 0,
                'register_updated' => 1,
                'pblock' => \CommonHelpers::checkArrayKey($data,'pblock'),
                'core_code' => \CommonHelpers::checkArrayKey($data,'core_code'),
                'service_area_id' => \CommonHelpers::checkArrayKey($data,'service_area_id'),
                'ward_id' => \CommonHelpers::checkArrayKey($data,'ward_id'),
                'parent_reference' => \CommonHelpers::checkArrayKey($data,'parent_reference'),
                'estate_code' => \CommonHelpers::checkArrayKey($data,'estate_code'),
                'asset_class_id' => \CommonHelpers::checkArrayKey($data,'asset_class_id'),
                'asset_type_id' => \CommonHelpers::checkArrayKey($data,'asset_type_id'),
                'tenure_type_id' => \CommonHelpers::checkArrayKey($data,'tenure_type_id'),
                'communal_area_id' => \CommonHelpers::checkArrayKey($data,'communal_area_id'),
                'responsibility_id' => \CommonHelpers::checkArrayKey($data,'responsibility_id'),
                'parent_id' => \CommonHelpers::checkArrayKey($data,'parent_id')
            ];
            if (!isset($data['team'])) {
                $data['team'] = [];
            }
            $dataPropertyInfo = [
                // 'address1' => CommonHelpers::checkArrayKey($data,'address1'),
                // 'address2' => CommonHelpers::checkArrayKey($data,'address2'),
                'flat_number' => CommonHelpers::checkArrayKey($data,'flat_number'),
                'building_name' => CommonHelpers::checkArrayKey($data,'building_name'),
                'street_number' => CommonHelpers::checkArrayKey($data,'street_number'),
                'street_name' => CommonHelpers::checkArrayKey($data,'street_name'),
                'address3' => CommonHelpers::checkArrayKey($data,'address3'),
                'address4' => CommonHelpers::checkArrayKey($data,'address4'),
                'address5' => CommonHelpers::checkArrayKey($data,'address5'),
                'postcode' => CommonHelpers::checkArrayKey($data,'postcode'),
                'country' => CommonHelpers::checkArrayKey($data,'country'),
                'town' => CommonHelpers::checkArrayKey($data,'town'),
                'telephone' => CommonHelpers::checkArrayKey($data,'telephone'),
                'mobile' => CommonHelpers::checkArrayKey($data,'mobile'),
                'email' => CommonHelpers::checkArrayKey($data,'email'),
                'app_contact' => CommonHelpers::checkArrayKey($data,'app_contact'),
                'team1' => CommonHelpers::checkArrayKey3($data['team'],0),
                'team2' => CommonHelpers::checkArrayKey3($data['team'],1),
                'team3' => CommonHelpers::checkArrayKey3($data['team'],2),
                'team4' => CommonHelpers::checkArrayKey3($data['team'],3),
                'team5' => CommonHelpers::checkArrayKey3($data['team'],4),
                'team6' => CommonHelpers::checkArrayKey3($data['team'],5),
                'team7' => CommonHelpers::checkArrayKey3($data['team'],6),
                'team8' => CommonHelpers::checkArrayKey3($data['team'],7),
                'team9' => CommonHelpers::checkArrayKey3($data['team'],8),
                'team10' => CommonHelpers::checkArrayKey3($data['team'],9),
                'estate' => CommonHelpers::checkArrayKey3($data,'estate'),
            ];

            $dataPropertySurvey = [
                "programme_type" => \CommonHelpers::checkArrayKey($data,'programme_type'),
                "programme_type_other" => \CommonHelpers::checkArrayKey($data,'programme_type_other'),
                "asset_use_primary" => \CommonHelpers::checkArrayKey($data,'asset_use_primary'),
                "asset_use_primary_other" => \CommonHelpers::checkArrayKey($data,'primaryusemore'),
                "asset_use_secondary" => \CommonHelpers::checkArrayKey($data,'asset_use_secondary'),
                "asset_use_secondary_other" => \CommonHelpers::checkArrayKey($data,'secondaryusemore'),
                "construction_age" => \CommonHelpers::checkArrayKey($data,'construction_age'),
                "construction_type" => \CommonHelpers::checkArrayKey($data,'construction_type'),
                "size_floors" => \CommonHelpers::checkArrayKey($data,'size_floors'),
                "size_floors_other" => \CommonHelpers::checkArrayKey($data,'size_floorsOther'),
                "size_staircases" => \CommonHelpers::checkArrayKey($data,'size_staircases'),
                "size_staircases_other" => \CommonHelpers::checkArrayKey($data,'size_staircasesOther'),
                "size_lifts" => \CommonHelpers::checkArrayKey($data,'size_lifts'),
                "size_lifts_other" => \CommonHelpers::checkArrayKey($data,'size_liftsOther'),
                "size_net_area" => \CommonHelpers::checkArrayKey($data,'size_net_area'),
                "electrical_meter" => \CommonHelpers::checkArrayKey($data,'electricalMeter'),
                "gas_meter" => \CommonHelpers::checkArrayKey($data,'gasMeter'),
                "loft_void" => \CommonHelpers::checkArrayKey($data,'loftVoid'),
                "size_bedrooms" => \CommonHelpers::checkArrayKey($data,'size_bedrooms'),
                "size_gross_area" => \CommonHelpers::checkArrayKey($data,'size_gross_area'),
                "size_comments" => \CommonHelpers::checkArrayKey($data,'size_comments'),
            ];
        }
        try {
            $message = '';
            if($id) {
                Property::where('id', $id)->update($dataProperty);
                $property = Property::where('id', $id)->first();
                $property->propertyType()->sync(\CommonHelpers::checkArrayKey($data,'riskType'));
                $message = 'Property Updated Successfully!';
                $comment = \Auth::user()->full_name . " edited Property " . $property->name;
                //log audit
                \CommonHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_EDIT, $property->property_reference, $property->client_id, $comment, 0 , $property->id);

                //update and send email
                \CommonHelpers::isRegisterUpdated($id);
            } else {
                $property =  Property::create($dataProperty);
                if ($property) {
                    $reference = 'PL'.$property->id;
                    Property::where('id', $property->id)->update(['reference' => $reference]);
                    $property->propertyType()->sync(\CommonHelpers::checkArrayKey($data,'riskType'));

                    //update and send email
                    \CommonHelpers::isRegisterUpdated($property->id);

                    //log audit
                    $comment = \Auth::user()->full_name . " added New Property " . $property->name;
                    \CommonHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_ADD, $property->property_reference, $property->client_id, $comment, 0 , $property->id);

                    //update EMP
                    // if (\CompliancePrivilege::checkPermission(PROPERTY_EMP_VIEW_PRIV) || (isset(\Auth::user()->userRole->is_everything) and (\Auth::user()->userRole->is_everything == 1))) {
                        \CompliancePrivilege::setViewEMP(PROPERTY_EMP_VIEW_PRIV, $property->id);
                    // }

                    //update EMP
                    // if (\CompliancePrivilege::checkUpdatePermission(PROPERTY_EMP_UPDATE_PRIV) || (isset(\Auth::user()->userRole->is_everything) and (\Auth::user()->userRoleUpdate->is_everything == 1))) {
                        \CompliancePrivilege::setUpdateEMP(PROPERTY_EMP_UPDATE_PRIV, $property->id);
                    // }

                }
                $message = 'Property Added Successfully!';
            }

            if ($property) {
                PropertyInfo::updateOrCreate(['property_id' => $property->id], $dataPropertyInfo);
                PropertySurvey::updateOrCreate(['property_id' => $property->id], $dataPropertySurvey);
                // store comment history
                \CommentHistory::storeCommentHistory('property', $property->id, $dataProperty['comments']);
            }
            if (isset($data['photo'])) {
                $saveLocationImage = \CommonHelpers::saveFileShineDocumentStorage($data['photo'], $property->id, PROPERTY_PHOTO);
            }
            return $response = \CommonHelpers::successResponse($message, $property);
        } catch (\Exception $e) {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to create or update property. Please try again!');
        }

    }

    public function updateOrCreatePropertyPlan($data, $id = null) {
        if (!empty($data)) {
            // SitePlanDocument
            $dataPlan = [
                "property_id" => \CommonHelpers::checkArrayKey3($data,'property_id'),
                "name" => \CommonHelpers::checkArrayKey3($data,'name'),
                "plan_reference" => \CommonHelpers::checkArrayKey($data,'description'),
                "survey_id" => \CommonHelpers::checkArrayKey3($data,'survey_id'),
                "type" => \CommonHelpers::checkArrayKey3($data,'type'),
                "category" => \CommonHelpers::checkArrayKey3($data,'category'),

                "added" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'plan_date')),
            ];
            $type_log = ($data['survey_id'] == 0) ? SITE_PLAN_DOCUMENT_TYPE : SURVEY_PLAN_DOCUMENT_TYPE;

            try {
                if (is_null($id)) {
                    $dataPlan["added_by"] = \Auth::user()->id;
                    $plan = SitePlanDocument::create($dataPlan);
                    if ($plan) {
                        if ($plan->survey_id == 0) {
                            $planRef = "PP" . $plan->id;
                        } else{
                            $planRef = "SP" . $plan->id;
                        }
                        SitePlanDocument::where('id', $plan->id)->update(['reference' => $planRef]);
                    //log audit
                    $comment = \Auth::user()->full_name . " add new plan " . $plan->name;
                    \CommonHelpers::logAudit($type_log, $plan->id, AUDIT_ACTION_ADD, $planRef, $dataPlan['survey_id'], $comment, 0 , $dataPlan['property_id']);
                    }
                    $response = CommonHelpers::successResponse('Upload plan document successfully !');
                } else {
                    SitePlanDocument::where('id', $id)->update($dataPlan);
                    $plan = SitePlanDocument::where('id', $id)->first();
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
                    SitePlanDocument::where('id', $plan->id)->update($dataUpdateImg);
                }

                return $response;
            } catch (\Exception $e) {
                return CommonHelpers::failResponse(STATUS_FAIL,'Failed to upload plan document. Please try again !');
            }
        }
    }

    public function updateOrCreateHistoricalCategory($data, $id = null) {
        if (!empty($data)) {
            $dataCat = [
                'category' => \CommonHelpers::checkArrayKey($data,'category_title'),
                'property_id' => \CommonHelpers::checkArrayKey($data,'property_id'),
                'order' => 0
            ];

            try {
                if(is_null($id)) {
                    $historicalCat =  HistoricDocCategory::create($dataCat);
                    $message = 'Historical Category Added Successfully!';

                    //log audit
                    \CommonHelpers::logAudit(HISTORICAL_CATEGORY_TYPE, $historicalCat->id, AUDIT_ACTION_ADD, $historicalCat->category, $historicalCat->property_id ,null, 0 ,$historicalCat->property_id);
                } else {
                    $historicalCat =  HistoricDocCategory::where('id', $id)->update($dataCat);
                    $message = 'Historical Category Updated Successfully!';
                    //log audit
                    \CommonHelpers::logAudit(HISTORICAL_CATEGORY_TYPE, $id, AUDIT_ACTION_EDIT, $dataCat['category'], $dataCat['property_id'] ,null, 0 ,$dataCat['property_id']);
                }
                return $response = \CommonHelpers::successResponse($message);

            } catch (\Exception $e) {
                return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to create or update historical category. Please try again!');
            }
        }
    }

    public function updateOrCreateHistoricalDoc($data, $id = null) {
        if (!empty($data)) {
            $dataHistorical = [
                'property_id' => \CommonHelpers::checkArrayKey($data,'property_id'),
                'name' => \CommonHelpers::checkArrayKey($data,'name'),
                'doc_type' => \CommonHelpers::checkArrayKey($data,'document_type'),
                'category' => \CommonHelpers::checkArrayKey($data,'category'),
                'is_external_ms' => isset($data['is_external_ms']) ? 1 : 0,
                'added' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'historic_date')),
                'created_by' => \Auth::user()->id,
            ];

            try {
                if(is_null($id)) {
                    $historical =  HistoricDoc::create($dataHistorical);
                    if ($historical) {
                        $refHD = "HD" . $historical->id;
                        //update is_external_ms by doc_type
                        HistoricDoc::where('id', $historical->id)->update(['reference' => $refHD, 'is_external_ms' => $historical->historicalDocType->is_external_ms ?? 0]);
                    }
                    $message = 'Historical Document Added Successfully!';

                    //log audit
                    \CommonHelpers::logAudit(HISTORICAL_DATA_TYPE, $historical->id, AUDIT_ACTION_ADD, $refHD, $historical->category ,null, 0 ,$historical->property_id);
                } else {
                    $historical = HistoricDoc::where('id', $id)->first();
                    //update is_external_ms by doc_type
                    $dataHistorical['is_external_ms'] = $historical->historicalDocType->is_external_ms ?? 0;
                    $updateHistorical =  HistoricDoc::where('id', $id)->update($dataHistorical);

                    $message = 'Historical Document Updated Successfully!';

                    //log audit
                    \CommonHelpers::logAudit(HISTORICAL_DATA_TYPE, $historical->id, AUDIT_ACTION_EDIT, $historical->reference, $historical->category ,null, 0 ,$historical->property_id);
                }
                if (isset($data['document'])) {
                    $saveLocationImage = \CommonHelpers::saveFileShineDocumentStorage($data['document'], $historical->id, HISTORICAL_DATA);
                }
                return $response = \CommonHelpers::successResponse($message);

            } catch (\Exception $e) {
                return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to create or update historical document. Please try again!');
            }
        }
    }

    public function decommissionProperty($property_id, $reason) {
        $property = Property::find($property_id);
        $surveys = Survey::where('property_id', $property_id)->get();
        $areas = Area::where('property_id', $property_id)->get();
        $locations = Location::where('property_id', $property_id)->get();
        $items = Item::where('property_id', $property_id)->get();
        try {
            if ($property->decommissioned == PROPERTY_DECOMMISSION) {
                Property::where('id', $property_id)->update(['decommissioned' => PROPERTY_UNDECOMMISSION]);
                Survey::where('property_id', $property_id)->update(['decommissioned' => SURVEY_UNDECOMMISSION]);
                Area::where('property_id', $property_id)->update(['decommissioned' => AREA_UNDECOMMISSION]);
                Location::where('property_id', $property_id)->update(['decommissioned' => LOCATION_UNDECOMMISSION]);
                Item::where('property_id', $property_id)->update(['decommissioned' => ITEM_UNDECOMMISSION]);

                //update and send email
                \CommonHelpers::isRegisterUpdated($property->id);
                //log audit
                //log audit
                if (!is_null($surveys)) {
                    foreach ($surveys as $survey) {
                       $comment = \Auth::user()->full_name . " Recommission Survey ".$survey->reference." by Recommission Property " . $property->name;
                       \CommonHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_RECOMMISSION, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);
                    }
                }
                if (!is_null($areas)) {
                    foreach ($areas as $area) {
                       $comment = \Auth::user()->full_name . " Recommission Area ".$area->area_reference." by Recommission Property " . $property->name;
                       \CommonHelpers::logAudit(AREA_TYPE, $area->id, AUDIT_ACTION_RECOMMISSION, $area->area_reference, $area->survey_id, $comment , 0 , $area->property_id ?? 0);
                    }
                }

                if (!is_null($locations)) {
                    foreach ($locations as $location) {
                       $comment = \Auth::user()->full_name . " Recommission Location ".$location->location_reference." by Recommission Property " . $property->name;
                       \CommonHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_RECOMMISSION, $location->location_reference, $location->survey_id ,$comment, 0 ,$location->property_id);
                    }
                }
                if (!is_null($items)) {
                    foreach ($items as $item) {
                       $comment = \Auth::user()->full_name . " Recommission Item ".$item->reference." by Recommission Property " . $property->name;
                       \CommonHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_RECOMMISSION, $item->reference, $item->survey_id ,$comment, 0 ,$item->property_id);
                    }
                }
                $comment = \Auth::user()->full_name . " recommission Property " . $property->name;
                \CommonHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_RECOMMISSION, $property->property_reference, $property->client_id, $comment, 0 , $property->id);

                return \CommonHelpers::successResponse('Property Recommissioned Successfully!');
            } else {
                Property::where('id', $property_id)->update(['decommissioned' => PROPERTY_DECOMMISSION, 'decommissioned_reason' => $reason]);
                Survey::where('property_id', $property_id)->update(['decommissioned' => SURVEY_DECOMMISSION]);
                Area::where('property_id', $property_id)->update(['decommissioned' => AREA_DECOMMISSION]);
                Location::where('survey_id', $property_id)->update(['decommissioned' => LOCATION_DECOMMISSION]);
                Item::where('property_id', $property_id)->update(['decommissioned' => ITEM_DECOMMISSION]);

                //update and send email
                \CommonHelpers::isRegisterUpdated($property->id);
                //log audit
                if (!is_null($surveys)) {
                    foreach ($surveys as $survey) {
                       $comment = \Auth::user()->full_name . " Decommission Survey ".$survey->reference." by Decommission Property " . $property->name;
                       \CommonHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_DECOMMISSION, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);
                    }
                }
                if (!is_null($areas)) {
                    foreach ($areas as $area) {
                       $comment = \Auth::user()->full_name . " Decommission Area ".$area->area_reference." by Decommission Property " . $property->name;
                       \CommonHelpers::logAudit(AREA_TYPE, $area->id, AUDIT_ACTION_DECOMMISSION, $area->area_reference, $area->survey_id, $comment , 0 , $area->property_id ?? 0);
                    }
                }

                if (!is_null($locations)) {
                    foreach ($locations as $location) {
                       $comment = \Auth::user()->full_name . " Decommission Location ".$location->location_reference." by Decommission Property " . $property->name;
                       \CommonHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_DECOMMISSION, $location->location_reference, $location->survey_id ,$comment, 0 ,$location->property_id);
                    }
                }
                if (!is_null($items)) {
                    foreach ($items as $item) {
                       $comment = \Auth::user()->full_name . " Decommission Item ".$item->reference." by Decommission Property " . $property->name;
                       \CommonHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_DECOMMISSION, $item->reference, $item->survey_id ,$comment, 0 ,$item->property_id);
                    }
                }

                $comment = \Auth::user()->full_name . " Decommission Property " . $property->name;
                \CommonHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_DECOMMISSION, $property->property_reference, $property->client_id, $comment, 0 , $property->id);
                return \CommonHelpers::successResponse('Property Decommissioned Successfully!');
            }
        } catch (\Exception $e) {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Update Property Fail!');
        }
    }

    public function getPropertyOperative($client_id, $zone_id)
    {
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();
        $properties = Property::with('itemACM', 'locationInaccessible', 'propertyType')
        ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'id')
        ->where([ 'zone_id' => $zone_id])
        ->orderBy('name')->get();


        if (count($properties)) {
            foreach ($properties as $property) {
                $build_after_2000 = false;

                if(isset($property->propertyType) &&  !$property->propertyType->isEmpty()){
                    foreach ($property->propertyType as $p_risk_type){
                        if($p_risk_type->id == 2){
                            $build_after_2000 = true;
                            break;
                        }
                    }
                }
                if (!$build_after_2000) {
                    if ($property->decommissioned != 1) { // only for un decomission property
                        if (!is_null($property->itemACM)) {
                            $property->property_operative = ['bg_color' => '#f2dede', 'border_color' => '#eed3d7', 'text_color' => '#b94a48', 'text' => 'Asbestos Present'];
                        } else {
                            $first_inacc_void_location =  DB::select("SELECT lv.* from tbl_location_void lv
                                                            JOIN tbl_location l ON lv.location_id = l.id
                                                            LEFT JOIN tbl_property p ON l.property_id = p.id
                                                            WHERE p.id = $property->id AND l.decommissioned = 0 AND l.survey_id = 0 AND
                                                            (
                                                                FIND_IN_SET(1108, lv.ceiling)
                                                                OR FIND_IN_SET(1453, lv.floor)
                                                                OR FIND_IN_SET(1216, lv.cavities)
                                                                OR FIND_IN_SET(1280, lv.risers)
                                                                OR FIND_IN_SET(1344, lv.ducting)
                                                                OR FIND_IN_SET(1733, lv.boxing)
                                                                OR FIND_IN_SET(1606, lv.pipework)
                                                            ) LIMIT 1");
                            if (!is_null($property->locationInaccessible) || $first_inacc_void_location || !is_null($property->itemInaccACM)) {
                                $property->property_operative = ['bg_color' => '#f2dede', 'border_color' => '#eed3d7', 'text_color' => '#b94a48', 'text' => 'Asbestos Presumed'];
                            } else {
                                $property->property_operative = ['bg_color' => '#dff0d8', 'border_color' => '#d6e9c6', 'text_color' => '#468847', 'text' => 'No Asbestos Detected'];
                            }
                        }
                    } else {
                        $property->property_operative = ['bg_color' => '#dff0d8', 'border_color' => '#d6e9c6', 'text_color' => '#468847', 'text' => 'Property Build in or After 2000'];
                    }
                } else {
                    $property->property_operative = ['bg_color' => '#dff0d8', 'border_color' => '#d6e9c6', 'text_color' => '#468847', 'text' => 'Property Build in or After 2000'];
                }
            }
        }
        return $properties;
    }

    public function isHasInaccessibleVoidLocation($property_id){
        $first_inacc_void_location =  DB::select("SELECT lv.* from tbl_location_void lv
                                                            JOIN tbl_location l ON lv.location_id = l.id
                                                            LEFT JOIN tbl_property p ON l.property_id = p.id
                                                            WHERE p.id = $property_id AND l.decommissioned = 0 AND l.survey_id = 0 AND
                                                            (
                                                                FIND_IN_SET(1108, lv.ceiling)
                                                                OR FIND_IN_SET(1453, lv.floor)
                                                                OR FIND_IN_SET(1216, lv.cavities)
                                                                OR FIND_IN_SET(1280, lv.risers)
                                                                OR FIND_IN_SET(1344, lv.ducting)
                                                                OR FIND_IN_SET(1733, lv.boxing)
                                                                OR FIND_IN_SET(1606, lv.pipework)
                                                            ) LIMIT 1");
        return $first_inacc_void_location ? true : false;
    }

    public function searchProperty($query_string, $type = 0, $parent = null) {
        $query_string =  addslashes($query_string);

        if ($type == 1) {
            $searchSQL = " p.`property_reference` LIKE '%" . $query_string . "%' ";
        } elseif ($type == 2) {
            $searchSQL = " p.`pblock` LIKE '%" . $query_string . "%'";
        } else {
            $searchSQL = " ( p.`name` LIKE '%" . $query_string . "%'
                        OR p.`property_reference` LIKE '%" . $query_string . "%'
                        OR pi.`address1` LIKE '%" . $query_string . "%'
                        OR  REPLACE(pi.postcode, ' ', '') LIKE '%" . str_replace(" ", "", $query_string) . "%'
                        OR p.`reference` LIKE '%" . $query_string . "%'
                        OR p.`pblock` LIKE '%" . $query_string . "%' ) ";
        }

        if (!is_null($parent)) {
            $searchSQL = " ( p.`name` LIKE '%" . $query_string . "%'
                        OR p.`property_reference` LIKE '%" . $query_string . "%'
                        OR  REPLACE(pi.postcode, ' ', '') LIKE '%" . str_replace(" ", "", $query_string) . "%'
                        OR p.`reference` LIKE '%" . $query_string . "%'
                        OR p.`pblock` LIKE '%" . $query_string . "%' )
                        AND parent_reference IS NULL";
        }
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

        if($type == 3){
            $decommission = "1";
        }else{
            $decommission = "0";
        }

        $sql = "SELECT p.id, p.name, p.reference, p.property_reference, pi.postcode, p.pblock
                FROM `tbl_property` as p
                LEFT JOIN tbl_property_info as pi ON pi.property_id = p.id
                JOIN $table_join_privs ON permission.prop_id = p.id
                WHERE $searchSQL
                AND p.`decommissioned` = $decommission
                ORDER BY
                CASE
                    WHEN p.`name` LIKE '" . $query_string . "' THEN 1
                    WHEN p.`name` LIKE '" . $query_string . "%' THEN 2
                    ELSE 3
                END, p.`property_reference`
                LIMIT 0,30";


        $list = DB::select($sql);

        return $list;
    }

    //get warning message of property for register pdf
    public function getWarningMessage($property)
    {
        $message = [];
        $pink_message = '';

        if ($property) {
            //todo comment+ check survey
            $first_acm_item = Item::where('state', '!=', ITEM_NOACM_STATE)->where(['decommissioned' => ITEM_UNDECOMMISSION, 'property_id' => $property->id, 'survey_id' => 0])->first();
            if ($first_acm_item) {
                $message[] = 'Asbestos Present within Property';
            }
            //todo comment
            $first_inacc_acm_full_accessment_item = Item::with('firstAcmFullAccess')->where('state', '=', ITEM_INACCESSIBLE_STATE)
                ->where(['decommissioned' => ITEM_UNDECOMMISSION, 'property_id' => $property->id, 'survey_id' => 0])->first();
            if ($first_inacc_acm_full_accessment_item && $first_inacc_acm_full_accessment_item->firstAcmFullAccess) {
                $message[] = 'Inaccessible Asbestos (Full Assessment); Asbestos must be Presumed to be Present';
            }
            //todo comment
            $first_inacc_acm_limit_accessment_item = Item::with('firstAcmLimitAccess')->where('state', '=', ITEM_INACCESSIBLE_STATE)
                ->where(['decommissioned' => ITEM_UNDECOMMISSION, 'property_id' => $property->id, 'survey_id' => 0])->first();
            if ($first_inacc_acm_limit_accessment_item && $first_inacc_acm_limit_accessment_item->firstAcmLimitAccess) {
                $message[] = 'Inaccessible Asbestos (Limited Assessment); Asbestos must be Presumed to be Present';
            }

            $first_acm_mas_12_item = Item::where('state', '!=', ITEM_NOACM_STATE)->where(['decommissioned' => ITEM_UNDECOMMISSION, 'property_id' => $property->id, 'survey_id' => 0, 'total_mas_risk' => 12])->first();
            //keep
            if ($first_acm_mas_12_item) {
                $message[] = 'High Risk Asbestos Present within the Property';
            }
            //todo comment + check survey
            $first_inacc_location = Location::where('state', '=', LOCATION_STATE_INACCESSIBLE)
                ->where(['decommissioned' => LOCATION_UNDECOMMISSION, 'property_id' => $property->id, 'survey_id' => 0])->first();
            if ($first_inacc_location) {
                $message[] = 'Inaccessible Room/locations; Asbestos must be Presumed to be Present';
            }
            //first project not complete
            //keep
            $first_progress_project = Project::whereIn('status', [1, 2, 3, 4])->where(['property_id' => $property->id])->first();
            $first_survey = Survey::whereNotIn('status', [COMPLETED_SURVEY_STATUS, REJECTED_SURVEY_STATUS])->where(['decommissioned' => SURVEY_UNDECOMMISSION, 'property_id' => $property->id])->first();
            if ($first_progress_project || $first_survey) {
                $message[] = 'Asbestos Project Works in Progress';
            }
            // comment
            if ($this->getDataNAInaccessibleLocations($property->id) > 0) {
                $message[] = 'Inaccessible Voids; Asbestos must be Presumed to be Present';
            }
            //keep
            $first_remedial_item = Item::with('ActionRecommendationValue')->whereHas('ActionRecommendationValue', function ($query) use($property) {
                return $query->whereIn('dropdown_data_item_id', ACTION_RECOMMENDATION_LIST_ID)->where(['property_id' => $property->id, 'survey_id' => 0, 'decommissioned' => ITEM_UNDECOMMISSION])->where('state', '!=', ITEM_NOACM_STATE);
            })->first();
            if ($first_remedial_item) {
                $message[] = 'Remedial or Removal Work Outstanding';
            }
        }

        //pink warning
        if (isset($property->propertyType) && !$property->propertyType->isEmpty()){
            $flag = false;
            foreach ($property->propertyType as $p_risk_type){
                //show warning message when select Duty to Manage 1, Duty to Manage (Partial Responsibility) 9, Duty to Manage (Delegated Responsibility) 10, Duty of Care 11, Duty of Care (Employees Only) 12.
                if( in_array($p_risk_type->id, [1,9,10,11,12])){
                    $flag = true;
                    break;
                }
            }
            // if ms_level = 1 then adding one more message
            // Asbestos Demolition Survey  = 3
            // Asbestos Management and Refurbishment Survey = 4
            // Asbestos Management Survey = 5
            // Asbestos Refurbishment Survey  = 6
            // Asbestos Re-Inspection Survey = 7
            // comment all + keep External Survey Added to Histor .. + get message from Orchard
            if($flag){
                //  when there is a completed Management Survey OR (Management and Refurbishment Survey) in the Survey Page but no ACMs in the Property Asbestos Register
                if ($property->completeMsSurvey && !$property->itemACM) {
                    $message[] = 'Management Survey conducted and No Presumed/Identified Asbestos Found; Further Inspection to be Undertaken Prior to Refurbishment Works';
                } else {
                    if(isset($property->completeRsSurvey) && !isset($property->completeMsSurvey)){
                        //when the User has completed a Refurbishment Survey when there is not a completes Management Survey OR Management and Refurbishment Survey on the Survey Page
                        $message[] = 'Targetted Refurbishment Survey only; Asbestos must be Presumed to be Present in the Remaining Unsurveyed Room/locations';
                    } else {
                        if (isset($property->historicalDoc) && !$property->historicalDoc->isEmpty()) {
                            $is_external = $property->historicalDoc->whereIn('doc_type', [3,4,5,6,7])->first();
                            if($is_external){
                                //when the User has added a Management Survey OR Management and Refubishment Survey OR Refurbishment Survey OR Demolition Survey OR a Re-Inspection Survey to the Historical Data Section on Properties
                                $message[] = 'External Survey Added to Historical Data Tab; Asbestos must be Presumed to be Present';
                            } else {
                                if(!$property->completeMsSurvey){
                                    $message[] = 'Management Survey Not Completed; Asbestos must be Presumed to be Present';
                                }
                            }
                        } else {
                            if(!$property->completeMsSurvey){
                                // when there is not a completed Management Survey OR Management and Refurbishment Survey in the Survey Page OR a Management Survey OR a Management and Refurbishment Survey added to the Historical Data Section
                                $message[] = 'Management Survey Not Completed; Asbestos must be Presumed to be Present';
                            }
                        }
                    }
                }

            }
        }

        if(count($message)){
            //warning message
            return implode(" and ", $message);
        }
//        else {
//            //pink warning
//            if(isset($property->completeMsSurvey) && !$property->completeMsSurvey->isEmpty()){
//                if(!$property->itemACM){
//                    $pink_message = 'Management Survey conducted and No Presumed/Identified Asbestos Found; Further Inspection to be Undertaken Prior to Refurbishment Works';
//                }
//            }else{
//                if(isset($property->historicalDoc) && !$property->historicalDoc->isEmpty()){
//                    $is_external_ms = $property->historicalDoc->firstWhere('is_external_ms',1);
//                    if($is_external_ms){
//                        $pink_message = 'External Survey Added to Historical Data Tab; Asbestos must be Presumed to be Present';
//                    } else {
//                        if(isset($property->area) && !$property->area->isEmpty()){
//                            $first_area_register = $property->area->firstWhere('decommissioned',AREA_UNDECOMMISSION);
//                            if(!$first_area_register){
//                                $pink_message = 'Management Survey Not Completed; Asbestos must be Presumed to be Present';
//                            }
//                        }
//                    }
//                }
//            }
//            return $pink_message;
//        }
    }

        //get warning message of property for register pdf
    public function getWarningMessagev2($property)
    {
        $message = [];
        $pink_message = '';

        if ($property) {

            $first_acm_mas_12_item = Item::where('state', '!=', ITEM_NOACM_STATE)->where(['decommissioned' => ITEM_UNDECOMMISSION, 'property_id' => $property->id, 'survey_id' => 0, 'total_mas_risk' => 12])->first();
            //keep
            if ($first_acm_mas_12_item) {
                $message[] = 'High Risk Asbestos Present within the Property';
            }
            //first project not complete
            //keep
            $first_progress_project = Project::whereIn('status', [1, 2, 3, 4])->where(['property_id' => $property->id])->first();
            $first_survey = Survey::whereNotIn('status', [COMPLETED_SURVEY_STATUS, REJECTED_SURVEY_STATUS])->where(['decommissioned' => SURVEY_UNDECOMMISSION, 'property_id' => $property->id])->first();
            if ($first_progress_project || $first_survey) {
                $message[] = 'Asbestos Project Works in Progress';
            }
            //keep
            $first_remedial_item = Item::with('ActionRecommendationValue')->whereHas('ActionRecommendationValue', function ($query) use($property) {
                return $query->whereIn('dropdown_data_item_id', ACTION_RECOMMENDATION_LIST_ID)->where(['property_id' => $property->id, 'survey_id' => 0, 'decommissioned' => ITEM_UNDECOMMISSION])->where('state', '!=', ITEM_NOACM_STATE);
            })->first();
            if ($first_remedial_item) {
                $message[] = 'Remedial or Removal Work Outstanding';
            }
        }
        $property_id = $property->id;
        //pink warning
        if (isset($property->propertyType) && !$property->propertyType->isEmpty()){
            $flag = false;
            foreach ($property->propertyType as $p_risk_type){
                //show warning message when select Duty to Manage 1, Duty to Manage (Partial Responsibility) 9, Duty to Manage (Delegated Responsibility) 10, Duty of Care 11, Duty of Care (Employees Only) 12.
                if( in_array($p_risk_type->id, [1,9,10,11,12])){
                    $flag = true;
                    break;
                }
            }
            // if ms_level = 1 then adding one more message
            // Asbestos Demolition Survey  = 3
            // Asbestos Management and Refurbishment Survey = 4
            // Asbestos Management Survey = 5
            // Asbestos Refurbishment Survey  = 6
            // Asbestos Re-Inspection Survey = 7
            // comment all + keep External Survey Added to Histor .. + get message from Orchard
            if($flag) {
                $warning = $this->getOrchardWarningMessage($property_id);
                $message[] = $warning['long'];
                if (isset($property->historicalDoc) && !$property->historicalDoc->isEmpty()) {
                    $is_external = $property->historicalDoc->whereIn('doc_type', [3,4,5,6,7])->first();
                    if($is_external){
                        //when the User has added a Management Survey OR Management and Refubishment Survey OR Refurbishment Survey OR Demolition Survey OR a Re-Inspection Survey to the Historical Data Section on Properties
                        $message[] = 'External Survey Added to Historical Data Tab; Asbestos must be Presumed to be Present';
                    }
                }
            }

        }

        if(count($message)){
            //warning message
            return implode(" with ", $message);
        }

    }

    //get array zone if base on list property_id
    public function listZoneId($property_ids){
        $property_ids = $property_ids ? $property_ids : 'NULL';
        $result = Property::whereRaw("id IN($property_ids)")->groupBy('zone_id')->pluck('zone_id')->toArray();
        return $result;
    }

    //get parent for property by reference
    public function getParentProperty($parent_id){
        $parent = Property::where('id', $parent_id)->first();
        return $parent;
    }

    //get parent for property by reference
    public function getChildrenProperty($id){
        $child = Property::where('parent_id', $id)->get();
        return $child;
    }

    //for NA chart
    public function getDataNAInaccessibleLocations($property_id){
        $condition = '';
        if($property_id && $property_id > 0){
            $condition = " AND p.id = " . $property_id;
        }

        $count_ceilling = DB::table('tbl_property as p')
            ->leftJoin('tbl_location as l','l.property_id','=','p.id')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.ceiling, ',', 1) = 1108 ".$condition)
            ->where(['p.decommissioned'=>PROPERTY_UNDECOMMISSION,'l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0])
            ->groupBy('p.id')
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->value('count');

        $count_floor = DB::table('tbl_property as p')
            ->leftJoin('tbl_location as l','l.property_id','=','p.id')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.floor, ',', 1) = 1453 ".$condition)
            ->where(['p.decommissioned'=>PROPERTY_UNDECOMMISSION,'l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0])
            ->groupBy('p.id')
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->value('count');

        $count_cavities = DB::table('tbl_property as p')
            ->leftJoin('tbl_location as l','l.property_id','=','p.id')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.cavities, ',', 1) = 1216 ".$condition)
            ->where(['p.decommissioned'=>PROPERTY_UNDECOMMISSION,'l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0])
            ->groupBy('p.id')
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->value('count');

        $count_risers = DB::table('tbl_property as p')
            ->leftJoin('tbl_location as l','l.property_id','=','p.id')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.risers, ',', 1) = 1280 ".$condition)
            ->where(['p.decommissioned'=>PROPERTY_UNDECOMMISSION,'l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0])
            ->groupBy('p.id')
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->value('count');

        $count_ducting = DB::table('tbl_property as p')
            ->leftJoin('tbl_location as l','l.property_id','=','p.id')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.ducting, ',', 1) = 1344 ".$condition)
            ->where(['p.decommissioned'=>PROPERTY_UNDECOMMISSION,'l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0])
            ->groupBy('p.id')
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->value('count');

        $count_boxing = DB::table('tbl_property as p')
            ->leftJoin('tbl_location as l','l.property_id','=','p.id')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.boxing, ',', 1) = 1733 ".$condition)
            ->where(['p.decommissioned'=>PROPERTY_UNDECOMMISSION,'l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0])
            ->groupBy('p.id')
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->value('count');

        $count_pipework = DB::table('tbl_property as p')
            ->leftJoin('tbl_location as l','l.property_id','=','p.id')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.pipework, ',', 1) = 1606 ".$condition)
            ->where(['p.decommissioned'=>PROPERTY_UNDECOMMISSION,'l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0])
            ->groupBy('p.id')
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->value('count');

        return $count_ceilling + $count_floor + $count_cavities + $count_risers + $count_ducting + $count_boxing + $count_pipework;
    }


    public static function get_data_stamping($data){
        $user_created = User::with('clients', 'department', 'departmentContractor')->find($data->created_by ?? 0);
        $user_updated = User::with('clients', 'department', 'departmentContractor')->find($data->updated_by ?? 0);

        $result_return = [
            'data_stamping' => isset($data->updated_at) ? $data->updated_at->format('d/m/Y H:i') : 'N/A',
            'organisation'  => !is_null($user_updated) ? ($user_updated->clients->name ?? '' ).' - ' .\CommonHelpers::getDepartmentname($user_updated) : 'N/A',
            'username'      =>  $user_updated->full_name ?? 'N/A',
            'data_stamping_create'  => isset($data->created_at) ? $data->created_at->format('d/m/Y H:i') : 'N/A',
            'organisation_create'   => !is_null($user_created) ? ($user_created->clients->name ?? '' ).' - ' .\CommonHelpers::getDepartmentname($user_created) : 'N/A',
            'username_create'    =>  $user_created->full_name ?? 'N/A',
        ];

        return $result_return;
    }

    public function getOrchardWarningMessage($property_id) {
        $property = Property::find($property_id);
        // When Post 2000 Property has been selected in the Property Risk Type

        $build_after_2000 = false;

        if(isset($property->propertyType) &&  !$property->propertyType->isEmpty()){
            foreach ($property->propertyType as $p_risk_type){
                if($p_risk_type->id == 2){
                    $build_after_2000 = true;
                    break;
                }
            }
        }

        if($build_after_2000){
            return [
                'sort' => "No Asbestos Detected",
                'long' => "Property Build In or After 2000, No Asbestos Detected"
            ];
        } else {

            // CHECK MANAGEMENT SURVEY NOT COMPLETED
            $sql_not_complete_management_survey = "SELECT `id`
                                        FROM `tbl_survey`
                                        WHERE  `property_id` = '" . $property_id . "'
                                        AND `decommissioned` = 0 and survey_type = 1 and `status` not in (5) ";
            $not_complete_management_survey = \DB::select($sql_not_complete_management_survey);


            // CHECK MANAGEMENT SURVEY EXIST
            $sql_first_management_survey = "SELECT id
                                        FROM `tbl_survey`
                                        WHERE  `property_id` = '" . $property_id . "'
                                        AND `decommissioned` = 0 and survey_type = 1 ";
            $first_management_survey = \DB::select($sql_first_management_survey);

            // CHECK REFURBISH SURVEY NOT COMPLETED
            $sql_not_complete_refurbish_survey = "SELECT id
                                        FROM `tbl_survey`
                                        WHERE  `property_id` = '" . $property_id . "'
                                        AND `decommissioned` = 0 and survey_type = 2 and `status` not in (5) ";
            $not_complete_refurbish_survey = \DB::select($sql_not_complete_refurbish_survey);

            // CHECK REFURBISH SURVEY EXIST
            $sql_first_refurbish_survey = "SELECT id
                                        FROM `tbl_survey`
                                        WHERE  `property_id` = '" . $property_id . "'
                                        AND `decommissioned` = 0 and survey_type = 2 ";
            $first_refurbish_survey = \DB::select($sql_first_refurbish_survey);

            // CHECK INACCESSIBLE LOCATION AVAILABLE IN REGISTER
            $location_in_access_sql = "SELECT id
                                            FROM `tbl_location`
                                            WHERE `survey_id` = 0
                                                AND  `property_id` =  $property_id
                                                AND `state` = 0
                                                AND `decommissioned` = 0
                                            LIMIT 1";
            $location_in_accessible = \DB::select($location_in_access_sql);

            // CHECK ITEM ACM
            $item_acm_register_sql = "SELECT id
                                    FROM `tbl_items`
                                    WHERE
                                        property_id = $property_id
                                        AND `state` != 1
                                        AND `decommissioned` = 0
                                    LIMIT 1";
            $item_acm_register = \DB::select($item_acm_register_sql);

            // Management Survey was completed and there was no Inaccessible Room/locations OR ACM Items found in the Survey.
            // Inaccessible Room/locations in completed management survey
            $location_in_access_in_management_survey_sql = "SELECT l.id
                                            FROM `tbl_location` l
                                            LEFT JOIN tbl_survey s on l.survey_id = s.id
                                            WHERE s.`property_id` =  $property_id
                                                AND l.`state` = 0
                                                AND s.`survey_type` = 1
                                                AND l.`decommissioned` = 0
                                                AND s.`decommissioned` = 0
                                                AND s.`status` in (5)
                                            LIMIT 1";
            $location_in_access_in_management_survey = \DB::select($location_in_access_in_management_survey_sql);

            // Inaccessible Room/locations in completed FS survey
            $location_in_access_in_refurbish_survey_sql = "SELECT l.id
                                            FROM `tbl_location` l
                                            LEFT JOIN tbl_survey s on l.survey_id = s.id
                                            WHERE s.`property_id` =  $property_id
                                                AND l.`state` = 0
                                                AND s.`survey_type` = 1
                                                AND l.`decommissioned` = 0
                                                AND s.`decommissioned` = 0
                                                AND s.`status` in (5)
                                            LIMIT 1";
            $location_in_access_in_refurbish_survey = \DB::select($location_in_access_in_refurbish_survey_sql);

            // ACM in completed management survey
            $ACM_in_management_survey_sql = "SELECT i.id
                                            FROM `tbl_items` i
                                            LEFT JOIN tbl_survey s on i.survey_id = s.id
                                            WHERE s.`property_id` =  $property_id
                                                AND i.`state` != 1
                                                AND s.`survey_type` = 1
                                                AND i.`decommissioned` = 0
                                                AND s.`decommissioned` = 0
                                                AND s.`status` in (5)
                                            LIMIT 1";
            $ACM_in_management_survey = \DB::select($ACM_in_management_survey_sql);

            // ACM in completed FS survey
            $ACM_in_refurbish_survey_sql = "SELECT i.id
                                            FROM `tbl_items` i
                                            LEFT JOIN tbl_survey s on i.survey_id = s.id
                                            WHERE s.`property_id` =  $property_id
                                                AND i.`state` != 1
                                                AND s.`survey_type` = 2
                                                AND i.`decommissioned` = 0
                                                AND s.`decommissioned` = 0
                                                AND s.`status` in (5)
                                            LIMIT 1";
            $ACM_in_refurbish_survey = \DB::select($ACM_in_refurbish_survey_sql);

            if ($not_complete_management_survey  || !$first_management_survey) {
                if ($first_refurbish_survey and !$not_complete_refurbish_survey) {//have a RS completed
                    // When a <2000 Property on the shineAsbestos Software has not had a Management Survey completed but has had a Refurbishment Survey completed that found no Inaccessible Room/location or ACMs
                    //Refurbishment Survey was completed on the property and there was an Inaccessible Room/location in the Survey
                    if($location_in_access_in_refurbish_survey) {
                        return [
                            'sort' => "Asbestos Presumed",
                            'long' => "Targetted Refurbishment Survey Conducted only - Not Assessed and Inaccessible Room/locations; Asbestos must be Presumed to be Present"
                        ];
                    // Refurbishment Survey was completed on the property and there was an ACM in the Survey
                    } else if ($ACM_in_refurbish_survey) {
                        return [
                            'sort' => "Asbestos Identified",
                            'long' => "Targeted Refurbishment Survey Conducted; Asbestos Present within Property"
                        ];
                    }

                    if($item_acm_register){
                        // Refurbishment Survey completed and no Management Survey. There is ACMs in the Register
                        return [
                            'sort' => "Asbestos Identified",
                            'long' => "Targeted Refurbishment Survey Conducted only; Asbestos Present within the Property"
                        ];
                    } else if ( $location_in_accessible) {
                        // Refurbishment Survey completed and no Management Survey. There is ACMs in the Register
                        return [
                            'sort' => "Asbestos Presumed",
                            'long' => "Targetted Refurbishment Survey Conducted only - Not Assessed and Inaccessible Room/locations; Asbestos must be Presumed to be Present"
                        ];
                    } else {
                        return [
                            'sort' => "Asbestos Presumed",
                            'long' => "Targetted Refurbishment Survey only; Asbestos must be Presumed to be Present in the Remaining Unsurveyed Room/locations"
                        ];
                    }
                }
                // Management Survey not completed on the Property
                return [
                    'sort' => "Asbestos Presumed",
                    'long' => "Management Survey Not Completed; Asbestos must be Presumed to be Present"
                ];
            } else {
                // Management Survey was completed and there was an Inaccessible Room/location found in the Survey.
                if($location_in_access_in_management_survey) {
                    return [
                        'sort' => "Asbestos Presumed",
                        'long' => "Management Survey conducted; Inaccessible Room/locations; Asbestos must be Presumed to be Present"
                    ];
                }
                // Management Survey was completed and there was an ACM Item found in the Survey
                if($ACM_in_management_survey) {
                    return [
                        'sort' => "Asbestos Identified",
                        'long' => "Management Survey conducted; Asbestos Present within Property"
                    ];
                }
                // Management Survey was completed and there was no Inaccessible Room/locations OR ACM Items found in the Survey.
                if(!$location_in_access_in_management_survey || $ACM_in_management_survey) {
                    return [
                        'sort' => "Asbestos Presumed",
                        'long' => "Management Survey conducted and No Presumed/Identified Asbestos Found; Further Inspection to be Undertaken Prior to Refurbishment Works"
                    ];
                }

                if($item_acm_register){
                    // Management Survey completed and there is No ACMs or Inaccessible Room/locations in Register but there is Inaccessible Void Investigations in Register
                    return [
                        'sort' => "Asbestos Identified",
                        'long' => "Management Survey conducted; Asbestos Present within the Property"
                    ];
                } else if ($location_in_accessible) {
                    // When a <2000 Property on the shineAsbestos Software has had a Management Survey completed and Inaccessible Room/locations were found.
                    return [
                        'sort' => "Asbestos Presumed",
                        'long' => "Management Survey conducted; Inaccessible Room/locations; Asbestos must be Presumed to be Present"
                    ];
                } else {
                    // When a <2000 Property on the shineAsbestos Software has had a Management Survey completed and no Inaccessible Room/locations or ACMs were found.
                    return [
                        'sort' => "Asbestos Presumed",
                        'long' => "Management Survey conducted; No Presumed/Identified Asbestos Found; Further Inspection to be Undertaken Prior to Refurbishment Works"
                    ];
                }
            }

            return [
                'sort' => "Asbestos Presumed",
                'long' => "Management Survey Not Completed; Asbestos must be Presumed to be Present"
            ];
        }
    }

        public function getPropertySurveyAdminTool($prop_id, $decommissioned = 0) {
        $surveys = Survey::where('property_id', $prop_id)
            ->where('decommissioned', $decommissioned)->get();
        if (!is_null($surveys)) {
            foreach ($surveys as $value) {
                $value->reference = strip_tags($value->reference);
                $value->description = '';
            }
        }
        return is_null($surveys) ? [] : $surveys;
    }

    public function getPropertyProjectAdminTool($prop_id, $decommissioned = 0) {
        $projects = Project::where('property_id', $prop_id)->get();
        if (!is_null($projects)) {
            foreach ($projects as $value) {
                $value->reference = strip_tags($value->reference);
                $value->description = '';
            }
        }
        return is_null($projects) ? [] : $projects;
    }

    public function getZoneAdminTool($decommissioned = 0) {
        $zones = Zone::get();
        if (!is_null($zones)) {
            foreach ($zones as $value) {
                $value->reference = strip_tags($value->reference);
                $value->description = strip_tags($value->zone_name);
            }
        }
        return is_null($zones) ? [] : $zones;
    }

    public function getPropertyAreaAdminTool($prop_id, $decommissioned = 0, $survey_id = 0) {
        $areas = Area::where('property_id', $prop_id)->where('survey_id', $survey_id)
            ->where('decommissioned', $decommissioned)
            ->oldest('reference')
            ->oldest('description')->get();
        if (!is_null($areas)) {
            foreach ($areas as $value) {
                $value->reference = strip_tags($value->area_reference);
                $value->description = strip_tags($value->description);
                $value->shine_reference = $value->getOriginal('reference');
            }
        }
        return is_null($areas) ? [] : $areas;
    }

    public function getPropertyLocationAdminTool($area_id, $decommissioned = 0, $survey_id = 0, $is_locked = false) {
        $whereRaw = TRUE;
        if($is_locked){
            $whereRaw = "is_locked = 0";
        }
        $location = Location::where('area_id', $area_id)->where('survey_id', $survey_id)
            ->where('decommissioned', $decommissioned)
            ->whereRaw($whereRaw)
            ->oldest('reference')
            ->oldest('description')->get();

        if (!is_null($location)) {
            foreach ($location as $value) {
                $value->reference = strip_tags($value->location_reference);
                $value->description = strip_tags($value->description);
                $value->shine_reference = $value->getOriginal('reference');
            }
        }
        return is_null($location) ? [] : $location;
    }

    public function getPropertyItemAdminTool($location_id, $decommissioned = 0, $survey_id = 0) {
        $item  = NULL;
        if($location_id){
            $item = Item::where('location_id', $location_id)->where('survey_id', $survey_id)
                ->where('decommissioned', $decommissioned)
                ->oldest('name')
                ->oldest('reference')->get();

            if (!is_null($item)) {
                foreach ($item as $value) {
                    $value->reference = strip_tags($value->reference);
                    $value->description = strip_tags($value->name);
                }
            }
        }
        return is_null($item) ? [] : $item;
    }

    public function getDocumentAdminTool($type, $survey_id, $property_id, $project_id, $incident_id){
        $documents = NULL;
        switch ($type){
            case 'property_plan':
                $documents = SitePlanDocument::where(['survey_id' => 0,'property_id' => $property_id])->get();
                break;
            case 'property_historical':
                $documents = ComplianceDocument::where(['property_id' => $property_id])->get();
                break;
            case 'survey_sc':
                $documents = SampleCertificate::where(['survey_id' => $survey_id])->get();
                break;
            case 'survey_ac':
                $documents = AirTestCertificate::where(['survey_id' => $survey_id])->get();
                break;
            case 'survey_plan':
                $documents = SitePlanDocument::where(['survey_id' => $survey_id])->get();
                break;
            case 'tender_doc':
                $documents = Document::where(['project_id' => $project_id, 'category' => TENDER_DOC_CATEGORY])->get();
                break;
            case 'contractor_doc':
                $documents = Document::where(['project_id' => $project_id, 'category' => CONTRACTOR_DOC_CATEGORY])->get();
                break;
            case 'gsk_doc':
                $documents = Document::where(['project_id' => $project_id, 'category' => GSK_DOC_CATEGORY])->get();
                break;
            case 'preconstruction_doc':
                $documents = Document::where(['project_id' => $project_id, 'category' => PRE_CONSTRUCTION_DOC_CATEGORY])->get();
                break;
            case 'design_doc':
                $documents = Document::where(['project_id' => $project_id, 'category' => DESIGN_DOC_CATEGORY])->get();
                break;
            case 'commercial_doc':
                $documents = Document::where(['project_id' => $project_id, 'category' => COMMERCIAL_DOC_CATEGORY])->get();
                break;
            case 'planning_doc':
                $documents = Document::where(['project_id' => $project_id, 'category' => PLANNING_DOC_CATEGORY])->get();
                break;
            case 'prestart_doc':
                $documents = Document::where(['project_id' => $project_id, 'category' => PRE_START_DOC_CATEGORY])->get();
                break;
            case 'site_rec_doc':
                $documents = Document::where(['project_id' => $project_id, 'category' => SITE_RECORDS_DOC_CATEGORY])->get();
                break;
            case 'completion_doc':
                $documents = Document::where(['project_id' => $project_id, 'category' => COMPLETION_DOC_CATEGORY])->get();
                break;
            case 'incident_doc':
                $documents = IncidentReportDocument::where(['incident_report_id' => $incident_id])->get();
                break;
        }
        if (!is_null($documents)) {
            foreach ($documents as $value) {
                $value->reference = strip_tags($value->reference);
                $name = '';
                if(isset($value->name)){
                    $name = $value->name;
                } else if(isset($value->sample_reference)){
                    $name = $value->sample_reference;
                } else if(isset($value->air_test_reference)){
                    $name = $value->air_test_reference;
                }
                $value->description = strip_tags($name);
            }
        }
        return is_null($documents) ? [] : $documents;
    }

    public function getPropertyInfoDropdown($propertyInfoDropdownId)
    {
        return PropertyInfoDropdown::find($propertyInfoDropdownId)->propertyInfoDropdownData;
    }
}
