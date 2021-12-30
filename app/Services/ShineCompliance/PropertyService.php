<?php

namespace App\Services\ShineCompliance;

use App\Helpers\CommonHelpers;
use App\Models\DecommissionReason;
use App\Models\ShineCompliance\ConstructionMaterial;
use App\Models\ShineCompliance\Decommission;
use App\Models\ShineCompliance\DropdownDataProperty;
use App\Models\ShineCompliance\BuildingCategory;
use App\Models\ShineCompliance\Division;
use App\Models\ShineCompliance\Hazard;
use App\Models\ShineCompliance\Item;
use App\Models\ShineCompliance\LocalAuthority;
use App\Models\ShineCompliance\Location;
use App\Models\ShineCompliance\Property;
use App\Models\ShineCompliance\PropertyDropdown;
use App\Models\ShineCompliance\PropertyDropdownTitle;
use App\Models\ShineCompliance\PropertyInfo;
use App\Models\ShineCompliance\PropertyInfoDropdown;
use App\Models\ShineCompliance\PropertyInfoDropdownData;
use App\Models\ShineCompliance\PropertyProgrammeType;
use App\Models\ShineCompliance\PropertySurvey;
use App\Models\ShineCompliance\PropertyType;
use App\Models\ShineCompliance\Region;
use App\Models\ShineCompliance\Responsibility;
use App\Models\ShineCompliance\CommunalArea;
use App\Models\ShineCompliance\AssetClass;
use App\Models\ShineCompliance\ServiceArea;
use App\Models\ShineCompliance\TenureType;
use App\Models\ShineCompliance\VulnerableOccupantType;
use App\Repositories\ShineCompliance\AreaRepository;
use App\Repositories\ShineCompliance\ClientRepository;
use App\Repositories\ShineCompliance\ComplianceCategoryDocumentRepository;
use App\Repositories\ShineCompliance\ComplianceDocumentRepository;
use App\Repositories\ShineCompliance\HistoricalDocumentCategoryRepository;
use App\Repositories\ShineCompliance\HistoricalDocumentRepository;
use App\Repositories\ShineCompliance\HistoricalDocumentTypeRepository;
use App\Repositories\ShineCompliance\ItemRepository;
use App\Repositories\ShineCompliance\LocationRepository;
use App\Repositories\ShineCompliance\ProjectRepository;
use App\Repositories\ShineCompliance\PropertyRepository;
use App\Repositories\ShineCompliance\PropertyVulnerableOccupantRepository;
use App\Repositories\ShineCompliance\SurveyRepository;
use App\Repositories\ShineCompliance\UserRepository;
use App\Repositories\ShineCompliance\AssessmentPlanDocumentRepository;
use App\Repositories\ShineCompliance\AssessmentNoteDocumentRepository;
use App\Repositories\ShineCompliance\ProjectTypeRepository;
use App\Repositories\ShineCompliance\ZoneRepository;
use App\Repositories\ShineCompliance\FireExitRepository;
use App\Repositories\ShineCompliance\AssemblyPointRepository;
use App\Repositories\ShineCompliance\HazardRepository;
use App\Repositories\ShineCompliance\AssessmentRepository;

class PropertyService{

    private $propertyRepository;
    private $clientRepository;
    private $itemRepository;
    private $projectRepository;
    private $surveyRepository;
    private $locationRepository;
    private $historicalDocumentRepository;
    private $historicalDocumentCategoryRepository;
    private $historicalDocumentTypeRepository;
    private $userRepository;
    private $areaRepository;
    private $complianceDocumentRepository;
    private $categoryDocumentRepository;
    private $vulnerableOccupantRepository;
    private $assessmentPlanDocumentRepository;
    private $assessmentRepository;
    private $assessmentNoteDocumentRepository;
    private $fireExitRepository;
    private $assemblyPointRepository;
    private $projectTypeRepository;
    private $hazardRepository;

    public function __construct(PropertyRepository $propertyRepository, ClientRepository $clientRepository,
                                ItemRepository $itemRepository, ProjectRepository $projectRepository,HazardRepository $hazardRepository,
                                SurveyRepository $surveyRepository, LocationRepository $locationRepository,
                                HistoricalDocumentRepository $historicalDocumentRepository,FireExitRepository $fireExitRepository,
                                HistoricalDocumentCategoryRepository $historicalDocumentCategoryRepository,
                                HistoricalDocumentTypeRepository $historicalDocumentTypeRepository,
                                UserRepository $userRepository,AssemblyPointRepository $assemblyPointRepository,
                                AreaRepository $areaRepository, ComplianceDocumentRepository $complianceDocumentRepository,
                                ComplianceCategoryDocumentRepository $categoryDocumentRepository,
                                AssessmentRepository $assessmentRepository,
                                PropertyVulnerableOccupantRepository $vulnerableOccupantRepository,
                                AssessmentPlanDocumentRepository $assessmentPlanDocumentRepository,
                                ProjectTypeRepository $projectTypeRepository,
                                AssessmentNoteDocumentRepository $assessmentNoteDocumentRepository
    ){
        $this->propertyRepository = $propertyRepository;
        $this->clientRepository = $clientRepository;
        $this->itemRepository = $itemRepository;
        $this->surveyRepository = $surveyRepository;
        $this->projectRepository = $projectRepository;
        $this->locationRepository = $locationRepository;
        $this->historicalDocumentRepository = $historicalDocumentRepository;
        $this->historicalDocumentCategoryRepository = $historicalDocumentCategoryRepository;
        $this->historicalDocumentTypeRepository = $historicalDocumentTypeRepository;
        $this->userRepository = $userRepository;
        $this->areaRepository = $areaRepository;
        $this->complianceDocumentRepository = $complianceDocumentRepository;
        $this->categoryDocumentRepository = $categoryDocumentRepository;
        $this->vulnerableOccupantRepository = $vulnerableOccupantRepository;
        $this->assessmentPlanDocumentRepository = $assessmentPlanDocumentRepository;
        $this->assessmentNoteDocumentRepository = $assessmentNoteDocumentRepository;
        $this->projectTypeRepository = $projectTypeRepository;
        $this->fireExitRepository = $fireExitRepository;
        $this->hazardRepository = $hazardRepository;
        $this->assemblyPointRepository = $assemblyPointRepository;
        $this->assessmentRepository = $assessmentRepository;
    }

    public function getListByZone($client_id, $zone_id, $relation = [], $request, $pagination = 9){
        return $this->propertyRepository->getListByZone($client_id, $zone_id, $relation, $request, $pagination);
    }

    public function getProperty($id, $relation = []){
        return $this->propertyRepository->getProperty($id, $relation);
    }

    public function getListClients(){
        return $this->clientRepository->getListClients();
    }

    public function updateProperty($id, $data){
        return $this->propertyRepository->updateProperty($id,$data);
    }

    public function getAllAssetClass($is_parent) {
        $condition = ['parent_id', '>', 0];
        if($is_parent){
            $condition = ['parent_id', '=', 0];
        }
        return AssetClass::where([$condition])->where('is_deleted', '!=', 1)->get();
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

    public function getAllRegion() {
        return Region::orderBy('description')->get();
    }

    public function getAllLocalAuthority() {
        return LocalAuthority::orderBy('description')->get();
    }

    public function getAllBuildingCategory() {
        return BuildingCategory::orderBy('description')->get();
    }

    public function getAllDivision() {
        return Division::orderBy('description')->get();
    }

    public function getAllRiskType() {
        return PropertyType::all();
    }

    public function getAllProgrammeType() {
        return PropertyProgrammeType::orderBy('order')->get();
    }

    public function getAllServiceArea(){
        return ServiceArea::all();
    }

    public function getPropertyFireExitAndAssemblyPoint($property_id, $limit = 1000, $request = null) {

        $fire_exits = $this->fireExitRepository->getListFireExit($property_id, $limit , $request);
        $assemblies = $this->assemblyPointRepository->getListAssemblyPoint($property_id, $limit , $request);
        $data = $fire_exits->concat($assemblies);
        return $data;
    }

    public function getAllProperty(){
        return $this->propertyRepository->getAllProperty();
    }

    public function getAllPropertyInfo()
    {
        return PropertyInfoDropdown::all();
    }

    public function getAllPropertyInfoDropdownData()
    {
        return PropertyInfoDropdownData::all();
    }

    public function getAllConstructionMaterials()
    {
        return ConstructionMaterial::orderBy('order')->orderBy('description')->get();
    }

    public function loadDropdownText($dropdown_property_id, $parent_id = 0) {
        return DropdownDataProperty::where('dropdown_property_id', $dropdown_property_id)
            ->where('parent_id', $parent_id)->where('decommissioned',0)->get();
    }

    public function getPropertyDropdownTitles()
    {
        return PropertyDropdownTitle::all();
    }

    public function getAllPropertyDropdownData()
    {
        return PropertyDropdown::all();
    }

    public function getAllPropertyDropdownTitle($property = NULL, $propertyInfoValue = null) {
        $allPropertyDropdowns = PropertyDropdownTitle::with('propertyDropdownData')->get();
        $dataDropdowns = [];
        foreach ($allPropertyDropdowns as $key => $propertyDropdown) {
            $tmp['description'] = $propertyDropdown->name;
            $tmp['name'] = $propertyDropdown->key_name;
            $tmp['value'] = $propertyDropdown->propertyDropdownData;
            $tmp['selected'] = [
                $property->propertySurvey->electrical_meter ?? $propertyInfoValue->electrical_meter ?? 0,
                $property->propertySurvey->gas_meter ?? $propertyInfoValue->gas_meter ?? 0,
                $property->propertySurvey->loft_void ?? $propertyInfoValue->loft_void ?? 0,
            ] ;
            $dataDropdowns[] = $tmp;
        }
        return $dataDropdowns;
    }

    public function createProperty($data, $id = false){
        if (!empty($data)) {

            $dataProperty = [
                'client_id' => CommonHelpers::checkArrayKey($data,'client_id'),
                'zone_id' => \CommonHelpers::checkArrayKey($data,'zone_id'),
                'property_reference' => \CommonHelpers::checkArrayKey($data,'property_reference'),
                'name' => \CommonHelpers::checkArrayKey($data,'name'),
                // 'decommissioned' => 0,
                'comments' => \CommonHelpers::checkArrayKey($data,'size_comments'),
                'programme_phase' => 0,
                'register_updated' => 1,
                'pblock' => \CommonHelpers::checkArrayKey($data,'pblock'),
//                'core_reference' => \CommonHelpers::checkArrayKey($data,'core_code'),
//                'cluster_reference' => \CommonHelpers::checkArrayKey($data,'cluster_reference'),
                'service_area_id' => \CommonHelpers::checkArrayKey($data,'service_area_id'),
                'ward_id' => \CommonHelpers::checkArrayKey($data,'ward_id'),
                'parent_reference' => \CommonHelpers::checkArrayKey($data,'parent_reference'),
                'estate_code' => \CommonHelpers::checkArrayKey($data,'estate_code'),
                'asset_class_id' => \CommonHelpers::checkArrayKey($data,'asset_class_id'),
                'asset_type_id' => \CommonHelpers::checkArrayKey($data,'asset_type_id'),
                'tenure_type_id' => \CommonHelpers::checkArrayKey($data,'tenure_type_id'),
                'communal_area_id' => \CommonHelpers::checkArrayKey($data,'communal_area_id'),
                'responsibility_id' => \CommonHelpers::checkArrayKey($data,'responsibility_id'),
//                'region_id' => \CommonHelpers::checkArrayKey($data,'region_id'),
//                'local_authority' => \CommonHelpers::checkArrayKey($data,'local_authority'),
//                'division_id' => \CommonHelpers::checkArrayKey($data,'division_id'),
//                'building_category' => \CommonHelpers::checkArrayKey($data,'building_category'),
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
                "property_status" => \CommonHelpers::checkArrayKey($data,'property_status'),
                "property_occupied" => \CommonHelpers::checkArrayKey($data,'property_occupied'),
                "programme_type" => \CommonHelpers::checkArrayKey($data,'programme_type'),
                "programme_type_other" => \CommonHelpers::checkArrayKey($data,'programme_type_other'),
                "asset_use_primary" => \CommonHelpers::checkArrayKey($data,'asset_use_primary'),
                "asset_use_primary_other" => \CommonHelpers::checkArrayKey($data,'primaryusemore'),
                "asset_use_secondary" => \CommonHelpers::checkArrayKey($data,'asset_use_secondary'),
                "asset_use_secondary_other" => \CommonHelpers::checkArrayKey($data,'secondaryusemore'),
                "construction_age" => \CommonHelpers::checkArrayKey($data,'construction_age'),
               "construction_type" => \CommonHelpers::checkArrayKey($data,'construction_type'),
                "listed_building" => \CommonHelpers::checkArrayKey($data,'listed_building'),
                "listed_building_other" => \CommonHelpers::checkArrayKey($data,'listed_building_other'),
                "size_floors" => \CommonHelpers::checkArrayKey($data,'size_floors'),
                "size_floors_other" => \CommonHelpers::checkArrayKey($data,'size_floors_other'),
                "size_staircases" => \CommonHelpers::checkArrayKey($data,'size_staircases'),
                "size_staircases_other" => \CommonHelpers::checkArrayKey($data,'size_staircases_other'),
                "size_lifts" => \CommonHelpers::checkArrayKey($data,'size_lifts'),
                "size_lifts_other" => \CommonHelpers::checkArrayKey($data,'size_lifts_other'),
                "size_net_area" => \CommonHelpers::checkArrayKey($data,'size_net_area'),
                "electrical_meter" => \CommonHelpers::checkArrayKey($data,'electricalMeter'),
                "gas_meter" => \CommonHelpers::checkArrayKey($data,'gasMeter'),
                "loft_void" => \CommonHelpers::checkArrayKey($data,'loftVoid'),
                "size_bedrooms" => \CommonHelpers::checkArrayKey($data,'size_bedrooms'),
                "size_gross_area" => \CommonHelpers::checkArrayKey($data,'size_gross_area'),
                "parking_arrangements" => \CommonHelpers::checkArrayKey($data,'parking_arrangements'),
                "parking_arrangements_other" => \CommonHelpers::checkArrayKey($data,'parking_arrangements_other'),
                "nearest_hospital" => \CommonHelpers::checkArrayKey($data,'nearest_hospital'),
                "restrictions_limitations" => \CommonHelpers::checkArrayKey($data,'restrictions_limitations'),
                "unusual_features" => \CommonHelpers::checkArrayKey($data,'unusual_features'),
                "size_comments" => \CommonHelpers::checkArrayKey($data,'size_comments'),
                "evacuation_strategy" => \CommonHelpers::checkArrayKey($data,'evacuation_strategy'),
                'stairs'          => \CommonHelpers::getMultiselectDataContruction(isset($data['stairs']) ? $data['stairs'] : null),
                'stairs_other'     => \CommonHelpers::checkArrayKey($data,'stairs-other'),
                'floors'            => \CommonHelpers::getMultiselectDataContruction(isset($data['floors']) ? $data['floors'] : null),
                'floors_other'       => \CommonHelpers::checkArrayKey($data,'floors-other'),
                'wall_construction'            => \CommonHelpers::getMultiselectDataContruction(isset($data['wall_construction']) ? $data['wall_construction'] : null),
                'wall_construction_other'       => \CommonHelpers::checkArrayKey($data,'wall_construction-other'),
                'wall_finish'            => \CommonHelpers::getMultiselectDataContruction(isset($data['wall_finish']) ? $data['wall_finish'] : null),
                'wall_finish_other'       => \CommonHelpers::checkArrayKey($data,'wall_finish-other'),
                "floors_above" => \CommonHelpers::checkArrayKey($data,'floors_above'),
                "floors_above_other" => \CommonHelpers::checkArrayKey($data,'floors_above_other'),
                "floors_below" => \CommonHelpers::checkArrayKey($data,'floors_below'),
                "floors_below_other" => \CommonHelpers::checkArrayKey($data,'floors_below_other'),
            ];

            $dataConstructionMaterials = [
                'construction_materials' => \CommonHelpers::checkArrayKey($data,'construction_materials'),
                'construction_materials_other' => \CommonHelpers::checkArrayKey($data,'construction_materials-other'),
            ];

            $dataPropertyVulnerable = [
                'vulnerable_occupant_type' => \CommonHelpers::checkArrayKey($data,'vulnerable_occupant_type'),
                'avg_vulnerable_occupants' => \CommonHelpers::checkArrayKey($data,'avg_vulnerable_occupants'),
                'max_vulnerable_occupants' => \CommonHelpers::checkArrayKey($data,'max_vulnerable_occupants'),
                'last_vulnerable_occupants' => \CommonHelpers::checkArrayKey($data,'last_vulnerable_occupants'),
            ];
        }
        try {
            \DB::beginTransaction();
            $message = '';
            if($id) {
                $property = $this->propertyRepository->update($dataProperty, $id);
                $property->propertyType()->sync(\CommonHelpers::checkArrayKey($data,'riskType'));
                // Vulnerable Occupants
                $dataPropertyVulnerable['property_id'] = $id;
                $vulnerableOccupant = $property->vulnerableOccupant;
                if ($vulnerableOccupant) {
                    $this->vulnerableOccupantRepository->update($dataPropertyVulnerable, $vulnerableOccupant->id);
                } else {
                    $vulnerableOccupant = $this->vulnerableOccupantRepository->create($dataPropertyVulnerable);
                }
//                $vulnerableOccupant->vulnerableOccupantTypes()->sync(\CommonHelpers::checkArrayKey($data,'vulnerable_occupant_types'));

                $message = 'Property Updated Successfully!';

                //log audit
                if(!is_null($property->parents)){
                    $parent_id = $property->parents->id ?? '';
                }else{
                    $parent_id = $property->zone->id ?? '';
                }
                $comment = \Auth::user()->full_name . " edited Property " . $property->name;
                \CommonHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_EDIT, $property->reference, $parent_id, $comment, 0 , $property->id);
                //update and send email
                // \CommonHelpers::isRegisterUpdated($id);
            } else {
                $property =  $this->propertyRepository->create($dataProperty);
                if ($property) {
                    $property->reference = 'PL'.$property->id;
                    $property->save();
                    $property->propertyType()->sync(\CommonHelpers::checkArrayKey($data,'riskType'));
                    // Vulnerable Occupants
                    $dataPropertyVulnerable['property_id'] = $property->id;
                    $vulnerableOccupant = $this->vulnerableOccupantRepository->create($dataPropertyVulnerable);
//                    $vulnerableOccupant->vulnerableOccupantTypes()->sync(\CommonHelpers::checkArrayKey($data,'vulnerable_occupant_types'));
                    //update and send email
                    \CommonHelpers::isRegisterUpdated($property->id);
                    //log audit
                    if(!is_null($property->parents)){
                        $parent_id = $property->parents->id ?? '';
                    }else{
                        $parent_id = $property->zone->id ?? '';
                    }
                    $comment = \Auth::user()->full_name . " added New Property " . $property->name;
                    \CommonHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_ADD,
                        $property->reference, $parent_id, $comment, 0 , $property->id);

                    // set view property EMP
                    \CompliancePrivilege::setViewEMP(JR_PROPERTY_EMP, $property->id );

                    // set update property EMP
                    \CompliancePrivilege::setUpdateEMP(JR_UPDATE_PROPERTY_EMP, $property->id );

                }
                $message = 'Property Created Successfully!';
            }

            if ($property) {
                PropertyInfo::updateOrCreate(['property_id' => $property->id], $dataPropertyInfo);
                PropertySurvey::updateOrCreate(['property_id' => $property->id], $dataPropertySurvey);

                // Property Construction Materials
                $property->constructionMaterials()->sync($dataConstructionMaterials['construction_materials']);
                if ($dataConstructionMaterials['construction_materials'] && in_array(MATERIAL_OTHER, $dataConstructionMaterials['construction_materials'])
                    && $dataConstructionMaterials['construction_materials_other']) {
                    $property->constructionMaterials()
                             ->updateExistingPivot(MATERIAL_OTHER, ['other' => $dataConstructionMaterials['construction_materials_other']]);
                }

                // store comment history
                \CommentHistory::storeCommentHistory('property', $property->id, $dataProperty['comments']);
            }
            if (isset($data['photo'])) {
                $saveLocationImage = \CommonHelpers::saveFileShineDocumentStorage($data['photo'], $property->id, PROPERTY_PHOTO);
            }
            \DB::commit();
            return $response = \CommonHelpers::successResponse($message, $property);
        } catch (\Exception $e) {
            \Log::debug($e);
            \DB::rollBack();
            dd($e);

            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to create or update property. Please try again!');
        }
    }

    public function getWarningMessagev2($property)
    {
        $message = [];
        $pink_message = '';
        $property_id = $property->id ?? '';
        if ($property) {
            $first_acm_mas_12_item = $this->itemRepository->getFirstHighRiskItem($property_id);
            //keep
            if ($first_acm_mas_12_item) {
                $message[] = 'High Risk Asbestos Present within the Property';
            }
            //first project not complete
            $first_progress_project = $this->projectRepository->getFirstProjectInProgress($property_id);
            $first_survey = $this->surveyRepository->getFirstSurveyInProgress($property_id);
            if ($first_progress_project || $first_survey) {
                $message[] = 'Asbestos Project Works in Progress';
            }
            //keep
            $first_remedial_item = $this->itemRepository->getFirstRemedialItem($property_id);
            if ($first_remedial_item) {
                $message[] = 'Remedial or Removal Work Outstanding';
            }
        }
        //pink warning
        if (isset($property->propertyType) && !$property->propertyType->isEmpty()){
            $flag = false;
            foreach ($property->propertyType as $p_risk_type){
                //show warning message when select Duty to Manage 1, Duty to Manage (Partial Responsibility) 9,
                // Duty to Manage (Delegated Responsibility) 10, Duty of Care 11, Duty of Care (Employees Only) 12.
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
                $warning = $this->propertyRepository->getOrchardWarningMessage($property_id);
                //first MS and RR historical document
                $first_MS_doc = $this->historicalDocumentRepository->getFirstMsHistoricalDocument($property_id);
                if(!$first_MS_doc && isset($warning) && isset($warning['long'])){
                    $message[] = $warning['long'];
                }
                if (isset($property->historicalDoc) && !$property->historicalDoc->isEmpty()) {
                    $is_external = $property->historicalDoc->whereIn('doc_type', [1,2,3,4,5])->first();
                    if($is_external){
                        //when the User has added a Management Survey OR Management and Refubishment Survey OR Refurbishment Survey OR Demolition Survey OR a Re-Inspection Survey to the Historical Data Section on Properties
                        $message[] = 'External Survey Added to Historical Data Tab; Asbestos must be Presumed to be Present';
                    }
                }
            }
        }
        return $message;
    }

    public function getParentProperty($parent_id){
        return $this->propertyRepository->getParentProperty($parent_id);
    }

    public function getPropertyContacts($property) {
        $contact = $property->propertyInfo->team ?? [];
        return $this->userRepository->getContactProperty($contact, ['contact']);
    }

    public function getAllPropDecommissionReason(){
        return DecommissionReason::where('type', 'property')->where('parent_id', 1)->get();
    }

    public function decommissionProperty($property_id, $reason) {
        $property = $this->getProperty($property_id, ['surveys','areas','locations','items',
            'assessment','fireExist','assemblyPoint','vehicleParking','system','equipment','programme','hazard']);
        try {
            \DB::beginTransaction();
            if ($property->decommissioned == PROPERTY_DECOMMISSION) {
                $undecommission_prop = $this->propertyRepository->update(['decommissioned' => PROPERTY_UNDECOMMISSION], $property_id);
                $undecommission_survey = $this->surveyRepository->decommission($property->id, SURVEY_UNDECOMMISSION);
                $undecommission_area = $this->areaRepository->decommission($property->id, AREA_UNDECOMMISSION);
                $undecommission_location = $this->locationRepository->decommission($property->id, LOCATION_UNDECOMMISSION);
                $undecommission_item = $this->itemRepository->decommission($property->id, ITEM_UNDECOMMISSION);
                $property->assessment()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED]);
                $property->fireExist()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED]);
                $property->assemblyPoint()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED]);
                $property->vehicleParking()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED]);
                $property->system()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED]);
                $property->equipment()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED]);
                $property->programme()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED]);
                $property->hazard()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED]);

                //update and send email
                \CommonHelpers::isRegisterUpdated($property->id);
                //log audit
                //log audit
                if(isset($property->surveys) && count($property->surveys)) {
                    foreach ($property->surveys as $survey) {
                        $comment = \Auth::user()->full_name . " Recommission Survey ".$survey->reference." by Recommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_RECOMMISSION, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);
                    }
                }
                if(isset($property->areas) && count($property->areas)) {
                    foreach ($property->areas as $area) {
                        $comment = \Auth::user()->full_name . " Recommission Area ".$area->area_reference." by Recommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(AREA_TYPE, $area->id, AUDIT_ACTION_RECOMMISSION, $area->area_reference, $area->survey_id, $comment , 0 , $area->property_id ?? 0);
                    }
                }

                if(isset($property->locations) && count($property->locations)) {
                    foreach ($property->locations as $location) {
                        $comment = \Auth::user()->full_name . " Recommission Location ".$location->location_reference." by Recommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_RECOMMISSION, $location->location_reference, $location->survey_id ,$comment, 0 ,$location->property_id);
                    }
                }
                if(isset($property->items) && count($property->items)) {
                    foreach ($property->items as $item) {
                        $comment = \Auth::user()->full_name . " Recommission Item ".$item->reference." by Recommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_RECOMMISSION, $item->reference, $item->survey_id ,$comment, 0 ,$item->property_id);
                    }
                }

                if(isset($property->assessment) && count($property->assessment)) {
                    foreach ($property->assessment as $assessment) {
                        $comment = \Auth::user()->full_name . " Recommission Assessment ".$assessment->reference." by Recommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $assessment->id, AUDIT_ACTION_RECOMMISSION, $assessment->reference, $assessment->property_id ,$comment, 0 ,$assessment->property_id);
                    }
                }

                if(isset($property->fireExist) && count($property->fireExist)) {
                    foreach ($property->fireExist as $fireExist) {
                        $comment = \Auth::user()->full_name . " Recommission Fire Exist ".$fireExist->reference." by Recommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(FIRE_EXIT_TYPE, $fireExist->id, AUDIT_ACTION_RECOMMISSION, $fireExist->reference, $fireExist->assess_id ,$comment, 0 ,$fireExist->property_id);
                    }
                }

                if(isset($property->assemblyPoint) && count($property->assemblyPoint)) {
                    foreach ($property->assemblyPoint as $assemblyPoint) {
                        $comment = \Auth::user()->full_name . " Recommission Assembly Point Exist ".$assemblyPoint->reference." by Recommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(ASSEMBLY_POINT, $assemblyPoint->id, AUDIT_ACTION_RECOMMISSION, $assemblyPoint->reference, $assemblyPoint->assess_id ,$comment, 0 ,$assemblyPoint->property_id);
                    }
                }

                if(isset($property->vehicleParking) && count($property->vehicleParking)) {
                    foreach ($property->vehicleParking as $vehicleParking) {
                        $comment = \Auth::user()->full_name . " Recommission Vehicle Parking ".$vehicleParking->reference." by Recommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(VEHICLE_PARKING_TYPE, $vehicleParking->id, AUDIT_ACTION_RECOMMISSION, $vehicleParking->reference, $vehicleParking->assess_id ,$comment, 0 ,$vehicleParking->property_id);
                    }
                }

                if(isset($property->system) && count($property->system)) {
                    foreach ($property->system as $system) {
                        $comment = \Auth::user()->full_name . " Recommission System ".$system->reference." by Recommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(SYSTEM_TYPE, $system->id, AUDIT_ACTION_RECOMMISSION, $system->reference, $system->assess_id ,$comment, 0 ,$system->property_id);
                    }
                }

                if(isset($property->equipment) && count($property->equipment)) {
                    foreach ($property->equipment as $equipment) {
                        $comment = \Auth::user()->full_name . " Recommission Equipment ".$equipment->reference." by Recommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $equipment->id, AUDIT_ACTION_RECOMMISSION, $equipment->reference, $equipment->assess_id ,$comment, 0 ,$equipment->property_id);
                    }
                }

                if(isset($property->programme) && count($property->programme)) {
                    foreach ($property->programme as $programme) {
                        $comment = \Auth::user()->full_name . " Recommission Programme ".$programme->reference." by Recommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(PROGRAMME_TYPE, $programme->id, AUDIT_ACTION_RECOMMISSION, $programme->reference, $programme->assess_id ,$comment, 0 ,$programme->property_id);
                    }
                }

                if(isset($property->hazard) && count($property->hazard)) {
                    foreach ($property->hazard as $hazard) {
                        $comment = \Auth::user()->full_name . " Recommission Hazard ".$hazard->reference." by Recommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(HAZARD_TYPE, $hazard->id, AUDIT_ACTION_RECOMMISSION, $hazard->reference, $hazard->assess_id ,$comment, 0 ,$hazard->property_id);
                    }
                }
                $comment = \Auth::user()->full_name . " recommission Property " . $property->name;
                \ComplianceHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_RECOMMISSION, $property->reference, $property->client_id, $comment, 0 , $property->id);

                $response = \CommonHelpers::successResponse('Property Recommissioned Successfully!');
            } else {
                $decommission_prop = $this->propertyRepository->update(['decommissioned' => PROPERTY_DECOMMISSION, 'decommissioned_reason' => $reason], $property_id);
                $decommission_survey = $this->surveyRepository->decommission($property->id, SURVEY_DECOMMISSION);
                $decommission_area = $this->areaRepository->decommission($property->id, AREA_DECOMMISSION);
                $decommission_location = $this->locationRepository->decommission($property->id, LOCATION_DECOMMISSION);
                $decommission_item = $this->itemRepository->decommission($property->id, ITEM_DECOMMISSION);
                $property->assessment()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_DECOMMISSIONED]);
                $property->fireExist()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_DECOMMISSIONED]);
                $property->assemblyPoint()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_DECOMMISSIONED]);
                $property->vehicleParking()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_DECOMMISSIONED]);
                $property->system()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_DECOMMISSIONED]);
                $property->equipment()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_DECOMMISSIONED]);
                $property->programme()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_DECOMMISSIONED]);
                $property->hazard()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_DECOMMISSIONED]);

                //update and send email
                \CommonHelpers::isRegisterUpdated($property->id);
                //log audit
                if (isset($property->surveys) && count($property->surveys)) {
                    foreach ($property->surveys as $survey) {
                        $comment = \Auth::user()->full_name . " Decommission Survey ".$survey->reference." by Decommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_DECOMMISSION, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);
                    }
                }
                if (isset($property->areas) && count($property->areas)) {
                    foreach ($property->areas as $area) {
                        $comment = \Auth::user()->full_name . " Decommission Area ".$area->area_reference." by Decommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(AREA_TYPE, $area->id, AUDIT_ACTION_DECOMMISSION, $area->area_reference, $area->survey_id, $comment , 0 , $area->property_id ?? 0);
                    }
                }

                if(isset($property->locations) && count($property->locations)) {
                    foreach ($property->locations as $location) {
                        $comment = \Auth::user()->full_name . " Decommission Location ".$location->location_reference." by Decommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_DECOMMISSION, $location->location_reference, $location->survey_id ,$comment, 0 ,$location->property_id);
                    }
                }
                if(isset($property->items) && count($property->items)) {
                    foreach ($property->items as $item) {
                        $comment = \Auth::user()->full_name . " Decommission Item ".$item->reference." by Decommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_DECOMMISSION, $item->reference, $item->survey_id ,$comment, 0 ,$item->property_id);
                    }
                }

                if(isset($property->assessment) && count($property->assessment)) {
                    foreach ($property->assessment as $assessment) {
                        $comment = \Auth::user()->full_name . " Decommission Assessment ".$assessment->reference." by Decommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $assessment->id, AUDIT_ACTION_DECOMMISSION, $assessment->reference, $assessment->property_id ,$comment, 0 ,$assessment->property_id);
                    }
                }

                if(isset($property->fireExist) && count($property->fireExist)) {
                    foreach ($property->fireExist as $fireExist) {
                        $comment = \Auth::user()->full_name . " Decommission Fire Exist ".$fireExist->reference." by Decommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(FIRE_EXIT_TYPE, $fireExist->id, AUDIT_ACTION_DECOMMISSION, $fireExist->reference, $fireExist->assess_id ,$comment, 0 ,$fireExist->property_id);
                    }
                }

                if(isset($property->assemblyPoint) && count($property->assemblyPoint)) {
                    foreach ($property->assemblyPoint as $assemblyPoint) {
                        $comment = \Auth::user()->full_name . " Decommission Assembly Point Exist ".$assemblyPoint->reference." by Decommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(ASSEMBLY_POINT, $assemblyPoint->id, AUDIT_ACTION_DECOMMISSION, $assemblyPoint->reference, $assemblyPoint->assess_id ,$comment, 0 ,$assemblyPoint->property_id);
                    }
                }

                if(isset($property->vehicleParking) && count($property->vehicleParking)) {
                    foreach ($property->vehicleParking as $vehicleParking) {
                        $comment = \Auth::user()->full_name . " Decommission Vehicle Parking ".$vehicleParking->reference." by Decommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(VEHICLE_PARKING_TYPE, $vehicleParking->id, AUDIT_ACTION_DECOMMISSION, $vehicleParking->reference, $vehicleParking->assess_id ,$comment, 0 ,$vehicleParking->property_id);
                    }
                }

                if(isset($property->system) && count($property->system)) {
                    foreach ($property->system as $system) {
                        $comment = \Auth::user()->full_name . " Decommission System ".$system->reference." by Decommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(SYSTEM_TYPE, $system->id, AUDIT_ACTION_DECOMMISSION, $system->reference, $system->assess_id ,$comment, 0 ,$system->property_id);
                    }
                }

                if(isset($property->equipment) && count($property->equipment)) {
                    foreach ($property->equipment as $equipment) {
                        $comment = \Auth::user()->full_name . " Decommission Equipment ".$equipment->reference." by Decommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $equipment->id, AUDIT_ACTION_DECOMMISSION, $equipment->reference, $equipment->assess_id ,$comment, 0 ,$equipment->property_id);
                    }
                }

                if(isset($property->programme) && count($property->programme)) {
                    foreach ($property->programme as $programme) {
                        $comment = \Auth::user()->full_name . " Decommission Programme ".$programme->reference." by Decommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(PROGRAMME_TYPE, $programme->id, AUDIT_ACTION_DECOMMISSION, $programme->reference, $programme->assess_id ,$comment, 0 ,$programme->property_id);
                    }
                }

                if(isset($property->hazard) && count($property->hazard)) {
                    foreach ($property->hazard as $hazard) {
                        $comment = \Auth::user()->full_name . " Decommission Hazard ".$hazard->reference." by Decommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(HAZARD_TYPE, $hazard->id, AUDIT_ACTION_DECOMMISSION, $hazard->reference, $hazard->assess_id ,$comment, 0 ,$hazard->property_id);
                    }
                }

                $comment = \Auth::user()->full_name . " Decommission Property " . $property->name;
                \ComplianceHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_DECOMMISSION, $property->reference, $property->client_id, $comment, 0 , $property->id);
                $response =  \CommonHelpers::successResponse('Property Decommission Successfully!');
            }
            \DB::commit();
            return $response;
        } catch (\Exception $e) {
            \Log::debug($e);
            \DB::rollBack();
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to Decommission/Recommission Property. Please try again!');
        }
    }

    public function getComplianceDocuments($property_id, $relation = [], $pagination = PAGINATION_DEFAULT){
        return  $this->complianceDocumentRepository->getComplianceDocuments($property_id, $relation, $pagination);
    }

    public function viewDocument($id){
        $document = $this->complianceDocumentRepository->getDocument($id, ['property','complianceDocumentStorage']);

        //log audit
        if (!is_null(\Auth::user()) && $document) {
            $comment = (\Auth::user()->full_name ?? 'system')  . " view document "  . $document->name ?? '' . ' on '. optional($document->property)->name;
            \CommonHelpers::logAudit(COMPLIANCE_DOCUMENT_PHOTO, $document->id, AUDIT_ACTION_VIEW, $document->name, optional($document->property)->id ,$comment, 0 ,optional($document->property)->id);
        }
        if($document->complianceDocumentStorage && file_exists($document->complianceDocumentStorage->path)){
            return response()->file($document->complianceDocumentStorage->path);
        }
        return abort(404);
    }

    public function viewHistoricalDocument($id){
        $document = $this->historicalDocumentRepository->getDocument($id, ['property','complianceDocumentStorage']);

        //log audit
        if (!is_null(\Auth::user()) && $document) {
            $comment = (\Auth::user()->full_name ?? 'system')  . " view document "  . $document->name ?? '' . ' on '. optional($document->property)->name;
            \CommonHelpers::logAudit(COMPLIANCE_HISTORICAL_DOCUMENT_PHOTO, $document->id, AUDIT_ACTION_VIEW, $document->name, optional($document->property)->id ,$comment, 0 ,optional($document->property)->id);
        }
        if($document->complianceDocumentStorage && file_exists($document->complianceDocumentStorage->path)){
            return response()->file($document->complianceDocumentStorage->path);
        }
        return abort(404);
    }

    public function downloadDocument($id){
        $document = $this->complianceDocumentRepository->getDocument($id, ['property','complianceDocumentStorage']);
        //audit log
        $comment =  \Auth::user()->full_name . ' downloaded document ' . ($document->complianceDocumentStorage->file_name ?? '');
        \CommonHelpers::logAudit(COMPLIANCE_DOCUMENT_PHOTO, $id , AUDIT_ACTION_DOWNLOAD, ($document->complianceDocumentStorage->file_name ?? ''), 0 , $comment , $id , $document->property->id ?? NULL);

        if (isset($document->complianceDocumentStorage->path)) {
            if (is_file($document->complianceDocumentStorage->path) || \Storage::exists($document->complianceDocumentStorage->path)) {
                // return response()->file(storage_path().'/app/'.$file->path);
                $headers = [
                    'Content-Type' => 'application/pdf',
                ];
                return response()->download(storage_path().'/app/'.$document->complianceDocumentStorage->path, $document->complianceDocumentStorage->file_name, $headers);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function downloadHistoricalDocument($id){
        $document = $this->historicalDocumentRepository->getDocument($id, ['property','complianceDocumentStorage']);
        //audit log
        $comment =  \Auth::user()->full_name . ' downloaded document ' . ($document->complianceDocumentStorage->file_name ?? '');
        \CommonHelpers::logAudit(COMPLIANCE_HISTORICAL_DOCUMENT_PHOTO, $id , AUDIT_ACTION_DOWNLOAD, ($document->complianceDocumentStorage->file_name ?? ''), 0 , $comment , $id , $document->property->id ?? NULL);

        if (isset($document->complianceDocumentStorage->path)) {
            if (is_file($document->complianceDocumentStorage->path) || \Storage::exists($document->complianceDocumentStorage->path)) {
                // return response()->file(storage_path().'/app/'.$file->path);
                $headers = [
                    'Content-Type' => 'application/pdf',
                ];
                return response()->download(storage_path().'/app/'.$document->complianceDocumentStorage->path, $document->complianceDocumentStorage->file_name, $headers);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function updateOrCreateHistoricalCategory($data, $id = 0){
        if (!empty($data)) {
            $dataCat = [
                'name' => \CommonHelpers::checkArrayKey($data,'category_title'),
                'property_id' => \CommonHelpers::checkArrayKey($data,'property_id'),
                'type' => NULL,
                'order' => 0
            ];
            try {
                \DB::beginTransaction();
                $historicalCat =  $this->categoryDocumentRepository->updateOrCreate(['id' => $id], $dataCat);
                if(is_null($id)) {
                    $message = 'Historical Category Created Successfully!';
                    //log audit
                    \CommonHelpers::logAudit(COMPLIANCE_HISTORICAL_DOCUMENT_CATEGORY, $historicalCat->id, AUDIT_ACTION_ADD, $historicalCat->name, $historicalCat->property_id ,null, 0 ,$historicalCat->property_id);
                } else {
                    $message = 'Historical Category Updated Successfully!';
                    //log audit
                    \CommonHelpers::logAudit(COMPLIANCE_HISTORICAL_DOCUMENT_CATEGORY, $id, AUDIT_ACTION_EDIT, $dataCat['name'], $dataCat['property_id'] ,null, 0 ,$dataCat['property_id']);
                }
                \DB::commit();
                return $response = \CommonHelpers::successResponse($message);
            } catch (\Exception $e) {
                \Log::debug($e);
                \DB::rollBack();
                return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to create or update historical category. Please try again!');
            }
        }
    }

    public function updateOrCreateHistoricalDocument($data, $id = null) {
        if (!empty($data)) {
            $dataHistorical = [
                'property_id' => \CommonHelpers::checkArrayKey($data,'property_id'),
                'name' => \CommonHelpers::checkArrayKey($data,'name'),
                'doc_type' => \CommonHelpers::checkArrayKey($data,'document_type'),
                'category' => \CommonHelpers::checkArrayKey($data,'category'),
                'is_external_ms' => isset($data['is_external_ms']) ? 1 : 0,
                'is_identified_acm' => \CommonHelpers::checkArrayKey($data,'is_identified_acm') ?? 0,
                'is_inaccess_room' => \CommonHelpers::checkArrayKey($data,'is_inaccess_room') ?? 0,
                'added' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'historic_date')),
            ];

            try {
                \DB::beginTransaction();
                if(is_null($id)) {
                    $historical =  $this->historicalDocumentRepository->create($dataHistorical);
                    if ($historical) {
                        $refHD = "HD" . $historical->id;
                        $historical->reference = $refHD;
                        $historical->created_by = \Auth::user()->id;
                        //update is_external_ms by doc_type
                        $historical->is_external_ms = $historical->historicalDocType->is_external_ms ?? 0;
                        $historical->save();
                    }
                    $message = 'Historical Document Created Successfully!';
                    //log audit
                    \CommonHelpers::logAudit(COMPLIANCE_HISTORICAL_DOCUMENT, $historical->id, AUDIT_ACTION_ADD, $refHD, $historical->category ,null, 0 ,$historical->property_id);
                } else {
                    //update is_external_ms by doc_type
                    $doc_type = $this->historicalDocumentTypeRepository->find($dataHistorical['doc_type']);
                    $dataHistorical['is_external_ms'] = $doc_type->is_external_ms ?? 0;
                    $historical =  $this->historicalDocumentRepository->updateOrCreate(['id' => $id], $dataHistorical);
                    $message = 'Historical Document Updated Successfully!';
                    //log audit
                    \CommonHelpers::logAudit(COMPLIANCE_HISTORICAL_DOCUMENT, $historical->id, AUDIT_ACTION_EDIT, $historical->reference, $historical->category ,null, 0 ,$historical->property_id);
                }
                if (isset($data['document'])) {
                    $saveLocationImage = \ComplianceHelpers::saveFileComplianceDocumentStorage($data['document'], $historical->id, COMPLIANCE_HISTORICAL_DOCUMENT);
                }
                \DB::commit();
                return $response = \CommonHelpers::successResponse($message);

            } catch (\Exception $e) {
                \Log::debug($e);
                \DB::rollBack();
                return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to create or update historical document. Please try again!');
            }
        }
    }

    public function getPropertyInfoDropdown($propertyInfoDropdownId)
    {
        return PropertyInfoDropdown::find($propertyInfoDropdownId)->propertyInfoDropdownData;
    }

    public function getVulnerableTypes()
    {
        return VulnerableOccupantType::all();
    }

    public function getHazardRegisterSummary($property_id, $assess_type, $decommission = 0, $source = null) {
        $counterAll = 0;
        $counterAll += $vhighRisk = $this->countRiskHazard($property_id, 'vhigh', $assess_type, $decommission);
        $counterAll += $highRisk = $this->countRiskHazard($property_id, 'high', $assess_type, $decommission);
        $counterAll += $mediumRisk = $this->countRiskHazard($property_id, 'medium', $assess_type, $decommission);
        $counterAll += $lowRisk = $this->countRiskHazard($property_id, 'low', $assess_type, $decommission);
        $counterAll += $vlowRisk = $this->countRiskHazard($property_id, 'vlow', $assess_type, $decommission);

        $source = $source ?? url()->full();
        $dataSummary = [
            "All Hazard Risk Count" => [
                'number' => $counterAll,
                'link' => $source. '?section='. TYPE_ALL_HAZARD_SUMMARY . '&decommissioned='.$decommission
            ],
            "Very High Risk Hazard Summary" => [
                'number' => $vhighRisk,
                'link' => $source. '?section='. TYPE_VERY_HIGH_RISK_HAZARD_SUMMARY . '&decommissioned='.$decommission
            ],
            "High Risk Hazard Summary" => [
                'number' => $highRisk,
                'link' => $source. '?section='. TYPE_HIGH_RISK_HAZARD_SUMMARY . '&decommissioned='.$decommission
            ],
            "Medium Risk Hazard Summary" =>  [
                'number' => $mediumRisk,
                'link' => $source. '?section='. TYPE_MEDIUM_RISK_HAZARD_SUMMARY . '&decommissioned='.$decommission
            ],
            "Low Risk Hazard Summary" => [
                'number' => $lowRisk,
                'link' => $source. '?section='. TYPE_LOW_RISK_HAZARD_SUMMARY . '&decommissioned='.$decommission
            ],
            "Very Low Risk Hazard Summary" => [
                'number' => $vlowRisk,
                'link' => $source. '?section='. TYPE_VERY_LOW_RISK_HAZARD_SUMMARY . '&decommissioned='.$decommission
            ]
        ];
        return $dataSummary;
    }

    public function countRiskHazard($property_id, $type, $assess_type, $decommission = 0){

        switch ($type) {
            case 'vhigh':
                $hazards = Hazard::where('decommissioned', $decommission)
                                            ->where('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('assess_type', $assess_type)
                                            ->whereBetween('total_risk',[21, 25])->count();
                break;

            case 'high':
                $hazards = Hazard::where('decommissioned', $decommission)
                                            ->where('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('assess_type', $assess_type)
                                            ->whereBetween('total_risk',[16, 20])->count();
                break;

            case 'medium':
                $hazards = Hazard::where('decommissioned', $decommission)
                                            ->where('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('assess_type', $assess_type)
                                            ->whereBetween('total_risk',[10, 15])->count();
                break;

            case 'low':
                $hazards = Hazard::where('decommissioned', $decommission)
                                            ->where('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('assess_type', $assess_type)
                                            ->whereBetween('total_risk',[4, 9])->count();
                break;

            case 'vlow':
                $hazards = Hazard::where('decommissioned', $decommission)
                                            ->where('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('assess_type', $assess_type)
                                            ->whereBetween('total_risk',[0, 3])->count();
                break;

            default:
                return 0;
                break;
        }

        return $hazards;
    }

    public function getRiskHazard($property_id, $type,$assess_type, $decommission = 0){
        $breadcrumb = '';
        $hazards = [];
        $decommissioned_text = $decommission == 0 ? '' : 'Decommissioned';
        switch ($type) {
            case TYPE_ALL_HAZARD_SUMMARY:
                $hazards = Hazard::with(['area','location', 'hazardType'])->where('decommissioned', $decommission)
                                            ->where('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('assess_type', $assess_type)
                                            ->get();
                $breadcrumb = "$decommissioned_text All Hazard Risk Summary";
                break;

            case TYPE_VERY_HIGH_RISK_HAZARD_SUMMARY:
                $hazards = Hazard::with(['area','location', 'hazardType'])->where('decommissioned', $decommission)
                                            ->where('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('assess_type', $assess_type)
                                            ->whereBetween('total_risk',[21, 25])->get();
                $breadcrumb = "$decommissioned_text Very High Risk Hazard Summary";
                break;

            case TYPE_HIGH_RISK_HAZARD_SUMMARY:
                $hazards = Hazard::with(['area','location', 'hazardType'])->where('decommissioned', $decommission)
                                            ->where('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('assess_type', $assess_type)
                                            ->whereBetween('total_risk',[16, 20])->get();
                $breadcrumb = "$decommissioned_text High Risk Hazard Summary";
                break;

            case TYPE_MEDIUM_RISK_HAZARD_SUMMARY:
                $hazards = Hazard::with(['area','location', 'hazardType'])->where('decommissioned', $decommission)
                                            ->where('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('assess_type', $assess_type)
                                            ->whereBetween('total_risk',[10, 15])->get();
                $breadcrumb = "$decommissioned_text Medium Risk Hazard Summary";
                break;

            case TYPE_LOW_RISK_HAZARD_SUMMARY:
                $hazards = Hazard::with(['area','location', 'hazardType'])->where('decommissioned', $decommission)
                                            ->where('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('assess_type', $assess_type)
                                            ->whereBetween('total_risk',[4, 9])->get();
                $breadcrumb = "$decommissioned_text Low Risk Hazard Summary";
                break;

            case TYPE_VERY_LOW_RISK_HAZARD_SUMMARY:
                $hazards = Hazard::with(['area','location', 'hazardType'])->where('decommissioned', $decommission)
                                            ->where('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('assess_type', $assess_type)
                                            ->whereBetween('total_risk',[0, 3])->get();
                $breadcrumb = "$decommissioned_text Very Low Risk Hazard Summary";
                break;

            default:
                $hazards = [];
                $breadcrumb = '';
                break;
        }

        return [
            'hazards' => $hazards,
            'breadcrumb' => $breadcrumb
        ];
    }

    public function getRegisterSummary($property_id, $decommissioned = 0, $source = null) {
        $item =  Item::where('property_id', $property_id)->where('survey_id', 0)->where('decommissioned', $decommissioned)->get();
        $counterAll = 0;
        $counterAll += $highRisk = $this->countRiskItem($item, 'high', $decommissioned);
        $counterAll += $mediumRisk = $this->countRiskItem($item, 'medium', $decommissioned);
        $counterAll += $lowRisk = $this->countRiskItem($item, 'low', $decommissioned);
        $counterAll += $vlowRisk = $this->countRiskItem($item, 'vlow', $decommissioned);

        $counterAll += $inaccessibleItems = $item->where('state', ITEM_INACCESSIBLE_STATE)->count();
        $noACMItems =  $item->where('state', ITEM_NOACM_STATE)->count();

        $inaccessibleRooms = count($this->countInaccessibleRooms($property_id, $decommissioned));

        $source = $source ?? url()->full();
        $dataSummary = [
            "All ACM Items" => [
                'number' => $counterAll,
                'link' => $source. '?section='. TYPE_All_ACM_ITEM_SUMMARY . '&decommissioned='.$decommissioned
            ],
            "Inaccessible ACM Item Summary" => [
                'number' => $inaccessibleItems,
                'link' => $source. '?section='. TYPE_INACCESS_ACM_ITEM_SUMMARY . '&decommissioned='.$decommissioned
            ],
            "High Risk ACM Item Summary" => [
                'number' => $highRisk,
                'link' => $source. '?section='. TYPE_HIGH_RISK_ITEM_SUMMARY . '&decommissioned='.$decommissioned
            ],
            "Medium Risk ACM Item Summary" =>  [
                'number' => $mediumRisk,
                'link' => $source. '?section='. TYPE_MEDIUM_RISK_ITEM_SUMMARY . '&decommissioned='.$decommissioned
            ],
            "Low Risk ACM Item Summary" => [
                'number' => $lowRisk,
                'link' => $source. '?section='. TYPE_LOW_RISK_ITEM_SUMMARY . '&decommissioned='.$decommissioned
            ],
            "Very Low Risk ACM Item Summary" => [
                'number' => $vlowRisk,
                'link' => $source. '?section='. TYPE_VERY_LOW_RISK_ITEM_SUMMARY . '&decommissioned='.$decommissioned
            ],
            "No Risk (NoACM) Item Summary" => [
                'number' => $noACMItems,
                'link' => $source. '?section='. TYPE_NO_RISK_ITEM_SUMMARY . '&decommissioned='.$decommissioned
            ],
        ];

        $dataSummary["Inaccessible Room/locations Summary"] = [
                    'number' => $inaccessibleRooms,
                    'link' => $source. '?section='. TYPE_INACCESS_ROOM_SUMMARY . '&decommissioned='.$decommissioned
                ];


        return $dataSummary;
    }

    public function countRiskItem($item, $type, $decommissioned){
        if (is_null($item)) {
            return [];
        }
        switch ($type) {
            case 'high':
                $items = $item->where('decommissioned', $decommissioned)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[10, 99])->count();
                break;

            case 'medium':
                $items = $item->where('decommissioned', $decommissioned)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[7, 9])->count();
                break;

            case 'low':
                $items = $item->where('decommissioned', $decommissioned)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[5, 6])->count();
                break;

            case 'vlow':
                $items = $item->where('decommissioned', $decommissioned)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[0, 4])->count();
                break;

            default:
                return 0;
                break;
        }

        return $items;
    }

    public function getRiskItem($property_id, $type, $decommissioned = 0){
        $decommissioned_text = $decommissioned == 0 ? '' : 'Decommissioned';
        $breadcrumb = '';

        switch ($type) {
            case TYPE_HIGH_RISK_ITEM_SUMMARY:
                $data = Item::with(['area', 'location'])
                                            ->where('decommissioned', $decommissioned)
                                            ->where('property_id', $property_id)
                                            ->where('survey_id', 0)
                                            ->where('state', ITEM_ACCESSIBLE_STATE)
                                            ->whereBetween('total_mas_risk',[10, 99])->get();
                $breadcrumb = "$decommissioned_text High Risk ACM Item Summary";
                break;

            case TYPE_MEDIUM_RISK_ITEM_SUMMARY:
                $data = Item::with(['area', 'location'])
                                                ->where('decommissioned', $decommissioned)
                                                ->where('survey_id', 0)
                                                 ->where('property_id', $property_id)
                                                ->where('state', ITEM_ACCESSIBLE_STATE)
                                                ->whereBetween('total_mas_risk',[7, 9])->get();
                $breadcrumb = "$decommissioned_text Medium Risk ACM Item Summary";
                break;

            case TYPE_LOW_RISK_ITEM_SUMMARY:
                $data = Item::with(['area', 'location'])->where('decommissioned', $decommissioned)
                                                ->where('property_id', $property_id)
                                                ->where('survey_id', 0)
                                                ->where('state', ITEM_ACCESSIBLE_STATE)
                                                ->whereBetween('total_mas_risk',[5, 6])->get();
                $breadcrumb = "$decommissioned_text Low Risk ACM Item Summary";
                break;

            case TYPE_VERY_LOW_RISK_ITEM_SUMMARY:
                $data = Item::with(['area', 'location'])
                                                ->where('decommissioned', $decommissioned)
                                                ->where('property_id', $property_id)
                                                ->where('survey_id', 0)
                                                ->where('state', ITEM_ACCESSIBLE_STATE)
                                                ->whereBetween('total_mas_risk',[0, 4])->get();
                $breadcrumb = "$decommissioned_text Very Low Risk ACM Item Summary";
                break;

            case TYPE_INACCESS_ROOM_SUMMARY:
                $data = $this->countInaccessibleRooms($property_id, $decommissioned);
                $breadcrumb = "$decommissioned_text Inaccessible Room/locations Summary";
                break;

            case TYPE_NO_RISK_ITEM_SUMMARY:
                $data = Item::with(['area', 'location'])->where('decommissioned', $decommissioned)
                                                ->where('property_id', $property_id)
                                                ->where('survey_id', 0)
                                                ->where('state', ITEM_NOACM_STATE)->get();
                $breadcrumb = "$decommissioned_text No Risk ACM Item Summary";
                break;

            case TYPE_INACCESS_ACM_ITEM_SUMMARY:
                $data = Item::with(['area', 'location'])->where('decommissioned', $decommissioned)
                                                ->where('property_id', $property_id)
                                                ->where('survey_id', 0)
                                                ->where('state', ITEM_INACCESSIBLE_STATE)
                                                ->get();
                $breadcrumb = "$decommissioned_text Inaccessible ACM Item Summary";
                break;

            case TYPE_All_ACM_ITEM_SUMMARY:
                $data = Item::with(['area', 'location'])->where('decommissioned', $decommissioned)
                                                ->where('property_id', $property_id)
                                                ->where('survey_id', 0)
                                                ->where('state', '!=' ,ITEM_NOACM_STATE)
                                                ->get();

                $breadcrumb = "$decommissioned_text All ACM Risk Count";
                break;

            default:
                $data = [];
                $breadcrumb = '';
                break;
        }

        return [
            'data' => $data ?? [],
            'breadcrumb' => $breadcrumb,
        ];
    }

    public function countInaccessibleRooms($property_id, $decommissioned) {

        $locations = Location::with('allItems', 'items')
                            ->where('property_id', $property_id)
                            ->where('survey_id', 0)
                            ->where('assess_id', 0)
                            ->where('state', LOCATION_STATE_INACCESSIBLE)
                            ->where('decommissioned', $decommissioned)->get();
        return $locations;
    }

    public function getRegisterOverallSummary($property_id, $asbestos = true, $fire = true, $water = true, $health_and_safety = true){
        $data = [];
        $register_summary = $this->getRegisterSummary($property_id, 0 ,route('shineCompliance.property.asbestos',['property_id' => $property_id]));
        if($asbestos) {
            // asbestos risk
            $data['Total Risk Count']['asbestos']['count'] = $register_summary['All ACM Items']['number'];
            $data['Total Risk Count']['asbestos']['link'] = $register_summary['All ACM Items']['link'];
            $data['Inaccessible ACM Item Summary']['asbestos']['count'] = $register_summary['Inaccessible ACM Item Summary']['number'];
            $data['Inaccessible ACM Item Summary']['asbestos']['link'] = $register_summary['Inaccessible ACM Item Summary']['link'];
            $data['Very High Risk Summary']['asbestos']['count'] = 'N/A';
            $data['Very High Risk Summary']['asbestos']['link'] = '#';
            $data['High Risk Summary']['asbestos']['count'] = $register_summary['High Risk ACM Item Summary']['number'];
            $data['High Risk Summary']['asbestos']['link'] = $register_summary['High Risk ACM Item Summary']['link'];
            $data['Medium Risk Summary']['asbestos']['count'] = $register_summary['Medium Risk ACM Item Summary']['number'];
            $data['Medium Risk Summary']['asbestos']['link'] = $register_summary['Medium Risk ACM Item Summary']['link'];
            $data['Low Risk Summary']['asbestos']['count'] = $register_summary['Low Risk ACM Item Summary']['number'];
            $data['Low Risk Summary']['asbestos']['link'] = $register_summary['Low Risk ACM Item Summary']['link'];
            $data['Very Low Risk Summary']['asbestos']['count'] = $register_summary['Very Low Risk ACM Item Summary']['number'];
            $data['Very Low Risk Summary']['asbestos']['link'] = $register_summary['Very Low Risk ACM Item Summary']['link'];
            $data['No Risk (NoACM) Summary']['asbestos']['count'] = $register_summary['No Risk (NoACM) Item Summary']['number'];
            $data['No Risk (NoACM) Summary']['asbestos']['link'] = $register_summary['No Risk (NoACM) Item Summary']['link'];
            $data['Inaccessible Room/locations Summary']['asbestos']['count'] = $register_summary['Inaccessible Room/locations Summary']['number'];
            $data['Inaccessible Room/locations Summary']['asbestos']['link'] = $register_summary['Inaccessible Room/locations Summary']['link'];
        }

        if($fire) {
            // hazard fire risk
            $fire_hazard = $this->getHazardRegisterSummary($property_id,ASSESSMENT_FIRE_TYPE, 0 , route('shineCompliance.property.fire',['property_id' => $property_id]));

            $data['Total Risk Count']['fire']['count'] = $fire_hazard['All Hazard Risk Count']['number'];
            $data['Total Risk Count']['fire']['link'] = $fire_hazard['All Hazard Risk Count']['link'];
            $data['Inaccessible ACM Item Summary']['fire']['count'] = 'N/A';
            $data['Inaccessible ACM Item Summary']['fire']['link'] = '#';
            $data['Very High Risk Summary']['fire']['count'] = $fire_hazard['Very High Risk Hazard Summary']['number'];
            $data['Very High Risk Summary']['fire']['link'] = $fire_hazard['Very High Risk Hazard Summary']['link'];
            $data['High Risk Summary']['fire']['count'] = $fire_hazard['High Risk Hazard Summary']['number'];
            $data['High Risk Summary']['fire']['link'] = $fire_hazard['High Risk Hazard Summary']['link'];
            $data['Medium Risk Summary']['fire']['count'] = $fire_hazard['Medium Risk Hazard Summary']['number'];
            $data['Medium Risk Summary']['fire']['link'] = $fire_hazard['Medium Risk Hazard Summary']['link'];
            $data['Low Risk Summary']['fire']['count'] = $fire_hazard['Low Risk Hazard Summary']['number'];
            $data['Low Risk Summary']['fire']['link'] = $fire_hazard['Low Risk Hazard Summary']['link'];
            $data['Very Low Risk Summary']['fire']['count'] = $fire_hazard['Very Low Risk Hazard Summary']['number'];
            $data['Very Low Risk Summary']['fire']['link'] = $fire_hazard['Very Low Risk Hazard Summary']['link'];
            $data['No Risk (NoACM) Summary']['fire']['count'] = 'N/A';
            $data['No Risk (NoACM) Summary']['fire']['link'] = '#';
            $data['Inaccessible Room/locations Summary']['fire']['count'] = $register_summary['Inaccessible Room/locations Summary']['number'];
            $data['Inaccessible Room/locations Summary']['fire']['link'] = $register_summary['Inaccessible Room/locations Summary']['link'];
        }

        //TO DO permission for GAS
        if($health_and_safety) {
            $hs_hazard = $this->getHazardRegisterSummary($property_id, ASSESSMENT_HS_TYPE, 0 , route('shineCompliance.property.health_and_safety',['property_id' => $property_id]));
            // gas risk
            $data['Total Risk Count']['hs']['count'] = $hs_hazard['All Hazard Risk Count']['number'];
            $data['Total Risk Count']['hs']['link'] = $hs_hazard['All Hazard Risk Count']['link'];
            $data['Inaccessible ACM Item Summary']['hs']['count'] = 'N/A';
            $data['Inaccessible ACM Item Summary']['hs']['link'] = '#';
            $data['Very High Risk Summary']['hs']['count'] = $hs_hazard['Very High Risk Hazard Summary']['number'];
            $data['Very High Risk Summary']['hs']['link'] = $hs_hazard['Very High Risk Hazard Summary']['link'];
            $data['High Risk Summary']['hs']['count'] = $hs_hazard['High Risk Hazard Summary']['number'];
            $data['High Risk Summary']['hs']['link'] = $hs_hazard['High Risk Hazard Summary']['link'];
            $data['Medium Risk Summary']['hs']['count'] = $hs_hazard['Medium Risk Hazard Summary']['number'];
            $data['Medium Risk Summary']['hs']['link'] = $hs_hazard['Medium Risk Hazard Summary']['link'];
            $data['Low Risk Summary']['hs']['count'] = $hs_hazard['Low Risk Hazard Summary']['number'];
            $data['Low Risk Summary']['hs']['link'] = $hs_hazard['Low Risk Hazard Summary']['link'];
            $data['Very Low Risk Summary']['hs']['count'] = $hs_hazard['Very Low Risk Hazard Summary']['number'];
            $data['Very Low Risk Summary']['hs']['link'] = $hs_hazard['Very Low Risk Hazard Summary']['link'];
            $data['No Risk (NoACM) Summary']['hs']['count'] = 'N/A';
            $data['No Risk (NoACM) Summary']['hs']['link'] = '#';
            $data['Inaccessible Room/locations Summary']['hs']['count'] = $register_summary['Inaccessible Room/locations Summary']['number'];
            $data['Inaccessible Room/locations Summary']['hs']['link'] = $register_summary['Inaccessible Room/locations Summary']['link'];
        }

        if($water) {
            // hazard water risk
            $water_hazard = $this->getHazardRegisterSummary($property_id, ASSESSMENT_WATER_TYPE, 0 , route('shineCompliance.property.water',['property_id' => $property_id]));

            $data['Total Risk Count']['water']['count'] = $water_hazard['All Hazard Risk Count']['number'];
            $data['Total Risk Count']['water']['link'] = $water_hazard['All Hazard Risk Count']['link'];
            $data['Inaccessible ACM Item Summary']['water']['count'] = 'N/A';
            $data['Inaccessible ACM Item Summary']['water']['link'] = '#';
            $data['Very High Risk Summary']['water']['count'] = $water_hazard['Very High Risk Hazard Summary']['number'];
            $data['Very High Risk Summary']['water']['link'] = $water_hazard['Very High Risk Hazard Summary']['link'];
            $data['High Risk Summary']['water']['count'] = $water_hazard['High Risk Hazard Summary']['number'];
            $data['High Risk Summary']['water']['link'] = $water_hazard['High Risk Hazard Summary']['link'];
            $data['Medium Risk Summary']['water']['count'] = $water_hazard['Medium Risk Hazard Summary']['number'];
            $data['Medium Risk Summary']['water']['link'] = $water_hazard['Medium Risk Hazard Summary']['link'];
            $data['Low Risk Summary']['water']['count'] = $water_hazard['Low Risk Hazard Summary']['number'];
            $data['Low Risk Summary']['water']['link'] = $water_hazard['Low Risk Hazard Summary']['link'];
            $data['Very Low Risk Summary']['water']['count'] = $water_hazard['Very Low Risk Hazard Summary']['number'];
            $data['Very Low Risk Summary']['water']['link'] = $water_hazard['Very Low Risk Hazard Summary']['link'];
            $data['No Risk (NoACM) Summary']['water']['count'] = 'N/A';
            $data['No Risk (NoACM) Summary']['water']['link'] = '#';
            $data['Inaccessible Room/locations Summary']['water']['count'] = $register_summary['Inaccessible Room/locations Summary']['number'];
            $data['Inaccessible Room/locations Summary']['water']['link'] = $register_summary['Inaccessible Room/locations Summary']['link'];
        }
        return $data;
    }

    public function getDecomissionedRegisterOverallSummary($property_id) {
        $register_summary = $this->getRegisterSummary($property_id, 1 ,route('shineCompliance.property.asbestos',['property_id' => $property_id]));

        $data['ACM Summary']['number'] = $register_summary['All ACM Items']['number'];
        $data['ACM Summary']['link'] = $register_summary['All ACM Items']['link'];

        $data['NoACM Summary']['number'] = $register_summary['No Risk (NoACM) Item Summary']['number'];
        $data['NoACM Summary']['link'] = $register_summary['No Risk (NoACM) Item Summary']['link'];

        $fire_hazard = $this->getHazardRegisterSummary($property_id,ASSESSMENT_FIRE_TYPE,  1 , route('shineCompliance.property.fire',['property_id' => $property_id]));

        $data['Fire Hazard Summary']['number'] = $fire_hazard['All Hazard Risk Count']['number'];
        $data['Fire Hazard Summary']['link'] = $fire_hazard['All Hazard Risk Count']['link'];

        $data['Gas Hazard Summary']['number'] = 0;
        $data['Gas Hazard Summary']['link'] = '#';

        $water_hazard = $this->getHazardRegisterSummary($property_id,ASSESSMENT_WATER_TYPE,  1 , route('shineCompliance.property.water',['property_id' => $property_id]));

        $data['Water Hazard Summary']['number'] = $water_hazard['All Hazard Risk Count']['number'];
        $data['Water Hazard Summary']['link'] = $water_hazard['All Hazard Risk Count']['link'];

        return $data;
    }

    public function getPropertyProject($id) {
        $client_type = \Auth::user()->clients->client_type;
        $client_id = \Auth::user()->client_id;
        $projects = $this->projectRepository->getPropertyProject($id,$client_id,$client_type);
        // missing role
        return is_null($projects) ? [] : $projects;
    }

    public function getClientUsers(){
        return $this->userRepository->getClientUsers();
    }

    public function getPropertySurvey($id, $decommissioned = 0, $client_id = null) {
        return $surveys = $this->surveyRepository->getPropertySurvey($id,$decommissioned,$client_id);
    }

    public function getLinkedPropertyProject($id) {
        return $this->projectRepository->getLinkedPropertyProject($id);
    }

    public function getPropertyByZone($zone_id){
        $property_ids = $this->propertyRepository->getPropertyByZone($zone_id);
        return $property_ids ?? [] ;
    }

    public function getPropertyProjectType($id,$risk_classification_id) {
        $client_type = \Auth::user()->clients->client_type;
        $client_id = \Auth::user()->client_id;
        $projects = $this->projectRepository->getPropertyProjectType($id,$client_id,$client_type,$risk_classification_id);
        // missing role
        return is_null($projects) ? [] : $projects;
    }

    public function getPropertyRegisterPermission() {
        $asbestos = true;
        $fire = true;
        $water = true;
        $overall = true;
        $health_and_safety = true;
        if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_REGISTER_ASBESTOS, JOB_ROLE_ASBESTOS)){
            $asbestos = false;
        }
        if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_REGISTER_FIRE, JOB_ROLE_FIRE)){
            $fire = false;
        }
        if((\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_REGISTER_WATER, JOB_ROLE_WATER)) || !env('WATER_MODULE')){
            $water = false;
        }
        if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_OVERALL)){
            $overall = false;
        }
        if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_REGISTER_HS,JOB_ROLE_H_S)){
            $health_and_safety = false;
        }

        return [
            'asbestos' => $asbestos,
            'fire' => $fire,
            'water' => $water,
            'overall' => $overall,
            'health_and_safety' => $health_and_safety,
        ];
    }

    public function getWarningMessagev3($property) {

        $property_id =  $property->id;

        $red_warnings = [];
        $amber_warnings = [];
        $green_warnings = [];
        $blue_warnings = [];
        $duty_to_manage = false;
        $time = time();

        if ($property->decommissioned == PROPERTY_DECOMMISSION) {
            if ($property->decommissioned_reason == 40) {
                $blue_warnings[] = 'Property No Longer under Management';
            } else {
                $blue_warnings[] = 'Property Demolished';
            }
        }
        //red warning
        // Duty Holder Responsibility
        if (isset($property->propertyType) && !$property->propertyType->isEmpty()){
            $flag = false;
            foreach ($property->propertyType as $p_risk_type){

                // When the User has selected Duty to Manage in the Property Risk Type Dropdown on the Property_ADD and Property_EDIT
                if ($p_risk_type->id == 1) {
                    $red_warnings[] = 'Duty to Manage';
                    $duty_to_manage =  true;
                }

                // When the User has selected Duty to Manage (Partial Responsibility) in the Property Risk Type Dropdown
                if ($p_risk_type->id == 9) {
                    $amber_warnings[] = 'Duty to Manage (Partial Responsibility)';
                }

                // When the User has selected Duty to Manage (Delegated Responsibility) in the Property Risk Type Dropdown
                if ($p_risk_type->id == 10) {
                    $amber_warnings[] = 'Duty to Manage (Delegated Responsibility)';
                }

                // When the User has selected Duty of Care in the Property Risk Type Dropdown
                if ($p_risk_type->id == 11) {
                    $green_warnings[] = 'Duty of Care';
                }

                // When the User has selected Duty of Care (Employees Only) in the Property Risk Type Dropdown
                if ($p_risk_type->id == 12) {
                    $green_warnings[] = 'Duty of Care (Employees Only)';
                }

                // When the User has selected Property Built In or After 2000, No Asbestos Detected in the Property Risk Type Dropdown
                if ($p_risk_type->id == 2) {
                    $green_warnings[] = 'Property Built In or After 2000, No Asbestos Detected';
                }

                // When the User has selected Property Built In or After 2000, No Asbestos Detected in the Property Risk Type Dropdown
                if ($p_risk_type->id == 18) {
                    $red_warnings[] = 'Property Built Pre-2000, Asbestos May Be Present';
                }

            }
        }

        // CHECK ITEM ACM
        $item_acm_register_sql = "SELECT count(id) count
                                FROM `tbl_items`
                                WHERE
                                    property_id = $property_id
                                    AND `state` != 1
                                    AND `decommissioned` = 0
                                LIMIT 1";
        $item_acm_register = \DB::select($item_acm_register_sql);

        // When ACM Count = 0 on a Property
        if (!$item_acm_register[0]->count) {
            $green_warnings[] = 'No Positively Identified or Presumed Asbestos Found';
        }

        // CHECK ITEM ACM Removal
        $repair_and_removal_item_available = \DB::select('select COUNT(DISTINCT i.id) count from tbl_items as i
                                    LEFT JOIN tbl_item_action_recommendation_value v on v.item_id = i.id
                                    WHERE i.decommissioned = 0
                                    and i.survey_id = 0
                                    and i.property_id = '.$property_id.'
                                    and (v.dropdown_data_item_id IN (' . implode(',', ACTION_RECOMMENDATION_LIST_ID) . ')
                                    OR v.dropdown_data_item_parent_id IN
                                    (' . implode(',', ACTION_RECOMMENDATION_LIST_ID) . ' ))');

        // When the ACM Count 1+ and no action/recommendations that are A&R Type = Remedial or Removal Works
        if ($repair_and_removal_item_available[0]->count) {
            $red_warnings[] = 'Asbestos Present within Property - Asbestos Remedial Action Required';
        } elseif($item_acm_register[0]->count and !$repair_and_removal_item_available[0]->count) {
            $amber_warnings[] = 'Asbestos Present within Property - No Remediation Required';
        }

        // CHECK  HIGH RISK ITEM ACM
        $item_acm_high_risk_register_sql = "SELECT count(id) count
                                FROM `tbl_items`
                                WHERE
                                    property_id = $property_id
                                    AND `state` != 1
                                    AND `decommissioned` = 0
                                    AND `survey_id` = 0
                                    and total_mas_risk >= 10
                                LIMIT 1";
        $high_risk_item_acm_register = \DB::select($item_acm_high_risk_register_sql);

        // When Property has a ACM with a High Risk
        if ($high_risk_item_acm_register[0]->count) {
            $red_warnings[] = 'High Risk Asbestos Present within Property';
        }
        // Fire Risk Compliance
        // When the FRA Overall Risk Rating of the latest Fire Risk Assessment is Trivial.
        $trivival = $this->assessmentRepository->getLastestAssessmentRiskRating($property_id,ASSESSMENT_FIRE_TYPE, TRIVIAL_RISK_RATING);
        if ($trivival) {
            $green_warnings[] = 'Property Fire Risk Trivial';
        }
        // When the WRA Overall Risk Rating of the latest Fire Risk Assessment is Tolerable.
        $tolerable = $this->assessmentRepository->getLastestAssessmentRiskRating($property_id,ASSESSMENT_FIRE_TYPE, TOLERABLE_RISK_RATING);
        if ($tolerable) {
            $amber_warnings[] = 'Property Fire Risk Tolerable';
        }
        // When the WRA Overall Risk Rating of the latest Fire Risk Assessment is moderate.
        $moderate = $this->assessmentRepository->getLastestAssessmentRiskRating($property_id,ASSESSMENT_FIRE_TYPE, MODERATE_RISK_RATING);
        if ($moderate) {
            $amber_warnings[] = 'Property Fire Risk Moderate';
        }
        // When the WRA Overall Risk Rating of the latest Fire Risk Assessment is Substantial .
        $substantial  = $this->assessmentRepository->getLastestAssessmentRiskRating($property_id,ASSESSMENT_FIRE_TYPE, SUBSTANTIAL_RISK_RATING);
        if ($substantial ) {
            $red_warnings[] = 'Property Fire Risk Substantial';
        }
        // When the WRA Overall Risk Rating of the latest Fire Risk Assessment is Intolerable .
        $intolerable  = $this->assessmentRepository->getLastestAssessmentRiskRating($property_id,ASSESSMENT_FIRE_TYPE, INTOLERABLE_RISK_RATING);
        if ($intolerable ) {
            $red_warnings[] = 'Property Fire Risk Intolerable';
        }

        // CHECK No fire hazard
        $risk_hazard = $this->hazardRepository->getRegisterHazardRisk($property_id, ASSESSMENT_FIRE_TYPE, 0, 26);
        // When Fire Hazard Count = 0 at the Property
        if (!$risk_hazard) {
            $green_warnings[] = 'No Known Fire Risk Present in Property';
        }

        // CHECK very low fire hazard
        $vlow_risk_hazard = $this->hazardRepository->getRegisterHazardRisk($property_id, ASSESSMENT_FIRE_TYPE, 0, 4);
        // When Fire Hazard Count = 0 at the Property
        if ($vlow_risk_hazard) {
            $green_warnings[] = 'Very Low Priority Fire Risk Present';
        }

        // CHECK low fire hazard
        $low_risk_hazard = $this->hazardRepository->getRegisterHazardRisk($property_id, ASSESSMENT_FIRE_TYPE, 3, 10);
        // When low risk Hazard at the Property
        if ($low_risk_hazard) {
            $green_warnings[] = 'Low Priority Fire Risk Present';
        }

        // CHECK medium fire hazard
        $medium_risk_hazard = $this->hazardRepository->getRegisterHazardRisk($property_id, ASSESSMENT_FIRE_TYPE, 9, 16);
        // When Property has a Fire Hazard with a Medium Risk
        if ($medium_risk_hazard) {
            $amber_warnings[] = 'Medium Priority Fire Risk Present';
        }

        // CHECK high fire hazard
        $high_risk_hazard = $this->hazardRepository->getRegisterHazardRisk($property_id, ASSESSMENT_FIRE_TYPE, 15, 21);
        // When Property has a Fire Hazard with a High Risk
        if ($high_risk_hazard) {
            $red_warnings[] = 'High Priority Fire Risk Present';
        }

        // CHECK high fire hazard
        $vhigh_risk_hazard = $this->hazardRepository->getRegisterHazardRisk($property_id, ASSESSMENT_FIRE_TYPE, 20, 26);
        // When Property has a Fire Hazard with a Very High Risk
        if ($vhigh_risk_hazard) {
            $red_warnings[] = 'Very High Priority Fire Risk Present';
        }

        // Notice of Deficiency Issued – Action Required
        // Enforcement Notice Issued – Urgent Action Required

        // H&S Register Status

        // No Known H&S Risk Present in Property

        // When H&S Hazard Count = 0 at the Property

        // Very Low Priority H&S Risk Present

        // When Property has a H&S Hazard with a Very Low Risk

        // Low Priority H&S Risk Present

        // When Property has a H&S Hazard with a Low Risk

        // Medium Priority H&S Risk Present

        // When Property has a H&S Hazard with a Medium Risk

        // High Priority H&S Risk Present

        // When Property has a H&S Hazard with a High Risk

        // Very High Priority H&S Risk Present

        // When Property has a H&S Hazard with a Very High Risk


        // M&E Compliance
        // Programme missing document
        $missing_programme_doc = \DB::select("SELECT count(cp.id) as count from
                                            compliance_programmes cp
                                            left join compliance_documents cd on cd.programme_id = cp.id
                                            WHERE cp.property_id = $property_id and cp.decommissioned = 0
                                            and cd.id is null and cd.is_reinspected = 1");
        // When a Programme on a Property/Equipment is missing a Service Document linked to it in Documents

        if ($missing_programme_doc[0]->count) {
            $red_warnings[] = 'Missing M&E Programme Service';
        }

        // Programme have overdue document
        $current = time();
        $over_due_programme_doc = \DB::select("SELECT
                                            count(cp.id) as count
                                        FROM
                                            compliance_programmes cp
                                            LEFT JOIN
                                            (SELECT * FROM compliance_documents GROUP BY programme_id ORDER BY date desc) cd
                                            ON cd.programme_id = cp.id
                                        WHERE
                                            cp.decommissioned = 0
                                            and cp.property_id = $property_id
                                            and (cd.date + cp.inspection_period*86000) > $current
                                            ");
        // When a Programme on a Property/Equipment is overdue a Service Document linked to it in Documents
        if ($over_due_programme_doc[0]->count) {
            $red_warnings[] = 'Overdue M&E Programme Service';
        }

        // Water Risk Compliance
        if(env('WATER_MODULE')) {

            // CHECK No water hazard
            $water_risk_hazard = $this->hazardRepository->getRegisterHazardRisk($property_id, ASSESSMENT_WATER_TYPE, 0, 25);
            // When water Hazard Count = 0 at the Property
            if (!$water_risk_hazard) {
                $green_warnings[] = 'No Known Water Risk Present in Property';
            }

            // CHECK very low water hazard
            $vlow_water_risk_hazard = $this->hazardRepository->getRegisterHazardRisk($property_id, ASSESSMENT_WATER_TYPE, 0, 4);
            // When water Hazard Count = 0 at the Property
            if ($vlow_water_risk_hazard) {
                $green_warnings[] = 'Very Low Priority Water Risk Present';
            }

            // CHECK low water hazard
            $low_water_risk_hazard = $this->hazardRepository->getRegisterHazardRisk($property_id, ASSESSMENT_WATER_TYPE, 3, 10);
            // When low risk Hazard at the Property
            if ($low_water_risk_hazard) {
                $green_warnings[] = 'Low Priority Water Risk Present';
            }

            // CHECK medium water hazard
            $medium_water_risk_hazard = $this->hazardRepository->getRegisterHazardRisk($property_id, ASSESSMENT_WATER_TYPE, 9, 16);
            // When Property has a water Hazard with a Medium Risk
            if ($medium_water_risk_hazard) {
                $amber_warnings[] = 'Medium Priority Water Risk Present';
            }

            // CHECK high water hazard
            $high_water_risk_hazard = $this->hazardRepository->getRegisterHazardRisk($property_id, ASSESSMENT_WATER_TYPE, 15, 21);
            // When Property has a water Hazard with a High Risk
            if ($high_water_risk_hazard) {
                $red_warnings[] = 'High Priority Water Risk Present';
            }

            // CHECK high water hazard
            $vhigh_water_risk_hazard = $this->hazardRepository->getRegisterHazardRisk($property_id, ASSESSMENT_WATER_TYPE, 20, 26);
            // When Property has a water Hazard with a Very High Risk
            if ($vhigh_water_risk_hazard) {
                $red_warnings[] = 'Very High Priority Water Risk Present';
            }
        }

        // Asbestos Survey Requirements
        $sql_not_complete_management_survey = "SELECT count(id) count
                                    FROM `tbl_survey`
                                    WHERE  `property_id` = $property_id
                                    AND `decommissioned` = 0 and survey_type = 1 and `status` not in (5) ";
        $not_complete_management_survey = \DB::select($sql_not_complete_management_survey);

        // completed management survey
        $sql_complete_management_survey = "SELECT count(id) count
                                    FROM `tbl_survey`
                                    WHERE  `property_id` = $property_id
                                    AND `decommissioned` = 0 and survey_type = 1 and `status` = 5 ";

        $complete_management_survey = \DB::select($sql_complete_management_survey);

        // When a SHINE Asbestos Management Survey has not been completed on the Property.
        if (!$complete_management_survey[0]->count) {
            $red_warnings[] = 'Asbestos Management Not Completed; Asbestos must be Presumed to Present';
        }

       // CHECK INACCESSIBLE LOCATION AVAILABLE IN REGISTER
        $location_in_access_sql = "SELECT count(id) count
                                        FROM `tbl_location`
                                        WHERE `survey_id` = 0 and assess_id = 0
                                            AND  `property_id` =  $property_id
                                            AND `state` = 0
                                            AND `decommissioned` = 0
                                        LIMIT 1";
        $location_in_accessible = \DB::select($location_in_access_sql);

        // CHECK ITEM ACM
        $item_acm_register_sql = "SELECT count(id) count
                                FROM `tbl_items`
                                WHERE
                                    property_id = $property_id
                                    AND survey_id = 0
                                    AND `state` != 1
                                    AND `decommissioned` = 0
                                LIMIT 1";
        $item_acm_register = \DB::select($item_acm_register_sql);

        // Asbestos Survey Requirements
        $sql_complete_refurbish_survey = "SELECT count(id) count
                                    FROM `tbl_survey`
                                    WHERE  `property_id` = $property_id
                                    AND `decommissioned` = 0 and survey_type = 2 and `status` = 5 ";
        $complete_refurbish_survey = \DB::select($sql_complete_refurbish_survey);
        // When a SHINE Asbestos Refurbishment Survey has been completed but there is not an Asbestos Management Survey completed on the Property when there is no inaccessible Room/locations or ACMs at the Property.
        if (($complete_refurbish_survey[0]->count) and !$complete_management_survey[0]->count and !$item_acm_register[0]->count and !$location_in_accessible[0]->count) {
            $red_warnings[] = 'Targeted Refurbishment Survey only; Asbestos must be Presumed to be Present in the Remaining Unsurveyed Room/locations';
        }

        // When a SHINE Asbestos Refurbishment Survey has been completed but there is not an Asbestos Management Survey completed on the Property when there is inaccessible Room/locations.
        if (($complete_refurbish_survey[0]->count) and !$complete_management_survey[0]->count and $location_in_accessible[0]->count) {
            $red_warnings[] = 'Targeted Refurbishment Survey only - Not Assessed and Inaccessible Room/locations; Asbestos must be Presumed to be Present';
        }

        // When a SHINE Asbestos Refurbishment Survey has been completed but there is not an Asbestos Management Survey completed on the Property when there is ACMs.
        if (($complete_refurbish_survey[0]->count) and !$complete_management_survey[0]->count and $item_acm_register[0]->count) {
            $red_warnings[] = 'Targeted Refurbishment Survey Conducted only; Asbestos Present within the Property';
        }

        // ASBESTOS SURVEY REQUIREMENTS

        // completed partial survey
        $sql_complete_partial_survey = "SELECT count(id) count
                                    FROM `tbl_survey`
                                    WHERE  `property_id` = $property_id
                                    AND `decommissioned` = 0 and survey_type = 5 and `status` = 5 ";

        $complete_partial_survey = \DB::select($sql_complete_partial_survey);
        // When a SHINE Asbestos Management Survey - Partial has been completed on the Property.
        if (($complete_partial_survey[0]->count)) {
            $amber_warnings[] = 'Partial Management Survey Completed; Not Assessed and Inaccessible Room/locations; Asbestos must be Presumed to be Present';
        }

        // When a SHINE Asbestos Management Survey has been completed and there is Inaccessible Room/locations at the Property.
        if (($complete_management_survey[0]->count)  and $location_in_accessible[0]->count) {
            $amber_warnings[] = 'Management Survey conducted; Inaccessible Room/locations; Asbestos must be Presumed to be Present';
        }

        // When a SHINE Asbestos Management Survey has been completed and there is ACMs at the Property.
        if (($complete_management_survey[0]->count)  and $item_acm_register[0]->count) {
            $red_warnings[] = 'Management Survey conducted; Asbestos Present within Property';
        }

        // When a SHINE Asbestos Management Survey has been completed on the Property when there is no Inaccessible Room/locations and ACM Count = 0

        if (($complete_management_survey[0]->count)  and !$location_in_accessible[0]->count and !$item_acm_register[0]->count) {
            $green_warnings[] = 'Management Survey conducted; No Presumed/Identified Asbestos Found; Further Inspection to be Undertaken Prior to Refurbishment Works';
        }

        // FIRE RISK ASSESSMENT REQUIREMENTS
        // CHECK Fire Risk Assessment Type 1 COMPLETED
        $sql_complete_fire_assess_type_1 = "SELECT count(id) count
                                    FROM `cp_assessments`
                                    WHERE  `property_id` = '" . $property_id . "'
                                    AND `decommissioned` = 0 and type = ".ASSESS_TYPE_FIRE_RISK_TYPE_1." and classification = ". ASSESSMENT_FIRE_TYPE .
                                    " and `status` = 5 ";
        $complete_fire_assess_type_1 = \DB::select($sql_complete_fire_assess_type_1);

        // CHECK Fire Risk Assessment type 1 OVERDUE
        $sql_overdue_fire_assess_type_1 = "SELECT count(id) count
                                    FROM `cp_assessments`
                                    WHERE  `property_id` = '" . $property_id . "'
                                    AND `decommissioned` = 0 and type = ".ASSESS_TYPE_FIRE_RISK_TYPE_1." and classification = ". ASSESSMENT_FIRE_TYPE .
                                    " and `status` != 5 and due_date < $time";
        $overdue_fire_assess_type_1 = \DB::select($sql_overdue_fire_assess_type_1);

        // CHECK Fire Risk Assessment Type 2 COMPLETED
        $sql_complete_fire_assess_type_2 = "SELECT count(id) count
                                    FROM `cp_assessments`
                                    WHERE  `property_id` = '" . $property_id . "'
                                    AND `decommissioned` = 0 and type = ".ASSESS_TYPE_FIRE_RISK_TYPE_2." and classification = ". ASSESSMENT_FIRE_TYPE .
                                    " and `status` = 5 ";
        $complete_fire_assess_type_2 = \DB::select($sql_complete_fire_assess_type_2);

        // CHECK Fire Risk Assessment Type 3 COMPLETED
        $sql_complete_fire_assess_type_3 = "SELECT count(id) count
                                    FROM `cp_assessments`
                                    WHERE  `property_id` = '" . $property_id . "'
                                    AND `decommissioned` = 0 and type = ".ASSESS_TYPE_FIRE_RISK_TYPE_3." and classification = ". ASSESSMENT_FIRE_TYPE .
                                    " and `status` = 5 ";
        $complete_fire_assess_type_3 = \DB::select($sql_complete_fire_assess_type_3);

        // CHECK Fire Risk Assessment Type 4 COMPLETED
        $sql_complete_fire_assess_type_4 = "SELECT count(id) count
                                    FROM `cp_assessments`
                                    WHERE  `property_id` = '" . $property_id . "'
                                    AND `decommissioned` = 0 and type = ".ASSESS_TYPE_FIRE_RISK_TYPE_4." and classification = ". ASSESSMENT_FIRE_TYPE .
                                    " and `status` = 5 ";
        $complete_fire_assess_type_4 = \DB::select($sql_complete_fire_assess_type_4);

        if ($duty_to_manage) {
            // When there is not a Fire Risk Assessment (Type 1) completed on a Property which has a Property Risk Type of Duty to Manage.
            if (!$complete_fire_assess_type_1[0]->count) {
                $red_warnings[] = 'Fire Risk Assessment (Type 1) Missing';
            } else {
                // When there is a Fire Risk Assessment completed on a Property which has a Property Risk Type of Duty to Manage.
                $green_warnings[] = 'Fire Risk Assessment (Type 1) Completed';
            }
            // When there is not a Fire Risk Assessment (Type 1) overdue on a Property which has a Property Risk Type of Duty to Manage.
            if ($overdue_fire_assess_type_1[0]->count) {
                $amber_warnings[] = 'Fire Risk Assessment (Type 1) Overdue';
            }
            // When there is a Fire Risk Assessment (Type 2) completed on a Property which has a Property Risk Type of Duty to Manage.
            if ($complete_fire_assess_type_2[0]->count) {
                $green_warnings[] = 'Fire Risk Assessment (Type 2) Completed';
            }
            // When there is a Fire Risk Assessment (Type 3) completed on a Property which has a Property Risk Type of Duty to Manage.
            if ($complete_fire_assess_type_3[0]->count) {
                $green_warnings[] = 'Fire Risk Assessment (Type 3) Completed';
            }
            // When there is a Fire Risk Assessment (Type 4) completed on a Property which has a Property Risk Type of Duty to Manage.
            if ($complete_fire_assess_type_4[0]->count) {
                $green_warnings[] = 'Fire Risk Assessment (Type 4) Completed';
            }
        }

        if(env('WATER_MODULE')) {
            // WATER RISK ASSESSMENT REQUIREMENTS
            // CHECK Fire Risk Assessment type 1 NOT COMPLETED
            $sql_complete_water_assess = "SELECT count(id) count
                                        FROM `cp_assessments`
                                        WHERE  `property_id` = '" . $property_id . "'
                                        AND `decommissioned` = 0 and classification = 4 and `status` = 5";
            $complete_water_assess = \DB::select($sql_complete_water_assess);


            if ($duty_to_manage) {
                // When there is not a Water Risk Assessment completed on a Property which has a Property Risk Type of Duty to Manage.
                if (!$complete_water_assess[0]->count) {
                    $red_warnings[] = 'Water Risk Assessment Missing';
                } else {
                    // When there is a Water Risk Assessment completed on a Property which has a Property Risk Type of Duty to Manage.
                    $green_warnings[] = 'Water Risk Assessment Completed';
                }
            }

        }

        // PROJECT STATUS
        $asbestos_project_inprogress = $this->projectRepository->getProjectInprogressByTpe(ASBESTOS_CLASSIFICATION, $property_id);
        $fire_project_inprogress = $this->projectRepository->getProjectInprogressByTpe(FIRE_CLASSIFICATION, $property_id);
        $water_project_inprogress = $this->projectRepository->getProjectInprogressByTpe(WATER_CLASSIFICATION, $property_id);
        $hs_project_inprogress = $this->projectRepository->getProjectInprogressByTpe(0, $property_id);
        $me_project_inprogress = $this->projectRepository->getProjectInprogressByTpe(0, $property_id);

        // When a live Asbestos Project on the Property i.e. Tendering in Progress or Technical in Progress
        if ($asbestos_project_inprogress) {
            $red_warnings[] = 'Asbestos Project in Progress';
        }

        // When a live Fire Project on the Property i.e. Tendering in Progress or Technical in Progress
        if ($fire_project_inprogress) {
            $red_warnings[] = 'Fire Project in Progress';
        }

        if(env('WATER_MODULE')) {
            // When a live Water Project on the Property i.e. Tendering in Progress or Technical in Progress
            if ($water_project_inprogress) {
                $red_warnings[] = 'Water Project in Progress';
            }
        }

        // When a live H&S Project on the Property i.e. Tendering in Progress or Technical in Progress
        if ($hs_project_inprogress) {
            $red_warnings[] = 'H&S Project in Progress';
        }

        // When a live M&E Project on the Property i.e. Tendering in Progress or Technical in Progress
        if ($me_project_inprogress) {
            $red_warnings[] = 'M&E Project in Progress';
        }

        // FURTHER INVESTIGATION
        //Inaccessible Void
        $inaccess_void = \DB::select("SELECT COUNT(tbl_location.id) count
                                    FROM tbl_location LEFT JOIN tbl_location_void lcv ON tbl_location.id = lcv.location_id
                            WHERE decommissioned = 0
                            and survey_id = 0
                            and assess_id = 0
                            AND ( lcv.ceiling like '1108%'
                            OR lcv.floor LIKE '1453%'
                            OR lcv.cavities LIKE '1216%'
                            OR lcv.risers LIKE '1280%'
                            OR lcv.ducting LIKE '1344%'
                            OR lcv.boxing LIKE '1733%'
                            OR lcv.pipework LIKE '1606%'
                            )
                            and property_id = $property_id");

        // When there is an Inaccessible Void in the Property Register
        if ($inaccess_void[0]->count) {
            $amber_warnings[] = 'Inaccessible Voids; Asbestos must be Presumed to be Present';
        }

        // When there is an Inaccessible Room/location in the Property Register
        if ($location_in_accessible[0]->count) {
            $amber_warnings[] = 'Inaccessible Room/locations; Asbestos must be Presumed to be Present';
        }
        // Inaccessible assembly
        $inaccess_assembly = \DB::select("SELECT count(id) count from cp_assembly_points where accessibility = 0
                                            and property_id = $property_id
                                            and assess_id = 0
                                            and decommissioned = 0 ");
        if ($inaccess_assembly[0]->count) {
            $red_warnings[] = 'Inaccessible Assembly Point';
        }

        // Inaccessible Fire Exit
        $inaccess_fire_exit = \DB::select("SELECT count(id) count from cp_fire_exits where accessibility = 0
                                            and property_id = $property_id
                                            and assess_id = 0
                                            and decommissioned = 0 ");
        if ($inaccess_fire_exit[0]->count) {
            $red_warnings[] = 'Inaccessible Fire Exit';
        }

        // Inaccessible Equipment
        $inaccess_equipment = \DB::select("SELECT count(id) count from cp_equipments where state = 0
                                            and property_id = $property_id
                                            and assess_id = 0
                                            and decommissioned = 0 ");
        if ($inaccess_equipment[0]->count) {
            $red_warnings[] = 'Inaccessible Equipment';
        }

        // Inaccessible Vehicle Parking
        $inaccess_vehicle = \DB::select("SELECT count(id) count from cp_vehicle_parking where accessibility = 0
                                            and property_id = $property_id
                                            and assess_id = 0
                                            and decommissioned = 0 ");

        if ($inaccess_vehicle[0]->count) {
            $red_warnings[] = 'Inaccessible Vehicle Parking';
        }

        return [
            'red_warnings' => $red_warnings,
            'amber_warnings' => $amber_warnings,
            'green_warnings' => $green_warnings,
            'blue_warnings' => $blue_warnings,
        ];
    }

    public function getSubProperty($property_id, $query, $limit){
        return $this->propertyRepository->getSubProperty($property_id, $query, $limit);
    }

}
