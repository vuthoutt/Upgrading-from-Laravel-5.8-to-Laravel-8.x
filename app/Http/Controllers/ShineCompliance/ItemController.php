<?php

namespace App\Http\Controllers\ShineCompliance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Request\ShineCompliance\Location\LocationRequest;
use App\Services\ShineCompliance\ItemService;
use App\Services\ShineCompliance\LocationService;
use App\Services\ShineCompliance\AssessmentService;
use Illuminate\Support\Facades\Auth;
use App\Http\Request\ShineCompliance\Item\ItemRequest;


class ItemController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $itemService;
    private $locationService;
    private $assessmentService;

    public function __construct(ItemService $itemService, LocationService $locationService, AssessmentService $assessmentService)
    {
        $this->itemService = $itemService;
        $this->locationService = $locationService;
        $this->assessmentService = $assessmentService;
    }

    /**
     * Show my organisation by id.
     *
     */
    public function index($location_id, Request $request)
    {
        $location = $this->locationService->getLocation($location_id);
        $data = $this->itemService->getItemTableBySection(SECTION_ROOM_LOCATION_SUMMARY, $location_id);

        $items = $data['item'];
        $dataSummary = $data['dataSummary'];
        $acm_type = $data['acm_type'];
        $dataDecommisstionItems = $data['dataDecommisstionItems'];
        $summary = $this->itemService->getItemSummaryTable($request->type ?? '', $items, $acm_type,
                                                            $location->property_id,$location->area_id, $location_id );

        $items_summary_table = $summary['items_summary_table'];
        $title = $summary['title'];
        $table_id = $summary['table_id'];
        $can_add_new = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_add_new = false;
        } elseif(\CommonHelpers::isSystemClient()) {
            // check update permission

            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_ASBESTOS_ITEMS,JOB_ROLE_ASBESTOS ) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $location->property_id)) {
                $can_add_new = false;
            }
        }
        // audit
        $comment = \Auth::user()->full_name . " viewed item list page on location" . $location->reference;
        \ComplianceHelpers::logAudit(ITEM_TYPE, $location->id, AUDIT_ACTION_VIEW, $location->reference, $location->id, $comment);

        return view('shineCompliance.item.list',[
            'location' => $location,
            'items' => $items,
            'dataSummary' => $dataSummary,
            'dataDecommisstionItems' => $dataDecommisstionItems,
            'items_summary_table' => $items_summary_table,
            'type' => $request->type ?? null,
            'title' => $title,
            'can_add_new' => $can_add_new,
            'table_id' => $table_id
        ]);
    }

    public function detail($id, Request $request){
        $item = $this->itemService->getItem($id);
        $itemStamping = \CommonHelpers::get_data_stamping($item);
        $assess_view = false;
        $assessment = false;
        if ($request->has('section') and $request->has('assess_id')) {
            $assess_view = true;
            $assessment =$this->assessmentService->getAssessmentDetail($request->assess_id);
        }
        $reason = $this->itemService->getReason($item);
        $can_update = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_update = false;
        } elseif(\CommonHelpers::isSystemClient()) {
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_ASBESTOS_ITEMS,JOB_ROLE_ASBESTOS ) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $item->property_id)) {
                $can_update = false;
            }
        }
        // audit
        $comment = \Auth::user()->full_name . " viewed item detail page " . $item->reference;
        \ComplianceHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_VIEW, $item->reference, $item->id, $comment);
        return view('shineCompliance.item.detail',['can_update' => $can_update, 'item' => $item, 'itemStamping' => $itemStamping,
            'assess_view' => $assess_view, 'assessment' => $assessment,'reason' => $reason
        ]);
    }

    public function getAddItem($location_id) {

        // Detail item tab
        $location = $this->locationService->getLocation($location_id);

        $specificLocations = $this->itemService->loadDropdownText(SPECIFIC_LOCATION_ID);
        $reasons = $this->itemService->loadDropdownText(ITEM_NO_ACCESS_ID);
        $sampleComments = $this->itemService->loadDropdownText(SAMPLE_COMMENTS_ID);
        $itemTypes = $this->itemService->loadDropdownText(PRODUCT_DEBRIS_TYPE_ID);
        $abestosTypes = $this->itemService->loadDropdownText(ASBESTOS_TYPE_ID);
        $extends = $this->itemService->loadDropdownText(EXTENT_ID, EXTENT_PARENT_ID);
        $asccessVulners = $this->itemService->loadDropdownText(ACCESSIBILITY_VULNERABILITY_ID);
        $additionalInfos = $this->itemService->loadDropdownText(ADDITIONAL_INFORMATION_ID);
        $licenseds = $this->itemService->loadDropdownText(LICENSED_NONLICENSED_ID);

        $airTestComments = [];

        // material tab MAS
        $assessmentTypeKeys = $this->itemService->loadDropdownText(MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_TYPE_KEY);
        $assessmentDamageKeys = $this->itemService->loadDropdownText(MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_DAMAGE_KEY);
        $assessmentTreatmentKeys = $this->itemService->loadDropdownText(MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_TREATMENT_KEY);
        $assessmentAsbestosKeys = $this->itemService->loadDropdownText(MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_ASBESTOS_KEY);

        // PAS tab
        $pasPrimaries = $this->itemService->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACTIVITY_PRIMARY_KEY);
        $pasSecondaryies = $this->itemService->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACTIVITY_SECONDARY_KEY);
        $pasLocations = $this->itemService->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_LOCATION_KEY);
        $pasAccessibilities = $this->itemService->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACCESSIBILITY_KEY);
        $pasExtents = $this->itemService->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_EXTENT_KEY);
        $pasNumbers = $this->itemService->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_OCCUPANTS_KEY);
        $pasHumanFrequencys = $this->itemService->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_FREQUENCY_OF_USE_KEY);
        $pasAverageTimes = $this->itemService->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_TIME_IN_AREA_KEY);
        $pasTypes = $this->itemService->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_TYPE_OF_ACTIVITY_KEY);
        $pasMaintenanceFrequencys = $this->itemService->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_FREQUENCY_OF_ACTIVITY_KEY);

        // Action recommendations tab
        $recommendations = $this->itemService->loadDropdownText(ACTIONS_RECOMMENDATIONS_ID);
        $surveySamples = $this->itemService->getSamplesItem( $location->property_id, $location->survey_id);

        // audit
        $comment = \Auth::user()->full_name . " viewed item add page on location" . $location->reference;
        \ComplianceHelpers::logAudit(ITEM_TYPE, $location->id, AUDIT_ACTION_VIEW, $location->reference, $location->id, $comment);

        return view('shineCompliance.item.add_item',[
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
            'surveySamples'=> $surveySamples
        ]);
    }

    public function postAddItem($location_id, ItemRequest $request) {
        $data = $request->validated();
        $item = $this->itemService->updateOrCreateItem($data);
        if (isset($item)) {
            if ($item['status_code'] == STATUS_OK) {
                return redirect()->route('shineCompliance.item.detail',['id' => $item['data']])->with('msg', $item['msg']);
            } else {
                return redirect()->back()->with('err', $item['msg']);
            }
        }
    }

    public function getEditItem($id, Request $request){
        $item = $this->itemService->getItem($id);

        // Detail item tab
        $location = $this->locationService->getLocation($item->location_id);
        $specificLocations = $this->itemService->loadDropdownText(SPECIFIC_LOCATION_ID);
        $reasons = $this->itemService->loadDropdownText(ITEM_NO_ACCESS_ID);
        $sampleComments = $this->itemService->loadDropdownText(SAMPLE_COMMENTS_ID);
        $itemTypes = $this->itemService->loadDropdownText(PRODUCT_DEBRIS_TYPE_ID);
        $abestosTypes = $this->itemService->loadDropdownText(ASBESTOS_TYPE_ID);
        $extends = $this->itemService->loadDropdownText(EXTENT_ID, EXTENT_PARENT_ID);
        $asccessVulners = $this->itemService->loadDropdownText(ACCESSIBILITY_VULNERABILITY_ID);
        $additionalInfos = $this->itemService->loadDropdownText(ADDITIONAL_INFORMATION_ID);
        $licenseds = $this->itemService->loadDropdownText(LICENSED_NONLICENSED_ID);
        // $airTestComments = $this->itemService->loadDropdownText(AIR_TEST_COMMENTS_ID);
        $surveySamples = $this->itemService->getSamplesItem($item->property_id, $item->survey_id);
        $airTestComments = [];

        // material tab MAS
        $assessmentTypeKeys = $this->itemService->loadDropdownText(MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_TYPE_KEY);
        $assessmentDamageKeys = $this->itemService->loadDropdownText(MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_DAMAGE_KEY);
        $assessmentTreatmentKeys = $this->itemService->loadDropdownText(MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_TREATMENT_KEY);
        $assessmentAsbestosKeys = $this->itemService->loadDropdownText(MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_ASBESTOS_KEY);

        // PAS tab
        $pasPrimaries = $this->itemService->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACTIVITY_PRIMARY_KEY);
        $pasSecondaryies = $this->itemService->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACTIVITY_SECONDARY_KEY);
        $pasLocations = $this->itemService->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_LOCATION_KEY);
        $pasAccessibilities = $this->itemService->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACCESSIBILITY_KEY);
        $pasExtents = $this->itemService->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_EXTENT_KEY);
        $pasNumbers = $this->itemService->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_OCCUPANTS_KEY);
        $pasHumanFrequencys = $this->itemService->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_FREQUENCY_OF_USE_KEY);
        $pasAverageTimes = $this->itemService->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_TIME_IN_AREA_KEY);
        $pasTypes = $this->itemService->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_TYPE_OF_ACTIVITY_KEY);
        $pasMaintenanceFrequencys = $this->itemService->loadDropdownText(PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_FREQUENCY_OF_ACTIVITY_KEY);

        // Action recommendations tab
        $recommendations = $this->itemService->loadDropdownText(ACTIONS_RECOMMENDATIONS_ID);

        //selected details value
        $selectedProductDebris = $this->itemService->getDropdownItemValue($id, PRODUCT_DEBRIS_TYPE_ID, 0, 'id');
        $selectedSpecificLocations = $this->itemService->getDropdownItemValue($id,SPECIFIC_LOCATION_ID, 0, 'id');
        $selectedAsbetosType = $this->itemService->getDropdownItemValue($id, ASBESTOS_TYPE_ID, 0, 'id');
        $selectedExtent = $this->itemService->getDropdownItemValue($id, EXTENT_ID,EXTENT_PARENT_ID, 'id');
        $selectedAsccessVulner = $this->itemService->getDropdownItemValue($id, ACCESSIBILITY_VULNERABILITY_ID, 0, 'id');
        $selectedAdditionalInfo = $this->itemService->getDropdownItemValue($id, ADDITIONAL_INFORMATION_ID, 0, 'id');
        $selectedLicensed = $this->itemService->getDropdownItemValue($id, LICENSED_NONLICENSED_ID, 0, 'id');
        $selectedReason = $item->ItemNoAccessValue->dropdown_data_item_id ?? null;
        $selectedReasonOther = $item->ItemNoAccessValue->dropdown_other ?? null;
        $selectedSampleId = $this->itemService->getDropdownItemValue($id, SAMPLE_ID, 0, 'id');
        $selectedSample = $this->itemService->findSample($selectedSampleId);

        //selected mas
        $selectedAssessmentTypeKeys = $this->itemService->getDropdownItemValue($id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_TYPE_KEY, 'id');
        $selectedAssessmentDamageKeys = $this->itemService->getDropdownItemValue($id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_DAMAGE_KEY, 'id');
        $selectedAssessmentTreatmentKeys = $this->itemService->getDropdownItemValue($id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_TREATMENT_KEY, 'id');
        $selectedAssessmentAsbestosKeys = $this->itemService->getDropdownItemValue($id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_ASBESTOS_KEY, 'id');

        // selected PAS tab
        $selectedPasPrimaries = $this->itemService->getDropdownItemValue($id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACTIVITY_PRIMARY_KEY, 'id');
        $selectedPasSecondaryies = $this->itemService->getDropdownItemValue($id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACTIVITY_SECONDARY_KEY, 'id');
        $selectedPasLocations = $this->itemService->getDropdownItemValue($id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_LOCATION_KEY, 'id');
        $selectedPasAccessibilities = $this->itemService->getDropdownItemValue($id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACCESSIBILITY_KEY, 'id');
        $selectedPasExtents = $this->itemService->getDropdownItemValue($id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_EXTENT_KEY, 'id');
        $selectedPasNumbers = $this->itemService->getDropdownItemValue($id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_OCCUPANTS_KEY, 'id');
        $selectedPasHumanFrequencys = $this->itemService->getDropdownItemValue($id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_FREQUENCY_OF_USE_KEY, 'id');
        $selectedPasAverageTimes = $this->itemService->getDropdownItemValue($id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_TIME_IN_AREA_KEY, 'id');
        $selectedPasTypes = $this->itemService->getDropdownItemValue($id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_TYPE_OF_ACTIVITY_KEY, 'id');
        $selectedPasMaintenanceFrequencys = $this->itemService->getDropdownItemValue($id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_FREQUENCY_OF_ACTIVITY_KEY, 'id');

        // selected Action recommendations tab
        $selectedRecommendations = $this->itemService->getDropdownItemValue($id, ACTIONS_RECOMMENDATIONS_ID, 0,'id');

        // other
        $otherReason = $this->itemService->getDropdownItemValue($id, ITEM_NO_ACCESS_ID, 0,'other');
        $otherSpecificLocation = $this->itemService->getDropdownItemValue($id, SPECIFIC_LOCATION_ID, 0,'other');
        $otherProductDebris = $this->itemService->getDropdownItemValue($id, PRODUCT_DEBRIS_TYPE_ID, 0,'other');
        $otherAdditionalInfo = $this->itemService->getDropdownItemValue($id, ADDITIONAL_INFORMATION_ID, 0,'other');

        // audit
        $comment = \Auth::user()->full_name . " viewed item edit page " . $item->reference;
        \ComplianceHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_VIEW, $item->reference, $item->id, $comment);

        return view('shineCompliance.item.edit_item',[
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
            'surveySamples'=> $surveySamples,
            'selectedSample'=> $selectedSample,
            'position'=> $request->position ?? 0,
            'category'=> $request->category ?? 0,
            'pagination_type'=> $request->pagination_type ?? 0,
        ]);
    }

    public function postEditItem($id, ItemRequest $request) {
        $data = $request->validated();
        $item = $this->itemService->updateOrCreateItem($data, $id);
        if (isset($item)) {
            if ($item['status_code'] == STATUS_OK) {
                return redirect()->route('shineCompliance.item.detail',['id' => $item['data']])->with('msg', $item['msg']);
            } else {
                return redirect()->back()->with('err', $item['msg']);
            }
        }
    }

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

        $dropdowns = $this->itemService->loadDropdownText($dropdown_item_id, $parent_id);
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

    public function decommissionItem($item_id, Request $request) {
        $reason = $request->item_decommisson_reason_add;
        $decommissionItem = $this->itemService->decommissionItem($item_id, $reason);
        if (isset($decommissionItem)) {
            if ($decommissionItem['status_code'] == 200) {
                return redirect()->back()->with('msg', $decommissionItem['msg']);
            } else {
                return redirect()->back()->with('err', $decommissionItem['msg']);
            }
        }
    }
}
