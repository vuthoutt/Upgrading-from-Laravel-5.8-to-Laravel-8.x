<?php
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\AppBaseController;
use App\Http\Request\API\CheckSurveyRequest;
use App\Http\Request\API\GetImageSurveyRequest;
use App\Http\Request\API\GetSurveyDetailRequest;
use App\Http\Request\API\GetImagePPlanRequest;
use App\Models\ApiToken;
use App\Models\DownloadManifest;
use App\Models\ShineDocumentStorage;
use App\Models\Survey;
use App\Repositories\ApiTokenRepository;
use App\Repositories\LocationRepository;
use App\Repositories\SurveyRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

/**
 * Created by PhpStorm.
 * User: Hoang Tran
 * Date: 8/8/2019
 * Time: 3:55 PM
 */
class DownloadController extends  AppBaseController {
    private $apiTokenRepository;
    private $surveyRepository;
    private $locationRepository;

    public function __construct(ApiTokenRepository $apiTokenRepository,
                                LocationRepository $locationRepository,
                                SurveyRepository $surveyRepository)
    {
        $this->apiTokenRepository = $apiTokenRepository;
        $this->surveyRepository = $surveyRepository;
        $this->locationRepository = $locationRepository;
    }

    public function checkSurvey(CheckSurveyRequest $checkSurveyRequest){
        $user_id = \Request::get('userID');
        $survey_ids = $this->surveyRepository->getCheckSurveyApi($user_id);
        $result['surveyIDs'] = $survey_ids;

        // $result
        return $this->sendResponse($result, 'Get Check Survey Successfully!');
    }

    /**
     * get all survey details when app login
     * @param $user_id
     * @return array
     */
    public function getSurveyDetail(GetSurveyDetailRequest $request) {
        //get all survey details
        $user_id = $request->userID;
        $survey_id = $request->surveyID;
        $appVersion = $request->appVersion; // For View Only No Risk Items
        $result = [];

        //get all survey_id by user_id

        $survey = $this->surveyRepository->getSurveyFromApi($survey_id);

        if($survey){
            $result['surveyDetailId'] = $survey->id;
            $result['sentOutDate'] = $survey->surveyDate->sent_out_date_raw ?? '';
            $result['surveyType'] = $survey->survey_type;
            $result['surveyReference'] = $survey->reference;
            // get client info

            // get asset info
            $result['assetDetails'] = $this->getAssetInfo($survey);
            $result['floorAreas'] = [];
            foreach ($survey->surveyArea as $area) {
                $result['floorAreas'][] =  $this->getArea($area, $appVersion);
            }

            $result['surveySettings'] = [
                "priorityAssessmentRequired" => isset($survey->surveySetting->is_require_priority_assessment) and ($survey->surveySetting->is_require_priority_assessment == 1) ? true : false,
                "constructionDetailsRequired" => isset($survey->surveySetting->is_require_construction_details) and ($survey->surveySetting->is_require_construction_details == 1) ? true : false,
                "photosRequired" => isset($survey->surveySetting->is_require_photos) and ($survey->surveySetting->is_require_photos == 1) ? true : false,
                "licenseStatusRequired" => isset($survey->surveySetting->is_require_license_status) and ($survey->surveySetting->is_require_license_status == 1) ? true : false,
                "sizeRequired" => isset($survey->surveySetting->is_require_size) and ($survey->surveySetting->is_require_size == 1) ? true : false,
                "propertyPlanPhoto" => isset($survey->surveySetting->is_property_plan_photo) and ($survey->surveySetting->is_property_plan_photo == 1) ? true : false,
                "locationConstructionDetailsRequired" => isset($survey->surveySetting->is_require_location_construction_details) and ($survey->surveySetting->is_require_location_construction_details == 1) ? true : false,
                "locationVoidInvestigationsRequired" => isset($survey->surveySetting->is_require_location_void_investigations) and ($survey->surveySetting->is_require_location_void_investigations == 1) ? true : false,
                "RDinManagementAllowed" => isset($survey->surveySetting->is_require_r_and_d_elements) and ($survey->surveySetting->is_require_r_and_d_elements == 1) ? true : false,
            ];
        }
        return $this->sendResponse($result, 'Get Survey Detail Successfully!');

    }

    private function getSurveyorNote($surveyor_note) {
        $result = [];
        $result['ID'] = $surveyor_note->id ?? 0;
        $result['reference'] = $surveyor_note->reference ?? 0;
        $result['planName'] = $surveyor_note->name ?? '';
        $result['planDescription'] = $surveyor_note->plan_reference ?? '';

        return $result;
    }


    private function getAssetInfo($survey){
        $result = [];
        $result['propertyId'] = $survey->property->id ?? "";

        $result['propertyStatus'] = $survey->property->propertySurvey->property_status ?? '';
        $result['propertyOccupied'] = $survey->property->propertySurvey->property_occupied ?? '';

        $result['propertyAccessType']['accessPrimary'] = $survey->property->propertySurvey->programme_type ?? "";
        $result['propertyAccessType']['accessPrimaryOther'] = $survey->property->propertySurvey->programme_type_other ?? ""; // new att

        $result['assetName'] = $survey->property->name ?? "";
        $result['reference'] = $survey->property->property_reference ?? "";
        $result['address'] = $survey->property->propertyInfo->list_address ?? ""; // list_address
        $result['postcode'] = $survey->property->propertyInfo->postcode ?? "";
        $result['contact'] =  $survey->property->propertyInfo->propertyInfoUser->full_name ?? "";
        $result['telephone'] = $survey->property->propertyInfo->telephone ?? "";
        $result['mobile'] = $survey->property->propertyInfo->mobile ?? "";
        $result['email'] = $survey->property->propertyInfo->email ?? "";

        $result['assetUse']['primary'] =  $survey->property->propertySurvey->asset_use_primary ?? "";
        $result['assetUse']['primaryOther'] = $survey->property->propertySurvey->asset_use_primary_other ?? "";
        $result['assetUse']['secondary'] =  $survey->property->propertySurvey->asset_use_secondary ?? "";
        $result['assetUse']['secondaryOther'] =  $survey->property->propertySurvey->asset_use_secondary_other ?? "";

        $result['construction']['assetAge'] =  $survey->property->propertySurvey->construction_age ?? "";
        $result['construction']['contructionType'] =  $survey->property->propertySurvey->construction_type ?? "";
        $result['construction']['electricalMeter'] = $survey->property->propertySurvey->electrical_meter ?? "";
        $result['construction']['gasMeter'] = $survey->property->propertySurvey->gas_meter ?? "";
        $result['construction']['loftVoid'] = $survey->property->propertySurvey->loft_void ?? "";

        $result['sizeVolume']['numberFloors'] =  $survey->property->propertySurvey->size_floors ?? "";
        $result['sizeVolume']['numberStaircases'] =  $survey->property->propertySurvey->size_staircases ?? "";
        $result['sizeVolume']['numberLifts'] =  $survey->property->propertySurvey->size_lifts ?? "";
        $result['sizeVolume']['netAreaPerFloor'] =  $survey->property->propertySurvey->size_net_area ?? "";
        $result['sizeVolume']['grossArea'] =  $survey->property->propertySurvey->size_gross_area ?? "";
        $result['sizeVolume']['comments'] =  $survey->property->propertySurvey->size_comments ?? "";

        $result['comments'] =  $survey->property->comments ?? "";
        return $result;

    }

    private function getSurveySetting($survey){

    }

    private function getArea($area, $appVersion = null) {

        $areaData = [];

        $decommission_status = $this->sendAssessStatusAndReason($area);
        $areaData['objectStatus'] = $decommission_status['objectStatus'];
        $areaData['statusReason'] = $decommission_status['statusReason'];

        $areaData['floorDetailID'] = $area->id ?? "";
        $areaData['reference'] = $area->area_reference ?? "";
        $areaData['description'] = $area->description ?? "";

        $areaData['roomLocations'] = [];

        foreach ($area->allAreaLocations as $location) {
            $areaData['roomLocations'][] = $this->getLocation($location, $appVersion);
        }
        return $areaData;
    }

    private function getLocation($location, $appVersion = null) {
        $locationData = [];
        $locationData['roomDetailID'] = $location->id ?? "";

        $decommission_status = $this->sendAssessStatusAndReason($location);
        $locationData['objectStatus'] = $decommission_status['objectStatus'];
        $locationData['statusReason'] = $decommission_status['statusReason'];

        $locationData['isAccessible'] = ($location->state == LOCATION_STATE_ACCESSIBLE) ? true : false;
        $locationData['reference'] = $location->location_reference ?? "";
        $locationData['description'] = $location->description ?? "";
        //construction
        $locationData['ceiling'] = $location->locationConstruction->ceiling ?? "";
        $locationData['ceilingOther'] = $location->locationConstruction->ceiling_other ?? "";
        $locationData['walls'] = $location->locationConstruction->walls ?? "";
        $locationData['wallsOther'] = $location->locationConstruction->walls_other ?? "";
        $locationData['floor'] = $location->locationConstruction->floor ?? "";
        $locationData['floorOther'] = $location->locationConstruction->floor_other ?? "";
        $locationData['doors'] = $location->locationConstruction->doors ?? "";
        $locationData['doorsOther'] = $location->locationConstruction->doors_other ?? "";
        $locationData['windows'] = $location->locationConstruction->windows ?? "";
        $locationData['windowsOther'] = $location->locationConstruction->windows_other ?? "";

        //void
        $locationData['ceilingVoid'] = $location->locationVoid->ceiling ?? "";
        $locationData['ceilingVoidOther'] = $location->locationVoid->ceiling_other ?? "";
        $locationData['floorVoid'] = $location->locationVoid->floor ?? "";
        $locationData['floorVoidOther'] = $location->locationVoid->floor_other ?? "";
        $locationData['cavities'] = $location->locationVoid->cavities ?? "";
        $locationData['cavitiesOther'] = $location->locationVoid->cavities_other ?? "";
        $locationData['risers'] = $location->locationVoid->risers ?? "";
        $locationData['risersOther'] = $location->locationVoid->risers_other ?? "";
        $locationData['ducting'] = $location->locationVoid->ducting ?? "";
        $locationData['ductingOther'] = $location->locationVoid->ducting_other ?? "";
        $locationData['boxing'] = $location->locationVoid->boxing ?? "";
        $locationData['boxingOther'] = $location->locationVoid->boxing_other ?? "";
        $locationData['pipework'] = $location->locationVoid->pipework ?? "";
        $locationData['pipeworkOther'] = $location->locationVoid->pipework_other ?? "";

        $locationData['reason'] = $location->locationInfo->reason_inaccess_key ?? "";
        $locationData['reasonOther'] = $location->locationInfo->reason_inaccess_other ?? "";
        $locationData['comments'] = $location->locationInfo->comments ?? "";


        $locationData['records'] = [];

        // View only No Risk Items
        if (!empty($appVersion)) {
            $viewOnlyNoRiskItems = $this->locationRepository->getViewOnlyNoRiskItems($location);
            foreach ($viewOnlyNoRiskItems as $item) {
                $locationData['records'][] = $this->getItem($item, true);
            }
        }

        foreach ($location->allSurveyItems as $item) {
            $locationData['records'][] = $this->getItem($item);
        }
        return $locationData;
    }

    private function getItem($item, $isViewOnly = false) {
        $itemData = [];
        $itemData['recordDetailID'] = $item->id ?? '';
        $itemData['isViewOnly'] = $isViewOnly;

        $decommission_status = $this->sendAssessStatusAndReason($item);
        $itemData['objectStatus'] = $decommission_status['objectStatus'];
        $itemData['statusReason'] = $decommission_status['statusReason'];

        $isACM = ($item->state == ITEM_NOACM_STATE) ? 0 : 1;

        $isAccessible = ($item->state == ITEM_ACCESSIBLE_STATE) ? 1 : 0;
        $isFullAssessment = ($item->itemInfo->assessment == ITEM_FULL_ASSESSMENT) ? 1 : 0;

        $itemData['itemType'] = \CommonHelpers::getItemStateText($isACM , $isAccessible , $isFullAssessment);

        $itemData['reference'] = $item->name ?? '';

        //sample
        $item_current_record_id = $item->record_id ?? 0;
        $original_item_id_sample = $item->sample->original_item_id ?? -1;
        $itemData['sample'] = [
            'sampleID' => $item->sample->id ?? 0,
            'reference' => $item->sample->description ?? '',
            'isDecommissioned' =>  isset($item->sample->decommissioned) and ($item->sample->decommissioned == ITEM_DECOMMISSION) ? true : false,
            'originalItem' => $item->sample->original_item_id ?? 0,
            'isOS' => ($item_current_record_id == $original_item_id_sample) ? true :false,
            'comments' => $item->sample->comment_key ?? '',
            'commentsOther' => $item->sample->comment_other ?? '',
        ];

        $itemData['reason'] = $item->ItemNoAccessValue->dropdown_data_item_id ?? 0;
        $itemData['reasonOther'] = $item->ItemNoAccessValue->dropdown_other ?? '';

        $itemData['specificLocation'] = $item->SpecificLocationValue->dropdown_data_item_id ?? 0;
        $itemData['specificLocationOther'] = $item->SpecificLocationValue->dropdown_other ?? '';

        $itemData['productDebris'] = $item->ProductDebrisTypeValue->dropdown_data_item_id ?? 0;
        $itemData['productDebrisOther'] = $item->ProductDebrisTypeValue->dropdown_other ?? '';

        $itemData['asbestosType'] = $item->AsbestosTypeValue->dropdown_data_item_id ?? 0;
        $itemData['asbestosTypeOther'] = $item->AsbestosTypeValue->dropdown_other ?? '';

        $itemData['extent'] = [
            'value' => $item->itemInfo->extent ?? 0,
            'measurement' => $item->ExtentValue->dropdown_data_item_id ?? 0,
            'measurementOther' => $item->ExtentValue->dropdown_other ?? '',
        ];

        $itemData['rAndD'] = (isset($item->itemInfo->is_r_and_d_element) and ($item->itemInfo->is_r_and_d_element == 1)) ? true : false;
        $itemData['comments'] = $item->itemInfo->comment ?? '';

        // Mas value
        $itemData['materialAssessment'] = [
            'productDebrisType' => $item->masProductDebris->dropdown_data_item_id ?? 0,
            'damageDeterioration' => $item->masDamage->dropdown_data_item_id ?? 0,
            'surfaceTreatment' => $item->masTreatment->dropdown_data_item_id ?? 0,
            'asbestosType' => $item->masAsbestos->dropdown_data_item_id ?? 0,
        ];

        // Pas value
        $itemData['priorityAssessment'] = [
            'normalOccupancyActivity' => [
                'primary' => $item->pasPrimary->dropdown_data_item_id ?? 0,
                'secondary' => $item->pasSecondary->dropdown_data_item_id ?? 0,
            ],

            'likelihoodOfDisturbance' => [
                'location' => $item->pasLocation->dropdown_data_item_id ?? 0,
                'accessibility' => $item->pasAccessibility->dropdown_data_item_id ?? 0,
                'extentAmount' => $item->pasExtent->dropdown_data_item_id ?? 0,
            ],

            'humanExposurePotential' => [
                'number' => $item->pasNumber->dropdown_data_item_id ?? 0,
                'frequency' => $item->pasHumanFrequency->dropdown_data_item_id ?? 0,
                'averageTime' => $item->pasAverageTime->dropdown_data_item_id ?? 0,
            ],

            'maintenanceActivity' => [
                'type' => $item->pasType->dropdown_data_item_id ?? 0,
                'frequency' => $item->pasMaintenanceFrequency->dropdown_data_item_id ?? 0,
            ],
        ];

        $itemData['actionsRecommendations'] = $item->ActionRecommendationValue->dropdown_data_item_id ?? 0;
        $itemData['actionsRecommendationsOther'] = $item->ActionRecommendationValue->dropdown_other ?? '';

        return $itemData;
    }

    //return download property image in survey
    public function getSurveyImage(GetImageSurveyRequest $request){
        $type = $request->type;
        $survey_id = $request->surveyID;
        $survey = Survey::where('id', $survey_id)->first();
        if($survey) {
            if ($type == 'asset') {
               $storage = ShineDocumentStorage::where(['type'=>PROPERTY_SURVEY_IMAGE,'object_id'=>$survey->id])->first();
               if($storage && file_exists($storage->path)){
                   return response()->download($storage->path, $storage->file_name);
               }
            }
        }
        return $this->sendError('Not found image', 404);
    }

    //return download property image in survey
    public function getPPlan(GetImagePPlanRequest $request){
        $id = $request->planID;

        $storage = ShineDocumentStorage::where(['type'=> PLAN_FILE, 'object_id'=> $id])->first();

        if($storage && file_exists($storage->path)){
            return response()->download($storage->path, $storage->file_name);
        }

        return $this->sendError('Not found image', 404);
    }

    public function sendAssessStatusAndReason($data) {
        if ($data->decommissioned == 0) {
            $object_id = $data->not_assessed ?? 0;
            $reason = $data->not_assessed_reason ?? 0;
        } else {
            $object_id = $data->decommissioned;
            $reason = $data->decommissioned_reason ?? 0;
        }
        return [
            'objectStatus' => $object_id,
            'statusReason' => $reason
        ];
    }
}
