<?php

namespace App\Http\Controllers;


use App\Repositories\AirTestCertificateRepository;
use App\Repositories\AreaRepository;
use App\Repositories\ClientRepository;
use App\Repositories\DocumentRepository;
use App\Repositories\HistoricDocRepository;
use App\Repositories\ItemRepository;
use App\Repositories\LocationRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\PropertyRepository;
use App\Repositories\SampleCertificateRepository;
use App\Repositories\ShineCompliance\HazardRepository;
use App\Repositories\ShineCompliance\IncidentReportRepository;
use App\Repositories\ShineCompliance\AssessmentRepository;
use App\Repositories\SitePlanDocumentRepository;
use App\Repositories\SummaryPdfRepository;
use App\Repositories\SurveyRepository;
use App\Repositories\WorkRequestRepository;
use App\Repositories\ShineCompliance\ComplianceSystemRepository;
use App\Repositories\ShineCompliance\EquipmentRepository;
use App\Repositories\ShineCompliance\ComplianceProgrammeRepository;
use App\Repositories\ShineCompliance\ComplianceDocumentRepository;
use App\Repositories\UserRepository;
use App\Repositories\ZoneRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    private $incidentReportRepository;
    private $hazardRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ClientRepository $clientRepository, UserRepository $userRepository,
    ZoneRepository $zoneRepository, PropertyRepository $propertyRepository, AreaRepository $areaRepository,
    LocationRepository $locationRepository, ItemRepository $itemRepository, SitePlanDocumentRepository $sitePlanDocumentRepository,
    HistoricDocRepository $historicDocRepository, SurveyRepository $surveyRepository, SampleCertificateRepository $sampleCertificateRepository,
    AirTestCertificateRepository $airTestCertificateRepository, ProjectRepository $projectRepository, DocumentRepository $documentRepository,
    SummaryPdfRepository $summaryPdfRepository, WorkRequestRepository $workRequestRepository,ComplianceProgrammeRepository $programmeRepository,
    EquipmentRepository $equipmentRepository, ComplianceSystemRepository $systemRepository
        , IncidentReportRepository $incidentReportRepository , AssessmentRepository $assessmentRepository,
    HazardRepository $hazardRepository,
    ComplianceDocumentRepository $complianceDocumentRepository
    )
    {
        $this->clientRepository = $clientRepository;
        $this->userRepository = $userRepository;
        $this->zoneRepository = $zoneRepository;
        $this->propertyRepository = $propertyRepository;
        $this->areaRepository = $areaRepository;
        $this->locationRepository = $locationRepository;
        $this->itemRepository = $itemRepository;
        $this->sitePlanDocumentRepository = $sitePlanDocumentRepository;
        $this->historicDocRepository = $historicDocRepository;
        $this->surveyRepository = $surveyRepository;
        $this->sampleCertificateRepository = $sampleCertificateRepository;
        $this->airTestCertificateRepository = $airTestCertificateRepository;
        $this->projectRepository = $projectRepository;
        $this->documentRepository = $documentRepository;
        $this->summaryPdfRepository = $summaryPdfRepository;
        $this->summaryPdfRepository = $summaryPdfRepository;
        $this->workRequestRepository = $workRequestRepository;
        $this->programmeRepository = $programmeRepository;
        $this->systemRepository = $systemRepository;
        $this->equipmentRepository = $equipmentRepository;
        $this->incidentReportRepository = $incidentReportRepository;
        $this->assessmentRepository = $assessmentRepository;
        $this->hazardRepository = $hazardRepository;
        $this->complianceDocumentRepository = $complianceDocumentRepository;
    }

    public function search(Request $request){

        $q = $request->q;
        $q = addslashes($q);
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
                            foreach ($users as $user){
                                $link = route('profile',['id'=>$user->id]);
                                $result[] =[
                                    'label' => $user->username,
                                    'value' => $link,
                                    'desc' => "ID" .$user->id
                                ];
                            }
                        }
                        break;
                    case "group":
                        $zones = $this->zoneRepository->searchZone($q);
                        if(!$zones->isEmpty()){
                            foreach ($zones as $zone){
                                if($user->is_site_operative == 0){
                                    $link = route('zone.group',['zone_id'=>$zone->id, 'client_id' => $zone->client_id]);
                                } else {
                                    $link = route('zone', ['client_id' => 1]);
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
                                $link = route('property_detail',['id'=>$property->id, 'section' => SECTION_DEFAULT, 'active_tab' => '#detail']);
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
                                $link = route('property_detail',['id'=>$property->id, 'section' => SECTION_DEFAULT, 'active_tab' => '#detail']);
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
                                $link = route('property_detail',['id'=>$property->id, 'section' => SECTION_DEFAULT, 'active_tab' => '#detail']);
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
                                $link = route('item.index',['id' => $item->id] );
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
                        $historicals = $this->complianceDocumentRepository->searchDocument($q);
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
                    case "BSsurvey":
                        $surveys = $this->surveyRepository->searchSurvey($q, 6);
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
                    case "MPSsurvey":
                        $surveys = $this->surveyRepository->searchSurvey($q, 5);
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

                    case "work":
                        $surveys = $this->workRequestRepository->searchWork($q, 0);
                        if(count($surveys)){
                            foreach ($surveys as $survey){
                                $link = route('wr.details',['id' => $survey->id, 'active_tab' => '#Details'] );
                                $result[] =[
                                    'label' => $survey->reference . " - " . $survey->property_name,
                                    'value' => $link,
                                    'desc' => ""
                                ];
                            }
                        }
                        break;

                    case "majorwork":
                        $surveys = $this->workRequestRepository->searchWork($q, 1);
                        if(count($surveys)){
                            foreach ($surveys as $survey){
                                $link = route('wr.details',['id' => $survey->id, 'active_tab' => '#Details'] );
                                $result[] =[
                                    'label' => $survey->reference . " - " . $survey->property_name,
                                    'value' => $link,
                                    'desc' => ""
                                ];
                            }
                        }
                        break;

                    case "BSsurvey":
                        $surveys = $this->surveyRepository->searchSurvey($q, 6);
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

                    case "Fra":
                        $assessments = $this->assessmentRepository->searchAssessment($q, 2);
                        if(count($assessments)){
                            foreach ($assessments as $assessment){
                                $link = route('shineCompliance.assessment.show',['id' => $assessment->id] );
                                $result[] =[
                                    'label' => $assessment->reference . " - " . $assessment->property_name,
                                    'value' => $link,
                                    'desc' => ""
                                ];
                            }
                        }
                        break;

                    case "Hsa":
                        $assessments = $this->assessmentRepository->searchAssessment($q, 5);
                        if(count($assessments)){
                            foreach ($assessments as $assessment){
                                $link = route('shineCompliance.assessment.show',['id' => $assessment->id] );
                                $result[] =[
                                    'label' => $assessment->reference . " - " . $assessment->property_name,
                                    'value' => $link,
                                    'desc' => ""
                                ];
                            }
                        }
                        break;

                    case "Hsi":
                        $assessments = $this->assessmentRepository->searchAssessment($q, 6);
                        if(count($assessments)){
                            foreach ($assessments as $assessment){
                                $link = route('shineCompliance.assessment.show',['id' => $assessment->id] );
                                $result[] =[
                                    'label' => $assessment->reference . " - " . $assessment->property_name,
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
                    case "ODocument":
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
                    case "PDocument":
                        $documents = $this->documentRepository->searchDocument($q, PLANNING_DOC_CATEGORY);
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
                    case "SDocument":
                        $documents = $this->documentRepository->searchDocument($q, PRE_START_DOC_CATEGORY);
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
                    case "RDocument":
                        $documents = $this->documentRepository->searchDocument($q, SITE_RECORDS_DOC_CATEGORY);
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
                    case "GDocument":
                        $documents = $this->documentRepository->searchDocument($q, 7);
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
                    case "CDocument":
                        $documents = $this->documentRepository->searchDocument($q, COMPLETION_DOC_CATEGORY);
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

                    case "FHazard":
                        $fire_hazards = $this->hazardRepository->searchHazard($q, ASSESSMENT_FIRE_TYPE);
                        if(count($fire_hazards)){
                            foreach ($fire_hazards as $fire_hazard){
                                $link = route('shineCompliance.assessment.get_hazard_detail',['id' => $fire_hazard->id] );
                                $result[] =[
                                    'label' => $fire_hazard->name,
                                    'value' => $link,
                                    'desc' => $fire_hazard->reference
                                ];
                            }
                        }
                        break;

                    case "WHazard":
                        $water_hazards = $this->hazardRepository->searchHazard($q, ASSESSMENT_WATER_TYPE);
                        if(count($water_hazards)){
                            foreach ($water_hazards as $water_hazard){
                                $link = route('shineCompliance.assessment.get_hazard_detail',['id' => $water_hazard->id] );
                                $result[] =[
                                    'label' => $water_hazard->name,
                                    'value' => $link,
                                    'desc' => $water_hazard->reference
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
