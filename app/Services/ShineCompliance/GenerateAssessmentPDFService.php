<?php


namespace App\Services\ShineCompliance;

use App\Helpers\ComplianceHelpers;
use App\Http\Controllers\GeneratePDFController;
use App\Models\Counter;
use App\Models\Property;
use App\Models\ShineCompliance\AssessmentAnswer;
use App\Models\ShineCompliance\AssessmentQuestion;
use App\Models\ShineCompliance\AssessmentResult;
use App\Models\ShineCompliance\AssessmentSection;
use App\Models\ShineCompliance\PropertySurvey;
use App\Models\SummaryPdf;
use App\Repositories\ShineCompliance\AreaRepository;
use App\Repositories\ShineCompliance\AssessmentManagementQuestionRepository;
use App\Repositories\ShineCompliance\AssessmentOtherQuestionRepository;
use App\Repositories\ShineCompliance\ComplianceSystemRepository;
use App\Repositories\ShineCompliance\ItemRepository;
use App\Repositories\ShineCompliance\AssemblyPointRepository;
use App\Repositories\ShineCompliance\AssessmentInfoRepository;
use App\Models\ShineCompliance\AssessmentValue;
use App\Repositories\ShineCompliance\AssessmentRepository;
use App\Repositories\ShineCompliance\EquipmentRepository;
use App\Repositories\ShineCompliance\FireExitRepository;
use App\Repositories\ShineCompliance\HazardRepository;
use App\Repositories\ShineCompliance\LocationRepository;
use App\Repositories\ShineCompliance\NonconformityRepository;
use App\Repositories\ShineCompliance\ProjectRepository;
use App\Repositories\ShineCompliance\PropertyRepository;
use App\Repositories\ShineCompliance\PublishedAssessmentRepository;
use App\Repositories\ShineCompliance\UserRepository;
use App\Repositories\ShineCompliance\AssessmentSectionRepository;
use App\Repositories\ShineCompliance\AssessmentPlanDocumentRepository;
use App\Repositories\ShineCompliance\AssessmentNoteDocumentRepository;
use App\Repositories\ShineCompliance\VehicleParkingRepository;
use App\Repositories\ShineCompliance\PropertyVulnerableOccupantRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \PDF;
use mikehaertl\pdftk\Pdf as PDFTK;

class GenerateAssessmentPDFService
{
    private $assessmentRepository;
    private $assessmentInfoRepository;
    private $userRepository;
    private $areaRepository;
    private $locationRepository;
    private $hazardRepository;
    private $assessmentSectionRepository;
    private $projectRepository;
    private $propertyRepository;
    private $assessmentPlanDocumentRepository;
    private $assessmentNoteDocumentRepository;
    private $itemRepository;
    private $assemblyRepository;
    private $exitRepository;
    private $vehicleParkingRepository;
    private $equipmentRepository;
    private $systemRepository;
    private $nonconformityRepository;
    private $publishedAssessmentRepository;
    private $vulnerableRepository;

    private $managementQuestionRepository;
    private $otherQuestionRepository;

    public function __construct(AssessmentRepository $assessmentRepository,
                                AssessmentInfoRepository $assessmentInfoRepository,
                                AssessmentSectionRepository $assessmentSectionRepository,
                                PropertyRepository $propertyRepository,
                                UserRepository $userRepository,
                                AreaRepository $areaRepository,
                                ItemRepository $itemRepository,
                                LocationRepository $locationRepository,
                                HazardRepository $hazardRepository,
                                AssessmentPlanDocumentRepository $assessmentPlanDocumentRepository,
                                AssessmentNoteDocumentRepository $assessmentNoteDocumentRepository,
                                ProjectRepository $projectRepository,
                                AssemblyPointRepository $assemblyRepository,
                                FireExitRepository $exitRepository,
                                VehicleParkingRepository $vehicleParkingRepository,
                                EquipmentRepository $equipmentRepository,
                                ComplianceSystemRepository $systemRepository,
                                NonconformityRepository $nonconformityRepository,
                                PublishedAssessmentRepository $publishedAssessmentRepository,
                                PropertyVulnerableOccupantRepository $vulnerableRepository,
                                AssessmentManagementQuestionRepository $managementQuestionRepository,
                                AssessmentOtherQuestionRepository $otherQuestionRepository
                                )
    {
        $this->assessmentRepository = $assessmentRepository;
        $this->assessmentInfoRepository = $assessmentInfoRepository;
        $this->assessmentSectionRepository = $assessmentSectionRepository;
        $this->userRepository = $userRepository;
        $this->areaRepository = $areaRepository;
        $this->hazardRepository = $hazardRepository;
        $this->locationRepository = $locationRepository;
        $this->projectRepository = $projectRepository;
        $this->propertyRepository = $propertyRepository;
        $this->assessmentNoteDocumentRepository = $assessmentNoteDocumentRepository;
        $this->assessmentPlanDocumentRepository = $assessmentPlanDocumentRepository;
        $this->itemRepository = $itemRepository;
        $this->assemblyRepository = $assemblyRepository;
        $this->exitRepository = $exitRepository;
        $this->vehicleParkingRepository = $vehicleParkingRepository;
        $this->equipmentRepository = $equipmentRepository;
        $this->systemRepository = $systemRepository;
        $this->nonconformityRepository = $nonconformityRepository;
        $this->publishedAssessmentRepository = $publishedAssessmentRepository;
        $this->vulnerableRepository = $vulnerableRepository;

        $this->managementQuestionRepository = $managementQuestionRepository;
        $this->otherQuestionRepository = $otherQuestionRepository;
    }

    public function downloadAssessPDF($id, $type = 'view') {

        $publish_survey_pdf = $this->publishedAssessmentRepository->where('id',$id)->first();

        //log audit todo
        // if (!is_null(\Auth::user()) and !is_null($publish_survey_pdf)) {
        //     $comment = (\Auth::user()->full_name ?? 'system')  . " view assessment PDF "  . $publish_survey_pdf->name . ' on '. $publish_survey_pdf->assessment->reference ?? '';
        //     \ComplianceHelpers::logAudit('assessment', $publish_survey_pdf->id, AUDIT_ACTION_VIEW, $publish_survey_pdf->name, $publish_survey_pdf->assessment->id ?? '',$comment, 0 ,$publish_survey_pdf->assessment->property_id ?? '');
        // }
        if($publish_survey_pdf && file_exists($publish_survey_pdf->path)){

            if ($type == 'view') {
                return response()->file($publish_survey_pdf->path);
            } else {

                $headers = [
                    'Content-Type' => 'application/pdf',
                ];
                // dd($headers);
                return response()->download($publish_survey_pdf->path, $publish_survey_pdf->filename, $headers);
            }
        }
        return abort(404);
    }

    private function downloadPDFSurvey($id){
        $publish_survey_pdf = PublishedSurvey::where('id',$id)->first();
        //log audit
        $comment = \Auth::user()->full_name  . " download survey PDF "  . $publish_survey_pdf->name . ' on '. optional($publish_survey_pdf->survey)->reference;
        \CommonHelpers::logAudit(SURVEY_TYPE, $publish_survey_pdf->id, AUDIT_ACTION_DOWNLOAD, $publish_survey_pdf->name, optional($publish_survey_pdf->survey)->id ,$comment, 0 ,optional($publish_survey_pdf->survey)->property_id);
        if($publish_survey_pdf && file_exists($publish_survey_pdf->path)){
            $headers = [
                'Content-Type' => 'application/pdf',
            ];
            return response()->download($publish_survey_pdf->path, $publish_survey_pdf->filename, $headers);

        }
        return abort(404);
    }

    public function publishAssessment($assessment, $publish_draft) {
        try {
            if ($publish_draft) {
               $response = \CommonHelpers::successResponse('Published Assessment as draft Successfully!');
            } else {
                // Change status, published date and lock assessment
                $assessment->status = ASSESSMENT_STATUS_PUBLISHED;
                $assessment->is_locked = COMPLIANCE_ASSESSMENT_LOCKED;
                $assessment->published_date = Carbon::now()->timestamp;
                $assessment->save();

                // log audit
                \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $assessment->id, AUDIT_ACTION_PUBLISH, $assessment->reference);
                // lock all items of assessment
                $this->lockAssessment($assessment->id);
                $response = \CommonHelpers::successResponse('Published Assessment Successfully!');
            }
            // TODO: Generate assessment pdf for public assessment
            $assessment_ref = $assessment->reference;
            $assessment_revision = count($assessment->publishedAssessments);
            $file_type = 'pdf';
            $mime = 'application/pdf';
            $file_name = $assessment_ref;
            if($assessment_revision > 0){
                $file_name = $assessment_ref . '.' . $assessment_revision;
            }
            $file_name .= '.' . $file_type;
            $save_path = storage_path('app/data/pdfs/assessments') ."/" . $file_name;

            $is_pdf = true;

            $equipments = $this->equipmentRepository->with(['property','assessment','system', 'area', 'location', 'equipmentType', 'parent','equipmentConstruction',
                'cleaning','tempAndPh', 'equipmentModel','inaccessReason', 'operationalUse', 'frequencyUse', 'specificLocationView',
                'cleaning.operationalExposure','cleaning.degreeFouling','cleaning.degreeBiological','cleaning.extentCorrosion','cleaning.cleanlinessRelation',
                'cleaning.cleanlinessRelation','cleaning.easeCleaning','cleaning.envidenceStagnation','equipmentConstruction.sourceRelation','equipmentConstruction.sourceAccessibility',
                'equipmentConstruction.sourceCondition','equipmentConstruction.mainAccessHatch','equipmentConstruction.directFired','equipmentConstruction.insulationCondition',
                'equipmentConstruction.pipeInsulationCondition','equipmentConstruction.aerosolRisk','equipmentConstruction.nearestFurthest',
                'nonconformities.hazardPDF', 'nonconformities.hazardPDF.hazardType'
            ])->where('assess_id', $assessment->id)
                ->get();

            $systems = $this->systemRepository->with(['equipments','systemType','equipments.location',
                                                'equipments.equipmentType', 'equipments.area'
                                ])->where('assess_id', $assessment->id)
                                ->where('decommissioned', 0)
                                ->get();

            $hazards = $assessment->unDecommissionHazardPdf;
            //HS pdf
            if($assessment->classification == ASSESSMENT_HS_TYPE){
                $data_merge = $this->genPdfAssessmentHS($assessment, $hazards, $is_pdf, $equipments, $systems);
                $arr_pdf_merge = $data_merge['first_merge'];
                $arr_pdf_garbage = $arr_pdf_merge_last = $data_merge['last_merge'];
                $path_burst  = $data_merge['path_burst'];
            }

            if ($assessment->classification == ASSESSMENT_WATER_TYPE) {
                // first generate to get total
                // Cover
                $cover_path = $this->generateCoverAssessmentWaterPdf($assessment, $hazards, $is_pdf);
                // TOC

                $content_page = $this->generateContentPageAssessmentWaterPdf($assessment, $is_pdf);
                // 1,2,3
                if($assessment->type == ASSESS_TYPE_WATER_RISK){
                    $part1 = $this->generateExecutivePageAssessmentWaterPdf($assessment, '1_property_info_and_executive_summary_risk', $is_pdf);
                }else{
                    $part1 = $this->generateExecutivePageAssessmentWaterPdf($assessment, '1_property_info_and_executive_summary', $is_pdf);
                }

                if(count($assessment->fireExits) == 0 &&  count($assessment->assemblyPoints) == 0 && count($assessment->vehicleParking) == 0){
                }else{
                    $part2 = $this->generateExecutivePageAssessmentWaterPdf($assessment, '2_property_info_and_executive_summary', $is_pdf);
                }

                $part3 = $this->generateExecutivePageAssessmentWaterPdf($assessment, '3_property_info_and_executive_summary', $is_pdf);

                // 4,5,6
//                $hazard_outlet = $this->generateWaterHazardAndOutletPdf($assessment, $hazards);
                $part4 = $this->generateWaterHazardAndOutletPdf($assessment, $hazards, '4_hazard_summary_and_outlet_register');
                $part5 = $this->generateWaterHazardAndOutletPdf($assessment, $hazards, '5_hazard_summary_and_outlet_register');
                $part6 = $this->generateWaterHazardAndOutletPdf($assessment, $hazards, '6_hazard_summary_and_outlet_register');

                // 7
                $part7 = $this->generateNonAssessedPageAssessmentWaterPdf($assessment, $equipments, $is_pdf);
                // 8
                if($assessment->type == ASSESS_TYPE_WATER_RISK) {
                    $part8 = $this->generateAuditPageAssessmentWaterPdf($assessment, $is_pdf);
                }
                // 9,10,11,12
                $part9 = $this->generateWaterEquipmentPdf($assessment, $equipments, $systems, '9_system_and_equipment');
                $part10 = $this->generateWaterEquipmentPdf($assessment, $equipments, $systems, '10_system_and_equipment');
                $part11 = $this->generateWaterEquipmentPdf($assessment, $equipments, $systems, '11_system_and_equipment');
                $part12 = $this->generateWaterEquipmentPdf($assessment, $equipments, $systems, '12_system_and_equipment');

//                $arr_pdf_merge = [$cover_path, $content_page, $executive_page, $hazard_outlet, $non_assessed_page,$audit_page, $equipment];
                $arr_pdf_merge = [
                    0 => $cover_path,
                    1 => $content_page,
                    2 => $part1,
                    4 => $part3,
                    5 => $part4,
                    6 => $part5,
                    7 => $part6,
                    8 => $part7,
                    10 => $part9,
                    11 => $part10,
                    12 => $part11,
                    13 => $part12,
                ];

//                if neu 3 cai khac empty => $arr_pdf_merge[3] = $part2
                if(count($assessment->fireExits) == 0 &&  count($assessment->assemblyPoints) == 0 && count($assessment->vehicleParking) == 0){
                }else{
                    $arr_pdf_merge[3] = $part2;
                }
                if($assessment->type == ASSESS_TYPE_WATER_RISK) {
                    $arr_pdf_merge[9] = $part8;
                }
                ksort($arr_pdf_merge);
                $array_path = [];
                $total_page = 1;
                $offset = 2;
                $path_burst = storage_path('app/data/pdfs/assessments/burst'.$assessment->reference);

                if(!\File::exists($path_burst)) {
                    \File::makeDirectory($path_burst, $mode = 0777, true, true);
                }
                if(count($arr_pdf_merge) > 0){
                    foreach ($arr_pdf_merge as $key => $pdf_path){
                        if($key > 0){
                            $pdf_merger = new PDFTK($pdf_path);
                            $pdf_merger->burst($path_burst."/".$assessment->reference."_".$key."_%01d.pdf");
                            $matching_files = \File::glob("{$path_burst}/".$assessment->reference."_".$key."_*");
                            $array_path[$key]['offset'] = $offset;
                            $total_page += count($matching_files);
                            $offset += count($matching_files);
                        }
                    }
                }
                // last generate to get total
                // Cover
                $cover_path_last = $this->generateCoverAssessmentWaterPdf($assessment, $hazards, $is_pdf);
                // TOC
                $content_page_last = $this->generateContentPageAssessmentWaterPdf($assessment, $is_pdf, $array_path[1]['offset'] ?? 0, $total_page, false, $array_path);
                // 1,2,3
//                $executive_page_last = $this->generateExecutivePageAssessmentWaterPdf($assessment, $is_pdf, $array_path[2]['offset'] ?? 0, $total_page, false);
                if($assessment->type == ASSESS_TYPE_WATER_RISK){
                    $part1_last = $this->generateExecutivePageAssessmentWaterPdf($assessment, '1_property_info_and_executive_summary_risk', $is_pdf, $array_path[2]['offset'] ?? 0, $total_page, false);
                }else{
                    $part1_last = $this->generateExecutivePageAssessmentWaterPdf($assessment, '1_property_info_and_executive_summary', $is_pdf, $array_path[2]['offset'] ?? 0, $total_page, false);
                }
                $part2_last = '';
                if(count($assessment->fireExits) == 0 &&  count($assessment->assemblyPoints) == 0 && count($assessment->vehicleParking) == 0){
                }else{
                    $part2_last = $this->generateExecutivePageAssessmentWaterPdf($assessment, '2_property_info_and_executive_summary', $is_pdf, $array_path[3]['offset'] ?? 0, $total_page, false);
                }
                $part3_last = $this->generateExecutivePageAssessmentWaterPdf($assessment, '3_property_info_and_executive_summary', $is_pdf, $array_path[4]['offset'] ?? 0, $total_page, false);
                // 4,5,6
//                $hazard_outlet_last = $this->generateWaterHazardAndOutletPdf($assessment, $hazards, $array_path[3]['offset'] ?? 0, $total_page, false);
                $part4_last = $this->generateWaterHazardAndOutletPdf($assessment, $hazards, '4_hazard_summary_and_outlet_register', $array_path[5]['offset'] ?? 0, $total_page, false);
                $part5_last = $this->generateWaterHazardAndOutletPdf($assessment, $hazards, '5_hazard_summary_and_outlet_register',$array_path[6]['offset'] ?? 0, $total_page, false);
                $part6_last = $this->generateWaterHazardAndOutletPdf($assessment, $hazards, '6_hazard_summary_and_outlet_register',$array_path[7]['offset'] ?? 0, $total_page, false);
                // 7
                $part7_last = $this->generateNonAssessedPageAssessmentWaterPdf($assessment, $equipments, $is_pdf, $array_path[8]['offset'] ?? 0, $total_page, false);
                // 8
                $part8_last = '';
                if($assessment->type == ASSESS_TYPE_WATER_RISK) {
                    $part8_last = $this->generateAuditPageAssessmentWaterPdf($assessment, $is_pdf, $array_path[9]['offset'] ?? 0, $total_page, false);
                }
                // 9,10,11,12
//                $equipment_last = $this->generateWaterEquipmentPdf($assessment, $equipments, $systems, $array_path[6]['offset'] ?? 0, $total_page, false);
                $part9_last = $this->generateWaterEquipmentPdf($assessment, $equipments, $systems, '9_system_and_equipment', $array_path[10]['offset'] ?? 0, $total_page, false);
                $part10_last = $this->generateWaterEquipmentPdf($assessment, $equipments, $systems, '10_system_and_equipment', $array_path[11]['offset'] ?? 0, $total_page, false);
                $part11_last = $this->generateWaterEquipmentPdf($assessment, $equipments, $systems, '11_system_and_equipment', $array_path[12]['offset'] ?? 0, $total_page, false);
                $part12_last = $this->generateWaterEquipmentPdf($assessment, $equipments, $systems, '12_system_and_equipment', $array_path[13]['offset'] ?? 0, $total_page, false);

//                $arr_pdf_merge_last = [$cover_path_last, $content_page_last, $executive_page_last, $hazard_outlet_last, $non_assessed_page_last, $audit_page_last, $equipment_last];
                $arr_pdf_merge_last = [
                    0 => $cover_path_last,
                    1 => $content_page_last,
                    2 => $part1_last,
                    4 => $part3_last,
                    5 => $part4_last,
                    6 => $part5_last,
                    7 => $part6_last,
                    8 => $part7_last,
                    10 => $part9_last,
                    11 => $part10_last,
                    12 => $part11_last,
                    13 => $part12_last,
                ];
                if(count($assessment->fireExits) == 0 &&  count($assessment->assemblyPoints) == 0 && count($assessment->vehicleParking) == 0){
                }else{
                    $arr_pdf_merge_last[3] = $part2_last;
                }
                if($assessment->type == ASSESS_TYPE_WATER_RISK) {
                    $arr_pdf_merge_last[9] = $part8_last;
                }
                ksort($arr_pdf_merge_last);
                $arr_pdf_garbage = $arr_pdf_merge_last;
            }

            if($assessment->classification == ASSESSMENT_FIRE_TYPE) {
                // first generate to get total
                $cover_path = $this->generateCoverAssessmentFirePdf($assessment, $hazards, $is_pdf);
                // 1
                $content_page = $this->generateContentPageAssessmentFirePdf($assessment, $is_pdf);
                // 2,3,4
                $executive_page = $this->generateExecutivePageAssessmentFirePdf($assessment, $is_pdf);

                $management_info_page = $this->generateManagementInfoAssessmentFirePdf($assessment);
                // 4,5
                $hazard_outlet = $this->generateFireHazardPdf($assessment, $hazards);
                // 6
                $non_assessed_page = $this->generateNonAssessedPageAssessmentFirePdf($assessment, $equipments, $is_pdf);
                // 7
                $audit_page = $this->generateAuditPageAssessmentFirePdf($assessment, $is_pdf);
                // 8, 9, 10
                $equipment = $this->generateFireEquipmentPdf($assessment, $equipments, $systems);

                $arr_pdf_garbage = $arr_pdf_merge = [$cover_path, $content_page, $executive_page, $management_info_page, $hazard_outlet, $non_assessed_page,$audit_page, $equipment];

                $array_path = [];
                $total_page = 1;
                $offset = 2;
                $path_burst = storage_path('app/data/pdfs/assessments/burst'.$assessment->reference);

                if(!\File::exists($path_burst)) {
                    \File::makeDirectory($path_burst, $mode = 0777, true, true);
                }
                if(count($arr_pdf_merge) > 0){
                    foreach ($arr_pdf_merge as $key => $pdf_path){
                        if($key > 0){
                            $pdf_merger = new PDFTK($pdf_path);
                            $pdf_merger->burst($path_burst."/".$assessment->reference."_".$key."_%01d.pdf");
                            $matching_files = \File::glob("{$path_burst}/".$assessment->reference."_".$key."_*");
                            $array_path[$key]['offset'] = $offset;
                            $total_page += count($matching_files);
                            $offset += count($matching_files);
                        }
                    }
                }

                // last generate to get total
                $cover_path_last = $this->generateCoverAssessmentFirePdf($assessment, $hazards, $is_pdf);
                // 1
                $content_page_last = $this->generateContentPageAssessmentFirePdf($assessment, $is_pdf, $array_path[1]['offset'] ?? 0, $total_page, false);
                // 2,3,4
                $executive_page_last = $this->generateExecutivePageAssessmentFirePdf($assessment, $is_pdf, $array_path[2]['offset'] ?? 0, $total_page, false);

                $management_info_page_last = $this->generateManagementInfoAssessmentFirePdf($assessment, $array_path[3]['offset'] ?? 0, $total_page, false);
                // 4,5
                $hazard_outlet_last = $this->generateFireHazardPdf($assessment, $hazards, $array_path[4]['offset'] ?? 0, $total_page, false);
                // 6
                $non_assessed_page_last = $this->generateNonAssessedPageAssessmentFirePdf($assessment, $equipments, $is_pdf, $array_path[5]['offset'] ?? 0, $total_page, false);
                // 7
                $audit_page_last = $this->generateAuditPageAssessmentFirePdf($assessment, $is_pdf, $array_path[6]['offset'] ?? 0, $total_page, false);
                // 8, 9, 10
                $equipment_last = $this->generateFireEquipmentPdf($assessment, $equipments, $systems, $array_path[7]['offset'] ?? 0, $total_page, false);

                $arr_pdf_merge_last = [$cover_path_last, $content_page_last, $executive_page_last, $management_info_page_last, $hazard_outlet_last, $non_assessed_page_last, $audit_page_last, $equipment_last];
            }

            if($assessment->classification == ASSESSMENT_WATER_TYPE && $assessment->type == ASSESS_TYPE_WATER_RISK
                || $assessment->classification == ASSESSMENT_FIRE_TYPE && in_array($assessment->type, ASSESS_TYPE_FIRE_RISK_ALL_TYPE)) {
                //merge plans
                if (isset($assessment->plans) && count($assessment->plans)) {
                    foreach ($assessment->plans as $p) {
                        if ($p->shineDocumentStorage) {
                            $path = storage_path() . '/app/' . $p->shineDocumentStorage->path;
                            if (isset($p->shineDocumentStorage) && file_exists($path)) {
                                $file_info = pathinfo($path);
                                if ($file_info['extension'] == 'pdf') {
                                    $arr_pdf_merge_last[] = storage_path() . '/app/' . $p->shineDocumentStorage->path;
                                }
                            }
                        }
                    }
                }

                // merge assessorNotes
                if (isset($assessment->assessorNotes) && count($assessment->assessorNotes)) {
                    foreach ($assessment->assessorNotes as $p) {
                        if ($p->shineDocumentStorage) {
                            $path = storage_path() . '/app/' . $p->shineDocumentStorage->path;
                            if (isset($p->shineDocumentStorage) && file_exists($path)) {
                                $file_info = pathinfo($path);
                                if ($file_info['extension'] == 'pdf') {
                                    $arr_pdf_merge_last[] = storage_path() . '/app/' . $p->shineDocumentStorage->path;
                                }
                            }
                        }
                    }
                }
                // Merge Sampling
                if (isset($assessment->samples) && count($assessment->samples)) {
                    foreach ($assessment->samples as $sample) {
                        if ($sample->shineDocumentStorage) {
                            $path = storage_path() . '/app/' . $sample->shineDocumentStorage->path;
                            if (isset($sample->shineDocumentStorage) && file_exists($path)) {
                                $file_info = pathinfo($path);
                                if ($file_info['extension'] == 'pdf') {
                                    $arr_pdf_merge_last[] = storage_path() . '/app/' . $sample->shineDocumentStorage->path;
                                }
                            }
                        }
                    }
                }
            }

            $pdf_merger = new PDFTK($arr_pdf_merge_last, [
                // 'useExec' => true,
            ]);

            $pdf_merger->allow('AllFeatures')      // Change permissions
            ->flatten() ;
            // Merge form data into document (doesn't work well with UTF-8!)

            if(!$pdf_merger->saveAs($save_path)){
                $error = $pdf_merger->getError();
            }

            // clean temp folders
            foreach ($arr_pdf_merge as $temp_pdf) {
                if (\File::exists($temp_pdf)) {
                    \File::delete($temp_pdf);
                }
            }

            foreach ($arr_pdf_garbage as $temp_pdf) {
                if (\File::exists($temp_pdf)) {
                    \File::delete($temp_pdf);
                }
            }
            if (\File::exists($path_burst)) {
                \File::deleteDirectory($path_burst);
            }

            $this->publishedAssessmentRepository->create([
                'assess_id' => $assessment->id,
                'name' => $assessment_revision > 0 ? $assessment_ref . "." . $assessment_revision : $assessment_ref,
                'revision' => $assessment_revision,
                'type' => $file_type,
                'size' => filesize($save_path),
                'filename' => $file_name,
                'mime' => $mime,
                'path' => $save_path,
                'created_by' => Auth::user()->id,
            ]);

            return $response;
        } catch (\Exception $exception) {
            Log::error($exception);
            dd($exception);
        }
        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to publish Assessment. Please try again!');
    }

    public function genPdfAssessmentHS($assessment, $hazards, $is_pdf, $equipments, $systems){
        $titles = [
                1 => 'info',
                2 => 'fire',
                3 => 'summary',
                4 => 'hazard',
                5 => 'nonconformities',
                6 => 'outlet',
                7 => 'non',
                8 => 'Hs',
                9 => 'system',
                10 => 'equiment',
                11 => 'risk',
                12 => 'example',
        ];
        $data_outlet = $this->equipmentRepository->getOutletRegister($assessment->id);
        // Cover
        $cover_path = $this->generateCoverAssessmentHSPdf($assessment, $hazards, $is_pdf);
        // TOC
        // 1,2,3
        $part1 = $this->generateExecutivePageAssessmentHsPdf($assessment, '1_property_info_and_executive_summary_risk', $is_pdf);

        if(count($assessment->fireExits) == 0 &&  count($assessment->assemblyPoints) == 0 && count($assessment->vehicleParking) == 0){
        }else{
            $part2 = $this->generateExecutivePageAssessmentHsPdf($assessment, '2_property_info_and_executive_summary', $is_pdf);
        }

        $part3 = $this->generateExecutivePageAssessmentHsPdf($assessment, '3_property_info_and_executive_summary', $is_pdf);

        // 4,5,6
//                $hazard_outlet = $this->generateWaterHazardAndOutletPdf($assessment, $hazards);
        $part4 = $this->generateHsHazardAndOutletPdf($assessment, $hazards, '4_hazard_summary_and_outlet_register');

        // 7
        $part7 = $this->generateNonAssessedPageAssessmentHsPdf($assessment, $equipments, $is_pdf);
        // 8
        $part8 = $this->generateAuditPageAssessmentHsPdf($assessment, $is_pdf);
        // 9,10,11,12

        $part11 = $this->generateHsEquipmentPdf($assessment, $equipments, $systems, '11_system_and_equipment');
        $part12 = $this->generateHsEquipmentPdf($assessment, $equipments, $systems, '12_system_and_equipment');

        $arr_pdf_merge = [
            0 => $cover_path,
            2 => $part1,
            4 => $part3,
            5 => $part4,
            8 => $part7,
            9 => $part8,
            12 => $part11,
            13 => $part12,
        ];

//                if neu 3 cai khac empty => $arr_pdf_merge[3] = $part2
        if(count($assessment->fireExits) == 0 &&  count($assessment->assemblyPoints) == 0 && count($assessment->vehicleParking) == 0){
            unset($titles[2]);
        }else{
            $arr_pdf_merge[3] = $part2;
        }

        if($assessment->assessmentActiveNonconformities->count()){
            $part5 = $this->generateHsHazardAndOutletPdf($assessment, $hazards, '5_hazard_summary_and_outlet_register');
            $arr_pdf_merge[6] = $part5;
        }else{
            unset($titles[5]);
        }

        if($data_outlet['count_outlet']){
            $part6 = $this->generateHsHazardAndOutletPdf($assessment, $hazards, '6_hazard_summary_and_outlet_register');
            $arr_pdf_merge[7] = $part6;
        }else{
            unset($titles[6]);
        }

        if(count($systems)){
            $part9 = $this->generateHsEquipmentPdf($assessment, $equipments, $systems, '9_system_and_equipment');
            $arr_pdf_merge[10] = $part9;
        }else{
            unset($titles[9]);
        }

        if(count($equipments)){
            $part10 = $this->generateHsEquipmentPdf($assessment, $equipments, $systems, '10_system_and_equipment');
            $arr_pdf_merge[11] = $part10;
        }else{
            unset($titles[10]);
        }
        $new_title = [];
        $i = 1;
        foreach ($titles as $key => $value) {
            $new_title[$value] =  $i;
            $i++;
        }
        $content_page = $this->generateContentPageAssessmentHSPdf($assessment, $is_pdf,$new_title);
        $arr_pdf_merge[1] = $content_page;

        ksort($arr_pdf_merge);
        $array_path = [];
        $total_page = 1;
        $offset = 2;
        $path_burst = storage_path('app/data/pdfs/assessments/burst'.$assessment->reference);

        if(!\File::exists($path_burst)) {
            \File::makeDirectory($path_burst, $mode = 0777, true, true);
        }
        if(count($arr_pdf_merge) > 0){
            foreach ($arr_pdf_merge as $key => $pdf_path){
                if($key > 0){
                    $pdf_merger = new PDFTK($pdf_path);
                    $pdf_merger->burst($path_burst."/".$assessment->reference."_".$key."_%01d.pdf");
                    $matching_files = \File::glob("{$path_burst}/".$assessment->reference."_".$key."_*");
                    $array_path[$key]['offset'] = $offset;
                    $total_page += count($matching_files);
                    $offset += count($matching_files);
                }
            }
        }
        // last generate to get total
        // Cover
        $cover_path_last = $this->generateCoverAssessmentHsPdf($assessment, $hazards, $is_pdf);
        // TOC

        // 1,2,3
//                $executive_page_last = $this->generateExecutivePageAssessmentWaterPdf($assessment, $is_pdf, $array_path[2]['offset'] ?? 0, $total_page, false);
        $part1_last = $this->generateExecutivePageAssessmentHsPdf($assessment, '1_property_info_and_executive_summary_risk', $is_pdf, $new_title, $array_path[2]['offset'] ?? 0, $total_page, false);

        $part2_last = '';
        if(count($assessment->fireExits) == 0 &&  count($assessment->assemblyPoints) == 0 && count($assessment->vehicleParking) == 0){
        }else{
            $part2_last = $this->generateExecutivePageAssessmentHsPdf($assessment, '2_property_info_and_executive_summary', $is_pdf, $new_title, $array_path[3]['offset'] ?? 0, $total_page, false);
        }

        $part3_last = $this->generateExecutivePageAssessmentHsPdf($assessment, '3_property_info_and_executive_summary', $is_pdf, $new_title, $array_path[4]['offset'] ?? 0, $total_page, false);
        // 4,5,6
//                $hazard_outlet_last = $this->generateWaterHazardAndOutletPdf($assessment, $hazards, $array_path[3]['offset'] ?? 0, $total_page, false);
        $part4_last = $this->generateHsHazardAndOutletPdf($assessment, $hazards, '4_hazard_summary_and_outlet_register', $new_title, $array_path[5]['offset'] ?? 0, $total_page, false);

        // 7
        $part7_last = $this->generateNonAssessedPageAssessmentHsPdf($assessment, $equipments, $is_pdf, $new_title, $array_path[8]['offset'] ?? 0, $total_page, false);
        // 8
        $part8_last = $this->generateAuditPageAssessmentHsPdf($assessment, $is_pdf, $new_title,$array_path[9]['offset'] ?? 0, $total_page, false);
        // 9,10,11,12
//                $equipment_last = $this->generateWaterEquipmentPdf($assessment, $equipments, $systems, $array_path[6]['offset'] ?? 0, $total_page, false);
        $part11_last = $this->generateHsEquipmentPdf($assessment, $equipments, $systems, '11_system_and_equipment', $new_title, $array_path[12]['offset'] ?? 0, $total_page, false);
        $part12_last = $this->generateHsEquipmentPdf($assessment, $equipments, $systems, '12_system_and_equipment', $new_title,$array_path[13]['offset'] ?? 0, $total_page, false);

//                $arr_pdf_merge_last = [$cover_path_last, $content_page_last, $executive_page_last, $hazard_outlet_last, $non_assessed_page_last, $audit_page_last, $equipment_last];
        $arr_pdf_merge_last = [
            0 => $cover_path_last,
            2 => $part1_last,
            4 => $part3_last,
            5 => $part4_last,
            8 => $part7_last,
            9 => $part8_last,
            12 => $part11_last,
            13 => $part12_last,
        ];

        if(count($assessment->fireExits) == 0 &&  count($assessment->assemblyPoints) == 0 && count($assessment->vehicleParking) == 0){
        }else{
            $arr_pdf_merge_last[3] = $part2_last;
        }

        if($assessment->assessmentActiveNonconformities->count()){
            $part5_last = $this->generateHsHazardAndOutletPdf($assessment, $hazards, '5_hazard_summary_and_outlet_register',$new_title,$array_path[6]['offset'] ?? 0, $total_page, false);
            $arr_pdf_merge_last[6] = $part5_last;
        }

        if($data_outlet['count_outlet']){
            $part6_last = $this->generateHsHazardAndOutletPdf($assessment, $hazards, '6_hazard_summary_and_outlet_register',$new_title,$array_path[7]['offset'] ?? 0, $total_page, false);
            $arr_pdf_merge_last[7] = $part6_last;
        }

        if(count($systems)){
            $part9_last = $this->generateHsEquipmentPdf($assessment, $equipments, $systems, '9_system_and_equipment', $new_title, $array_path[10]['offset'] ?? 0, $total_page, false);
            $arr_pdf_merge_last[10] = $part9_last;
        }

        if(count($equipments)){
            $part10_last = $this->generateHsEquipmentPdf($assessment, $equipments, $systems, '10_system_and_equipment', $new_title, $array_path[11]['offset'] ?? 0, $total_page, false);
            $arr_pdf_merge_last[11] = $part10_last;
        }

        $content_page_last = $this->generateContentPageAssessmentHsPdf($assessment, $is_pdf,$new_title, $array_path[1]['offset'] ?? 0, $total_page, false, $array_path);
        $arr_pdf_merge_last[1] = $content_page_last;
        ksort($arr_pdf_merge_last);

        $data_merge = [ 'first_merge' => $arr_pdf_merge,
            'last_merge' =>$arr_pdf_merge_last,
            'path_burst' => $path_burst,
        ];
        return $data_merge;
    }
    // cover pdf
    public function generateCoverAssessmentWaterPdf($assessment, $hazards, $is_pdf){
        $property = $assessment->property;
        $temp_path = storage_path('/app');
        $count_very_high_risk_hazard = $count_high_risk_hazard = $count_medium_risk_hazard =
        $count_low_risk_hazard = $count_very_low_risk_hazard = $count_no_risk_hazard = 0;
        if($hazards && count($hazards)){
            foreach ($hazards as $hazard){
                if($hazard->total_risk  == 0){
                    $count_no_risk_hazard++;
                } else if($hazard->total_risk < 4){
                    $count_very_low_risk_hazard++;
                } else if($hazard->total_risk < 10){
                    $count_low_risk_hazard++;
                } else if($hazard->total_risk < 16){
                    $count_medium_risk_hazard++;
                }  else if($hazard->total_risk < 21){
                    $count_high_risk_hazard++;
                }  else if($hazard->total_risk < 26){
                    $count_very_high_risk_hazard++;
                }
            }
        }
        //new megered pdf file
        $pdf_cover = PDF::loadView('shineCompliance.pdf.water.cover', compact(
            'assessment', 'property','count_no_risk_hazard','count_very_low_risk_hazard',
            'count_low_risk_hazard','count_medium_risk_hazard','count_high_risk_hazard',
            'count_very_high_risk_hazard','is_pdf'
        ))->setOption('margin-bottom', MARGIN_BOTTOM)
            ->setOption('margin-top', MARGIN_TOP)
            ->setOption('margin-right', 0)
            ->setOption('margin-left', 0)
            ->setPaper('a4','portrait');

        $file_name1 = $assessment->reference . '_1_' . \Str::random(10).".pdf";

        $save_path1 = $temp_path ."/" . $file_name1;
        // allow overwrite
        $pdf_cover->save($save_path1, true);
        $pdf_cover->resetOptions();

        return $save_path1;
    }

    public function generateCoverAssessmentHSPdf($assessment, $hazards, $is_pdf){
        $property = $assessment->property;
        $temp_path = storage_path('/app');
        $count_very_high_risk_hazard = $count_high_risk_hazard = $count_medium_risk_hazard =
        $count_low_risk_hazard = $count_very_low_risk_hazard = $count_no_risk_hazard = 0;
        if($hazards && count($hazards)){
            foreach ($hazards as $hazard){
                if($hazard->total_risk  == 0){
                    $count_no_risk_hazard++;
                } else if($hazard->total_risk < 4){
                    $count_very_low_risk_hazard++;
                } else if($hazard->total_risk < 10){
                    $count_low_risk_hazard++;
                } else if($hazard->total_risk < 16){
                    $count_medium_risk_hazard++;
                }  else if($hazard->total_risk < 21){
                    $count_high_risk_hazard++;
                }  else if($hazard->total_risk < 26){
                    $count_very_high_risk_hazard++;
                }
            }
        }
        //new megered pdf file
        $pdf_cover = PDF::loadView('shineCompliance.pdf.hs.cover', compact(
            'assessment', 'property','count_no_risk_hazard','count_very_low_risk_hazard',
            'count_low_risk_hazard','count_medium_risk_hazard','count_high_risk_hazard',
            'count_very_high_risk_hazard','is_pdf'
        ))->setOption('margin-bottom', MARGIN_BOTTOM)
            ->setOption('margin-top', MARGIN_TOP)
            ->setOption('margin-right', 0)
            ->setOption('margin-left', 0)
            ->setPaper('a4','portrait');

        $file_name1 = $assessment->reference . '_1_' . \Str::random(10).".pdf";

        $save_path1 = $temp_path ."/" . $file_name1;
        // allow overwrite
        $pdf_cover->save($save_path1, true);
        $pdf_cover->resetOptions();

        return $save_path1;
    }
    // content page
    public function generateContentPageAssessmentWaterPdf($assessment, $is_pdf, $offset = 0, $total_page = 0, $first_gen = true, $tocData = []){

        if($first_gen){
            $pdf_content_page = PDF::loadView('shineCompliance.pdf.water.content_page_water', compact(
                'assessment','is_pdf'
            ))
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');
        } else {
            // DD(1, $offset, $total_page, $first_gen);
            $pdf_content_page = PDF::loadView('shineCompliance.pdf.water.content_page_water', compact(
                'assessment','is_pdf', 'tocData'
            ))
                ->setOption('footer-html', view('shineCompliance.pdf.assessment_footer',[
                    'is_pdf' => true,
                    'current' => $offset ?? 0,
                    'total' => $total_page ?? 0,
                    'assessment' => $assessment,
                    'property' => $assessment->property ?? null]))
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');
        }

        $file_name2 = '2_' . \Str::random(10).".pdf";
        $temp_path = storage_path('/app');
        $save_path2 = $temp_path ."/" . $file_name2;
        // allow overwrite
        $pdf_content_page->save($save_path2, true);
        $pdf_content_page->resetOptions();

        return $save_path2;
    }

    // content page
    public function generateContentPageAssessmentHSPdf($assessment, $is_pdf,$new_title, $offset = 0, $total_page = 0, $first_gen = true, $tocData = []){

        if($first_gen){
            $pdf_content_page = PDF::loadView('shineCompliance.pdf.hs.content_page_hs', compact(
                'assessment','is_pdf','new_title'
            ))
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');
        } else {
            // DD(1, $offset, $total_page, $first_gen);
            $pdf_content_page = PDF::loadView('shineCompliance.pdf.hs.content_page_hs', compact(
                'assessment','is_pdf', 'tocData','new_title'
            ))
                ->setOption('footer-html', view('shineCompliance.pdf.assessment_footer',[
                    'is_pdf' => true,
                    'current' => $offset ?? 0,
                    'total' => $total_page ?? 0,
                    'assessment' => $assessment,
                    'property' => $assessment->property ?? null]))
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');
        }

        $file_name2 = '2_' . \Str::random(10).".pdf";
        $temp_path = storage_path('/app');
        $save_path2 = $temp_path ."/" . $file_name2;
        // allow overwrite
        $pdf_content_page->save($save_path2, true);
        $pdf_content_page->resetOptions();

        return $save_path2;
    }
    // page 1,2,3
    public function generateExecutivePageAssessmentWaterPdf($assessment, $view, $is_pdf, $offset = 0, $total_page = 0, $first_gen = true){
        if($first_gen){
            $pdf_executive_page = PDF::loadView('shineCompliance.pdf.water.' . $view, compact(
                'assessment','is_pdf'
            ))
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');

        } else {
            $pdf_executive_page = PDF::loadView('shineCompliance.pdf.water.' . $view, compact(
                'assessment','is_pdf'
            ))
                ->setOption('footer-html', view('shineCompliance.pdf.assessment_footer',[
                    'is_pdf' => true,
                    'current' => $offset ?? 0,
                    'total' => $total_page ?? 0,
                    'assessment' => $assessment,
                    'property' => $assessment->property ?? null]))
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');

        }

        $file_name3 = '3_' . \Str::random(10).".pdf";
        $temp_path = storage_path('/app');
        $save_path3 = $temp_path ."/" . $file_name3;
        // allow overwrite
        $pdf_executive_page->save($save_path3, true);
        $pdf_executive_page->resetOptions();
        return $save_path3;
    }

    // page 1,2,3
    public function generateExecutivePageAssessmentHsPdf($assessment, $view, $is_pdf,$new_title = [], $offset = 0, $total_page = 0, $first_gen = true){
        if($first_gen){
            $pdf_executive_page = PDF::loadView('shineCompliance.pdf.hs.' . $view, compact(
                'assessment','is_pdf','new_title'
            ))
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');

        } else {
            $pdf_executive_page = PDF::loadView('shineCompliance.pdf.hs.' . $view, compact(
                'assessment','is_pdf', 'new_title'
            ))
                ->setOption('footer-html', view('shineCompliance.pdf.assessment_footer',[
                    'is_pdf' => true,
                    'current' => $offset ?? 0,
                    'total' => $total_page ?? 0,
                    'assessment' => $assessment,
                    'property' => $assessment->property ?? null]))
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');

        }

        $file_name3 = '3_' . \Str::random(10).".pdf";
        $temp_path = storage_path('/app');
        $save_path3 = $temp_path ."/" . $file_name3;
        // allow overwrite
        $pdf_executive_page->save($save_path3, true);
        $pdf_executive_page->resetOptions();
        return $save_path3;
    }
    // page 6
    public function generateNonAssessedPageAssessmentWaterPdf($assessment, $equipments, $is_pdf, $offset = 0, $total_page = 0, $first_gen = true){
        if($first_gen){
            $pdf_non_assessed_page = PDF::loadView('shineCompliance.pdf.water.7_non_assessed_room_equipment', compact(
                'assessment','equipments','is_pdf'
            ))
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');
        } else {
            $pdf_non_assessed_page = PDF::loadView('shineCompliance.pdf.water.7_non_assessed_room_equipment', compact(
                'assessment','equipments','is_pdf'
            ))
                ->setOption('footer-html', view('shineCompliance.pdf.assessment_footer',[
                    'is_pdf' => true,
                    'current' => $offset ?? 0,
                    'total' => $total_page ?? 0,
                    'assessment' => $assessment,
                    'property' => $assessment->property ?? null]))
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');
        }


        $file_name5 = '5_' . \Str::random(10).".pdf";
        $temp_path = storage_path('/app');
        $save_path5 = $temp_path ."/" . $file_name5;
        // allow overwrite
        $pdf_non_assessed_page->save($save_path5, true);
        $pdf_non_assessed_page->resetOptions();

        return $save_path5;
    }

    // page 6
    public function generateNonAssessedPageAssessmentHsPdf($assessment, $equipments, $is_pdf, $new_title= [],$offset = 0, $total_page = 0, $first_gen = true){
        if($first_gen){
            $pdf_non_assessed_page = PDF::loadView('shineCompliance.pdf.hs.7_non_assessed_room_equipment', compact(
                'assessment','equipments','is_pdf','new_title'
            ))
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');
        } else {
            $pdf_non_assessed_page = PDF::loadView('shineCompliance.pdf.hs.7_non_assessed_room_equipment', compact(
                'assessment','equipments','is_pdf','new_title'
            ))
                ->setOption('footer-html', view('shineCompliance.pdf.assessment_footer',[
                    'is_pdf' => true,
                    'current' => $offset ?? 0,
                    'total' => $total_page ?? 0,
                    'assessment' => $assessment,
                    'property' => $assessment->property ?? null]))
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');
        }


        $file_name5 = '5_' . \Str::random(10).".pdf";
        $temp_path = storage_path('/app');
        $save_path5 = $temp_path ."/" . $file_name5;
        // allow overwrite
        $pdf_non_assessed_page->save($save_path5, true);
        $pdf_non_assessed_page->resetOptions();

        return $save_path5;
    }
    // page 6
    public function generateAuditPageAssessmentWaterPdf($assessment, $is_pdf, $offset = 0, $total_page = 0, $first_gen = true){
        $sections = $this->assessmentSectionRepository->getQuestionnaire($assessment->id, $assessment->classification);
        if($first_gen){
            $pdf_audit_page = PDF::loadView('shineCompliance.pdf.water.8_water_risk_audit', compact(
                'assessment','is_pdf','sections'
            ))
                ->setPaper('a4','landscape')
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT);
        } else {
            $pdf_audit_page = PDF::loadView('shineCompliance.pdf.water.8_water_risk_audit', compact(
                'assessment','is_pdf','sections'
            ))
                ->setOption('footer-html', view('shineCompliance.pdf.assessment_footer_landscape',[
                    'is_pdf' => true,
                    'current' => $offset ?? 0,
                    'total' => $total_page ?? 0,
                    'assessment' => $assessment,
                    'property' => $assessment->property ?? null]))
                ->setPaper('a4','landscape')
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT);
        }


        $file_name6 = '6_' . \Str::random(10).".pdf";
        $temp_path = storage_path('/app');
        $save_path6 = $temp_path ."/" . $file_name6;
        // allow overwrite
        $pdf_audit_page->save($save_path6, true);
        $pdf_audit_page->resetOptions();
        return $save_path6;
    }
    // page 6
    public function generateAuditPageAssessmentHsPdf($assessment, $is_pdf, $new_title = [], $offset = 0, $total_page = 0, $first_gen = true){
        $sections = $this->assessmentSectionRepository->getQuestionnaire($assessment->id, $assessment->classification);
        if($first_gen){
            $pdf_audit_page = PDF::loadView('shineCompliance.pdf.hs.8_hs_risk_audit', compact(
                'assessment','is_pdf','sections'
            ))
                ->setPaper('a4','landscape')
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT);
        } else {
            $pdf_audit_page = PDF::loadView('shineCompliance.pdf.hs.8_hs_risk_audit', compact(
                'assessment','is_pdf','sections','new_title'
            ))
                ->setOption('footer-html', view('shineCompliance.pdf.assessment_footer_landscape',[
                    'is_pdf' => true,
                    'current' => $offset ?? 0,
                    'total' => $total_page ?? 0,
                    'assessment' => $assessment,
                    'property' => $assessment->property ?? null]))
                ->setPaper('a4','landscape')
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT);
        }


        $file_name6 = '6_' . \Str::random(10).".pdf";
        $temp_path = storage_path('/app');
        $save_path6 = $temp_path ."/" . $file_name6;
        // allow overwrite
        $pdf_audit_page->save($save_path6, true);
        $pdf_audit_page->resetOptions();
        return $save_path6;
    }
    // page 4,5 hazard and outlet
    public function generateWaterHazardAndOutletPdf($assessment, $hazards, $view, $offset = 0, $total_page = 0, $first_gen = true){

        $vhigh_risk_hazards = $high_risk_hazards = $medium_risk_hazards = $low_risk_hazards = $vlow_risk_hazards = [];
        foreach ($hazards as $key => $hazard) {
            if ($hazard->total_risk >= 21) {
                $vhigh_risk_hazards[] = $hazard;
            }
            if ($hazard->total_risk >= 16 and $hazard->total_risk <= 20) {
                $high_risk_hazards[] = $hazard;
            }
            if ($hazard->total_risk >= 10 and $hazard->total_risk <= 15) {
                $medium_risk_hazards[] = $hazard;
            }
            if ($hazard->total_risk >= 4 and $hazard->total_risk <= 9) {
                $low_risk_hazards[] = $hazard;
            }
            if ($hazard->total_risk < 4) {
                $vlow_risk_hazards[] = $hazard;
            }
        }
        $data_outlet = $this->equipmentRepository->getOutletRegister($assessment->id);
        $count_hazard = count($hazards);

        $data_pdf = [
            'data_outlets' =>  $data_outlet['data'],
            'outlet_names' =>  $data_outlet['outlet_names'],
            'all_outlet_name_ids' =>  $data_outlet['all_outlet_name_ids'],
            'count_outlet' =>  $data_outlet['count_outlet'],
            'vhigh_risk_hazards' =>  $vhigh_risk_hazards,
            'high_risk_hazards' =>  $high_risk_hazards,
            'medium_risk_hazards' =>  $medium_risk_hazards,
            'low_risk_hazards' =>  $low_risk_hazards,
            'vlow_risk_hazards' =>  $vlow_risk_hazards,
            'count_hazard' =>  $count_hazard,
            'assessment' =>  $assessment,
            'is_pdf' => true,
        ];

        $temp_path = storage_path('/app');
        if($first_gen){
            $pdf_hazard_outlet = PDF::loadView('shineCompliance.pdf.water.' . $view, $data_pdf)
                ->setPaper('a4','landscape')
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT);
        } else {
            $pdf_hazard_outlet = PDF::loadView('shineCompliance.pdf.water.' . $view, $data_pdf)
                ->setOption('footer-html', view('shineCompliance.pdf.assessment_footer_landscape',[
                    'is_pdf' => true,
                    'current' => $offset ?? 0,
                    'total' => $total_page ?? 0,
                    'assessment' => $assessment,
                    'property' => $assessment->property ?? null]))
                ->setPaper('a4','landscape')
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT);
        }


        $file_name = $assessment->reference . '_7_8' . \Str::random(10).".pdf";

        $save_path = $temp_path ."/" . $file_name;
        // allow overwrite
        $pdf_hazard_outlet->save($save_path, true);
        $pdf_hazard_outlet->resetOptions();
        return $save_path;
    }
    // page 4,5 hazard and outlet
    public function generateHsHazardAndOutletPdf($assessment, $hazards, $view,$new_title = [], $offset = 0, $total_page = 0, $first_gen = true){

        $vhigh_risk_hazards = $high_risk_hazards = $medium_risk_hazards = $low_risk_hazards = $vlow_risk_hazards = [];
        foreach ($hazards as $key => $hazard) {
            if ($hazard->total_risk >= 21) {
                $vhigh_risk_hazards[] = $hazard;
            }
            if ($hazard->total_risk >= 16 and $hazard->total_risk <= 20) {
                $high_risk_hazards[] = $hazard;
            }
            if ($hazard->total_risk >= 10 and $hazard->total_risk <= 15) {
                $medium_risk_hazards[] = $hazard;
            }
            if ($hazard->total_risk >= 4 and $hazard->total_risk <= 9) {
                $low_risk_hazards[] = $hazard;
            }
            if ($hazard->total_risk < 4) {
                $vlow_risk_hazards[] = $hazard;
            }
        }
        $data_outlet = $this->equipmentRepository->getOutletRegister($assessment->id);
        $count_hazard = count($hazards);

        $data_pdf = [
            'data_outlets' =>  $data_outlet['data'],
            'outlet_names' =>  $data_outlet['outlet_names'],
            'all_outlet_name_ids' =>  $data_outlet['all_outlet_name_ids'],
            'count_outlet' =>  $data_outlet['count_outlet'],
            'vhigh_risk_hazards' =>  $vhigh_risk_hazards,
            'high_risk_hazards' =>  $high_risk_hazards,
            'medium_risk_hazards' =>  $medium_risk_hazards,
            'low_risk_hazards' =>  $low_risk_hazards,
            'vlow_risk_hazards' =>  $vlow_risk_hazards,
            'count_hazard' =>  $count_hazard,
            'new_title' => $new_title,
            'assessment' =>  $assessment,
            'is_pdf' => true,
        ];

        $temp_path = storage_path('/app');
        if($first_gen){
            $pdf_hazard_outlet = PDF::loadView('shineCompliance.pdf.hs.' . $view, $data_pdf)
                ->setPaper('a4','landscape')
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT);
        } else {
            $pdf_hazard_outlet = PDF::loadView('shineCompliance.pdf.hs.' . $view, $data_pdf)
                ->setOption('footer-html', view('shineCompliance.pdf.assessment_footer_landscape',[
                    'is_pdf' => true,
                    'current' => $offset ?? 0,
                    'total' => $total_page ?? 0,
                    'assessment' => $assessment,
                    'property' => $assessment->property ?? null]))
                ->setPaper('a4','landscape')
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT);
        }


        $file_name = $assessment->reference . '_7_8' . \Str::random(10).".pdf";

        $save_path = $temp_path ."/" . $file_name;
        // allow overwrite
        $pdf_hazard_outlet->save($save_path, true);
        $pdf_hazard_outlet->resetOptions();
        return $save_path;
    }
    // page 7, 8 equipment and system

    public function generateWaterEquipmentPdf($assessment, $equipments, $systems, $view, $offset = 0, $total_page = 0, $first_gen = true){
        $miscs = $mixer_outlets = $cold_water_outlets = $hot_water_outlets = $point_use_heaters = $hot_water_services = $cold_storage_tanks = $incoming_mains =  [];
        $boilers = $calorifires = $hot_heaters = $instants = $combined_outlet_hots = $combined_outlet_colds  = $combined_outlet_mixers = $combined_outlet_cold_and_hots = $tmvs = [];
        foreach ($equipments as $equipment) {
            $template_id = $equipment->equipmentType->template_id ?? 0;
            if ($template_id == 1) {
                $miscs[] = $equipment;
            }
            if ($template_id == 2) {
                $hot_water_services[] = $equipment;
            }
            if ($template_id == 3) {
                $cold_storage_tanks[] = $equipment;
            }
            if ($template_id == 4) {
                $cold_water_outlets[] = $equipment;
            }
            if ($template_id == 5) {
                $mixer_outlets[] = $equipment;
            }
            if ($template_id == 6) {
                $hot_water_outlets[] = $equipment;
            }
            if ($template_id == 7) {
                $point_use_heaters[] = $equipment;
            }
            if ($template_id == 8) {
                $incoming_mains[] = $equipment;
            }
            if ($template_id == 9) {
                $combined_outlet_colds[] = $equipment;
            }
            if ($template_id == 10) {
                $boilers[] = $equipment;
            }
            if ($template_id == 11) {
                $calorifires[] = $equipment;
            }
            if ($template_id == 12) {
                $hot_heaters[] = $equipment;
            }
            if ($template_id == 13) {
                $instants[] = $equipment;
            }
            if ($template_id == 14) {
                $combined_outlet_hots[] = $equipment;
            }
            if ($template_id == 15) {
                $combined_outlet_mixers[] = $equipment;
            }
            if ($template_id == 16) {
                $combined_outlet_cold_and_hots[] = $equipment;
            }
            if ($template_id == 17) {
                $tmvs[] = $equipment;
            }
        }

        $data_equipment_pdf = [
            'miscs' => $miscs,
            'hot_water_services' => $hot_water_services,
            'cold_storage_tanks' => $cold_storage_tanks,
            'cold_water_outlets' => $cold_water_outlets,
            'mixer_outlets' => $mixer_outlets,
            'hot_water_outlets' => $hot_water_outlets,
            'point_use_heaters' => $point_use_heaters,
            'incoming_mains' => $incoming_mains,
            'systems' => $systems,
            'assessment' => $assessment,
            'boilers' => $boilers ,
            'calorifires' => $calorifires ,
            'hot_heaters' => $hot_heaters ,
            'instants' => $instants ,
            'combined_outlet_hots' => $combined_outlet_hots ,
            'combined_outlet_colds' => $combined_outlet_colds  ,
            'combined_outlet_mixers' => $combined_outlet_mixers ,
            'combined_outlet_cold_and_hots' => $combined_outlet_cold_and_hots ,
            'tmvs' => $tmvs,
            'is_pdf' => true,
        ];
        $temp_path = storage_path('/app');
        if($first_gen){
            $pdf_equipment = PDF::loadView('shineCompliance.pdf.water.' . $view, $data_equipment_pdf)
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');
        } else {
            $pdf_equipment = PDF::loadView('shineCompliance.pdf.water.' . $view, $data_equipment_pdf)
            ->setOption('footer-html', view('shineCompliance.pdf.assessment_footer',[
                    'is_pdf' => true,
                    'current' => $offset ?? 0,
                    'total' => $total_page ?? 0,
                    'assessment' => $assessment,
                    'property' => $assessment->property ?? null]))
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');
        }


        $file_name5 = $assessment->reference . '_7_8_' . \Str::random(10).".pdf";

        $save_path5 = $temp_path ."/" . $file_name5;
        // allow overwrite
        $pdf_equipment->save($save_path5, true);
        $pdf_equipment->resetOptions();

        return $save_path5;
    }

    public function generateHsEquipmentPdf($assessment, $equipments, $systems, $view, $new_title = [], $offset = 0, $total_page = 0, $first_gen = true){
        $miscs = $mixer_outlets = $cold_water_outlets = $hot_water_outlets = $point_use_heaters = $hot_water_services = $cold_storage_tanks = $incoming_mains =  [];
        $boilers = $calorifires = $hot_heaters = $instants = $combined_outlet_hots = $combined_outlet_colds  = $combined_outlet_mixers = $combined_outlet_cold_and_hots = $tmvs = [];
        foreach ($equipments as $equipment) {
            $template_id = $equipment->equipmentType->template_id ?? 0;
            if ($template_id == 1) {
                $miscs[] = $equipment;
            }
            if ($template_id == 2) {
                $hot_water_services[] = $equipment;
            }
            if ($template_id == 3) {
                $cold_storage_tanks[] = $equipment;
            }
            if ($template_id == 4) {
                $cold_water_outlets[] = $equipment;
            }
            if ($template_id == 5) {
                $mixer_outlets[] = $equipment;
            }
            if ($template_id == 6) {
                $hot_water_outlets[] = $equipment;
            }
            if ($template_id == 7) {
                $point_use_heaters[] = $equipment;
            }
            if ($template_id == 8) {
                $incoming_mains[] = $equipment;
            }
            if ($template_id == 9) {
                $combined_outlet_colds[] = $equipment;
            }
            if ($template_id == 10) {
                $boilers[] = $equipment;
            }
            if ($template_id == 11) {
                $calorifires[] = $equipment;
            }
            if ($template_id == 12) {
                $hot_heaters[] = $equipment;
            }
            if ($template_id == 13) {
                $instants[] = $equipment;
            }
            if ($template_id == 14) {
                $combined_outlet_hots[] = $equipment;
            }
            if ($template_id == 15) {
                $combined_outlet_mixers[] = $equipment;
            }
            if ($template_id == 16) {
                $combined_outlet_cold_and_hots[] = $equipment;
            }
            if ($template_id == 17) {
                $tmvs[] = $equipment;
            }
        }

        $data_equipment_pdf = [
            'miscs' => $miscs,
            'hot_water_services' => $hot_water_services,
            'cold_storage_tanks' => $cold_storage_tanks,
            'cold_water_outlets' => $cold_water_outlets,
            'mixer_outlets' => $mixer_outlets,
            'hot_water_outlets' => $hot_water_outlets,
            'point_use_heaters' => $point_use_heaters,
            'incoming_mains' => $incoming_mains,
            'systems' => $systems,
            'assessment' => $assessment,
            'boilers' => $boilers ,
            'calorifires' => $calorifires ,
            'hot_heaters' => $hot_heaters ,
            'instants' => $instants ,
            'combined_outlet_hots' => $combined_outlet_hots ,
            'combined_outlet_colds' => $combined_outlet_colds  ,
            'combined_outlet_mixers' => $combined_outlet_mixers ,
            'combined_outlet_cold_and_hots' => $combined_outlet_cold_and_hots ,
            'tmvs' => $tmvs,
            'new_title' => $new_title,
            'is_pdf' => true,
        ];
        $temp_path = storage_path('/app');
        if($first_gen){
            $pdf_equipment = PDF::loadView('shineCompliance.pdf.hs.' . $view, $data_equipment_pdf)
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');
        } else {
            $pdf_equipment = PDF::loadView('shineCompliance.pdf.hs.' . $view, $data_equipment_pdf)
            ->setOption('footer-html', view('shineCompliance.pdf.assessment_footer',[
                    'is_pdf' => true,
                    'current' => $offset ?? 0,
                    'total' => $total_page ?? 0,
                    'assessment' => $assessment,
                    'property' => $assessment->property ?? null]))
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');
        }


        $file_name5 = $assessment->reference . '_7_8_' . \Str::random(10).".pdf";

        $save_path5 = $temp_path ."/" . $file_name5;
        // allow overwrite
        $pdf_equipment->save($save_path5, true);
        $pdf_equipment->resetOptions();

        return $save_path5;
    }

    // cover fire pdf
    public function generateCoverAssessmentFirePdf($assessment, $hazards, $is_pdf){
        $property = $assessment->property;
        $temp_path = storage_path('/app');
        $count_very_high_risk_hazard = $count_high_risk_hazard = $count_medium_risk_hazard =
        $count_low_risk_hazard = $count_very_low_risk_hazard = $count_no_risk_hazard = 0;
        if($hazards && count($hazards)){
            foreach ($hazards as $hazard){
                if($hazard->total_risk  == 0){
                    $count_no_risk_hazard++;
                } else if($hazard->total_risk < 4){
                    $count_very_low_risk_hazard++;
                } else if($hazard->total_risk < 10){
                    $count_low_risk_hazard++;
                } else if($hazard->total_risk < 16){
                    $count_medium_risk_hazard++;
                }  else if($hazard->total_risk < 21){
                    $count_high_risk_hazard++;
                }  else if($hazard->total_risk < 26){
                    $count_very_high_risk_hazard++;
                }
            }
        }
        //new megered pdf file
        $pdf_cover = PDF::loadView('shineCompliance.pdf.fire.cover', compact(
            'assessment', 'property','count_no_risk_hazard','count_very_low_risk_hazard',
                     'count_low_risk_hazard','count_medium_risk_hazard','count_high_risk_hazard',
                     'count_very_high_risk_hazard','is_pdf'
        ))->setOption('margin-bottom', MARGIN_BOTTOM)
            ->setOption('margin-top', MARGIN_TOP)
            ->setOption('margin-right', 0)
            ->setOption('margin-left', 0)
            ->setPaper('a4','portrait');

        $file_name1 = $assessment->reference . '_1_' . \Str::random(10).".pdf";

        $save_path1 = $temp_path ."/" . $file_name1;
        // allow overwrite
        $pdf_cover->save($save_path1, true);
        $pdf_cover->resetOptions();

        return $save_path1;
    }

    // content page fire
    public function generateContentPageAssessmentFirePdf($assessment, $is_pdf, $offset = 0, $total_page = 0, $first_gen = true){

        if($first_gen){
            $pdf_content_page = PDF::loadView('shineCompliance.pdf.fire.content_page_fire', compact(
                'assessment','is_pdf'
            ))
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');
        } else {
        // DD(1, $offset, $total_page, $first_gen);
            $pdf_content_page = PDF::loadView('shineCompliance.pdf.fire.content_page_fire', compact(
                'assessment','is_pdf'
            ))
                ->setOption('footer-html', view('shineCompliance.pdf.assessment_footer',[
                    'is_pdf' => true,
                    'current' => $offset ?? 0,
                    'total' => $total_page ?? 0,
                    'assessment' => $assessment,
                    'property' => $assessment->property ?? null]))
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');
        }

        $file_name2 = '2_' . \Str::random(10).".pdf";
        $temp_path = storage_path('/app');
        $save_path2 = $temp_path ."/" . $file_name2;
        // allow overwrite
        $pdf_content_page->save($save_path2, true);
        $pdf_content_page->resetOptions();

        return $save_path2;
    }
    // page 1,2,3 fire
    public function generateExecutivePageAssessmentFirePdf($assessment, $is_pdf, $offset = 0, $total_page = 0, $first_gen = true){
        if($first_gen){
            $pdf_executive_page = PDF::loadView('shineCompliance.pdf.fire.1_2_3_property_info_and_executive_summary', compact(
                'assessment','is_pdf'
            ))
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');

        } else {
            $pdf_executive_page = PDF::loadView('shineCompliance.pdf.fire.1_2_3_property_info_and_executive_summary', compact(
                'assessment','is_pdf'
            ))
            ->setOption('footer-html', view('shineCompliance.pdf.assessment_footer',[
                    'is_pdf' => true,
                    'current' => $offset ?? 0,
                    'total' => $total_page ?? 0,
                    'assessment' => $assessment,
                    'property' => $assessment->property ?? null]))
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');

        }

        $file_name3 = '3_' . \Str::random(10).".pdf";
        $temp_path = storage_path('/app');
        $save_path3 = $temp_path ."/" . $file_name3;
        // allow overwrite
        $pdf_executive_page->save($save_path3, true);
        $pdf_executive_page->resetOptions();
        return $save_path3;
    }

    public function generateManagementInfoAssessmentFirePdf($assessment, $offset = 0, $total_page = 0, $first_gen = true)
    {
        $managementInfoQueries = $this->managementQuestionRepository->getManagementQuestionsByAssessId($assessment->id);
        $otherInfoQueries = $this->otherQuestionRepository->getOtherQuestionsByAssessId($assessment->id);

        if($first_gen){
            $pdf = PDF::loadView('shineCompliance.pdf.fire.3_management_info_other_info',
                compact('managementInfoQueries', 'otherInfoQueries'))
                ->setPaper('a4','landscape')
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT);
        } else {
            $pdf = PDF::loadView('shineCompliance.pdf.fire.3_management_info_other_info',
                compact('managementInfoQueries', 'otherInfoQueries'))
                ->setOption('footer-html', view('shineCompliance.pdf.assessment_footer_landscape',[
                    'is_pdf' => true,
                    'current' => $offset ?? 0,
                    'total' => $total_page ?? 0,
                    'assessment' => $assessment,
                    'property' => $assessment->property ?? null]))
                ->setPaper('a4','landscape')
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT);
        }


        $file_name = $assessment->reference . '_management_info_' . \Str::random(10).".pdf";
        $temp_path = storage_path('/app');

        $save_path = $temp_path ."/" . $file_name;
        // allow overwrite
        $pdf->save($save_path, true);
        $pdf->resetOptions();
        return $save_path;
    }

    // page 6
    public function generateNonAssessedPageAssessmentFirePdf($assessment, $equipments, $is_pdf, $offset = 0, $total_page = 0, $first_gen = true){
        if($first_gen){
            $pdf_non_assessed_page = PDF::loadView('shineCompliance.pdf.fire.5_non_assessed_room_equipment', compact(
                'assessment','equipments','is_pdf'
            ))
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');
        } else {
            $pdf_non_assessed_page = PDF::loadView('shineCompliance.pdf.fire.5_non_assessed_room_equipment', compact(
                'assessment','equipments','is_pdf'
            ))
            ->setOption('footer-html', view('shineCompliance.pdf.assessment_footer',[
                    'is_pdf' => true,
                    'current' => $offset ?? 0,
                    'total' => $total_page ?? 0,
                    'assessment' => $assessment,
                    'property' => $assessment->property ?? null]))
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');
        }


        $file_name5 = '5_' . \Str::random(10).".pdf";
        $temp_path = storage_path('/app');
        $save_path5 = $temp_path ."/" . $file_name5;
        // allow overwrite
        $pdf_non_assessed_page->save($save_path5, true);
        $pdf_non_assessed_page->resetOptions();

        return $save_path5;
    }
    // page 6
    public function generateAuditPageAssessmentFirePdf($assessment, $is_pdf, $offset = 0, $total_page = 0, $first_gen = true){
        $sections = $this->assessmentSectionRepository->getQuestionnaire($assessment->id, $assessment->classification);
        if($first_gen){
            $pdf_audit_page = PDF::loadView('shineCompliance.pdf.fire.6_fire_risk_audit', compact(
                'assessment','is_pdf','sections'
            ))
                ->setPaper('a4','landscape')
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT);
        } else {
            $pdf_audit_page = PDF::loadView('shineCompliance.pdf.fire.6_fire_risk_audit', compact(
                'assessment','is_pdf','sections'
            ))
            ->setOption('footer-html', view('shineCompliance.pdf.assessment_footer_landscape',[
                    'is_pdf' => true,
                    'current' => $offset ?? 0,
                    'total' => $total_page ?? 0,
                    'assessment' => $assessment,
                    'property' => $assessment->property ?? null]))
                ->setPaper('a4','landscape')
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT);
        }


        $file_name6 = '6_' . \Str::random(10).".pdf";
        $temp_path = storage_path('/app');
        $save_path6 = $temp_path ."/" . $file_name6;
        // allow overwrite
        $pdf_audit_page->save($save_path6, true);
        $pdf_audit_page->resetOptions();
        return $save_path6;
    }

    // page 4 hazard fire
    public function generateFireHazardPdf($assessment, $hazards, $offset = 0, $total_page = 0, $first_gen = true){

        $vhigh_risk_hazards = $high_risk_hazards = $medium_risk_hazards = $low_risk_hazards = $vlow_risk_hazards = [];
        foreach ($hazards as $key => $hazard) {
            if ($hazard->total_risk >= 21) {
                $vhigh_risk_hazards[] = $hazard;
            }
            if ($hazard->total_risk >= 16 and $hazard->total_risk <= 20) {
                $high_risk_hazards[] = $hazard;
            }
            if ($hazard->total_risk >= 10 and $hazard->total_risk <= 15) {
                $medium_risk_hazards[] = $hazard;
            }
            if ($hazard->total_risk >= 4 and $hazard->total_risk <= 9) {
                $low_risk_hazards[] = $hazard;
            }
            if ($hazard->total_risk < 4) {
                $vlow_risk_hazards[] = $hazard;
            }
        }

        $count_hazard = count($hazards);

        $data_pdf = [
            'vhigh_risk_hazards' =>  $vhigh_risk_hazards,
            'high_risk_hazards' =>  $high_risk_hazards,
            'medium_risk_hazards' =>  $medium_risk_hazards,
            'low_risk_hazards' =>  $low_risk_hazards,
            'vlow_risk_hazards' =>  $vlow_risk_hazards,
            'count_hazard' =>  $count_hazard,
            'is_pdf' => true,
        ];

        $temp_path = storage_path('/app');
        if($first_gen){
            $pdf_hazard_outlet = PDF::loadView('shineCompliance.pdf.fire.4_hazard_summary_register', $data_pdf)
                ->setPaper('a4','landscape')
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT);
        } else {
            $pdf_hazard_outlet = PDF::loadView('shineCompliance.pdf.fire.4_hazard_summary_register', $data_pdf)
            ->setOption('footer-html', view('shineCompliance.pdf.assessment_footer_landscape',[
                    'is_pdf' => true,
                    'current' => $offset ?? 0,
                    'total' => $total_page ?? 0,
                    'assessment' => $assessment,
                    'property' => $assessment->property ?? null]))
                ->setPaper('a4','landscape')
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT);
        }


        $file_name = $assessment->reference . '_7_8' . \Str::random(10).".pdf";

        $save_path = $temp_path ."/" . $file_name;
        // allow overwrite
        $pdf_hazard_outlet->save($save_path, true);
        $pdf_hazard_outlet->resetOptions();
        return $save_path;
    }

    // page 7, 8 9 equipment and system and appendix fire
    public function generateFireEquipmentPdf($assessment, $equipments, $systems, $offset = 0, $total_page = 0, $first_gen = true){
        $miscs = $mixer_outlets = $cold_water_outlets = $hot_water_outlets = $point_use_heaters = $hot_water_services = $cold_storage_tanks = $incoming_mains =  [];
        $boilers = $calorifires = $hot_heaters = $instants = $combined_outlet_hots = $combined_outlet_colds  = $combined_outlet_mixers = $combined_outlet_cold_and_hots = $tmvs = [];
        foreach ($equipments as $equipment) {
            $template_id = $equipment->equipmentType->template_id ?? 0;
            if ($template_id == 1) {
                $miscs[] = $equipment;
            }
            if ($template_id == 2) {
                $hot_water_services[] = $equipment;
            }
            if ($template_id == 3) {
                $cold_storage_tanks[] = $equipment;
            }
            if ($template_id == 4) {
                $cold_water_outlets[] = $equipment;
            }
            if ($template_id == 5) {
                $mixer_outlets[] = $equipment;
            }
            if ($template_id == 6) {
                $hot_water_outlets[] = $equipment;
            }
            if ($template_id == 7) {
                $point_use_heaters[] = $equipment;
            }
            if ($template_id == 8) {
                $incoming_mains[] = $equipment;
            }
            if ($template_id == 9) {
                $combined_outlet_colds[] = $equipment;
            }
            if ($template_id == 10) {
                $boilers[] = $equipment;
            }
            if ($template_id == 11) {
                $calorifires[] = $equipment;
            }
            if ($template_id == 12) {
                $hot_heaters[] = $equipment;
            }
            if ($template_id == 13) {
                $instants[] = $equipment;
            }
            if ($template_id == 14) {
                $combined_outlet_hots[] = $equipment;
            }
            if ($template_id == 15) {
                $combined_outlet_mixers[] = $equipment;
            }
            if ($template_id == 16) {
                $combined_outlet_cold_and_hots[] = $equipment;
            }
            if ($template_id == 17) {
                $tmvs[] = $equipment;
            }
        }

        $data_equipment_pdf = [
            'miscs' => $miscs,
            'hot_water_services' => $hot_water_services,
            'cold_storage_tanks' => $cold_storage_tanks,
            'cold_water_outlets' => $cold_water_outlets,
            'mixer_outlets' => $mixer_outlets,
            'hot_water_outlets' => $hot_water_outlets,
            'point_use_heaters' => $point_use_heaters,
            'incoming_mains' => $incoming_mains,
            'systems' => $systems,
            'assessment' => $assessment,
            'boilers' => $boilers ,
            'calorifires' => $calorifires ,
            'hot_heaters' => $hot_heaters ,
            'instants' => $instants ,
            'combined_outlet_hots' => $combined_outlet_hots ,
            'combined_outlet_colds ' => $combined_outlet_colds  ,
            'combined_outlet_mixers' => $combined_outlet_mixers ,
            'combined_outlet_cold_and_hots' => $combined_outlet_cold_and_hots ,
            'tmvs' => $tmvs,
            'is_pdf' => true,
        ];
        $temp_path = storage_path('/app');
        if($first_gen){
            $pdf_equipment = PDF::loadView('shineCompliance.pdf.fire.7_8_9_system_and_equipment', $data_equipment_pdf)
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');
        } else {
            $pdf_equipment = PDF::loadView('shineCompliance.pdf.fire.7_8_9_system_and_equipment', $data_equipment_pdf)
            ->setOption('footer-html', view('shineCompliance.pdf.assessment_footer',[
                    'is_pdf' => true,
                    'current' => $offset ?? 0,
                    'total' => $total_page ?? 0,
                    'assessment' => $assessment,
                    'property' => $assessment->property ?? null]))
                ->setOption('margin-bottom', MARGIN_BOTTOM)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('margin-right', MARGIN_RIGHT)
                ->setPaper('a4','portrait');
        }


        $file_name5 = $assessment->reference . '_7_8_' . \Str::random(10).".pdf";

        $save_path5 = $temp_path ."/" . $file_name5;
        // allow overwrite
        $pdf_equipment->save($save_path5, true);
        $pdf_equipment->resetOptions();

        return $save_path5;
    }

    private function lockAssessment($assess_id)
    {
        try {
            $this->hazardRepository->lockHazardsByAssessmentId($assess_id);
            $this->equipmentRepository->lockEquipmentsByAssessmentId($assess_id);
            $this->systemRepository->lockSystemsByAssessmentId($assess_id);
            $this->vehicleParkingRepository->lockVehicleParkingByAssessmentId($assess_id);
            $this->exitRepository->lockExitsByAssessmentId($assess_id);
            $this->assemblyRepository->lockAssemblyPointsByAssessmentId($assess_id);
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }

    public function createFireRegisterPDF($property, $warning_message){
        try {
            \DB::beginTransaction();
            // miss auditrail
            // property pdf need to download as ZIP file
            $next_number = Counter::where('count_table_name','summaries')->first()->total;
            $ss_ref = "SS" . sprintf("%03d", $next_number);

            $footer_left = "Fire Register " . $property->name . " " . date("d_m_Y"); // Them reference

            $shine_ref = $property->reference ?? '';
            $count_file = SummaryPdf::where(['type' => FIRE_REGISTER_PDF,'object_id' => $property->id])->get()->count(); // only property pdf need SS111.xxx
            $file_name_next_number = ($count_file) ? "." . $count_file : "";
            $file_name = "FireRegister". "_" . $shine_ref. "_"  . date("d_m_Y") . $file_name_next_number . ".pdf";
//            //set warning for cover page
//            $risk_type_one = $risk_type_two = NULL;
//            if(isset($property->propertyType) && !$property->propertyType->isEmpty()){
//                $risk_type_one = $property->propertyType->where('id',1)->first();
//                $risk_type_two = $property->propertyType->where('id',2)->first();
//            }
            //filter fire warning message
            $warning_message = $warning_message['red_warnings'];
            $fire_warning_message = [];
            if(count($warning_message)){
                foreach ($warning_message as $warning_mes){
                    if (strpos($warning_mes, 'fire') !== false || strpos($warning_mes, 'Fire') !== false) {
                        $fire_warning_message[] = $warning_mes;
                    }
                }
            }
            $hazards = $this->hazardRepository->listRegisterHazardsByType($property->id, ASSESSMENT_FIRE_TYPE, ['area','location','hazardType','hazardPotential','hazardLikelihoodHarm']);
            $pdf = PDF::loadView('pdf.fire_register_pdf', [
                'hazards' => $hazards,
                'is_pdf' => true,
                'property' => $property,
                'ss_ref' => $ss_ref,
                'type' => FIRE_REGISTER_PDF
            ])
                ->setOption('header-font-size', 8)
                ->setOption('footer-font-size', 8)
                ->setOption('footer-right', "Page [page] of [toPage]")
                ->setOption('footer-left', $footer_left)
                ->setOption('cover', view('pdf.fire_register_cover',[
                    'property' => $property,
                    'is_pdf' => true,
                    'warning_message' => $fire_warning_message,
                    'ss_ref' => $ss_ref,
                    'type' => FIRE_REGISTER_PDF]))
            ;
            $is_local = env('APP_ENV') != 'local';
            if($is_local){
                $toc_name = "publishedSurveyToc.xsl";
                $toc_path = Config::get('view.paths')[0] . "/pdf/".$toc_name;
                $pdf->setOption('toc' , true)
                    ->setOption('xsl-style-sheet',$toc_path);
            }

            $save_path = storage_path('app/data/pdfs/registers') ."/" . $file_name;
            //for overwrite
            $pdf->save($save_path, true);
            //update reference will be name not null like before
            SummaryPdf::create([
                'reference'=> $ss_ref,
                'type'=> FIRE_REGISTER_PDF,
                'object_id'=> $property->id,
                'file_name'=> $file_name,
                'path'=> $save_path
            ]);
            //log audit
            $comment = \Auth::user()->full_name  . " download fire register PDF "  . $ss_ref . ' on '. optional($property)->reference;
            \CommonHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_DOWNLOAD, $ss_ref, $property->id ,$comment, 0 ,$property->id);
            Counter::where('count_table_name','summaries')->update(['total'=> $next_number +1]);
            \DB::commit();
            return file_exists($save_path) ? ['path' => $save_path,'file_name' => $file_name] :  [];
        } catch (\Exception $e){
            dd($e);
            Log::error($e);
            \DB::rollBack();
        }
        return [];
    }

    public function createWaterRegisterPDF($property, $warning_message){
        try {
            \DB::beginTransaction();
            // miss auditrail
            // property pdf need to download as ZIP file
            $next_number = Counter::where('count_table_name','summaries')->first()->total;
            $ss_ref = "SS" . sprintf("%03d", $next_number);

            $footer_left = "Water Register " . $property->name . " " . date("d_m_Y"); // Them reference

            $shine_ref = $property->reference ?? '';
            $count_file = SummaryPdf::where(['type' => WATER_REGISTER_PDF,'object_id' => $property->id])->get()->count(); // only property pdf need SS111.xxx
            $file_name_next_number = ($count_file) ? "." . $count_file : "";
            $file_name = "WaterRegister". "_" . $shine_ref. "_"  . date("d_m_Y") . $file_name_next_number . ".pdf";
//            //set warning for cover page
//            $risk_type_one = $risk_type_two = NULL;
//            if(isset($property->propertyType) && !$property->propertyType->isEmpty()){
//                $risk_type_one = $property->propertyType->where('id',1)->first();
//                $risk_type_two = $property->propertyType->where('id',2)->first();
//            }
            //filter fire warning message
            $warning_message = $warning_message['red_warnings'];
            $fire_warning_message = [];
            if(count($warning_message)){
                foreach ($warning_message as $warning_mes){
                    if (strpos($warning_mes, 'water') !== false || strpos($warning_mes, 'Water') !== false) {
                        $fire_warning_message[] = $warning_mes;
                    }
                }
            }
            $hazards = $this->hazardRepository->listRegisterHazardsByType($property->id, ASSESSMENT_WATER_TYPE, ['area','location','hazardType','hazardPotential','hazardLikelihoodHarm']);
            $pdf = PDF::loadView('pdf.water_register_pdf', [
                'hazards' => $hazards,
                'is_pdf' => true,
                'property' => $property,
                'ss_ref' => $ss_ref,
                'type' => FIRE_REGISTER_PDF
            ])
                ->setOption('header-font-size', 8)
                ->setOption('footer-font-size', 8)
                ->setOption('footer-right', "Page [page] of [toPage]")
                ->setOption('footer-left', $footer_left)
                ->setOption('cover', view('pdf.water_register_cover',[
                    'property' => $property,
                    'is_pdf' => true,
                    'warning_message' => $fire_warning_message,
                    'ss_ref' => $ss_ref,
                    'type' => FIRE_REGISTER_PDF]))
            ;
            $is_local = env('APP_ENV') != 'local';
            if($is_local){
                $toc_name = "publishedSurveyToc.xsl";
                $toc_path = Config::get('view.paths')[0] . "/pdf/".$toc_name;
                $pdf->setOption('toc' , true)
                    ->setOption('xsl-style-sheet',$toc_path);
            }

            $save_path = storage_path('app/data/pdfs/registers') ."/" . $file_name;
            //for overwrite
            $pdf->save($save_path, true);
            //update reference will be name not null like before
            SummaryPdf::create([
                'reference'=> $ss_ref,
                'type'=> WATER_REGISTER_PDF,
                'object_id'=> $property->id,
                'file_name'=> $file_name,
                'path'=> $save_path
            ]);
            //log audit
            $comment = \Auth::user()->full_name  . " download water register PDF "  . $ss_ref . ' on '. optional($property)->reference;
            \CommonHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_DOWNLOAD, $ss_ref, $property->id ,$comment, 0 ,$property->id);
            Counter::where('count_table_name','summaries')->update(['total'=> $next_number +1]);
            \DB::commit();
            return file_exists($save_path) ? ['path' => $save_path,'file_name' => $file_name] :  [];
        } catch (\Exception $e){
            dd($e);
            Log::error($e);
            \DB::rollBack();
        }
        return [];
    }

    public function viewFireRegisterPDF($property, $warning_message){
        try {
            $ss_ref = "";

//            $shine_ref = $property->reference ?? '';
//            $file_name = "FireRegister". "_" . $shine_ref. "_"  . date("d_m_Y") .  ".pdf";
            //filter fire warning message
            $warning_message = $warning_message['red_warnings'];
            $fire_warning_message = [];
            if(count($warning_message)){
                foreach ($warning_message as $warning_mes){
                    if (strpos($warning_mes, 'fire') !== false || strpos($warning_mes, 'Fire') !== false) {
                        $fire_warning_message[] = $warning_mes;
                    }
                }
            }

            $hazards = $this->hazardRepository->listRegisterHazardsByType($property->id, ASSESSMENT_FIRE_TYPE, ['area','location','hazardType','hazardPotential','hazardLikelihoodHarm']);
            return view('pdf.fire_register_pdf', [
                'hazards' => $hazards,
                'is_pdf' => false,
                'property' => $property,
                'ss_ref' => $ss_ref,
                'type' => FIRE_REGISTER_PDF
            ]);
            //log audit
            $comment = \Auth::user()->full_name  . " view fire register PDF "  . $ss_ref . ' on '. optional($property)->reference;
            \CommonHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_VIEW, $ss_ref, $property->id ,$comment, 0 ,$property->id);
//            return file_exists($save_path) ? ['path' => $save_path,'file_name' => $file_name] :  [];
        } catch (\Exception $e){
            dd($e);
            Log::error($e);
        }
        abort(404);
    }

    public function viewWaterRegisterPDF($property, $warning_message){
        try {
            $ss_ref = "";

//            $shine_ref = $property->reference ?? '';
//            $file_name = "FireRegister". "_" . $shine_ref. "_"  . date("d_m_Y") .  ".pdf";
            //filter fire warning message
            $warning_message = $warning_message['red_warnings'];
            $fire_warning_message = [];
            if(count($warning_message)){
                foreach ($warning_message as $warning_mes){
                    if (strpos($warning_mes, 'water') !== false || strpos($warning_mes, 'Water') !== false) {
                        $fire_warning_message[] = $warning_mes;
                    }
                }
            }

            $hazards = $this->hazardRepository->listRegisterHazardsByType($property->id, ASSESSMENT_WATER_TYPE, ['area','location','hazardType','hazardPotential','hazardLikelihoodHarm']);
            return view('pdf.water_register_pdf', [
                'hazards' => $hazards,
                'is_pdf' => false,
                'property' => $property,
                'ss_ref' => $ss_ref,
                'type' => WATER_REGISTER_PDF
            ]);
            //log audit
            $comment = \Auth::user()->full_name  . " view water register PDF "  . $ss_ref . ' on '. optional($property)->reference;
            \CommonHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_VIEW, $ss_ref, $property->id ,$comment, 0 ,$property->id);
//            return file_exists($save_path) ? ['path' => $save_path,'file_name' => $file_name] :  [];
        } catch (\Exception $e){
            dd($e);
            Log::error($e);
        }
        abort(404);
    }

    public function viewHsRegisterPDF($property, $warning_message){
        try {
            $ss_ref = "";

//            $shine_ref = $property->reference ?? '';
//            $file_name = "FireRegister". "_" . $shine_ref. "_"  . date("d_m_Y") .  ".pdf";
            //filter fire warning message
            $warning_message = $warning_message['red_warnings'];
            $fire_warning_message = [];
            if(count($warning_message)){
                foreach ($warning_message as $warning_mes){
                    if (strpos($warning_mes, 'water') !== false || strpos($warning_mes, 'Water') !== false) {
                        $fire_warning_message[] = $warning_mes;
                    }
                }
            }

            $hazards = $this->hazardRepository->listRegisterHazardsByType($property->id, ASSESSMENT_HS_TYPE, ['area','location','hazardType','hazardPotential','hazardLikelihoodHarm']);
            return view('pdf.hs_register_pdf', [
                'hazards' => $hazards,
                'is_pdf' => false,
                'property' => $property,
                'ss_ref' => $ss_ref,
                'type' => WATER_REGISTER_PDF
            ]);
            //log audit
            $comment = \Auth::user()->full_name  . " view hs register PDF "  . $ss_ref . ' on '. optional($property)->reference;
            \CommonHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_VIEW, $ss_ref, $property->id ,$comment, 0 ,$property->id);
//            return file_exists($save_path) ? ['path' => $save_path,'file_name' => $file_name] :  [];
        } catch (\Exception $e){
            dd($e);
            Log::error($e);
        }
        abort(404);
    }

    public function createHsRegisterPDF($property, $warning_message){
        try {
            \DB::beginTransaction();
            // miss auditrail
            // property pdf need to download as ZIP file
            $next_number = Counter::where('count_table_name','summaries')->first()->total;
            $ss_ref = "SS" . sprintf("%03d", $next_number);

            $footer_left = "Health & Safety Register " . $property->name . " " . date("d_m_Y"); // Them reference

            $shine_ref = $property->reference ?? '';
            $count_file = SummaryPdf::where(['type' => WATER_REGISTER_PDF,'object_id' => $property->id])->get()->count(); // only property pdf need SS111.xxx
            $file_name_next_number = ($count_file) ? "." . $count_file : "";
            $file_name = "H&SRegister". "_" . $shine_ref. "_"  . date("d_m_Y") . $file_name_next_number . ".pdf";
//            //set warning for cover page
//            $risk_type_one = $risk_type_two = NULL;
//            if(isset($property->propertyType) && !$property->propertyType->isEmpty()){
//                $risk_type_one = $property->propertyType->where('id',1)->first();
//                $risk_type_two = $property->propertyType->where('id',2)->first();
//            }
            //filter fire warning message
            $warning_message = $warning_message['red_warnings'];
            $fire_warning_message = [];
            if(count($warning_message)){
                foreach ($warning_message as $warning_mes){
                    if (strpos($warning_mes, 'water') !== false || strpos($warning_mes, 'Water') !== false) {
                        $fire_warning_message[] = $warning_mes;
                    }
                }
            }
            $hazards = $this->hazardRepository->listRegisterHazardsByType($property->id, ASSESSMENT_WATER_TYPE, ['area','location','hazardType','hazardPotential','hazardLikelihoodHarm']);
            $pdf = PDF::loadView('pdf.hs_register_pdf', [
                'hazards' => $hazards,
                'is_pdf' => true,
                'property' => $property,
                'ss_ref' => $ss_ref,
                'type' => FIRE_REGISTER_PDF
            ])
                ->setOption('header-font-size', 8)
                ->setOption('footer-font-size', 8)
                ->setOption('footer-right', "Page [page] of [toPage]")
                ->setOption('footer-left', $footer_left)
                ->setOption('cover', view('pdf.hs_register_cover',[
                    'property' => $property,
                    'is_pdf' => true,
                    'warning_message' => $fire_warning_message,
                    'ss_ref' => $ss_ref,
                    'type' => FIRE_REGISTER_PDF]))
            ;
            $is_local = env('APP_ENV') != 'local';
            if($is_local){
                $toc_name = "publishedSurveyToc.xsl";
                $toc_path = Config::get('view.paths')[0] . "/pdf/".$toc_name;
                $pdf->setOption('toc' , true)
                    ->setOption('xsl-style-sheet',$toc_path);
            }

            $save_path = storage_path('app/data/pdfs/registers') ."/" . $file_name;
            //for overwrite
            $pdf->save($save_path, true);
            //update reference will be name not null like before
            SummaryPdf::create([
                'reference'=> $ss_ref,
                'type'=> WATER_REGISTER_PDF,
                'object_id'=> $property->id,
                'file_name'=> $file_name,
                'path'=> $save_path
            ]);
            //log audit
            $comment = \Auth::user()->full_name  . " download hs register PDF "  . $ss_ref . ' on '. optional($property)->reference;
            \CommonHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_DOWNLOAD, $ss_ref, $property->id ,$comment, 0 ,$property->id);
            Counter::where('count_table_name','summaries')->update(['total'=> $next_number +1]);
            \DB::commit();
            return file_exists($save_path) ? ['path' => $save_path,'file_name' => $file_name] :  [];
        } catch (\Exception $e){
            dd($e);
            Log::error($e);
            \DB::rollBack();
        }
        return [];
    }
}
