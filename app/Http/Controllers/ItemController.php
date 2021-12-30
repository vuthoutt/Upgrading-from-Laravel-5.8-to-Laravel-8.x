<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelpers;
use Illuminate\Http\Request;
use App\Repositories\ItemRepository;
use App\Models\Item;
use App\Models\Sample;
use App\Repositories\LocationRepository;
use App\Repositories\PropertyRepository;
use App\Repositories\SurveyRepository;
use App\Http\Request\Item\ItemCreateRequest;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(LocationRepository $locationRepository, ItemRepository $itemRepository, SurveyRepository $surveyRepository, PropertyRepository $propertyRepository)
    {
        $this->itemRepository = $itemRepository;
        $this->locationRepository = $locationRepository;
        $this->surveyRepository = $surveyRepository;
        $this->propertyRepository = $propertyRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($id, Request $request)
    {
        $location_id = $request->location;

        // only pagination for some case
        $pagination = [];
        $item = $this->itemRepository->getItem($id);

        if (!$item) {
            abort(404);
        }
        if(isset($request->position)){
            $list_items = $this->itemRepository->getItemPagination($item->survey_id, $item->decommissioned, $item->property_id, $item->area_id, $item->location_id, $item->zone->id ?? NULL, $item->client->id ?? NULL, $request->category, $request->pagination_type);
            //set path
            $pagination = CommonHelpers::setPathPagination($request, $list_items, '', $id);
        }
        $canBeUpdateThisItem = true;
        //check privilege
        if (\CommonHelpers::isSystemClient()) {
            // property permission
            if(!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $item->property_id)){
                abort(404);
            }
            // register permission
            if ($item->survey_id == 0) {
                // if (!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $item->property_id) ||!\CompliancePrivilege::checkPermission(REGISTER_VIEW_PRIV)) {
                //     abort(404);
                // }
                // $canBeUpdateThisItem = \CompliancePrivilege::checkUpdatePermission(REGISTER_UPDATE_PRIV) and \CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $item->property_id);
            // survey permission
            } else {
                // if (!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $item->property_id) ||!\CompliancePrivilege::checkPermission(SURVEYS_PROP_VIEW_PRIV)) {
                //     abort(404);
                // }
                // $canBeUpdateThisItem = \CompliancePrivilege::checkUpdatePermission(SURVEYS_PROP_UPDATE_PRIV) and \CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $item->property_id);
            }

        } else {
            if ($item->survey_id > 0) {
                if(\Auth::user()->client_id == $item->survey->client_id || \Auth::user()->id == $item->survey->surveyor_id || \Auth::user()->id == $item->survey->consultant_id || \Auth::user()->id == $item->survey->created_by) {
                    $canBeUpdateThisItem = true;
                } else {
                    $canBeUpdateThisItem = false;
                }
            } else {
                $canBeUpdateThisItem = false;
            }
        }

        if (is_null($item)) {
            return abort(404);
        }
        $extent = $this->itemRepository->getDropdownItemValue($id, EXTENT_ID);
        $specificLocation = $this->itemRepository->getDropdownItemValue($id, SPECIFIC_LOCATION_ID);
        $selectedSampleId = $this->itemRepository->getDropdownItemValue($id, SAMPLE_ID, 0, 'id');
        $selectedSample = Sample::find($selectedSampleId);

        $sampleComment = $this->itemRepository->getDropdownText(optional($selectedSample)->comment_key, SAMPLE_COMMENTS_ID);

        $selectedReason = $item->ItemNoAccessValue->dropdown_data_item_id ?? 0;

        if ($selectedReason != 592) {
            $reason = $this->itemRepository->getDropdownText($selectedReason, ITEM_NO_ACCESS_ID);
        } else {
            $reason = $this->itemRepository->getDropdownText($selectedReason, ITEM_NO_ACCESS_ID) . ' ' .($item->ItemNoAccessValue->dropdown_other ?? '');
        }
        $reason = str_replace('Other', '', $reason);
        // material tab MAS
        $assessmentTypeKey = $this->itemRepository->getDropdownItemValue($id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_TYPE_KEY);
        $assessmentDamageKey = $this->itemRepository->getDropdownItemValue($id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_DAMAGE_KEY);
        $assessmentTreatmentKey = $this->itemRepository->getDropdownItemValue($id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_TREATMENT_KEY);
        $assessmentAsbestosKey = $this->itemRepository->getDropdownItemValue($id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_ASBESTOS_KEY);

        // PAS tab
        $pasPrimary = $this->itemRepository->getDropdownItemValue($id,PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACTIVITY_PRIMARY_KEY);
        $pasSecondary = $this->itemRepository->getDropdownItemValue($id,PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACTIVITY_SECONDARY_KEY);
        $pasLocation = $this->itemRepository->getDropdownItemValue($id,PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_LOCATION_KEY);
        $pasAccessibility = $this->itemRepository->getDropdownItemValue($id,PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACCESSIBILITY_KEY);
        $pasExtent = $this->itemRepository->getDropdownItemValue($id,PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_EXTENT_KEY);
        $pasNumber = $this->itemRepository->getDropdownItemValue($id,PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_OCCUPANTS_KEY);
        $pasHumanFrequency = $this->itemRepository->getDropdownItemValue($id,PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_FREQUENCY_OF_USE_KEY);
        $pasAverageTime = $this->itemRepository->getDropdownItemValue($id,PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_TIME_IN_AREA_KEY);
        $pasType = $this->itemRepository->getDropdownItemValue($id,PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_TYPE_OF_ACTIVITY_KEY);
        $pasMaintenanceFrequency = $this->itemRepository->getDropdownItemValue($id,PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_FREQUENCY_OF_ACTIVITY_KEY);

        $product_debris_type = $item->ProductDebrisTypeValue->dropdown_data_item_id ?? 0;

        $item->product_type = $item->masProductDebris->getData->score ?? 0;
        $item->extend_damage = $item->masDamage->getData->score ?? 0;
        $item->surface_treatment = $item->masTreatment->getData->score ?? 0;
        $item->asbestos_fibre = $item->masAsbestos->getData->score ?? 0;
            //pas
        $pasPrimary = $item->pasPrimary->getData->score ?? 0;
        $pasSecondary = $item->pasSecondary->getData->score ?? 0;
        $pasLocation = $item->pasLocation->getData->score ?? 0;
        $pasAccessibility = $item->pasAccessibility->getData->score ?? 0;
        $pasExtent = $item->pasExtent->getData->score ?? 0;
        $pasNumber = $item->pasNumber->getData->score ?? 0;
        $pasHumanFrequency = $item->pasHumanFrequency->getData->score ?? 0;
        $pasAverageTime = $item->pasAverageTime->getData->score ?? 0;
        $pasType = $item->pasType->getData->score ?? 0;
        $pasMaintenanceFrequency = $item->pasMaintenanceFrequency->getData->score ?? 0;
        $item->primary = round(($pasPrimary + $pasSecondary)/2);
        $item->likelihood =  round(($pasLocation + $pasAccessibility + $pasExtent)/3);
        $item->human_exposure_potential = round(($pasNumber + $pasHumanFrequency + $pasAverageTime)/3);
        $item->maintenance_activity = round(($pasType + $pasMaintenanceFrequency)/2);
        $asbestos_type = $item->AsbestosTypeValue->dropdown_data_item_id ?? NULL;

        $asbestos_type_warning = false;
        // if product debris = Gasket
        if (in_array($product_debris_type, ITEM_ASBESTOS_WARNING_ID)) {
            $asbestos_type_warning = true;
        }
        $survey = $this->surveyRepository->getSurvey($item->survey_id);

        // stamping item
        if ($item->survey_id == 0) {
            $itemStamping  = $this->propertyRepository->get_data_stamping($item);
        } else {
            $itemStamping = [];
        }
        if (Auth::user()->is_site_operative == 1) {
            $view = 'items.operative';
            //log audit
            $comment_audit = \Auth::user()->full_name  . " viewed item as operative " . $item->reference .(isset($item->survey->reference) ? 'on '.$item->survey->reference : ''). ' on ' . ($item->property->name ?? '');
        } else {
            $comment_audit = \Auth::user()->full_name  . " viewed item " . $item->reference .(isset($item->survey->reference) ? 'on '.$item->survey->reference : ''). ' on ' . ($item->property->name ?? '');
            $view = 'items.index';
        }
        $view = Auth::user()->is_site_operative == 1 ? 'items.operative' : 'items.index';
        $is_site_operative = false;
        if (Auth::user()->is_site_operative == 1) {
            $is_site_operative = true;
        }


        \CommonHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_VIEW, $item->reference, $item->survey_id ,$comment_audit, 0 ,$item->property_id);

        return view($view,['item' => $item,
            'assessmentTypeKey' => $assessmentTypeKey,
            'assessmentDamageKey' => $assessmentDamageKey,
            'assessmentTreatmentKey' => $assessmentTreatmentKey,
            'assessmentAsbestosKey' => $assessmentAsbestosKey,
            'pasPrimary' => $pasPrimary,
            'pasSecondary' => $pasSecondary,
            'pasLocation' => $pasLocation,
            'pasAccessibility' => $pasAccessibility,
            'pasExtent' => $pasExtent,
            'pasNumber' => $pasNumber,
            'pasHumanFrequency' => $pasHumanFrequency,
            'pasAverageTime' => $pasAverageTime,
            'pasType' => $pasType,
            'pasMaintenanceFrequency' => $pasMaintenanceFrequency,
            'specificLocation' => $specificLocation,
            'survey' => $survey,
            'selectedSample' => $selectedSample,
            'sampleComment' => $sampleComment,
            'reason' => $reason,
            'canBeUpdateThisItem' => $canBeUpdateThisItem,
            'pagination' => $pagination,
            'itemStamping' => $itemStamping,
            'asbestos_type_warning' => $asbestos_type_warning,
            'position' => $request->position ?? 0,
            'category' => $request->category ?? 0,
            'pagination_type' => $request->pagination_type ?? 0,
            'is_site_operative' => $is_site_operative,
        ]);
    }

    public function getAddItem(Request $request) {
        $location_id = $request->location;

        // Detail item tab
        $location = $this->locationRepository->getLocation($location_id);

        if (\CommonHelpers::isSystemClient()) {
            //check privilege
            // if(!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $location->property_id) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $location->property_id)){
            //     abort(404);
            // }
        } else {
            if ($location->survey_id > 0) {
                if(\Auth::user()->client_id == $location->survey->client_id || \Auth::user()->id == $location->survey->surveyor_id || \Auth::user()->id == $location->survey->consultant_id || \Auth::user()->id == $location->survey->created_by) {
                } else {
                    abort (404);
                }
            } else {
                abort(404);
            }
        }

        $specificLocations = $this->itemRepository->loadDropdownText(SPECIFIC_LOCATION_ID);
        $reasons = $this->itemRepository->loadDropdownText(ITEM_NO_ACCESS_ID);
        $sampleComments = $this->itemRepository->loadDropdownText(SAMPLE_COMMENTS_ID);
        $itemTypes = $this->itemRepository->loadDropdownText(PRODUCT_DEBRIS_TYPE_ID);
        $abestosTypes = $this->itemRepository->loadDropdownText(ASBESTOS_TYPE_ID);
        $extends = $this->itemRepository->loadDropdownText(EXTENT_ID, EXTENT_PARENT_ID);
        $asccessVulners = $this->itemRepository->loadDropdownText(ACCESSIBILITY_VULNERABILITY_ID);
        $additionalInfos = $this->itemRepository->loadDropdownText(ADDITIONAL_INFORMATION_ID);
        $licenseds = $this->itemRepository->loadDropdownText(LICENSED_NONLICENSED_ID);

        $airTestComments = [];

        // material tab MAS
        $assessmentTypeKeys = $this->itemRepository->loadDropdownText(MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_TYPE_KEY);
        $assessmentDamageKeys = $this->itemRepository->loadDropdownText(MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_DAMAGE_KEY);
        $assessmentTreatmentKeys = $this->itemRepository->loadDropdownText(MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_TREATMENT_KEY);
        $assessmentAsbestosKeys = $this->itemRepository->loadDropdownText(MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_ASBESTOS_KEY);

        // PAS tab
        $pasPrimaries = $this->itemRepository->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACTIVITY_PRIMARY_KEY);
        $pasSecondaryies = $this->itemRepository->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACTIVITY_SECONDARY_KEY);
        $pasLocations = $this->itemRepository->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_LOCATION_KEY);
        $pasAccessibilities = $this->itemRepository->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACCESSIBILITY_KEY);
        $pasExtents = $this->itemRepository->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_EXTENT_KEY);
        $pasNumbers = $this->itemRepository->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_OCCUPANTS_KEY);
        $pasHumanFrequencys = $this->itemRepository->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_FREQUENCY_OF_USE_KEY);
        $pasAverageTimes = $this->itemRepository->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_TIME_IN_AREA_KEY);
        $pasTypes = $this->itemRepository->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_TYPE_OF_ACTIVITY_KEY);
        $pasMaintenanceFrequencys = $this->itemRepository->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_FREQUENCY_OF_ACTIVITY_KEY);

        // Action recommendations tab
        $recommendations = $this->itemRepository->loadDropdownText(ACTIONS_RECOMMENDATIONS_ID);
        $surveySamples = $this->surveyRepository->getSamplesItem( $location->property_id, $location->survey_id);
        $survey = $this->surveyRepository->getSurvey($location->survey_id);

        return view('items.add_item',[
            'location' => $location,
            'specificLocations' => $specificLocations,
            'reasons' => $reasons,
            'sampleComments' => $sampleComments,
            'itemTypes' => $itemTypes,
            'abestosTypes' => $abestosTypes,
            'extends' => $extends,
            'asccessVulners' => $asccessVulners,
            'additionalInfos' => $additionalInfos,
            'licenseds' => $licenseds,
            'airTestComments' => $airTestComments,
            'assessmentTypeKeys' => $assessmentTypeKeys,
            'assessmentDamageKeys' => $assessmentDamageKeys,
            'assessmentTreatmentKeys' => $assessmentTreatmentKeys,
            'assessmentAsbestosKeys' => $assessmentAsbestosKeys,
            'pasPrimaries'=> $pasPrimaries,
            'pasSecondaryies'=> $pasSecondaryies,
            'pasLocations'=> $pasLocations,
            'pasAccessibilities'=> $pasAccessibilities,
            'pasExtents'=> $pasExtents,
            'pasNumbers'=> $pasNumbers,
            'pasHumanFrequencys'=> $pasHumanFrequencys,
            'pasAverageTimes'=> $pasAverageTimes,
            'pasTypes'=> $pasTypes,
            'pasMaintenanceFrequencys'=> $pasMaintenanceFrequencys,
            'recommendations'=> $recommendations,
            'survey'=> $survey,
            'surveySamples'=> $surveySamples,
        ]);
    }

    // get dropdown by ajax
    public function getDropdownItem(Request $request) {
        $validator = \Validator::make($request->all(), [
            'dropdown_item_id' => 'required',
            'parent_id' => 'sometimes',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=> $validator->errors()]);
        }
        $dropdown_item_id = $request->dropdown_item_id;
        $parent_id = ($request->has('parent_id')) ? $request->parent_id : 0;

        $dropdowns = $this->itemRepository->loadDropdownText($dropdown_item_id, $parent_id);
        $have_child = false;
        foreach ($dropdowns as $dropdown) {
            if (count($dropdown->allChildrens)) {
                $have_child = true;
            }
        }

        $response = [
            'data' => $dropdowns,
            'have_child' => $have_child
        ];
        return response()->json($response);
    }

    public function postAddItem(ItemCreateRequest $itemCreateRequest) {
        $validatedData = $itemCreateRequest->validated();
        $addItem = $this->itemRepository->createOrUpdateItem($validatedData);

        if (isset($addItem) and !is_null($addItem)) {
            if ($addItem['status_code'] == 200) {
                return redirect()->route('item.index',['id' => $addItem['data']->id ])->with('msg', $addItem['msg']);
            } else {
                return redirect()->back()->with('err', $addItem['msg']);
            }
        }
    }

    public function getEditItem($id, Request $request){
        $item = $this->itemRepository->getItem($id);
        if (is_null($item)) {
            return abort(404);
        }

        if (\CommonHelpers::isSystemClient()) {
            //check privilege
            // if(!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $item->property_id) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $item->property_id)){
            //     abort(404);
            // }
        } else {
            if ($item->survey_id > 0) {
                if(\Auth::user()->client_id == $item->survey->client_id || \Auth::user()->id == $item->survey->surveyor_id || \Auth::user()->id == $item->survey->consultant_id || \Auth::user()->id == $item->survey->created_by) {
                } else {
                    abort (404);
                }
            } else {
                abort(404);
            }
        }

        // Detail item tab
        $location = $this->locationRepository->getLocation($item->location_id);
        $specificLocations = $this->itemRepository->loadDropdownText(SPECIFIC_LOCATION_ID);
        $reasons = $this->itemRepository->loadDropdownText(ITEM_NO_ACCESS_ID);
        $sampleComments = $this->itemRepository->loadDropdownText(SAMPLE_COMMENTS_ID);
        $itemTypes = $this->itemRepository->loadDropdownText(PRODUCT_DEBRIS_TYPE_ID);
        $abestosTypes = $this->itemRepository->loadDropdownText(ASBESTOS_TYPE_ID);
        $extends = $this->itemRepository->loadDropdownText(EXTENT_ID, EXTENT_PARENT_ID);
        $asccessVulners = $this->itemRepository->loadDropdownText(ACCESSIBILITY_VULNERABILITY_ID);
        $additionalInfos = $this->itemRepository->loadDropdownText(ADDITIONAL_INFORMATION_ID);
        $licenseds = $this->itemRepository->loadDropdownText(LICENSED_NONLICENSED_ID);
        // $airTestComments = $this->itemRepository->loadDropdownText(AIR_TEST_COMMENTS_ID);
        $surveySamples = $this->surveyRepository->getSamplesItem($item->property_id, $item->survey_id);
        $airTestComments = [];

        // material tab MAS
        $assessmentTypeKeys = $this->itemRepository->loadDropdownText(MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_TYPE_KEY);
        $assessmentDamageKeys = $this->itemRepository->loadDropdownText(MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_DAMAGE_KEY);
        $assessmentTreatmentKeys = $this->itemRepository->loadDropdownText(MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_TREATMENT_KEY);
        $assessmentAsbestosKeys = $this->itemRepository->loadDropdownText(MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_ASBESTOS_KEY);

        // PAS tab
        $pasPrimaries = $this->itemRepository->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACTIVITY_PRIMARY_KEY);
        $pasSecondaryies = $this->itemRepository->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACTIVITY_SECONDARY_KEY);
        $pasLocations = $this->itemRepository->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_LOCATION_KEY);
        $pasAccessibilities = $this->itemRepository->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACCESSIBILITY_KEY);
        $pasExtents = $this->itemRepository->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_EXTENT_KEY);
        $pasNumbers = $this->itemRepository->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_OCCUPANTS_KEY);
        $pasHumanFrequencys = $this->itemRepository->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_FREQUENCY_OF_USE_KEY);
        $pasAverageTimes = $this->itemRepository->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_TIME_IN_AREA_KEY);
        $pasTypes = $this->itemRepository->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_TYPE_OF_ACTIVITY_KEY);
        $pasMaintenanceFrequencys = $this->itemRepository->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_FREQUENCY_OF_ACTIVITY_KEY);

        // Action recommendations tab
        $recommendations = $this->itemRepository->loadDropdownText(ACTIONS_RECOMMENDATIONS_ID);

        //selected details value
        $selectedProductDebris = $this->itemRepository->getDropdownItemValue($id, PRODUCT_DEBRIS_TYPE_ID, 0, 'id');
        $selectedSpecificLocations = $this->itemRepository->getDropdownItemValue($id,SPECIFIC_LOCATION_ID, 0, 'id');
        $selectedAsbetosType = $this->itemRepository->getDropdownItemValue($id, ASBESTOS_TYPE_ID, 0, 'id');
        $selectedExtent = $this->itemRepository->getDropdownItemValue($id, EXTENT_ID,EXTENT_PARENT_ID, 'id');
        $selectedAsccessVulner = $this->itemRepository->getDropdownItemValue($id, ACCESSIBILITY_VULNERABILITY_ID, 0, 'id');
        $selectedAdditionalInfo = $this->itemRepository->getDropdownItemValue($id, ADDITIONAL_INFORMATION_ID, 0, 'id');
        $selectedLicensed = $this->itemRepository->getDropdownItemValue($id, LICENSED_NONLICENSED_ID, 0, 'id');
        $selectedReason = $item->ItemNoAccessValue->dropdown_data_item_id;
        $selectedReasonOther = $item->ItemNoAccessValue->dropdown_other;
        $selectedSampleId = $this->itemRepository->getDropdownItemValue($id, SAMPLE_ID, 0, 'id');
        $selectedSample = Sample::find($selectedSampleId);

        //selected mas
        $selectedAssessmentTypeKeys = $this->itemRepository->getDropdownItemValue($id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_TYPE_KEY, 'id');
        $selectedAssessmentDamageKeys = $this->itemRepository->getDropdownItemValue($id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_DAMAGE_KEY, 'id');
        $selectedAssessmentTreatmentKeys = $this->itemRepository->getDropdownItemValue($id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_TREATMENT_KEY, 'id');
        $selectedAssessmentAsbestosKeys = $this->itemRepository->getDropdownItemValue($id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_ASBESTOS_KEY, 'id');

        // selected PAS tab
        $selectedPasPrimaries = $this->itemRepository->getDropdownItemValue($id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACTIVITY_PRIMARY_KEY, 'id');
        $selectedPasSecondaryies = $this->itemRepository->getDropdownItemValue($id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACTIVITY_SECONDARY_KEY, 'id');
        $selectedPasLocations = $this->itemRepository->getDropdownItemValue($id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_LOCATION_KEY, 'id');
        $selectedPasAccessibilities = $this->itemRepository->getDropdownItemValue($id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACCESSIBILITY_KEY, 'id');
        $selectedPasExtents = $this->itemRepository->getDropdownItemValue($id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_EXTENT_KEY, 'id');
        $selectedPasNumbers = $this->itemRepository->getDropdownItemValue($id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_OCCUPANTS_KEY, 'id');
        $selectedPasHumanFrequencys = $this->itemRepository->getDropdownItemValue($id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_FREQUENCY_OF_USE_KEY, 'id');
        $selectedPasAverageTimes = $this->itemRepository->getDropdownItemValue($id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_TIME_IN_AREA_KEY, 'id');
        $selectedPasTypes = $this->itemRepository->getDropdownItemValue($id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_TYPE_OF_ACTIVITY_KEY, 'id');
        $selectedPasMaintenanceFrequencys = $this->itemRepository->getDropdownItemValue($id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_FREQUENCY_OF_ACTIVITY_KEY, 'id');

        // selected Action recommendations tab
        $selectedRecommendations = $this->itemRepository->getDropdownItemValue($id, ACTIONS_RECOMMENDATIONS_ID, 0,'id');

        // other
        $otherReason = $this->itemRepository->getDropdownItemValue($id, ITEM_NO_ACCESS_ID, 0,'other');
        $otherSpecificLocation = $this->itemRepository->getDropdownItemValue($id, SPECIFIC_LOCATION_ID, 0,'other');
        $otherProductDebris = $this->itemRepository->getDropdownItemValue($id, PRODUCT_DEBRIS_TYPE_ID, 0,'other');
        $otherAdditionalInfo = $this->itemRepository->getDropdownItemValue($id, ADDITIONAL_INFORMATION_ID, 0,'other');

        $survey = $this->surveyRepository->getSurvey($item->survey_id);

        return view('items.edit_item',[
            'item' => $item,
            'location' => $location,
            'specificLocations' => $specificLocations,
            'reasons' => $reasons,
            'sampleComments' => $sampleComments,
            'itemTypes' => $itemTypes,
            'abestosTypes' => $abestosTypes,
            'extends' => $extends,
            'asccessVulners' => $asccessVulners,
            'additionalInfos' => $additionalInfos,
            'licenseds' => $licenseds,
            'airTestComments' => $airTestComments,
            'assessmentTypeKeys' => $assessmentTypeKeys,
            'assessmentDamageKeys' => $assessmentDamageKeys,
            'assessmentTreatmentKeys' => $assessmentTreatmentKeys,
            'assessmentAsbestosKeys' => $assessmentAsbestosKeys,
            'pasPrimaries'=> $pasPrimaries,
            'pasSecondaryies'=> $pasSecondaryies,
            'pasLocations'=> $pasLocations,
            'pasAccessibilities'=> $pasAccessibilities,
            'pasExtents'=> $pasExtents,
            'pasNumbers'=> $pasNumbers,
            'pasHumanFrequencys'=> $pasHumanFrequencys,
            'pasAverageTimes'=> $pasAverageTimes,
            'pasTypes'=> $pasTypes,
            'pasMaintenanceFrequencys'=> $pasMaintenanceFrequencys,
            'recommendations'=> $recommendations,
            'selectedProductDebris'=> $selectedProductDebris,
            'selectedSpecificLocations'=> $selectedSpecificLocations,
            'selectedReason'=> $selectedReason,
            'selectedAsbetosType'=> $selectedAsbetosType,
            'selectedReasonOther'=> $selectedReasonOther,
            'selectedExtent'=> $selectedExtent,
            'selectedAsccessVulner'=> $selectedAsccessVulner,
            'selectedAdditionalInfo'=> $selectedAdditionalInfo,
            'selectedLicensed'=> $selectedLicensed,
            'selectedAssessmentTypeKeys' => $selectedAssessmentTypeKeys,
            'selectedAssessmentDamageKeys' => $selectedAssessmentDamageKeys,
            'selectedAssessmentTreatmentKeys' => $selectedAssessmentTreatmentKeys,
            'selectedAssessmentAsbestosKeys' => $selectedAssessmentAsbestosKeys,
            'selectedPasPrimaries'=> $selectedPasPrimaries,
            'selectedPasSecondaryies'=> $selectedPasSecondaryies,
            'selectedPasLocations'=> $selectedPasLocations,
            'selectedPasAccessibilities'=> $selectedPasAccessibilities,
            'selectedPasExtents'=> $selectedPasExtents,
            'selectedPasNumbers'=> $selectedPasNumbers,
            'selectedPasHumanFrequencys'=> $selectedPasHumanFrequencys,
            'selectedPasAverageTimes'=> $selectedPasAverageTimes,
            'selectedPasTypes'=> $selectedPasTypes,
            'selectedPasMaintenanceFrequencys'=> $selectedPasMaintenanceFrequencys,
            'selectedRecommendations'=> $selectedRecommendations,
            'otherReason'=> $otherReason,
            'otherSpecificLocation'=> $otherSpecificLocation,
            'otherProductDebris'=> $otherProductDebris,
            'otherAdditionalInfo'=> $otherAdditionalInfo,
            'survey'=> $survey,
            'surveySamples'=> $surveySamples,
            'selectedSample'=> $selectedSample,
            'position'=> $request->position ?? 0,
            'category'=> $request->category ?? 0,
            'pagination_type'=> $request->pagination_type ?? 0,
        ]);
    }

    public function postEditItem($id, ItemCreateRequest $itemCreateRequest) {
        $validatedData = $itemCreateRequest->validated();

        $updateItem = $this->itemRepository->createOrUpdateItem($validatedData,$id);
        if (isset($updateItem) and !is_null($updateItem)) {
            if ($updateItem['status_code'] == 200) {
                return redirect()->route('item.index',['id' => $updateItem['data']->id,
                                        'position' => $validatedData['position'] ?? 0,
                                        'category' => $validatedData['category'] ?? 0,
                                        'pagination_type' => $validatedData['pagination_type'] ?? 0,
                                         ])->with('msg', $updateItem['msg']);
            } else {
                return redirect()->back()->with('err', $updateItem['msg']);
            }
        }
    }

    public function itemSummary() {
        $dataDecommisstionItems = Item::with('area', 'location', 'ProductDebrisTypeValue','itemInfo')->where('property_id', 9)->where('decommissioned', ITEM_DECOMMISSION);

        $data = [];
        foreach ($dataDecommisstionItems->get() as $item) {
            $item = [
                 $item->name,
                 optional($item->area)->title,
                 optional($item->location)->location_reference,
                 $item->product_debris_text,
                 optional($item->itemInfo)->comment,
                 $item->total_mas_risk
            ];
            $data[] = $item;
        }

        return response()->json([
            "recordsTotal" => $dataDecommisstionItems->count(),
            "recordsFiltered" => $dataDecommisstionItems->count(),
            "data" => $data
        ]);
    }

    public function decommissionItem($item_id, Request $request) {
        $reason = $request->item_decommisson_reason_add;
        $decommissionItem = $this->itemRepository->decommissionItem($item_id, $reason);
        if (isset($decommissionItem)) {
            if ($decommissionItem['status_code'] == 200) {
                return redirect()->back()->with('msg', $decommissionItem['msg']);
            } else {
                return redirect()->back()->with('err', $decommissionItem['msg']);
            }
        }
    }

    public function decommissionItemReason($item_id, Request $request) {
        $reason = $request->item_decommisson_reason;
        $decommissionItemReason = $this->itemRepository->decommissionItemReason($item_id, $reason);
        if (isset($decommissionItemReason)) {
            if ($decommissionItemReason['status_code'] == 200) {
                return redirect()->back()->with('msg', $decommissionItemReason['msg']);
            } else {
                return redirect()->back()->with('err', $decommissionItemReason['msg']);
            }
        }
    }

    public function searchItem(Request $request) {
        $query_string = '';
        if ($request->has('query_string')) {
            $query_string = $request->query_string;
        }
        $data = $this->itemRepository->searchItem2($query_string);
        return response()->json($data);
    }
}
