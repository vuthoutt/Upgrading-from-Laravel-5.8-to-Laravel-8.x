<?php
namespace App\Repositories;
use App\Http\Controllers\GeneratePDFController;
use App\Jobs\SendApprovalEmail;
use App\Jobs\SendEmail;
use App\Models\Client;
use App\Models\SurveyRejectHistory;
use App\Models\WorkEmailCC;
use App\Models\WorkRequest;
use App\Models\WorkStream;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Survey;
use App\Models\SurveyInfo;
use App\Models\ItemInfo;
use App\Models\SurveyDate;
use App\Models\SurveySetting;
use App\Models\Area;
use App\Models\Location;
use App\Jobs\SendClientEmailNotification;
use App\Models\LocationInfo;
use App\Models\LocationVoid;
use App\Models\LocationConstruction;
use App\Models\Item;
use App\Models\Property;
use App\Models\PropertySurvey;
use App\User;
use Carbon\Carbon;
use App\Repositories\ItemRepository;
use App\Models\SitePlanDocument;
use App\Models\ShineDocumentStorage;
use App\Models\SurveyAnswer;
use App\Models\Project;
use App\Models\Sample;
use App\Helpers\CommonHelpers;
use Illuminate\Support\Collection;
use App\Models\DropdownItemValue\ProductDebrisTypeValue;//3
use App\Models\DropdownItemValue\ExtentValue;//4
use App\Models\DropdownItemValue\AsbestosTypeValue;//5
use App\Models\DropdownItemValue\ActionRecommendationValue;//7
use App\Models\DropdownItemValue\AdditionalInformationValue;//8
use App\Models\DropdownItemValue\SampleCommentValue;//9
use App\Models\DropdownItemValue\SpecificLocationValue;//11
use App\Models\DropdownItemValue\AccessibilityVulnerabilityValue;//12
use App\Models\DropdownItemValue\LicensedNonLicensedValue;//13
use App\Models\DropdownItemValue\UnableToSampleValue;//14
use App\Models\DropdownItemValue\ItemNoAccessValue;//15
use App\Models\DropdownItemValue\NoACMCommentsValue;//16
use App\Models\DropdownItemValue\PriorityAssessmentRiskValue;//18
use App\Models\DropdownItemValue\MaterialAssessmentRiskValue;//19
use App\Models\DropdownItemValue\SampleIdValue;//500
use App\Models\DropdownItemValue\SubSampleIdValue;//502
use App\Jobs\SendClientEmail;
use App\Jobs\SendDuplicateDataEmailWarning;
use App\Jobs\SendHighRiskSurveyApproval;
use Illuminate\Support\Facades\DB;

class SurveyRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;

    }

    function model()
    {
        return Survey::class;
    }

    /**
     * Get survey with all relation from id
     */

    public function getApprovalSurvey() {
        if (\CommonHelpers::isSystemClient()) {
            // property privilege
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();

            $survey = Survey::with('project','property', 'surveyDate','publishedSurvey')
                        ->where('decommissioned', SURVEY_UNDECOMMISSION)
                        ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
                        ->where('status', PULISHED_SURVEY_STATUS)->get();
        } else {
            $survey = Survey::with('project','property', 'surveyDate','publishedSurvey')
                        ->where('decommissioned', SURVEY_UNDECOMMISSION)
                        ->where('status', PULISHED_SURVEY_STATUS)
                        ->where('client_id', \Auth::user()->client_id)
                        ->get();

        }
        return $survey;
    }

    public function getMyApprovalSurvey() {
            // property privilege
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();
            $user_id = \Auth::user()->id;

            $sqlQuery = "SELECT s.project_id, s.id, s.property_id, pr.pblock ,pj.title, s.status, pj.reference,s.status, pr.property_reference pref, pr.name pname,
                                sd.completed_date, sd.published_date, s.id as survey_id,sd.due_date,
                                s.reference as doc_type
                                FROM `tbl_survey` s
                                LEFT JOIN tbl_survey_date sd ON s.id =  sd.survey_id
                                LEFT JOIN tbl_survey_type st ON s.survey_type =  st.id
                                LEFT JOIN tbl_property pr ON pr.id =  s.property_id
                                LEFT JOIN tbl_project pj ON pj.id =  s.project_id
                                JOIN $table_join_privs on permission.prop_id = s.property_id
                                AND s.decommissioned = 0
                                AND s.status = 4
                                AND s.lead_by = $user_id
                                ORDER BY s.id DESC ";

        $results = DB::select($sqlQuery);
        return $results;
    }

    public function getRejectedSurvey() {
        if (\CommonHelpers::isSystemClient()) {
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

        $survey = DB::select("SELECT s.id, s.status,p.pblock ,
                    s.reference as survey_reference, pj.title, pj.reference  as project_reference,
                    p.name as property_name,
                    pj.id as project_id,
                    s.property_id,
                    si.comments, sd.due_date
                    FROM tbl_survey s
                    LEFT JOIN tbl_survey_info as si ON si.survey_id = s.id
                    LEFT JOIN tbl_survey_date as sd ON sd.survey_id = s.id
                    LEFT JOIN tbl_project as pj ON pj.id = s.project_id
                    LEFT JOIN tbl_property as p ON p.id = s.property_id
                    LEFT JOIN (
                        SELECT GROUP_CONCAT(t.description) as reject_type_description, srt.survey_id FROM tbl_survey_reject_history srt
                        JOIN tbl_rejection_type t on FIND_IN_SET(t.id,srt.rejection_type_ids)
                        GROUP BY srt.survey_id
                    ) his ON his.survey_id = s.id
                    JOIN $table_join_privs ON permission.prop_id = s.property_id
                    WHERE s.decommissioned = 0
                    AND (s.status = 6 OR (si.comments IS NOT NULL AND s.status NOT IN (4,5)))
                    ORDER BY s.id DESC");
        } else {
        $client_id = \Auth::user()->client_id;
        $survey = DB::select("SELECT s.id, s.status,p.pblock ,
                    s.reference as survey_reference, pj.title, pj.reference  as project_reference,
                    p.name as property_name,
                    pj.id as project_id,
                    s.property_id,
                    si.comments, sd.due_date
                    FROM tbl_survey s
                    LEFT JOIN tbl_survey_info as si ON si.survey_id = s.id
                    LEFT JOIN tbl_survey_date as sd ON sd.survey_id = s.id
                    LEFT JOIN tbl_project as pj ON pj.id = s.project_id
                    LEFT JOIN tbl_property as p ON p.id = s.property_id
                    LEFT JOIN (
                        SELECT GROUP_CONCAT(t.description) as reject_type_description, srt.survey_id FROM tbl_survey_reject_history srt
                        JOIN tbl_rejection_type t on FIND_IN_SET(t.id,srt.rejection_type_ids)
                        GROUP BY srt.survey_id
                    ) his ON his.survey_id = s.id
                    WHERE s.decommissioned = 0
                    AND s.client_id = $client_id
                    AND (s.status = 6 OR (si.comments IS NOT NULL AND s.status NOT IN (4,5)))
                    ORDER BY s.id DESC ");
        }
        return $survey;
    }

    public function getSurvey($survey_id) {
        $survey = Survey::with('property','project','publishedSurvey','sampleCertificate','sitePlanDocuments','clients','surveyArea','surveyReason',
                                'airTestCertificate','surveyAnswer.dropdownQuestionData','surveyAnswer.dropdownAnswerData', 'surveyInfo','surveySetting','surveyDate')
                        ->where('id', $survey_id)->first();
        return is_null($survey) ? [] : $survey;
    }

    public function getSurveyFromApi($survey_id) {
        $survey = Survey::with('property','clients','surveyArea', 'surveyInfo','surveySetting','surveyDate','surveyorNote',
                                'surveyArea','surveyArea.allAreaLocations','surveyArea.allAreaLocations.locationConstruction',
                                'surveyArea.allAreaLocations.locationVoid','surveyArea.allAreaLocations.locationInfo',
                                'surveyArea.allAreaLocations.allSurveyItems','surveyArea.allAreaLocations.allSurveyItems.itemInfo',
                                'surveyArea.allAreaLocations.allSurveyItems.ItemNoAccessValue','surveyArea.allAreaLocations.allSurveyItems.SpecificLocationValue',
                                'surveyArea.allAreaLocations.allSurveyItems.ProductDebrisTypeValue','surveyArea.allAreaLocations.allSurveyItems.AsbestosTypeValue',
                                'surveyArea.allAreaLocations.allSurveyItems.ExtentValue','surveyArea.allAreaLocations.allSurveyItems.sample',
                                'surveyArea.allAreaLocations.allSurveyItems.pasPrimary','surveyArea.allAreaLocations.allSurveyItems.pasSecondary',
                                'surveyArea.allAreaLocations.allSurveyItems.pasLocation','surveyArea.allAreaLocations.allSurveyItems.pasAccessibility',
                                'surveyArea.allAreaLocations.allSurveyItems.pasExtent','surveyArea.allAreaLocations.allSurveyItems.pasNumber',
                                'surveyArea.allAreaLocations.allSurveyItems.pasHumanFrequency','surveyArea.allAreaLocations.allSurveyItems.pasAverageTime',
                                'surveyArea.allAreaLocations.allSurveyItems.pasType','surveyArea.allAreaLocations.allSurveyItems.pasMaintenanceFrequency',
                                'surveyArea.allAreaLocations.allSurveyItems.masProductDebris','surveyArea.allAreaLocations.allSurveyItems.masDamage',
                                'surveyArea.allAreaLocations.allSurveyItems.masTreatment','surveyArea.allAreaLocations.allSurveyItems.masAsbestos',
                                'surveyArea.allAreaLocations.allSurveyItems.ActionRecommendationValue'
                            )
                        ->where('id', $survey_id)->first();
        return is_null($survey) ? [] : $survey;
    }

    /**
     * Get property_id from survey_id
     */
    public function getPropertyFromSurvey($survey_id) {
        $survey = Survey::find($survey_id);
        return is_null($survey) ? 0 : $survey->property_id;
    }

    public function getSurveyByType($client_id, $type) {

        if ($client_id) {
            $survey = Survey::with('project', 'property', 'surveyDate','clients')->where([
                'client_id' => $client_id,
                'survey_type' => $type,
            ])->where('status','!=',COMPLETED_SURVEY_STATUS)->where('decommissioned', 0)->get();
        } else {
            // property privilege
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();

            $survey = Survey::with('project', 'property', 'surveyDate','clients','surveySetting')->where([
                    'survey_type' => $type
                ])->where('status','!=',COMPLETED_SURVEY_STATUS)->where('decommissioned', 0)
                    ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
                    ->get();
        }
        return $survey;
    }
    /**
     * Get survey item with decommissioned status from id
     */
    public function getSurveyItems($survey_id, $decommissioned) {
        $items = Item::where('property_id', $survey_id)->where('survey_id', 0)
                ->where('state', PROPERTY_NOACM_STATE)
                ->where('decommissioned', $decommissioned)->get();
        return is_null($items) ? [] : $items;
    }

    /**
     * Get survey area with decommissioned status from id
     */
    public function getSurveyArea($id, $decommissioned = 0) {
        $areas = Area::where('survey_id', $id)
                    ->where('decommissioned', $decommissioned)
                    ->oldest('reference')
                    ->oldest('description')->get();
        return is_null($areas) ? [] : $areas;
    }

    /**
     * Get lastest survey plans from id
     */
    public function getSurveyPlans($property_id, $id) {
        $plans = SitePlanDocument::where('property_id', $property_id)->where('survey_id', $id)->latest('added')->get();
        return is_null($plans) ? [] : $plans;
    }

    /**
     * Get lastest surveyors notes from id
     */
    public function getSurveyorsNotes($property_id, $id) {
        $surveyorsNotes = SitePlanDocument::where('property_id', $property_id)->where('survey_id', 0)->where('category',$id)->latest('added')->get();
        return is_null($surveyorsNotes) ? [] : $surveyorsNotes;
    }

    //get survey information with type
    public function getSurveyInfomationData($survey_id, $type) {
        $survey = SurveyInfo::where('survey_id', $survey_id)->first();
        if (is_null($survey)) {
            $response = CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Survey not found! Please try again');
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
    /**
     * Update survey information
     */
    public function updateSurvey($id, $data) {
        $surveyInfo = Survey::where('id', $id)->first();

        if (!is_null($surveyInfo)) {
            try {
                $surveyInfo = $surveyInfo->update($data);
                return CommonHelpers::successResponse('Update succesful!',$surveyInfo);
            } catch (\Exception $e) {
                return CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Failed to update! Please try again');
            }
        } else {
            return CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Survey not found!');
        }
    }

    public function updateSurveyInfo($id, $data, $image = null) {
        $surveyInfo = SurveyInfo::where('survey_id', $id)->first();
        $survey =  Survey::find($id);
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
                return CommonHelpers::successResponse('Update succesful!',$surveyInfo);
            } catch (\Exception $e) {
                return CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Failed to update! Please try again');
            }
        } else {
            return CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Survey not found!');
        }
    }

    // update method question
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
                    $update = SurveyAnswer::updateOrCreate(['survey_id' => $survey_id, 'question_id' => $question], $dataSurveyAnswer);
                    $response = CommonHelpers::successResponse('Update method questionaire successful!');
                } catch (\Exception $e) {
                    $response = CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Failed to update! Please try again');
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
                        $update = SurveyAnswer::updateOrCreate(['survey_id' => $survey_id, 'question_id' => $questionOtherData], $dataSurveyOtherAnswer);
                        $response = CommonHelpers::successResponse('Update method questionaire successful!');

                    } catch (\Exception $e) {
                        $response = CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Failed to update! Please try again');
                    }
                }
            }
             return $response;
        }
    }

    public function getSurveyUsers() {
        $users = User::where('client_id', 1)->where('is_locked',0)->orderBy('first_name','ASC')->get();
        return $users;
    }

    public function getWorkStreams() {
        $users = WorkStream::where('is_deleted', '!=', 1)->get();
        return $users;
    }

    public function getSurveyProjects($property_id) {
        $client_id = \Auth::user()->client_id;
        if (\CommonHelpers::isSystemClient()) {
            $projects = Project::where('property_id', $property_id)->where('status','<>',5)->where('risk_classification_id', ASBESTOS_PROJECT)->get();
        } else {
            $projects = Project::where('property_id', $property_id)
                                ->where('status', '!=', 5)
                                ->where('risk_classification_id', ASBESTOS_PROJECT)
                                ->whereRaw("FIND_IN_SET('$client_id', REPLACE(contractors, ' ', ''))")
                                ->orderBy('id','desc')
                                ->get();
        }
        return $projects;
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
                'quality_id' => \CommonHelpers::checkArrayKey($data,'qualityKey'),
                'analyst_id' => \CommonHelpers::checkArrayKey($data,'analystKey'),
                'consultant_id' => \CommonHelpers::checkArrayKey($data,'consultantKey'),
                'is_locked' => 0,
                'work_stream' => \CommonHelpers::checkArrayKey($data,'work_stream'),
                'cost' => \CommonHelpers::checkArrayKey($data,'cost'),
                'organisation_reference' => \CommonHelpers::checkArrayKey($data,'organisation_reference'),
                'created_by' => \Auth::user()->id,
            ];

            $dataSurveyDate = [
                'due_date' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'due-date')),
                'surveying_start_date' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'sv-start-date')),
                'surveying_finish_date' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'sv-finish-date')),
                'published_date' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'published-date')),
                'completed_date' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'completed-date')),
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
            ];
            $dataSurveyInfo = [];

            try {
                // create survey
                if (is_null($survey_id)) {
                    $dataSurvey['status'] = 1;
                    $survey = Survey::create($dataSurvey);
                    $reference = $this->getSurveyReferenceText($survey->survey_type, $survey->id);
                    Survey::where('id', $survey->id)->update(['reference'=> $reference]);

                    $dataSurveyDate['started_date'] = time();

                    $message = 'New Survey Created Successfully!';
                    if (isset($data['constructionDetailsRequired'])) {
                        $propertyData = Property::with('propertySurvey')->where('id', $property_id)->first();
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
                            'property_status' => $propertyData->propertySurvey->property_status ?? '',
                            'property_occupied' => $propertyData->propertySurvey->property_occupied ?? '',
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
                    Survey::where('id', $survey_id)->update($dataSurvey);
                    $survey = Survey::where('id', $survey_id)->first();
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
//                    array_push($survey_ids,$survey->id);
//                    $survey_project = implode(",",$survey_ids);
//
//                    Project::where('id', $project->id)->update(['survey_id' => $survey_project]);
//                }

                if ($survey) {
                    SurveyDate::updateOrCreate(['survey_id' => $survey->id],$dataSurveyDate);
                    SurveyInfo::updateOrCreate(['survey_id' => $survey->id],$dataSurveyInfo);
                    SurveySetting::updateOrCreate(['survey_id' => $survey->id],$dataSurveySetting);
                }
                return $response = \CommonHelpers::successResponse($message, $survey);
            } catch (\Exception $e) {
                \Log::error($e);
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
            DB::beginTransaction();

            if (!is_null($item_ids)) {
                $item_ids = $this->convertString2ArrayInt($item_ids);
                // lock item
                foreach ($item_ids as $item_id) {
                    $item = Item::where('id', $item_id)->first();
                    // if area already inserted
                    if (in_array($item->area_id, $areaInserted)) {
                        $areaOldRecordId = Area::where('id', $item->area_id)->first()->record_id;
                        $cloneAreaSurvey = Area::where('record_id', $areaOldRecordId)->where('survey_id', $survey_id)->first();
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
                        $locationOldRecordId = Location::where('id', $item->location_id)->first()->record_id;
                        $cloneLocationSurvey = Location::where('record_id', $locationOldRecordId)->where('survey_id', $survey_id)->first();
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
                    $location = Location::where('id', $location_id)->first();
                    // if area already inserted
                    if (in_array($location->area_id, $areaInserted)) {
                        $areaOldRecordId = Area::where('id', $location->area_id)->first()->record_id;
                        $cloneAreaSurvey = Area::where('record_id', $areaOldRecordId)->where('survey_id', $survey_id)->first();
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
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function cloneArea($areaId, $surveyId, $approval = false) {
        $area = Area::find($areaId);
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
            \DB::select("Update tbl_area set is_locked = 0 where id = $areaId");
        } else {
            //lock area
            // Area::where('id', $areaId)->update(['is_locked' => AREA_LOCKED]);
            \DB::select("Update tbl_area set is_locked = 1 where id = $areaId");
        }
        //duplicate area
        $newArea = Area::create($dataArea);

        return $newArea->id;
    }

    public function cloneLocation($locationId, $surveyId, $areaId, $approval = false) {

        $location = Location::with('locationInfo', 'locationVoid', 'locationConstruction','shineDocumentStorage')->find($locationId);
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
           \DB::select("Update tbl_location set is_locked = 0 where id = $locationId");
        } else {
            //lock location
            // Location::where('id', $locationId)->update(['is_locked' => LOCATION_LOCKED]);
           \DB::select("Update tbl_location set is_locked = 1 where id = $locationId");
        }

        //duplicate location
        $newLocation = Location::create($dataNewLocation);
        // Location::where('id', $newLocation->id)->update(['reference' => 'RL'.$newLocation->id]);
        //duplicate location relation
        $this->checkMultipleRelations($location->locationInfo, $newLocation->id, 'location');
        $this->checkMultipleRelations($location->locationVoid, $newLocation->id, 'location');
        $this->checkMultipleRelations($location->locationConstruction, $newLocation->id, 'location');
        $this->checkMultipleRelations($location->shineDocumentStorage, $newLocation->id, 'file');

        return $newLocation->id;
    }

    public function cloneItem($itemId, $locationId, $areaId, $surveyId, $approval = false, $decommissioned = false) {
        try {
            $item =  Item::with('itemInfo',
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
            )->find($itemId);

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
                \DB::select("Update tbl_items set is_locked = 0 where id = $itemId");
            } else {
                //lock item
                // Item::where('id', $itemId)->update(['is_locked' => ITEM_LOCKED]);
                \DB::select("Update tbl_items set is_locked = 1 where id = $itemId");
            }

            //duplicate item
            $newItem = Item::create($dataNewItem);
            // Item::where('id', $newItem->id)->update(['reference' => 'IN'.$newItem->id]);
            // duplicate item relation
            $this->checkMultipleRelations($item->commentHistory, $newItem->id,"comment_his");
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
        }catch (\Exception $e){
        }

    }

    public function updateRegisterArea($area_id, $register_area_id = null) {
        $area = Area::find($area_id);
        $dataUpdate = [
            "version" => $area->version,
            "property_id" => $area->property_id ,
            "survey_id" => 0 ,
            "is_locked" => AREA_UNLOCKED ,
            "description" => $area->description ,
            "area_reference" => $area->area_reference ,
            "decommissioned" => $area->decommissioned ,
            'decommissioned_reason'         => $area->decommissioned_reason ,
            'not_assessed'                  => $area->not_assessed ,
            'not_assessed_reason'           => $area->not_assessed_reason,
            'updated_by' => $area->updated_by ?? $area->created_by,
            'updated_at'    =>  $area->updated_at ?? $area->created_at
        ];

        if (is_null($register_area_id)) {
            $registerArea = Area::where(['record_id' => $area->record_id])->where('survey_id', 0)->first();
        } else {
            $registerArea = Area::where(['id' => $register_area_id])->first();
        }
        // if register area has been delete
        if (is_null($registerArea)) {
            $registerArea = Area::create([
                'property_id' => $area->property_id,
                'record_id' => $area->record_id,
                'survey_id' => 0
            ]);
        }
        Area::where(['id' => $registerArea->id])->update($dataUpdate);

        return $registerArea;
    }

    public function updateRegisterLocation($location_id, $area_record_id = null , $is_require_location_void_investigations = 1, $is_require_location_construction_details = 1, $area_reference = null) {
        $location = Location::with('locationInfo', 'locationVoid', 'locationConstruction','shineDocumentStorage')->find($location_id);

        if (is_null($area_record_id)) {
            $registerLocation = Location::where('record_id', $location->record_id)->where('survey_id', 0)->first();
        } else {
            $registerLocation = Location::whereHas('area', function($query) use ($area_record_id,$area_reference, $location){
                                                    return $query->where('record_id', $area_record_id)
                                                                    ->orWhere('area_reference', $area_reference)
                                                                    ->where('property_id', $location->property_id);
                                                })->where('location_reference', $location->location_reference)
                                                ->where('property_id', $location->property_id)
                                                ->where('survey_id', 0)->first();
        }
        // if register location has been delete
        if (is_null($registerLocation)) {
            $registerLocation = Location::create([
                'property_id' => $location->property_id,
                'record_id' => $location->record_id,
                'survey_id' => 0
            ]);
            LocationConstruction::create(['location_id' => $registerLocation->id]);
            LocationVoid::create(['location_id' => $registerLocation->id]);
            LocationInfo::create(['location_id' => $registerLocation->id]);
        }
        $dataUpdateRegisterLocation = [
            'survey_id'                    => 0,
            'is_locked'                    => LOCATION_UNLOCKED,
            'state'                        => $location->state,
            'decommissioned'               => $location->decommissioned,
            'decommissioned_reason'         => $location->decommissioned_reason ,
            'not_assessed'                  => $location->not_assessed ,
            'not_assessed_reason'           => $location->not_assessed_reason ,
            'version'                      => $location->version,
            'description'                  => $location->description,
            'location_reference'           => $location->location_reference,
            'updated_by'                    => $location->updated_by ?? $location->created_by,
            'updated_at'                   =>  $location->updated_at ?? $location->created_at
        ];
        if (!is_null($registerLocation)) {
            Location::where('id', $registerLocation->id)->update($dataUpdateRegisterLocation);

            // store comment history
            \CommentHistory::storeCommentHistory('location', $registerLocation->id ?? 0, $registerLocation->locationInfo->comments ?? '');

            LocationInfo::where('location_id', $registerLocation->id)->update($location->locationInfo->toArray());
            if ($is_require_location_void_investigations == 1) {

                //need to check difference with out of scope/ list out of scope value
                //Ceiling Void: 1807
                //Floor Void: 1811
                //Cavities: 1808
                //Risers: 1809
                //Ducting: 1810
                //Boxing: 1813
                //Pipework: 1812
                // merge data if same selected parent
                $data_void = [
                    'ceiling_other' => ($location->locationVoid->ceiling != CELLING_VOID_OOS) ? $location->locationVoid->ceiling_other : $registerLocation->locationVoid->ceiling_other,
                    'ceiling' => ($location->locationVoid->ceiling != CELLING_VOID_OOS) ?
                                $this->locationMergeVoid($location->locationVoid->ceiling,$registerLocation->locationVoid->ceiling) : $registerLocation->locationVoid->ceiling,
                    'cavities_other' => ($location->locationVoid->cavities != CAVITY_OOS) ? $location->locationVoid->cavities_other : $registerLocation->locationVoid->cavities_other,
                    'cavities' => ($location->locationVoid->cavities != CAVITY_OOS) ?
                                $this->locationMergeVoid($location->locationVoid->cavities,$registerLocation->locationVoid->cavities) : $registerLocation->locationVoid->cavities,
                    'risers_other' => ($location->locationVoid->risers != RISER_OOS) ? $location->locationVoid->risers_other : $registerLocation->locationVoid->risers_other,
                    'risers' => ($location->locationVoid->risers != RISER_OOS) ?
                                $this->locationMergeVoid($location->locationVoid->risers,$registerLocation->locationVoid->risers) : $registerLocation->locationVoid->risers,
                    'ducting_other' => ($location->locationVoid->ducting != DUCTING_OOS) ? $location->locationVoid->ducting_other : $registerLocation->locationVoid->ducting_other,
                    'ducting' => ($location->locationVoid->ducting != DUCTING_OOS) ?
                                $this->locationMergeVoid($location->locationVoid->ducting,$registerLocation->locationVoid->ducting) : $registerLocation->locationVoid->ducting,
                    'boxing_other' =>($location->locationVoid->boxing != BOXING_OOS) ? $location->locationVoid->boxing_other : $registerLocation->locationVoid->boxing_other,
                    'boxing' => ($location->locationVoid->boxing != BOXING_OOS) ?
                                $this->locationMergeVoid($location->locationVoid->boxing,$registerLocation->locationVoid->boxing) : $registerLocation->locationVoid->boxing,
                    'pipework_other' => ($location->locationVoid->pipework != PIPE_WORK_OOS) ? $location->locationVoid->pipework_other : $registerLocation->locationVoid->pipework_other,
                    'pipework' => ($location->locationVoid->pipework != PIPE_WORK_OOS) ?
                                $this->locationMergeVoid($location->locationVoid->pipework,$registerLocation->locationVoid->pipework) : $registerLocation->locationVoid->pipework,
                    'floor_other' => ($location->locationVoid->floor != FLOOR_VOID_OOS) ? $location->locationVoid->floor_other : $registerLocation->locationVoid->floor_other,
                    'floor' => ($location->locationVoid->floor != FLOOR_VOID_OOS) ?
                                $this->locationMergeVoid($location->locationVoid->floor,$registerLocation->locationVoid->floor) : $registerLocation->locationVoid->floor,
                ];
                LocationVoid::where('location_id', $registerLocation->id)->update($data_void);
            }

            if ($is_require_location_construction_details == 1) {
                //Ceiling : 1957
                //Wall : 1958
                //Floor: 1959
                //Door: 1960
                //Window: 1961
                $celing  = $this->stringToArray($location->locationConstruction->ceiling ?? null);
                $walls  = $this->stringToArray($location->locationConstruction->walls ?? null);
                $floor  = $this->stringToArray($location->locationConstruction->floor ?? null);
                $doors  = $this->stringToArray($location->locationConstruction->doors ?? null);
                $windows  = $this->stringToArray($location->locationConstruction->windows ?? null);
                $data_construction = [
                    'ceiling' => !in_array(CELLING_CONSTRUCTION_OOS, $celing) ?
                                $this->locationMergeConstruction($location->locationConstruction->ceiling,$registerLocation->locationConstruction->ceiling) : $registerLocation->locationConstruction->ceiling ?? '',
                    'ceiling_other' => !in_array(CELLING_CONSTRUCTION_OOS, $celing) ? $location->locationConstruction->ceiling_other ?? '' : $registerLocation->locationConstruction->ceiling_other ?? '',
                    'walls' => !in_array(WALL_CONSTRUCTION_OOS, $walls) ?
                                $this->locationMergeConstruction($location->locationConstruction->walls,$registerLocation->locationConstruction->walls): $registerLocation->locationConstruction->walls ?? '',
                    'walls_other' => !in_array(WALL_CONSTRUCTION_OOS, $walls) ? $location->locationConstruction->walls_other ?? '' : $registerLocation->locationConstruction->walls_other ?? '',
                    'floor' => !in_array(FLOOR_CONSTRUCTION_OOS, $floor) ?
                                $this->locationMergeConstruction($location->locationConstruction->floor,$registerLocation->locationConstruction->floor) : $registerLocation->locationConstruction->floor ?? '',
                    'floor_other' => !in_array(FLOOR_CONSTRUCTION_OOS, $floor) ? $location->locationConstruction->floor_other ?? '' : $registerLocation->locationConstruction->floor_other ?? '',
                    'doors' => !in_array(DOOR_CONSTRUCTION_OOS, $doors) ?
                                $this->locationMergeConstruction($location->locationConstruction->doors,$registerLocation->locationConstruction->doors) : $registerLocation->locationConstruction->doors ?? '',
                    'doors_other' => !in_array(DOOR_CONSTRUCTION_OOS, $doors) ? $location->locationConstruction->doors_other ?? '' : $registerLocation->locationConstruction->doors_other ?? '',
                    'windows' => !in_array(WINDOWN_CONSTRUCTION_OOS, $windows) ?
                                $this->locationMergeConstruction($location->locationConstruction->windows,$registerLocation->locationConstruction->windows) : $registerLocation->locationConstruction->windows ?? '',
                    'windows_other' => !in_array(WINDOWN_CONSTRUCTION_OOS, $windows) ? $location->locationConstruction->windows_other ?? '' : $registerLocation->locationConstruction->windows_other ?? '',
                ];
                LocationConstruction::where('location_id', $registerLocation->id)->update($data_construction);
            }

            // for One to Many relation
            if (isset($location->shineDocumentStorage) and !is_null($location->shineDocumentStorage)) {
                ShineDocumentStorage::updateOrCreate(
                                            ['object_id' => $registerLocation->id, 'type' => LOCATION_IMAGE],
                                            [
                                                "path" => $location->shineDocumentStorage->path ,
                                                "file_name" => $location->shineDocumentStorage->file_name ,
                                                "mime" => $location->shineDocumentStorage->mime ,
                                                "size" => $location->shineDocumentStorage->size ,
                                                "addedBy" => $location->shineDocumentStorage->addedBy ,
                                                "addedDate" => $location->shineDocumentStorage->addedDate ,
                                            ]);
            }


        }
        return $registerLocation;
    }

    public function locationMergeVoid($new_data, $old_data) {
        $new_data_array = explode(",",$new_data);
        $old_data_array = explode(",",$old_data);
        if (count($new_data_array) > 0 and count($old_data_array) > 0) {
            // same parent select , merge old data with new data
            if ($new_data_array[0] == $old_data_array[0]) {
                $data = array_merge($new_data_array,$old_data_array);
                $data = array_unique($data);
                return implode(",",$data);
            }
        }
        return $new_data;
    }

    public function locationMergeConstruction($new_data, $old_data){
        $new_data_array = explode(",",$new_data);
        $old_data_array = explode(",",$old_data);
        $data = array_merge($new_data_array,$old_data_array);
        $data = array_unique($data);
        return implode(",",$data);
    }

    public function stringToArray($string) {
        if (isset($string)) {
            if (!is_null($string)) {
                return explode(",",$string);
            }
        }
        return [];
    }
    /**
     * [updateRegisterItem description]
     * @param  [type]  $item_id          [description]
     * @param  integer $required_pas     [description]
     * @param  integer $required_photo   [description]
     * @param  integer $required_license [description]
     * @param  integer $required_r_and_d [description]
     * @return [type]                    [description]
     */
    public function updateRegisterItem($register_item_id,$item_id, $required_pas = 1, $required_photo = 1, $required_license = 1, $required_r_and_d = 1, $surveyor_id = false) {
        $item = Item::find($item_id);

        $registerItem = Item::find($register_item_id);

        $dataUpdateRegisterItem = [
             'survey_id'                     => 0 ,
             'state'                         => $item->state,
             'version'                       => $item->version ,
             'is_locked'                     => ITEM_UNLOCKED ,
             'total_risk'                    => $item->total_risk ,
             'total_mas_risk'                => $item->total_mas_risk ,
             'decommissioned'                => $item->decommissioned ,
             'decommissioned_reason'         => $item->decommissioned_reason ,
             'not_assessed'                  => $item->not_assessed ,
             'not_assessed_reason'           => $item->not_assessed_reason ,
             'name'                          => $item->name ,
             'updated_by'                    => $item->updated_by ?? $item->created_by,
             'updated_at'                   =>  $item->updated_at ?? $item->created_at
        ];

        if (!is_null($registerItem)) {
            // survey setting pas required
            if ($required_pas == 1) {
                $dataUpdateRegisterItem['total_pas_risk']  = $item->total_pas_risk ;
            } else {
                $dataUpdateRegisterItem['total_risk'] = $item->total_mas_risk + $registerItem->total_pas_risk;
            }

            if ($item->state == ITEM_NOACM_STATE) {
                $dataUpdateRegisterItem['total_pas_risk'] = 0;
                $dataUpdateRegisterItem['total_mas_risk'] = 0;
                $dataUpdateRegisterItem['total_risk'] = 0;
            }


            $registerItem->update($dataUpdateRegisterItem);
            // store comment history
            if(empty($item->itemInfo->comment)){
                $comment = $registerItem->itemInfo->comment ?? '';
            }else{
                $comment = $item->itemInfo->comment ?? '';
            }
            \CommentHistory::storeCommentHistory('item', $registerItem->id ?? 0, $comment );
            $item_info = $item->itemInfo->toArray();
            // survey setting r and d required
            if ($required_r_and_d == 0) {
                unset($item_info['is_r_and_d_element']);
            }

            if (!is_null($registerItem->itemInfo)) {
                $registerItem->itemInfo->fill($item_info)->push();
            }

            // ARRAY CONFIG
            // for one to one relation
            $item_ralation_tables = ITEM_HAS_ONE_RELATION_TABLES;
            if ($required_license == 0) {
                $item_ralation_tables = array_diff($item_ralation_tables, ['LicensedNonLicensedValue']);
            }
            foreach ( $item_ralation_tables as $table_has_one_relation) {
                $this->updateRegisterRelation($item, $registerItem, $table_has_one_relation);
            }
            // for One to Many relation
            // survey setting pas required
            if ($required_pas == 1) {
                if (!is_null($registerItem->PriorityAssessmentRiskValue)) {
                    foreach ($item->PriorityAssessmentRiskValue as $pas) {
                        PriorityAssessmentRiskValue::updateOrCreate(
                                                        ['item_id' => $registerItem->id, 'dropdown_data_item_parent_id' => $pas->dropdown_data_item_parent_id, 'dropdown_item_id' => PRIORITY_ASSESSMENT_RISK_ID],
                                                        ['dropdown_data_item_id' => $pas->dropdown_data_item_id, 'dropdown_other' => $pas->dropdown_other]
                                                    );
                    }
                }
            }

            if (!is_null($registerItem->MaterialAssessmentRiskValue)) {
                foreach ($item->MaterialAssessmentRiskValue as $mas) {
                    MaterialAssessmentRiskValue::updateOrCreate(
                                                ['item_id' => $registerItem->id, 'dropdown_data_item_parent_id' => $mas->dropdown_data_item_parent_id, 'dropdown_item_id' => MATERIAL_ASSESSMENT_RISK_ID],
                                                ['dropdown_data_item_id' => $mas->dropdown_data_item_id, 'dropdown_other' => $mas->dropdown_other]
                                                );
                }
            }
            // survey setting item photo
            if ($required_photo == 1) {
                if (!is_null($registerItem->shineDocumentStorage)) {
                    foreach ($item->shineDocumentStorage as $shineDocumentStorage) {

                        ShineDocumentStorage::updateOrCreate(
                            ['object_id' => $registerItem->id, 'type' => $shineDocumentStorage->type],
                            [
                            "path" => $shineDocumentStorage->path ,
                            "file_name" => $shineDocumentStorage->file_name ,
                            "mime" => $shineDocumentStorage->mime ,
                            "size" => $shineDocumentStorage->size ,
                            "addedBy" => $shineDocumentStorage->addedBy ,
                            "addedDate" => $shineDocumentStorage->addedDate ,
                        ]);
                    }
                }
            }
        }
    }

    public function updateRegisterRelation($item, $registerItem, $relation) {
        if (!is_null($registerItem-> $relation) and !is_null($item-> $relation)) {
            $registerItem-> $relation->fill($item-> $relation->toArray())->push();
        }
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
        } elseif ($type == 'comment_his') {
            $newRelation->record_id = $newRelationId;
        }
        else {
            $newRelation->location_id = $newRelationId;
        }

        $newRelation = $newRelation->save();
    }

    public function convertString2ArrayInt($string) {
        $result_array = [];
        $string = explode(',', $string);

        foreach ($string as $each_number) {
            $result_array[] = (int) $each_number;
        }
        return $result_array;
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

     //lock
    public function publishSurvey($survey_id, $survey_draf) {
        try {
            //generate survey pdf for public survey
            $is_sucess = GeneratePDFController::publishSurveyPDF($survey_id);
            if($is_sucess){
                if ($survey_draf) {
                    return $response = \CommonHelpers::successResponse('Published survey as draf successful!');
                } else {
                    $publish_survey = Survey::where('id', $survey_id)->update(['status' => PULISHED_SURVEY_STATUS, 'is_locked' => SURVEY_LOCKED]);
                    $publish_date = SurveyDate::where('survey_id', $survey_id)->update(['published_date' => Carbon::now()->timestamp]);
                    return $response = \CommonHelpers::successResponse('Published survey successful!');
                }
            }
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to publish survey. Please try again!');
        } catch (\Exception $e) {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to publish survey. Please try again!');
        }
    }

    //unlock
    public function rejectSurvey($survey_id, $data) {
        try {
            $reject_survey = Survey::where('id', $survey_id)->update(['status' => REJECTED_SURVEY_STATUS, 'is_locked' => SURVEY_UNLOCKED]);
            SurveyInfo::where('survey_id', $survey_id)->update(['comments' => $data['note']]);
            $due_date_raw = $data['due_date'] ?? Carbon::now();
            if (isset($data['due_date'])) {
                $due_date = Carbon::createFromFormat('d/m/Y', $due_date_raw );
            } else {
                $due_date = Carbon::now();
            }
            SurveyDate::where('survey_id', $survey_id)->update(['due_date' => Carbon::parse($due_date)->timestamp]);

            $this->unLockSurvey($survey_id);
            //rejection types
            $rejection_type_ids = null;
            if ( isset($data['rejection_type_ids']) and count($data['rejection_type_ids']) > 0 ) {
                $rejection_type_ids = \CommonHelpers::convertArrayUnique2String($data['rejection_type_ids']);
            }
            //log audit
            $survey =  Survey::find($survey_id);
            $comment = \Auth::user()->full_name  . " rejected survey "  . $survey->reference;

            //create rejection history
            $survey_reject_history = SurveyRejectHistory::create([
                "survey_id" => $survey_id,
                "client_id" => $survey->client_id ?? 0,
                "user_id" => \Auth::user()->id,
                "date" => time(),
                "note" => $data['note'],
                "rejection_type_ids" => $rejection_type_ids
            ]);
            \CommonHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_REJECTED, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);

            if ($survey->client_id == LIFE_ENVIRONMENTAL_CLIENT) {
                //send mail queue
                \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendClientEmailNotification(
                                    $survey->client->name ?? '',
                                    SURVEY_REJECTED_EMAILTYPE,
                                    REJECT_USER_ID,
                                    $survey->property->property_reference ?? '',
                                    $survey->property->name ?? '',
                                    $survey->property->propertyInfo->pblock ?? '',
                                    $survey->reference,
                                    \Auth::user()->clients->name ?? ''
                                ));

            }

            //send email reject
            $dataContractor = Client::where('id', $survey->client_id)->first();
            $property_ref = $survey->property->property_reference ?? '';
            $property_name = $survey->property->name ?? '';
            $property_block = $survey->property->propertyInfo->pblock ?? '';
            $survey_ref = $survey->reference ?? '';
            $contractor_name = $dataContractor->name ?? '';
            $email = $dataContractor->email_notification ?? '';
            $data = [
                'subject' => 'Survey Reject',
                'contractor_name' => $contractor_name,
                'survey_reference' => $survey_ref,
                'property_block' => $property_block,
                'property_reference' => $property_ref,
                'property_name' => $property_name,
                'company_name' => "COW",
                'domain' => \Config::get('app.url'),
            ];
            \Queue::pushOn(CLIENT_EMAIL_QUEUE, new SendEmail($email,$data, SURVEY_REJECT_EMAIL_QUEUE));

            return $response = \CommonHelpers::successResponse('Rejected survey successful!');

        } catch (\Exception $e) {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to reject survey. Please try again!');
        }

    }

    //cancel
    public function cancelSurvey($survey_id) {
        try {
            $cancel_survey = Survey::where('id', $survey_id)->update(['status' => READY_FOR_QA_SURVEY_STATUS, 'is_locked' => SURVEY_UNLOCKED]);
            $this->unLockSurvey($survey_id);
            //log audit
            $survey =  Survey::find($survey_id);
            $comment = \Auth::user()->full_name  . " canceled survey "  . $survey->reference;
            \CommonHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_CANCELED, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);
            return $response = \CommonHelpers::successResponse('Canceled survey successful!');

        } catch (\Exception $e) {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to cancel survey. Please try again!');
        }

    }

    //unlock
    public function approvalSurvey($survey_id) {
        $survey = Survey::where('id', $survey_id)->first();
        if (!is_null($survey)) {
            $areas = Area::where('survey_id', $survey_id)->get();
            //add Transactions
            DB::beginTransaction();
            try {
                // duplicate areas and locations
                $areas_dup = [];
                $locations_dup = [];
                foreach ($areas as $area) {
                    // Check is new area
                    // check survey area reference with register
                    $checkIsNewArea = ($area->id == $area->record_id) ? true : false;
                    $register_area_id = null;
                    if ($checkIsNewArea) {
                        $checkAreaReference = Area::where(['area_reference' => $area->area_reference,
                                                            'survey_id' => 0,
                                                            'property_id' => $area->property_id,
                                                            'decommissioned' => $area->decommissioned
                                                            ])
                                                        ->first();
                        if (!is_null($checkAreaReference)) {
                            $register_area_id = $checkAreaReference->id;
                            $checkIsNewArea = false;
                            if ($checkAreaReference->record_id == $area->record_id) {
                            } else {
                                $areas_dup[] = $register_area_id;
                            }
                        }
                    }
                    //create new area
                    if ($checkIsNewArea) {
                        $area_register_new = $this->cloneArea($area->id, 0, true);
                        if (!is_null($area->surveyLocations)) {
                            foreach ($area->surveyLocations as $location) {
                                $location_register_new = $this->cloneLocation($location->id, 0, $area_register_new, true);
                                if (!is_null($location->surveyItems)) {
                                    foreach ($location->surveyItems as $item) {
                                        $item_register_new = $this->cloneItem($item->id, $location_register_new, $area_register_new, 0, true);
                                    }
                                }
                            }
                        }
                    // update area
                    } else {
                        if (isset($register_area_id)) {
                            $area_register_update = $this->updateRegisterArea($area->id, $register_area_id);
                        } else {
                            $area_register_update = $this->updateRegisterArea($area->id);
                        }

                        $locations = $area->surveyLocations;
                        if (!is_null($locations)) {
                            foreach ($locations as $location) {
                                //create new location
                                //check survey location reference with register
                                $checkIsNewLocation = ($location->id == $location->record_id) ? true : false;
                                $same_location_reference = false;
                                if ($checkIsNewLocation) {
                                    $checkLocationReference = Location::whereHas('area', function($query) use ($area) {
                                                                                    return $query->where('record_id', $area->record_id)->orWhere('area_reference', $area->area_reference);
                                                                                })->where([
                                                                                'location_reference' => $location->location_reference ,
                                                                                'survey_id' => 0,
                                                                                'property_id' => $location->property_id,
                                                                                'decommissioned' => $location->decommissioned])
                                                                                ->first();
                                    if (!is_null($checkLocationReference)) {
                                        $same_location_reference = true;
                                        $checkIsNewLocation = false;
                                        if ($checkLocationReference->record_id == $location->record_id) {
                                        } else {
                                           $locations_dup[] = $checkLocationReference->id;
                                        }

                                    }
                                }
                                if ($checkIsNewLocation) {
                                    $location_register_new = $this->cloneLocation($location->id, 0, $area_register_update->id);
                                    if (!is_null($location->surveyItems)) {
                                        foreach ($location->surveyItems as $item) {
                                            $item_register_new = $this->cloneItem($item->id, $location_register_new, $area_register_update->id, 0);
                                        }
                                    }
                                //update location
                                } else {
                                    if ($same_location_reference) {
                                        $location_register_update = $this->updateRegisterLocation($location->id, $area->record_id, optional($survey->surveySetting)->is_require_location_void_investigations,optional($survey->surveySetting)->is_require_location_construction_details,$area->area_reference );
                                    } else {
                                        $location_register_update = $this->updateRegisterLocation($location->id,null, optional($survey->surveySetting)->is_require_location_void_investigations,optional($survey->surveySetting)->is_require_location_construction_details );
                                    }
                                    $items = $location->surveyItems;
                                    if (!is_null($items)) {
                                        $hasRegisterItem = false;
                                        foreach ($items as $item) {
                                            $registerItemData = Item::where('area_id',$area_register_update->id)
                                                                        ->where('location_id',$location_register_update->id)
                                                                        ->where('record_id',$item->record_id)
                                                                        ->first();
                                            // create new item
                                            if (is_null($registerItemData)) {
                                                $item_register_new = $this->cloneItem($item->id, $location_register_update->id, $area_register_update->id, 0);
                                            //update item
                                            } else {
                                                $this->updateRegisterItem($registerItemData->id,$item->id, optional($survey->surveySetting)->is_require_priority_assessment, optional($survey->surveySetting)->is_require_photos, optional($survey->surveySetting)->is_require_license_status, optional($survey->surveySetting)->is_require_r_and_d_elements, $survey->surveyor_id);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                //pull survey property image
                if (\CommonHelpers::checkFile($survey->id, PROPERTY_SURVEY_IMAGE)) {
                    $file = ShineDocumentStorage::where('object_id', $survey->id)->where('type', PROPERTY_SURVEY_IMAGE)->first();

                    if (is_file($file->path)) {
                        ShineDocumentStorage::updateOrCreate(
                            ['object_id' => $survey->property_id, 'type' => PROPERTY_IMAGE],
                            [
                                'path' => $file->path,
                                'file_name' => $file->file_name,
                                'mime' => $file->mime,
                                'size' => $file->size,
                                "addedBy" => $file->addedBy ,
                                'addedDate' => $file->addedDate
                            ]
                        );
                    }
                }
                //survey seting site data
                if (optional($survey->surveySetting)->is_require_construction_details == 1 ) {
                    $this->updatePropertySurvey($survey);
                }
                $approval_survey = Survey::where('id', $survey_id)->update(['status' => COMPLETED_SURVEY_STATUS, 'is_locked' => SURVEY_UNLOCKED]);
                $completed_date = SurveyDate::where('survey_id', $survey_id)->update(['completed_date' => Carbon::now()->timestamp]);

                $this->lockSurvey($survey_id);

                //INSPECTION SETTINGS: remove this logic
                // $list_inspection_items = Item::where('survey_id', $survey_id)->where('state', ITEM_NOACM_STATE)->get();
                // if (count($list_inspection_items) > 0) {
                //     foreach ($list_inspection_items as $item) {
                //         $publis_date = \CommonHelpers::toTimeStamp($survey->surveyDate->published_date);

                //         $inspectionDate = $this->LoadInspectionFormula($publis_date ?? Carbon::now()->timestamp, $item->total_risk);
                //         $item_register = Item::where('record_id', $item->record_id)->where('survey_id',0)->first();
                //         if (!is_null($item_register)) {
                //             ItemInfo::where('item_id', $item_register->id)->update(['inspection_date' => $inspectionDate]);
                //         }
                //     }
                // }
                //Check Remedial available
                $remedial_available = DB::select('select COUNT(DISTINCT i.id) from tbl_items as i
                                    LEFT JOIN tbl_item_action_recommendation_value v on v.item_id = i.id
                                    WHERE i.decommissioned = 0
                                    and i.survey_id = '.$survey_id.'
                                    and (v.dropdown_data_item_id IN ("' . implode(',', ACTION_RECOMMENDATION_LIST_ID) . '")
                                    OR v.dropdown_data_item_parent_id IN
                                    ("' . implode(',', ACTION_RECOMMENDATION_LIST_ID) . ' "))');
                if ($remedial_available > 0) {
                    //update and send email
                    // \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendClientEmail($survey->property_id, REMEDIAL_ACTIONS_REQUIRED_EMAILTYPE,$survey_id));
                }

                //Check High Risk Items
                $high_risk = DB::select('select COUNT(DISTINCT i.id) from tbl_items as i
                                    LEFT JOIN tbl_item_material_assessment_risk_value v on v.item_id = i.id and v.dropdown_data_item_parent_id = 604
                                    WHERE i.decommissioned = 0
                                    AND i.state = '.ITEM_ACCESSIBLE_STATE.'
                                    and i.survey_id = '.$survey_id.'
                                    and v.dropdown_data_item_id = 608 ');
                if ($high_risk > 0) {
                    //privilege
                    // if(\CompliancePrivilege::checkPermission(HIGH_RISK_ITEM_VIEW_PRIV)) {
                        // \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendClientEmail($survey->property_id, HIGH_RISK_ITEM_EMAILTYPE, $survey_id));
                    // }
                }
                // if have linked project in survey
                if(isset($survey->project_id) and $survey->project_id > 0){
                    $project_id = $survey->project_id;
                    // find project id in work request or in project major of work request
                    $data_work_request = WorkRequest::where('project_id' , $project_id)
                        ->orWhereRaw("FIND_IN_SET('$project_id', REPLACE(project_id_major, ' ', ''))")
                        ->first();
                    // check if work request exist
                    if ($data_work_request) {
                        $emailCC = WorkEmailCC::where('work_id',$data_work_request->id)->first();
                        $additional_email = [];
                        if($emailCC){
                            $additional_email = explode(",", $emailCC->email);
                        }
                        $data_user = User::where('id', $data_work_request->created_by)->first();
                        $property_ref = $survey->property->property_reference ?? '';
                        $property_name = $survey->property->name ?? '';
                        $survey_type = $survey->getSurveyTypeTextAttribute($survey->type);
                        $project_ref = $survey->project->reference ?? '';
                        $wr_ref = $data_work_request->reference ?? '';
                        $wr_type = $data_work_request->workData->description ?? '';
                        $link_pdf = route('survey.view.pdf', ['type' => VIEW_SURVEY_PDF, 'id' => \CommonHelpers::getLatestPdfBySurvey($survey->id)]);
                        $data = [
                            'subject' => 'Survey Approved',
                            'work_requester' => $data_user->full_name ?? '',
                            'email' => $data_user->email ?? '',
                            'survey_reference' => $survey->reference ?? '',
                            'survey_type' => $survey_type,
                            'project_reference' => $project_ref,
                            'work_request_reference' => $wr_ref,
                            'work_request_type' => $wr_type,
                            'property_reference' => $property_ref,
                            'property_name' => $property_name,
                            'company_name' => $data_user->clients->name ?? '',
                            'domain' => \Config::get('app.url'),
                            'link_pdf' => $link_pdf
                        ];
                        \Queue::pushOn(CLIENT_EMAIL_QUEUE, new SendApprovalEmail($data, SURVEY_APPROVAL_EMAIL_QUEUE,$additional_email));
                    } else {
                        // send email high risk for project
                        \Queue::pushOn(CLIENT_EMAIL_QUEUE, new SendHighRiskSurveyApproval($survey_id));
                    }
                 }
                // send email high risk for project
                \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendHighRiskSurveyApproval($survey_id));
                // send register update email
                \CommonHelpers::isRegisterUpdated($survey->property_id);
                // \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendClientEmail($survey->property_id ?? 0, ASBESTOS_REGISTER_EMAILTYPE, $survey_id));

                // send duplicate email warning
                if ((count($areas_dup) > 0) or (count($locations_dup) > 0) ) {
                    \Queue::pushOn(DUPLICATE_DATA_EMAIL_QUEUE,new SendDuplicateDataEmailWarning($survey->id, $areas_dup, $locations_dup));
                }

                //log audit
                $comment = \Auth::user()->full_name  . " approved survey "  . $survey->reference;
                \CommonHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_APPROVED, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);
                DB::commit();
                if ($approval_survey) {
                    return $response = \CommonHelpers::successResponse('Approval survey successful!');
                } else {
                    return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to approval survey. Please try again!');
                }

            } catch (\Exception $e) {
                DB::rollback();
                return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Approval Survey Fail!');
            }
        } else {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Survey does not exist!');
        }
    }

    public function updatePropertySurvey($survey) {
        $propertySurveyData = [
           'programme_type'     => $survey->surveyInfo->property_data->programmeType ?? 0,
           'programme_type_other'     => $survey->surveyInfo->property_data->programmetypemore ?? 0,
           'asset_use_primary'        => $survey->surveyInfo->property_data->PrimaryUse ?? 0,
           'asset_use_primary_other'    => $survey->surveyInfo->property_data->primaryusemore ?? 0,
           'asset_use_secondary'      => $survey->surveyInfo->property_data->SecondaryUse ?? 0,
           'asset_use_secondary_other'  => $survey->surveyInfo->property_data->secondaryusemore ?? 0,
           'construction_age' => $survey->surveyInfo->property_data->constructionAge ?? 0,
           'construction_type' => $survey->surveyInfo->property_data->constructionType ?? 0,
           'size_floors' => $survey->surveyInfo->property_data->sizeFloors ?? 0,
           'size_staircases' => $survey->surveyInfo->property_data->sizeStaircases ?? 0,
           'size_lifts' => $survey->surveyInfo->property_data->sizeLifts ?? 0,
           'electrical_meter' => $survey->surveyInfo->property_data->electricalMeter ?? 0,
           'gas_meter' => $survey->surveyInfo->property_data->gasMeter ?? 0,
           'loft_void' => $survey->surveyInfo->property_data->loftVoid ?? 0,
           'size_net_area' => $survey->surveyInfo->property_data->sizeNetArea ?? 0,
           'size_gross_area' => $survey->surveyInfo->property_data->sizeGrossArea ?? 0,
           'size_comments' => $survey->surveyInfo->property_data->sizeComments ?? 0,
       ];

       PropertySurvey::updateOrCreate(['property_id' => $survey->property_id],$propertySurveyData);
    }

    public function getSamplesTable($property_id, $survey_id) {

        $survey_samples = DB::select("

                                SELECT
                                    tmp.id,
                                    tmp.reference,
                                    tmp.description,
                                    tmp.original_item_id,
                                    tmp.sample_id,
                                    GROUP_CONCAT( IF ( tmp.record_id = tmp.original_item_id, CONCAT( tmp.item_ref, '(OS)' ), tmp.item_ref ) ORDER BY tmp.is_os desc) AS item_reference,
                                    GROUP_CONCAT( tmp.item_id ORDER BY tmp.is_os desc) AS item_ids,
                                    tmp.product_debris ,
                                    tmp.asbestos_type,
                                    tmp.location_reference,
                                    tmp.location_id,
                                    tmp.original_state,
                                    tmp.survey_id,
                                    tmp.is_real FROM
                                        (   SELECT
                                              sv.dropdown_data_item_id as id,
                                              sp.reference,
                                              sp.description,
                                              sp.original_item_id,
                                              sv.dropdown_data_item_id as sample_id,
                                               i.record_id,
                                               i.reference as item_ref,
                                               i.id as item_id,
                                               i2.state as original_state,
                                                ipd.product_debris ,
                                                iab.asbestos_type,
                                                l.location_reference AS location_reference,
                                                l.id AS location_id,
                                                i.survey_id as survey_id,
                                                sp.is_real,
                                                IF(i.record_id = sp.original_item_id, 1, 0) as is_os
                                        FROM
                                                tbl_items AS i
                                                JOIN tbl_item_sample_id_value AS sv ON i.id = sv.item_id
                                                JOIN tbl_sample sp ON sp.id = sv.dropdown_data_item_id
                                                JOIN tbl_items i2 on i2.record_id = sp.original_item_id
                                                JOIN tbl_location l ON i2.location_id = l.id
                                                JOIN tbl_item_product_debris_view ipd ON ipd.item_id = i2.id
                                                JOIN tbl_item_asbestos_type_view iab ON iab.item_id = i2.id
                                        WHERE
                                                i.survey_id = $survey_id
                                        AND
                                                i2.survey_id = $survey_id
                                        AND
                                                i.decommissioned = 0
                                        AND
                                                i2.decommissioned = 0
                                        ORDER BY is_os DESC
                                        ) tmp

                                GROUP BY
                                    tmp.sample_id
                                 ");
        foreach ($survey_samples as $survey_sample) {
            $survey_sample->item_reference = (explode(",",$survey_sample->item_reference));
            $survey_sample->item_ids = (explode(",",$survey_sample->item_ids));
        }

        return $survey_samples;
    }

    public function getSamplesItem($property_id, $survey_id) {

        $survey_samples = DB::select("SELECT
                                              sv.dropdown_data_item_id as id,
                                              sp.reference,
                                              sp.description,
                                              sp.original_item_id,
                                              sp.id as sample_id,
                                              i.reference as item_reference,
                                               i.record_id,
                                               i.reference as item_ref,
                                               i.id as item_id,
                                             i.survey_id as survey_id

                                        FROM
                                                tbl_items AS i
                                                JOIN tbl_item_sample_id_value AS sv ON i.id = sv.item_id
                                                JOIN tbl_sample sp ON sp.id = sv.dropdown_data_item_id
                                        WHERE i.survey_id = $survey_id
                                        AND  i.property_id = $property_id
                                        AND i.decommissioned = 0
                                        ");
        return $survey_samples;
    }

    public function getActiveSamplesTable($property_id, $survey_id) {

        $survey_samples = DB::select("

                                SELECT
                                    tmp.id,
                                    tmp.reference,
                                    tmp.description,
                                    tmp.original_item_id,
                                    tmp.sample_id,
                                    GROUP_CONCAT( IF ( tmp.record_id = tmp.original_item_id, CONCAT( tmp.item_ref, '(OS)' ), tmp.item_ref ) ORDER BY tmp.is_os desc) AS item_reference,
                                    GROUP_CONCAT( tmp.item_id ORDER BY tmp.is_os desc) AS item_ids,
                                    tmp.product_debris ,
                                    tmp.asbestos_type,
                                    tmp.location_reference,
                                    tmp.location_id,
                                    tmp.survey_id,
                                    tmp.is_real FROM
                                        (   SELECT
                                            sv.dropdown_data_item_id as id,
                                            sp.reference,
                                            sp.description,
                                            sp.original_item_id,
                                            sv.dropdown_data_item_id as sample_id,
                                            i.record_id,
                                            i.reference as item_ref,
                                            i.id as item_id,
                                            ipd.product_debris ,
                                            iab.asbestos_type,
                                            l.location_reference AS location_reference,
                                            l.id AS location_id,
                                            i.survey_id as survey_id,
                                            sp.is_real,
                                            IF(i.record_id = sp.original_item_id, 1, 0) as is_os
                                        FROM
                                            tbl_items AS i
                                            JOIN tbl_item_sample_id_value AS sv ON i.id = sv.item_id
                                            JOIN tbl_sample sp ON sp.id = sv.dropdown_data_item_id
                                            JOIN tbl_items i2 on i2.record_id = sp.original_item_id
                                            JOIN tbl_location l ON i2.location_id = l.id
                                            JOIN tbl_item_product_debris_view ipd ON ipd.item_id = i2.id
                                            JOIN tbl_item_asbestos_type_view iab ON iab.item_id = i2.id
                                        WHERE
                                            i.survey_id = $survey_id
                                        AND
                                            i2.survey_id = $survey_id
                                        AND
                                            i.decommissioned = 0
                                        AND
                                            i2.decommissioned = 0
                                        AND
                                            (sp.is_real = -1 OR sp.is_real = 1)
                                        ORDER BY is_os DESC
                                        ) tmp

                                GROUP BY
                                    tmp.sample_id");
        foreach ($survey_samples as $survey_sample) {
            $survey_sample->item_reference = (explode(",",$survey_sample->item_reference));
            $survey_sample->item_ids = (explode(",",$survey_sample->item_ids));
        }

        return $survey_samples;
    }

    public function getSampleById($property_id, $survey_id, $sample_id) {
        $survey_samples = DB::select("

                                SELECT
                                    tmp.id,
                                    tmp.reference,
                                    tmp.description,
                                    tmp.original_item_id,
                                    tmp.sample_id,
                                    GROUP_CONCAT( IF ( tmp.record_id = tmp.original_item_id, CONCAT( tmp.item_ref, '(OS)' ), tmp.item_ref ) ORDER BY tmp.is_os desc) AS item_reference,
                                    GROUP_CONCAT( tmp.item_id ORDER BY tmp.is_os desc) AS item_ids,
                                    tmp.product_debris ,
                                    tmp.asbestos_type,
                                    tmp.location_reference,
                                    tmp.location_id,
                                    tmp.survey_id,
                                    tmp.is_real FROM
                                        (   SELECT
                                            sv.dropdown_data_item_id as id,
                                            sp.reference,
                                            sp.description,
                                            sp.original_item_id,
                                            sv.dropdown_data_item_id as sample_id,
                                            i.record_id,
                                            i.reference as item_ref,
                                            i.id as item_id,
                                            ipd.product_debris ,
                                            iab.asbestos_type,
                                            l.location_reference AS location_reference,
                                            l.id AS location_id,
                                            i.survey_id as survey_id,
                                            sp.is_real,
                                            IF(i.record_id = sp.original_item_id, 1, 0) as is_os
                                        FROM
                                            tbl_items AS i
                                            JOIN tbl_item_sample_id_value AS sv ON i.id = sv.item_id
                                            JOIN tbl_sample sp ON sp.id = sv.dropdown_data_item_id
                                            JOIN tbl_items i2 on i2.record_id = sp.original_item_id
                                            JOIN tbl_location l ON i2.location_id = l.id
                                            JOIN tbl_item_product_debris_view ipd ON ipd.item_id = i2.id
                                            JOIN tbl_item_asbestos_type_view iab ON iab.item_id = i2.id
                                        WHERE
                                            i.survey_id = $survey_id
                                        AND
                                            i2.survey_id = $survey_id
                                        AND
                                            i.decommissioned = 0
                                        AND
                                            i2.decommissioned = 0
                                        AND
                                            sp.id = $sample_id
                                        ORDER BY is_os DESC
                                        ) tmp

                                GROUP BY
                                    tmp.sample_id");

        foreach ($survey_samples as $survey_sample) {
            $survey_sample->item_reference = (explode(",",$survey_sample->item_reference));
            $survey_sample->item_ids = (explode(",",$survey_sample->item_ids));
        }

        return $survey_samples;
    }

    public function updateSample($sample_id, $description, $comment) {
        if (is_array($comment)) {
            $comment = implode(",",$comment);
        }
        $updateSample = Sample::where('id', $sample_id)->update(['description' => $description, 'comment_other' => $comment]);
        if ($updateSample) {
            return \CommonHelpers::successResponse('Sample Updated Successfully!');
        } else {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Sample Updated Fail!');
        }
    }

    public function updateSampleIsReal($check, $uncheck) {
        try {
            Sample::whereIn('id',$check)->update(['is_real' => 1]);
            Sample::whereIn('id',$uncheck)->update(['is_real' => 0]);
            return \CommonHelpers::successResponse('Update Sample successful!');
        } catch (\Exception $e) {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Update Sample Fail!');
        }
    }

    public function decommissionSurvey($survey_id,$decommission_reason) {
        $survey = Survey::find($survey_id);
        $areas = Area::where('survey_id', $survey_id)->get();
        $locations = Location::where('survey_id', $survey_id)->get();
        $items = Item::where('survey_id', $survey_id)->get();
        try {
            if ($survey->decommissioned == SURVEY_DECOMMISSION) {
                Survey::where('id', $survey_id)->update(['decommissioned' => SURVEY_UNDECOMMISSION]);
                Area::where('survey_id', $survey_id)->update(['decommissioned' => AREA_UNDECOMMISSION]);
                Location::where('survey_id', $survey_id)->update(['decommissioned' => LOCATION_UNDECOMMISSION]);
                Item::where('survey_id', $survey_id)->update(['decommissioned' => ITEM_UNDECOMMISSION]);

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
                Survey::where('id', $survey_id)->update(['decommissioned' => SURVEY_DECOMMISSION,'reason_decommissioned' => $decommission_reason]);
                Area::where('survey_id', $survey_id)->update(['decommissioned' => AREA_DECOMMISSION]);
                Location::where('survey_id', $survey_id)->update(['decommissioned' => LOCATION_DECOMMISSION]);
                Item::where('survey_id', $survey_id)->update(['decommissioned' => ITEM_DECOMMISSION]);

                // unlock register data
                // Unlock register areas
                DB::update("UPDATE tbl_area a,
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
                DB::update("UPDATE tbl_items a,
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
                DB::update("UPDATE tbl_location a,
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
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Update Survey Fail!');
        }
    }

    public function listOverdueSurveys($type, $limit) {
        $time = "";
        switch ($type) {
            case "deadline":
                $botTime = 120 * 86400 + time();
                $timeSQL = " AND sd.due_date > $botTime ";
                break;
            case "attention":
                $topTime = 120 * 86400 + time();
                $botTime = 60 * 86400 + time();
                $timeSQL = " AND sd.due_date <= $topTime AND sd.due_date > $botTime ";
                break;
            case "important":
                $topTime = 60 * 86400 + time();
                $botTime = 30 * 86400 + time();
                $timeSQL = " AND sd.due_date <= $topTime AND sd.due_date > $botTime ";
                break;
            case "urgent":
                $topTime = 30 * 86400 + time();
                $botTime = 15 * 86400 + time();
                $timeSQL = " AND sd.due_date <= $topTime AND sd.due_date > $botTime ";
                break;
            case "critical":
                $topTime = 15 * 86400 + time();
                $timeSQL = " AND sd.due_date <= $topTime ";
                break;
            default:
                break;
        }
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

        $result = DB::select("
                        SELECT s.id, sd.due_date, s.reference, p.pblock ,
                        pj.title as project_title,
                        pj.id as project_id,
                        pj.reference as project_reference,
                        s.status
                        FROM tbl_survey s
                        LEFT JOIN tbl_survey_date as sd ON sd.survey_id = s.id
                        LEFT JOIN tbl_project as pj ON pj.id = s.project_id
                        LEFT JOIN tbl_property as p ON p.id = s.property_id
                        JOIN $table_join_privs on permission.prop_id = s.property_id
                        WHERE s.decommissioned = 0
                        AND s.status !=5
                          $timeSQL
                        ORDER BY sd.due_date DESC");
        return $result;
    }

    public function getOverdueSurveySites($datacentreRisk, $clientID = 0, $page = 0, $limit = 2000) {

        $daymarker = 365;
        $toTime = time();
        $deadlineTime = 120;
        $attentionTime = 60;
        $importantTime = 30;
        $urgentTime = 15;
        $varGroupBy = "lastDay";
        if (is_numeric($datacentreRisk)) {

            $sqlCheck = " AND p.zone_id = " . $datacentreRisk;
            $sqlHaving = " HAVING $varGroupBy > 0 ";
        } else {
            switch ($datacentreRisk) {
                case "deadline":
                    $sqlHaving = " HAVING $varGroupBy > $deadlineTime ";
                    break;
                case "attention":
                    $sqlHaving = " HAVING $varGroupBy <= $deadlineTime AND $varGroupBy > $attentionTime ";
                    break;
                case "important":
                    $sqlHaving = " HAVING $varGroupBy <= $attentionTime AND $varGroupBy > $importantTime ";
                    break;
                case "urgent":
                    $sqlHaving = " HAVING $varGroupBy <= $importantTime AND $varGroupBy >= $urgentTime ";
                    break;
                case "critical":
                    $sqlHaving = " HAVING $varGroupBy < $urgentTime ";
                    break;
                default:
                    $sqlHaving = "failed";
                    break;
            }

            $sqlCheck = ($clientID) ? " AND p.client_id= " . $clientID : "";
        }
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();
        // Get management surveys
        $sql = "SELECT
                p.id,
                p.zone_id,
                p.property_reference AS UPRN,
                p.pblock AS BlockName,
                TRIM( p.`name` ) AS name,
                p.`client_id`,
                GREATEST(
                IFNULL( s.started_date, 0 ),
                IFNULL( sR.started_date, 0 ),
                IFNULL( h.added, 0 )
                ) + ( 365 * 86400 ) 'lastDate',
                FLOOR(
                    (
                        GREATEST(
                        IFNULL( s.started_date, 0 ),
                        IFNULL( sR.started_date, 0 ),
                        IFNULL( h.added, 0 )
                        ) + ( 365 * 86400 ) - $toTime
                    ) / 86400
                ) AS 'lastDay',
                z.zone_name
            FROM
                tbl_property AS p
                LEFT JOIN ( SELECT MIN( added ) added, property_id FROM
                                    tbl_historicdocs WHERE property_id > 0
                                    AND `is_external_ms` = 1
                                    GROUP BY property_id ) AS h ON p.id = h.property_id
                LEFT JOIN ( SELECT s.id, started_date, property_id FROM
                                    tbl_survey s LEFT JOIN tbl_survey_date sv ON s.id = sv.survey_id
                                    WHERE s.survey_type = 1 AND s.decommissioned = 0 AND `status` = 5 GROUP BY property_id )
                                    AS s ON p.id = s.property_id
                LEFT JOIN ( SELECT MAX( sv.started_date ) as started_date, property_id FROM tbl_survey s
                                    LEFT JOIN tbl_survey_date sv ON s.id = sv.survey_id
                                    WHERE s.survey_type = 3 AND s.decommissioned = 0 AND s.`status` = 5
                                    GROUP BY s.property_id ) AS sR ON p.id = sR.property_id
                LEFT JOIN (SELECT COUNT(DISTINCT id) countACM, property_id
                                FROM tbl_items WHERE survey_id = 0 AND state != 1 AND decommissioned = 0 GROUP BY property_id) AS i ON i.property_id= p.id
                JOIN (SELECT ppt.property_id from property_property_type ppt
                            JOIN tbl_property_type pt ON ppt.property_type_id = pt.id
                            WHERE pt.ms_level = 1
                            GROUP BY ppt.property_id) AS r ON p.id = r.property_id
                LEFT JOIN tbl_zones AS z ON z.id = p.zone_id
                JOIN $table_join_privs ON permission.prop_id = p.id
            WHERE
                p.decommissioned = 0
                AND i.countACM > 0  " . $sqlCheck
            . " GROUP BY p.id " . $sqlHaving ;

        // order
        if ($page == 1) {
            $sql .= " ORDER BY lastDay";
        } else {
            $sql .= " ORDER BY lastDay LIMIT $page, $limit ";
        }
        $results = DB::select($sql);
        return $results;
    }

    public  function getOverdueSurveySites2($datacentreRisk, $user_id, $clientID = 0, $page = 0, $limit = 2000) {

        $daymarker = 730;
        if (is_numeric($datacentreRisk)) {

            $sqlCheck = " AND site.zone_id = " . $datacentreRisk;
            $sqlHaving = " HAVING completed_date > 0 ";
        } else {
            switch ($datacentreRisk) {
                case "deadline":
                    $botTime = time() - (($daymarker - 120) * 86400);
                    $sqlHaving = "HAVING completed_date > $botTime ";
                    break;
                case "attention":
                    $topTime = time() - (($daymarker - 120) * 86400);
                    $botTime = time() - (($daymarker - 60) * 86400);
                    $sqlHaving = " HAVING completed_date <= $topTime AND completed_date > $botTime ";
                    break;
                case "important":
                    $topTime = time() - (($daymarker - 60) * 86400);
                    $botTime = time() - (($daymarker - 30) * 86400);
                    $sqlHaving = " HAVING completed_date <= $topTime AND completed_date > $botTime ";
                    break;
                case "urgent":
                    $topTime = time() - (($daymarker - 30) * 86400);
                    $botTime = time() - (($daymarker - 15) * 86400);
                    $sqlHaving = " HAVING completed_date <= $topTime AND completed_date > $botTime ";
                    break;
                case "critical":
                    $topTime = time() - (($daymarker - 15) * 86400);
                    //                    $sqlHaving = " HAVING MAX(s.completed_date) <= $topTime OR MAX(s.completed_date) IS NULL ";
                    $sqlHaving = " HAVING completed_date <= $topTime ";
                    break;
                default:
                    $sqlHaving = "failed";
                    break;
            }
        }
        // Get management surveys
        $sqlQuery = "SELECT site.id,
                            site.zone_id,
                            site.reference AS UPRN,
                            site.pblock AS BlockName,
                            site.`name`,
                            site.`client_id`,
                            GREATEST(IFNULL(s.surveying_finish_date, 0), IFNULL(sM.surveying_finish_date, 0), IFNULL(h.added, 0)) as 'completed_date',
                            z.zone_name
                    FROM tbl_property AS site
                    LEFT JOIN tbl_location as l ON l.property_id = site.id and l.survey_id = 0 and l.decommissioned = 0 and l.state = 0
                    LEFT JOIN tbl_items as i ON i.property_id = site.id and i.survey_id = 0 and i.decommissioned = 0

                    LEFT JOIN (SELECT MIN(date) added, property_id FROM compliance_documents WHERE property_id > 0 AND `is_external_ms` = 1 GROUP BY property_id) as h ON p.id = h.property_id
                    LEFT JOIN ( SELECT reference, sd.completed_date, sd.surveying_finish_date, sd.started_date, property_id FROM tbl_survey LEFT JOIN tbl_survey_date sd ON tbl_survey.id = sd.survey_id  WHERE survey_type = 1 AND decommissioned = 0 AND `status`= 5 GROUP BY property_id) AS sM ON site.id = sM.property_id
                    LEFT JOIN ( SELECT MAX(tbl_survey_date.surveying_finish_date) surveying_finish_date, property_id FROM tbl_survey LEFT JOIN tbl_survey_date ON tbl_survey.id = tbl_survey_date.survey_id WHERE survey_type = 3 AND decommissioned = 0 AND `status`= 5 GROUP BY property_id) AS s ON site.id= s.property_id
                    LEFT JOIN (SELECT COUNT(DISTINCT id) countACM, property_id FROM tbl_items WHERE survey_id = 0 AND state != 1 AND decommissioned = 0 GROUP BY property_id) AS i ON i.property_id= site.id
                    JOIN (SELECT ppt.property_id from property_property_type ppt
                            JOIN tbl_property_type pt ON ppt.property_type_id = pt.id
                            WHERE pt.ms_level = 1
                            GROUP BY ppt.property_id) AS r ON site.id = r.property_id
                    LEFT JOIN tbl_zones as z ON z.id = site.zone_id
                    WHERE site.decommissioned = 0 AND i.countACM > 0  " . $sqlCheck
            . " GROUP BY site.id " . $sqlHaving . " AND (COUNT(i.id) > 0 OR COUNT(l.ID) > 0) ";

        // order
        if ($page == -1) {
            $sqlQuery .= " ORDER BY completed_date";
        } else {
            $sqlQuery .= " ORDER BY completed_date LIMIT $page, $limit ";
        }
        $results = \DB::select($sqlQuery);

        if ($results)
            return $results;
        else
            return [];
    }
    /**
     *  1 Management Survey
        2 Refurbishment Survey
        3 Re-Inspection Survey
        4 Demolition Survey
     * @param $query_string
     * @param int $type
     * @return array
     */
    public function searchSurvey($query_string, $type = 0) {

        $checkType = ($type) ? "AND s.`survey_type` = " . $type : "";

        if (\Auth::user()->clients->client_type == 1) {
            $checkType .= " AND s.client_id = " . \Auth::user()->client_id;
        }
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

        $sql = "SELECT s.id as id, s.reference as reference, p.`name` AS 'property_name', p.property_reference
                FROM `tbl_survey` as s
                LEFT JOIN tbl_property as p ON p.id = s.property_id
                JOIN $table_join_privs ON permission.prop_id = s.property_id
                WHERE
                    s.`reference` LIKE '%" . $query_string . "%'
                    $checkType
                    AND s.`decommissioned` = 0
                ORDER BY `reference` ASC
                LIMIT 0,20";
        $list = DB::select($sql);

        return $list;
    }

    public function sendSurvey($survey_id) {
        $survey =  Survey::find($survey_id);
        try {
            Survey::where('id', $survey_id)->update([
                        'is_locked' => SURVEY_LOCKED,
                        'status' => 2,
                    ]);
            $dataSurveyDate['sent_out_date'] = Carbon::now()->timestamp;
            if (is_null($survey->surveyDate->started_date)) {
                $dataSurveyDate['sent_out_date'] = Carbon::now()->timestamp;
            }
            SurveyDate::where('survey_id', $survey_id)->update($dataSurveyDate);
            $this->lockSurvey($survey_id);

            //log audit
            $comment = \Auth::user()->full_name  . " send survey "  . $survey->reference ." to surveyor " . \CommonHelpers::getUserFullname($survey->surveyor_id);
            \CommonHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_SEND, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);
            return $response = \CommonHelpers::successResponse('Survey Sent successfully!');
        } catch (\Exception $e) {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Send survey fail!');
        }
    }

    // donot update timestamps
    public function lockSurvey($survey_id){
        Survey::where('id', $survey_id)->update(['is_locked' => 1]);
        // Area::where('survey_id', $survey_id)->update(['is_locked'=> 1]);
        // Location::where('survey_id', $survey_id)->update(['is_locked'=> 1]);
        // Item::where('survey_id', $survey_id)->update(['is_locked'=> 1]);
        \DB::select("Update tbl_area set is_locked = 1 where survey_id = $survey_id");
        \DB::select("Update tbl_location set is_locked = 1 where survey_id = $survey_id");
        \DB::select("Update tbl_items set is_locked = 1 where survey_id = $survey_id");
    }

    // donot update timestamps
    public function unLockSurvey($survey_id){
        Survey::where('id', $survey_id)->update(['is_locked' => 0]);
        // Area::where('survey_id', $survey_id)->update(['is_locked'=> 0]);
        // Location::where('survey_id', $survey_id)->update(['is_locked'=> 0]);
        // Item::where('survey_id', $survey_id)->update(['is_locked'=> 0]);
        \DB::select("Update tbl_area set is_locked = 0 where survey_id = $survey_id");
        \DB::select("Update tbl_location set is_locked = 0 where survey_id = $survey_id");
        \DB::select("Update tbl_items set is_locked = 0 where survey_id = $survey_id");
    }
    /**
     * pluck survey_id => sent_out_date
     * @param $user_id
     * @return array|Collection
     */
    public function getDataManifest($user_id){
        $survey = Survey::with('surveyDate')
            ->where(['surveyor_id'=> $user_id,'is_locked'=> SURVEY_LOCKED,'status'=> LOCKED_SURVEY_STATUS])->get()->pluck('surveyDate.sent_out_date','id');
        return is_null($survey) ? [] : $survey;
    }

    public function getCheckSurveyApi($surveyor_id){
        return Survey::where('status',LOCKED_SURVEY_STATUS)->where('surveyor_id', $surveyor_id)->orderBy('created_at','desc')->pluck('id');
    }

    public function LoadInspectionFormula($currentDate, $totalRisk) {

        $inspectionDate = 0;
        if ($totalRisk > 0) {
            if ($totalRisk >= 17) {
                $inspectionDate = $currentDate + (90 * 86400);
            } elseif ($totalRisk >= 13 && $totalRisk <= 16) {
                $inspectionDate = $currentDate + (180 * 86400);
            } elseif ($totalRisk >= 8 && $totalRisk <= 12) {
                $inspectionDate = $currentDate + (365 * 86400);
            } elseif ($totalRisk < 8) {
                $inspectionDate = $currentDate + (540 * 86400);
            }
        }

        return $inspectionDate;
    }

    public function missingSurvey($client_id = 0, $page = 0, $limit = 500) {
        $checkClientType = ($client_id != 0) ? " AND p.`client_id` = $client_id " : " ";

        $sqlQuery = "SELECT DISTINCT
                            p.`id`,
                            p.`property_reference`,
                            p.`name`,
                            p.`pblock`,
                            p.`client_id`,
                            IF(p.created_at > 0 , Date_Format(p.created_at,'%d/%m/%Y'), '') created_at
                    FROM
                        tbl_property AS p
                    LEFT JOIN tbl_survey AS s ON p.id = s.property_id
                    AND s.survey_type = 1 AND s.decommissioned = 0
                    LEFT JOIN `tbl_historicdocs` AS his ON p.id = his.property_id
                    AND his.historical_type = 1
                    LEFT JOIN
                    (SELECT property_id from property_property_type pp1
                    JOIN tbl_property_type pp2 ON pp1.property_type_id = pp2.id AND pp2.ms_level = 1
                    GROUP BY pp1.property_id) pp3 ON pp3.property_id = p.id
                    WHERE pp3.property_id IS NOT NULL
                    AND p.decommissioned = 0
                    AND s.id IS NULL
                    AND his.id IS NULL
                    $checkClientType
                    ORDER BY p.`created_at` DESC";
        return \DB::select($sqlQuery) ?? [];
    }

    public function searchSurveyAdminTool($query_string, $type = 0) {
        $where = '';
        // for unlock and revert back survey
        if($type == 1){
            $where = ' AND status IN(2,4) ';
        } else if($type == 2){
            $where = ' AND status = 5 ';
        }

        $sql = "SELECT s.id as id, s.reference as reference, p.`name` AS 'property_name', p.property_reference
                FROM `tbl_survey` as s
                LEFT JOIN tbl_property as p ON p.id = s.property_id
                WHERE
                    s.`reference` LIKE '%" . $query_string . "%'
                    $where
                ORDER BY `reference` ASC
                LIMIT 0,20";
        $list = DB::select($sql);

        return $list;
    }

    public function hasAwardedSurveyProperty($client_id, $property_id){
//        $survey = Survey::where('property_id', $property_id)
//            ->where('decommissioned', 0)
//            ->whereRaw("(client_id = $client_id)")
//            ->orderBy('id','desc')
//            ->first();
        if($client_id == 13){
            return true;
        } else {
            return false;
        }
    }
}
