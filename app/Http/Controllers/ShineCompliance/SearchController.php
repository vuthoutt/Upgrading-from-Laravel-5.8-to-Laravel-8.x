<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Repositories\AirTestCertificateRepository;
use App\Repositories\AuditRepository;
use App\Repositories\ClientRepository;
use App\Repositories\DocumentRepository;
use App\Repositories\HistoricDocRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\SampleCertificateRepository;
use App\Repositories\ShineCompliance\ComplianceDocumentRepository;
use App\Repositories\ShineCompliance\ComplianceDocumentTypeRepository;
use App\Repositories\ShineCompliance\PropertyRepository;
use App\Repositories\ShineCompliance\UserRepository;
use App\Repositories\ShineCompliance\ZoneRepository;
use App\Repositories\ShineCompliance\AreaRepository;
use App\Repositories\ShineCompliance\LocationRepository;
use App\Repositories\ShineCompliance\ItemRepository;
use App\Repositories\ShineCompliance\ComplianceSystemRepository;
use App\Repositories\ShineCompliance\ComplianceEquipmentRepository;
use App\Repositories\ShineCompliance\ComplianceProgrammeRepository;
use App\Repositories\SitePlanDocumentRepository;
use App\Repositories\SummaryPdfRepository;
use App\Repositories\SurveyRepository;
use App\Services\Certificate\CrtAirTestGroupService;
use App\Services\Certificate\CrtAirTestService;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository,
                                ZoneRepository $zoneRepository,
                                PropertyRepository $propertyRepository,
                                AreaRepository $areaRepository,
                                LocationRepository $locationRepository,
                                ItemRepository $itemRepository,
                                HistoricDocRepository $historicDocRepository,
                                ComplianceProgrammeRepository $programmeRepository,
                                ComplianceEquipmentRepository $equipmentRepository,
                                ComplianceSystemRepository $systemRepository,
                                ClientRepository $clientRepository,
                                SitePlanDocumentRepository $sitePlanDocumentRepository,
                                ComplianceDocumentRepository $complianceDocumentRepository,
                                SummaryPdfRepository $summaryPdfRepository,
                                DocumentRepository $documentRepository,
                                ProjectRepository $projectRepository,
                                AirTestCertificateRepository $airTestCertificateRepository,
                                SampleCertificateRepository $sampleCertificateRepository,
                                SurveyRepository $surveyRepository,
                                AuditRepository $auditRepository,
                                CrtAirTestGroupService $crtAirTestGroupService,
                                CrtAirTestService $crtAirTestService)
    {
        $this->userRepository = $userRepository;
        $this->zoneRepository = $zoneRepository;
        $this->propertyRepository = $propertyRepository;
        $this->areaRepository = $areaRepository;
        $this->locationRepository = $locationRepository;
        $this->itemRepository = $itemRepository;
        $this->programmeRepository = $programmeRepository;
        $this->systemRepository = $systemRepository;
        $this->equipmentRepository = $equipmentRepository;
        $this->clientRepository = $clientRepository;
        $this->sitePlanDocumentRepository = $sitePlanDocumentRepository;
        $this->complianceDocumentRepository = $complianceDocumentRepository;
        $this->summaryPdfRepository = $summaryPdfRepository;
        $this->documentRepository = $documentRepository;
        $this->projectRepository = $projectRepository;
        $this->airTestCertificateRepository = $airTestCertificateRepository;
        $this->sampleCertificateRepository = $sampleCertificateRepository;
        $this->surveyRepository = $surveyRepository;
        $this->historicDocRepository = $historicDocRepository;
        $this->auditRepository = $auditRepository;
        $this->crtAirTestGroupService = $crtAirTestGroupService;
        $this->crtAirTestService = $crtAirTestService;

    }

    public function search(Request $request){
        $q = $request->q;
        $options = explode(",",$request->options);
        $result = [];
        $link = "";
        $user = Auth::user();
        if(isset($q) && count($options)){
            foreach ($options as $option){
                switch ($option){
                    case "organisation":
                        $organisations = $this->clientRepository->searchClient($q);
                        if(!$organisations->isEmpty()){
                            foreach ($organisations as $organisation){
                                if($user->client_id == $organisation->id){
                                    // same client id then go to my organisation page
                                    $link = route('my_organisation', ['client_id' => $organisation->id]);
                                } else {
                                    // dif client id then go to contractor page
                                    $link = route('contractor', ['client_id' => $organisation->id]);
                                }
                                $result[] =[
                                    'label' => $organisation->name,
                                    'value' => $link,
                                    'desc' => $organisation->reference
                                ];
                            }
                        }
                        break;
                    case "user":
                        $users = $this->userRepository->searchUser($q);
                        if(count($users)){
                            foreach ($users as $us){
                                $link = route('shineCompliance.profile-shineCompliance',['id'=>$us->id]);
                                $result[] =[
                                    'label' => $us->username,
                                    'value' => $link,
                                    'desc' => "ID" .$us->id
                                ];
                            }
                        }
                        break;
                    case "group":
                        $zones = $this->zoneRepository->searchZone($q, $user);
                        if(!$zones->isEmpty()){
                            foreach ($zones as $zone){
                                if($user->is_site_operative == 0){
                                    $link = route('shineCompliance.zone.details',['zone_id'=>$zone->id, 'client_id' => $zone->client_id]);
                                } else {
                                    $link = route('zone', ['client_id' => 1,'zone_id' => $zone->id]);
                                }
                                $result[] =[
                                    'label' => $zone->zone_name,
                                    'value' => $link,
                                    'desc' => $zone->reference
                                ];
                            }
                        }
                        break;
                    case "property":
                        $propertys = $this->propertyRepository->searchProperty($q);
                        if(count($propertys)){
                            foreach ($propertys as $property){
                                if($user->is_site_operative == 0){
                                    $link = route('shineCompliance.property.property_detail',['id'=>$property->id]);
                                } else {
                                    $link = route('property_detail',['id'=>$property->id, 'section' => SECTION_DEFAULT, 'active_tab' => '#detail']);
                                }
                                $result[] =[
                                    'label' => $property->name . " (" . $property->reference . ") ",
                                    'value' => $link,
                                    'desc' => "Postcode: " . $property->postcode . ", UPRN: " . $property->property_reference
                                ];
                            }
                        }
                        break;
                    case "propertyReference":
                        $propertys = $this->propertyRepository->searchProperty($q, 1);
                        if(count($propertys)){
                            foreach ($propertys as $property){
                                if($user->is_site_operative == 0){
                                    $link = route('shineCompliance.property.property_detail',['id'=>$property->id]);
                                } else {
                                    $link = route('property_detail',['id'=>$property->id, 'section' => SECTION_DEFAULT, 'active_tab' => '#detail']);
                                }
                                $result[] =[
                                    'label' => $property->name . " (" . $property->reference . ") ",
                                    'value' => $link,
                                    'desc' => "Postcode: " . $property->postcode . ", UPRN: " . $property->property_reference
                                ];
                            }
                        }
                        break;
                    case "pblock":
                        $propertys = $this->propertyRepository->searchProperty($q, 2);
                        if(count($propertys)){
                            foreach ($propertys as $property){
                                if($user->is_site_operative == 0){
                                    $link = route('shineCompliance.property.property_detail',['id'=>$property->id]);
                                } else {
                                    $link = route('property_detail',['id'=>$property->id, 'section' => SECTION_DEFAULT, 'active_tab' => '#detail']);
                                }
                                $result[] =[
                                    'label' => $property->name . " (" . $property->reference . ") ",
                                    'value' => $link,
                                    'desc' => "Postcode: " . $property->postcode . ", UPRN: " . $property->property_reference
                                ];
                            }
                        }
                        break;
                    case "area":
                        $areas = $this->areaRepository->searchArea($q);
                        if(!$areas->isEmpty()){
                            foreach ($areas as $area){
                                if($user->is_site_operative == 0){
                                    $link = route('shineCompliance.property.area_detail',['property_id' => $area->property_id, 'area' => $area->id] );
                                } else {
                                    $link = route('property.operative.detail',['id' => $area->property_id,'section' => SECTION_AREA_FLOORS_SUMMARY, 'area' => $area->id] );
                                }
                                $result[] =[
                                    'label' => $area->area_reference,
                                    'value' => $link,
                                    'desc' => $area->reference
                                ];
                            }
                        }
                        break;
                    case "room":
                        $locations = $this->locationRepository->searchLocation($q);
                        if(!$locations->isEmpty()){
                            foreach ($locations as $location){
                                if($user->is_site_operative == 0){
                                    $link = route('shineCompliance.location.detail',['id' => $location->id] );
                                } else {
                                    $link = route('property.operative.detail',['id' => $location->property_id,'section' => SECTION_ROOM_LOCATION_SUMMARY, 'location' => $location->id] );
                                }
                                $result[] =[
                                    'label' => $location->location_reference,
                                    'value' => $link,
                                    'desc' => $location->reference
                                ];
                            }
                        }
                        break;

                    case "item":
                        $items = $this->itemRepository->searchItem($q);
                        if(!$items->isEmpty()){
                            foreach ($items as $item){
                                if($user->is_site_operative == 0){
                                    $link = route('shineCompliance.item.detail',['id' => $item->id] );
                                } else {
                                    $link = route('item.index', ['id' => $item->id]);
                                }
                                $result[] =[
                                    'label' => $item->name,
                                    'value' => $link,
                                    'desc' => $item->reference
                                ];
                            }
                        }
                        break;
                    case "PPlan":
                        $pplans = $this->sitePlanDocumentRepository->searchPlan($q);
                        if(!$pplans->isEmpty()){
                            foreach ($pplans as $pplan){
                                $link = route('property_detail',['id' => $pplan->property_id, 'section' => SECTION_DEFAULT, 'active_tab' => '#detail'] );
                                $result[] =[
                                    'label' => trim($pplan->plan_reference) ? $pplan->plan_reference : ' ',
                                    'value' => $link,
                                    'desc' => $pplan->reference
                                ];
                            }
                        }
                        break;
                    case "historical":
                        $historicals = $this->historicDocRepository->searchHistoric($q);
                        if(!$historicals->isEmpty()){
                            foreach ($historicals as $historical){
                                $link = route('property_detail',['id' => $historical->property_id, 'section' => SECTION_DEFAULT, 'active_tab' => '#historical-data'] );
                                $result[] =[
                                    'label' => $historical->name,
                                    'value' => $link,
                                    'desc' => $historical->reference
                                ];
                            }
                        }
                        break;
                    case "MSsurvey":
                        $surveys = $this->surveyRepository->searchSurvey($q, 1);
                        if(count($surveys)){
                            foreach ($surveys as $survey){
                                $link = route('property.surveys',['id' => $survey->id, 'section' => SECTION_DEFAULT, 'active_tab' => '#Details'] );
                                $result[] =[
                                    'label' => $survey->reference . " - " . $survey->property_name,
                                    'value' => $link,
                                    'desc' => ""
                                ];
                            }
                        }
                        break;
                    case "RRsurvey":
                        $surveys = $this->surveyRepository->searchSurvey($q, 3);
                        if(count($surveys)){
                            foreach ($surveys as $survey){
                                $link = route('property.surveys',['id' => $survey->id, 'section' => SECTION_DEFAULT, 'active_tab' => '#Details'] );
                                $result[] =[
                                    'label' => $survey->reference . " - " . $survey->property_name,
                                    'value' => $link,
                                    'desc' => ""
                                ];
                            }
                        }
                        break;
                    case "FSsurvey":
                        $surveys = $this->surveyRepository->searchSurvey($q, 2);
                        if(count($surveys)){
                            foreach ($surveys as $survey){
                                $link = route('property.surveys',['id' => $survey->id, 'section' => SECTION_DEFAULT, 'active_tab' => '#Details'] );
                                $result[] =[
                                    'label' => $survey->reference . " - " . $survey->property_name,
                                    'value' => $link,
                                    'desc' => ""
                                ];
                            }
                        }
                        break;
                    case "DSsurvey":
                        $surveys = $this->surveyRepository->searchSurvey($q, 4);
                        if(count($surveys)){
                            foreach ($surveys as $survey){
                                $link = route('property.surveys',['id' => $survey->id, 'section' => SECTION_DEFAULT, 'active_tab' => '#Details'] );
                                $result[] =[
                                    'label' => $survey->reference . " - " . $survey->property_name,
                                    'value' => $link,
                                    'desc' => ""
                                ];
                            }
                        }
                        break;

                    case "BSsurvey":
                        $surveys = $this->surveyRepository->searchSurvey($q, SAMPLE_SURVEY);
                        if(count($surveys)){
                            foreach ($surveys as $survey){
                                $link = route('property.surveys',['id' => $survey->id, 'section' => SECTION_DEFAULT, 'active_tab' => '#Details'] );
                                $result[] =[
                                    'label' => $survey->reference . " - " . $survey->property_name,
                                    'value' => $link,
                                    'desc' => ""
                                ];
                            }
                        }
                        break;

                    case "sample":
                        // there is no search for sample
                        break;
                    case "samplecertificate":
                        $sample_certificates = $this->sampleCertificateRepository->searchSampleCertificate($q);
                        if(!$sample_certificates->isEmpty()){
                            foreach ($sample_certificates as $sample_certificate){
                                $link = route('property.surveys', ['survey_id' => $sample_certificate->survey_id, 'section' => SECTION_DEFAULT, 'active_tab' => '#Samples']);
                                $result[] =[
                                    'label' => $sample_certificate->sample_reference,
                                    'value' => $link,
                                    'desc' => $sample_certificate->reference
                                ];
                            }
                        }
                        break;
                    case "airtestcertificate":
                        $air_test_certificates = $this->airTestCertificateRepository->searchAirTestCertificate($q);
                        if(!$air_test_certificates->isEmpty()){
                            foreach ($air_test_certificates as $air_test_certificate){
                                $link = route('property.surveys', ['survey_id' => $air_test_certificate->survey_id, 'section' => SECTION_DEFAULT, 'active_tab' => '#air-test']);
                                $result[] =[
                                    'label' => $air_test_certificate->air_test_reference,
                                    'value' => $link,
                                    'desc' => $air_test_certificate->reference
                                ];
                            }
                        }
                        break;
                    case "SPlan":
                        $pplans = $this->sitePlanDocumentRepository->searchPlan($q, 1);
                        if(!$pplans->isEmpty()){
                            foreach ($pplans as $pplan){
                                $link = route('property.surveys', ['survey_id' => $pplan->survey_id, 'section' => SECTION_DEFAULT, 'active_tab' => '#plans']);
                                $result[] =[
                                    'label' => trim($pplan->plan_reference) ? $pplan->plan_reference : ' ',
                                    'value' => $link,
                                    'desc' => $pplan->reference
                                ];
                            }
                        }
                        break;
                    case "project":
                        $projects = $this->projectRepository->searchProject($q);
                        if(!$projects->isEmpty()){
                            foreach ($projects as $project){
                                $link = route('project.index', ['project_id' => $project->id]);
                                $result[] =[
                                    'label' => $project->title,
                                    'value' => $link,
                                    'desc' => $project->reference
                                ];
                            }
                        }
                        break;
                    case "TDdocument":
                        $documents = $this->documentRepository->searchDocument($q, 4);
                        if(!$documents->isEmpty()){
                            foreach ($documents as $document){
                                $link = route('project.index', ['project_id' => $document->project_id]);
                                $result[] =[
                                    'label' => $document->name,
                                    'value' => $link,
                                    'desc' => $document->reference
                                ];
                            }
                        }
                        break;
                    case "ODdocument":
                        $documents = $this->documentRepository->searchDocument($q, 5);
                        if(!$documents->isEmpty()){
                            foreach ($documents as $document){
                                $link = route('project.index', ['project_id' => $document->project_id]);
                                $result[] =[
                                    'label' => $document->name,
                                    'value' => $link,
                                    'desc' => $document->reference
                                ];
                            }
                        }
                        break;
                    case "PDdocument":
                        $documents = $this->documentRepository->searchDocument($q, 1);
                        if(!$documents->isEmpty()){
                            foreach ($documents as $document){
                                $link = route('project.index', ['project_id' => $document->project_id]);
                                $result[] =[
                                    'label' => $document->name,
                                    'value' => $link,
                                    'desc' => $document->reference
                                ];
                            }
                        }
                        break;
                    case "SDdocument":
                        $documents = $this->documentRepository->searchDocument($q, 2);
                        if(!$documents->isEmpty()){
                            foreach ($documents as $document){
                                $link = route('project.index', ['project_id' => $document->project_id]);
                                $result[] =[
                                    'label' => $document->name,
                                    'value' => $link,
                                    'desc' => $document->reference
                                ];
                            }
                        }
                        break;
                    case "RDdocument":
                        $documents = $this->documentRepository->searchDocument($q, 3);
                        if(!$documents->isEmpty()){
                            foreach ($documents as $document){
                                $link = route('project.index', ['project_id' => $document->project_id]);
                                $result[] =[
                                    'label' => $document->name,
                                    'value' => $link,
                                    'desc' => $document->reference
                                ];
                            }
                        }
                        break;
                    case "GDdocument":
                        $documents = $this->documentRepository->searchDocument($q, 7);
                        $result1 = [];
                        $result2 = [];
                        if(!$documents->isEmpty()){
                            foreach ($documents as $document){
                                $link = route('project.index', ['project_id' => $document->project_id]);
                                $result1[] =[
                                    'label' => $document->name,
                                    'value' => $link,
                                    'desc' => $document->reference
                                ];
                            }
                        }
                        $gskdocuments = $this->documentRepository->searchGSKDocument($q);
                        if(!$gskdocuments->isEmpty()){
                            foreach ($gskdocuments as $document){
                                $link = route('zone.group', ['zone_id' => $document->zone_id,'section' => SECTION_DEFAULT]);
                                $result2[] =[
                                    'label' => $document->name,
                                    'value' => $link,
                                    'desc' => $document->reference
                                ];
                            }
                        }
                        $result = array_merge($result1, $result2);
                        break;
                    case "summaries":
                        $summaries = $this->summaryPdfRepository->searchSummary($q);
                        if(!$summaries->isEmpty()){
                            foreach ($summaries as $summary){
                                $link = route('view_summary', ['id'=>$summary->id,'type' => $summary->type]);
                                $result[] =[
                                    'label' => $summary->reference ?? $summary->file_name,
                                    'value' => $link,
                                    'desc' => ""
                                ];
                            }
                        }
                        break;
                    case "DProperty":
                        $propertyDeco = $this->propertyRepository->searchProperty($q, 3);
                        if(count($propertyDeco)){
                            foreach ($propertyDeco as $property){
                                $link = route('property_detail',['id'=>$property->id, 'section' => SECTION_DEFAULT, 'active_tab' => '#detail']);
                                $result[] =[
                                    'label' => $property->name . " (" . $property->reference . ") ",
                                    'value' => $link,
                                    'desc' => "Postcode: " . $property->postcode . ", UPRN: " . $property->property_reference
                                ];
                            }
                        }
                        break;
                    case "audits":
                        $audits = $this->auditRepository->searchAudit($q);
                        if(count($audits)){
                            foreach ($audits as $au){
                                $link = route('au.details',['id'=>$au->id]);
                                $result[] =[
                                    'label' => $au->reference,
                                    'value' => $link,
                                    'desc' => ""
                                ];
                            }
                        }
                        break;

                    case 'crtAirTestGroup':
                        $crtAirTestGroups = $this->crtAirTestGroupService->searchCrtAirTestGroup($q);
                        if (!$crtAirTestGroups->isEmpty()) {
                            foreach ($crtAirTestGroups as $group) {
                                $link = route('certificate.group.detail', ['groupId'=>$group->id]);
                                $result[] =[
                                    'label' => $group->reference,
                                    'value' => $link,
                                    'desc' => ""
                                ];
                            }
                        }
                        break;

                    case 'crtAirTest':
                        $crtAirTest = $this->crtAirTestService->searchCrtAirTest($q);
                        if (!$crtAirTest->isEmpty()) {
                            foreach ($crtAirTest as $cert) {
                                $link = route('certificate.detail', ['id' => $cert->id]);
                                $result[] = [
                                    'label' => $cert->reference,
                                    'value' => $link,
                                    'desc' => ""
                                ];
                            }
                        }
                        break;
                    case "system":
                        $items = $this->systemRepository->searchSystem($q);
                        if(!$items->isEmpty()){
                            foreach ($items as $item){
                                $link = route('shineCompliance.systems.detail',['id' => $item->id] );
                                $result[] =[
                                    'label' => $item->name,
                                    'value' => $link,
                                    'desc' => $item->reference
                                ];
                            }
                        }
                        break;
                    case "programme":
                        $items = $this->programmeRepository->searchProgramme($q);
                        if(!$items->isEmpty()){
                            foreach ($items as $item){
                                $link = route('shineCompliance.programme.detail',['id' => $item->id] );
                                $result[] =[
                                    'label' => $item->name,
                                    'value' => $link,
                                    'desc' => $item->reference
                                ];
                            }
                        }
                        break;
                    case "equipment":
                        $items = $this->equipmentRepository->searchEquipment($q);
                        if(!$items->isEmpty()){
                            foreach ($items as $item){
                                $link = route('shineCompliance.equipment.detail',['id' => $item->id] );
                                $result[] =[
                                    'label' => $item->name,
                                    'value' => $link,
                                    'desc' => $item->reference
                                ];
                            }
                        }
                        break;
                    case "incident":
                        $items = $this->incidentReportRepository->searchIncidentReport($q);
                        if(!$items->isEmpty()){
                            foreach ($items as $item){
                                $link = route('shineCompliance.incident_reporting.incident_Report',['incident_id' => $item->id] );
                                $result[] =[
                                    'label' => $item->reference,
                                    'value' => $link,
                                    'desc' => "(" . $item->incidentType->description . ")"
                                ];
                            }
                        }
                        break;
                      case "FDdocument":
                        $documents = $this->documentRepository->searchDocument($q, COMMERCIAL_DOC_CATEGORY);
                        if(!$documents->isEmpty()){
                            foreach ($documents as $document){
                                $link = route('project.index', ['project_id' => $document->project_id]);
                                $result[] =[
                                    'label' => $document->name,
                                    'value' => $link,
                                    'desc' => $document->reference
                                ];
                            }
                        }
                        break;
                    case "DesDocument":
                        $documents = $this->documentRepository->searchDocument($q, DESIGN_DOC_CATEGORY);
                        if(!$documents->isEmpty()){
                            foreach ($documents as $document){
                                $link = route('project.index', ['project_id' => $document->project_id]);
                                $result[] =[
                                    'label' => $document->name,
                                    'value' => $link,
                                    'desc' => $document->reference
                                ];
                            }
                        }
                        break;
                    case "PcdDocument":
                        $documents = $this->documentRepository->searchDocument($q, PRE_CONSTRUCTION_DOC_CATEGORY);
                        if(!$documents->isEmpty()){
                            foreach ($documents as $document){
                                $link = route('project.index', ['project_id' => $document->project_id]);
                                $result[] =[
                                    'label' => $document->name,
                                    'value' => $link,
                                    'desc' => $document->reference
                                ];
                            }
                        }
                        break;
                }
            }

        }
        $output = array_slice($result, 0, 300);

        return $output;
    }

}
