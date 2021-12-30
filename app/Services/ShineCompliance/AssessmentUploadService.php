<?php


namespace App\Services\ShineCompliance;


use App\Helpers\CommonHelpers;
use App\Models\ShineCompliance\AssessmentFireSafetyAnswer;
use App\Models\ShineCompliance\AssessmentManagementAnswer;
use App\Models\ShineCompliance\AssessmentManagementQuestion;
use App\Models\ShineCompliance\AssessmentOtherAnswer;
use App\Models\ShineCompliance\AssessmentOtherQuestion;
use App\Models\ShineCompliance\AssessmentResult;
use App\Models\ShineCompliance\AssessmentValue;
use App\Models\ShineCompliance\ComplianceDocumentStorage;
use App\Models\ShineCompliance\HazardSpecificLocationValue;
use App\Models\ShineCompliance\Nonconformity;
use App\Models\ShineCompliance\ShineDocumentStorage;
use App\Repositories\ShineCompliance\AreaRepository;
use App\Repositories\ShineCompliance\AssemblyPointRepository;
use App\Repositories\ShineCompliance\AssessmentInfoRepository;
use App\Repositories\ShineCompliance\AssessmentManagementValueRepository;
use App\Repositories\ShineCompliance\AssessmentNoteDocumentRepository;
use App\Repositories\ShineCompliance\AssessmentOtherValueRepository;
use App\Repositories\ShineCompliance\AssessmentRepository;
use App\Repositories\ShineCompliance\AssessmentSectionRepository;
use App\Repositories\ShineCompliance\AssessmentUploadDataRepository;
use App\Repositories\ShineCompliance\AssessmentUploadManifestRepository;
use App\Repositories\ShineCompliance\AssessmentUploadImageRepository;
use App\Repositories\ShineCompliance\ComplianceSystemRepository;
use App\Repositories\ShineCompliance\EquipmentRepository;
use App\Repositories\ShineCompliance\FireExitRepository;
use App\Repositories\ShineCompliance\HazardRepository;
use App\Repositories\ShineCompliance\LocationRepository;
use App\Repositories\ShineCompliance\NonconformityRepository;
use App\Repositories\ShineCompliance\VehicleParkingRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssessmentUploadService
{
    protected const STATUS_UPLOADED = 1;
    protected const STATUS_PROCESSED = 2;
    protected const STATUS_ERROR = 9;

    private $assessmentRepository;
    private $assessmentInfoRepository;
    private $manifestRepository;
    private $uploadDataRepository;
    private $uploadImageRepository;
    private $areaRepository;
    private $locationRepository;
    private $assemblyPointRepository;
    private $exitRepository;
    private $parkingRepository;
    private $systemRepository;
    private $equipmentRepository;
    private $hazardRepository;
    private $nonconformityRepository;
    private $sectionRepository;
    private $noteDocumentRepository;
    private $otherQuestionRepository;
    private $otherValueRepository;

    public function __construct(AssessmentRepository $assessmentRepository,
                                AssessmentInfoRepository $assessmentInfoRepository,
                                AssessmentUploadManifestRepository $manifestRepository,
                                AssessmentUploadDataRepository $uploadDataRepository,
                                AssessmentUploadImageRepository $uploadImageRepository,
                                AreaRepository $areaRepository,
                                LocationRepository $locationRepository,
                                AssemblyPointRepository $assemblyPointRepository,
                                FireExitRepository $exitRepository,
                                VehicleParkingRepository $parkingRepository,
                                ComplianceSystemRepository $systemRepository,
                                EquipmentRepository $equipmentRepository,
                                HazardRepository $hazardRepository,
                                NonconformityRepository $nonconformityRepository,
                                AssessmentSectionRepository $sectionRepository,
                                AssessmentNoteDocumentRepository $noteDocumentRepository,
                                AssessmentManagementValueRepository $managementValueRepository,
                                AssessmentOtherValueRepository $otherValueRepository)
    {
        $this->assessmentRepository = $assessmentRepository;
        $this->assessmentInfoRepository = $assessmentInfoRepository;
        $this->manifestRepository = $manifestRepository;
        $this->uploadDataRepository = $uploadDataRepository;
        $this->uploadImageRepository = $uploadImageRepository;
        $this->areaRepository = $areaRepository;
        $this->locationRepository = $locationRepository;
        $this->assemblyPointRepository = $assemblyPointRepository;
        $this->exitRepository = $exitRepository;
        $this->parkingRepository = $parkingRepository;
        $this->systemRepository = $systemRepository;
        $this->equipmentRepository = $equipmentRepository;
        $this->hazardRepository = $hazardRepository;
        $this->nonconformityRepository = $nonconformityRepository;
        $this->sectionRepository = $sectionRepository;
        $this->noteDocumentRepository = $noteDocumentRepository;
        $this->managementValueRepository = $managementValueRepository;
        $this->otherValueRepository = $otherValueRepository;
    }

    public function createUploadManifest($assess_id)
    {
        try {
            // Validate => Remove for force quite app while uploading assessment
//            if ($this->manifestRepository->where('assess_id', $assess_id)->where('status', self::STATUS_UPLOADED)->count() > 0) {
//                return \CommonHelpers::failResponse(STATUS_FAIL, 'Upload manifest failed!');
//            }
            $uploadManifest = $this->manifestRepository->create([
                'assess_id' => $assess_id,
                'assessor_id' => \Auth::user()->id,
                'status'=> self::STATUS_UPLOADED, // 1: Uploaded, 2: Processed, 9: Error
            ]);

            return \CommonHelpers::successResponse('Upload Manifest Api successfully', ['manifest_id' => $uploadManifest->id]);
        } catch (\Exception $exception) {
            Log::error($exception);
            return \CommonHelpers::failResponse(STATUS_FAIL, $exception->getMessage());
        }
    }

    public function uploadImage($data)
    {
        if (!is_null($data['file']) and $data['file']->isValid()) {
            try {
                $path = \CommonHelpers::getFileStoragePath($data['assess_id'], $data['image_type'], 0, true);
                \Storage::disk('local')->put($path, $data['file']);
                if ($data['image_type'] == 'assess_note_photo') {
                    $data['image_type'] = 'note';
                }
                // save to upload_image_table
                $uploadedImage = $this->uploadImageRepository->create([
                    'assess_id' => $data['assess_id'],
                    'manifest_id' => $data['manifest_id'],
                    'image_type' => $data['image_type'],
                    'file_name' => $data['file']->getClientOriginalName(),
                    'path' => $path . $data['file']->hashName(),
                    'mime' => $data['file']->getClientMimeType(),
                    'size' => $data['file']->getSize(),
                ]);

                return \CommonHelpers::successResponse('Upload Api file successfully', ['upload_image_id' => $uploadedImage->id]);
            } catch (\Exception $exception) {
                Log::error($exception);
                $this->manifestRepository->update(['status'=> self::STATUS_ERROR], $data['manifest_id']);
                return \CommonHelpers::failResponse(STATUS_FAIL, $exception->getMessage());
            }
        } else {
            $this->manifestRepository->update(['status'=> self::STATUS_ERROR], $data['manifest_id']);
            return \CommonHelpers::failResponse(STATUS_FAIL, 'File not exist or invalid!');
        }
    }

    public function uploadData($data, $dataContent)
    {
        // Log Data into assessment_upload_data table
        try {
            // TODO: check 1 manifest must have only 1 assessment
            $uploadData = $this->uploadDataRepository->create([
                'manifest_id' => $data['manifest_id'],
                'assess_id' => $data['assess_id'],
                'data' => $dataContent,
                'status' => self::STATUS_UPLOADED,
            ]);
        } catch (\Exception $exception) {
            Log::error($exception);
            $this->manifestRepository->update(['status'=> self::STATUS_ERROR], $data['manifest_id']);
            return \CommonHelpers::failResponse(STATUS_FAIL, $exception->getMessage());
        }

        // Update data of assessment
        try {
            $success = true;
            DB::beginTransaction();
            if (!$assessment = $this->assessmentRepository->getAssessmentDetail($data['assess_id'], [])) {
                return \CommonHelpers::failResponse(STATUS_FAIL, "Not found assessment!");
            }

            // Check status of upload assessment
            if ($data['status'] == ASSESSMENT_STATUS_ABORTED) {
                // Update assessment to aborted and completed upload
                $assessment->status = ASSESSMENT_STATUS_ABORTED;
                $assessment->is_locked = ASSESMENT_UNLOCKED;
                $assessment->sent_back_date = time();
                $assessment->aborted_reason = $data['aborted_reason'] ?? 0;
                $assessment->save();

                // Unlock all items of assessment
                $assessment->systems()->update(['is_locked' => 0]);
                $assessment->equipments()->update(['is_locked' => 0]);
                $assessment->fireExits()->update(['is_locked' => 0]);
                $assessment->assemblyPoints()->update(['is_locked' => 0]);
                $assessment->vehicleParking()->update(['is_locked' => 0]);
                $assessment->unDecommissionHazard()->update(['is_locked' => 0]);
            } elseif ($data['status'] == ASSESSMENT_STATUS_SENT_BACK_FROM_DEVICE) {
                $assessment->status = ASSESSMENT_STATUS_SENT_BACK_FROM_DEVICE;
                $assessment->is_locked = SURVEY_UNLOCKED;
                $assessment->sent_back_date = time();
                $assessment->save();

                // Update property information
                $success *= $this->updatePropertyInfo(json_encode($data['property_information']), $assessment->assessmentInfo->id);
                // update executive_summary
                $this->updateExecutiveSummary(($data['executive_summary']), $assessment->assessmentInfo->id);
                // update image from upload_images to shine_document_storage
                $this->saveUploadedImageToShineDocumentStorage($data['property_information']['property_upload_image_id'], $assessment->id);

                // Save fire safety equipment & system
                if ($assessment->classification == ASSESSMENT_FIRE_TYPE) {
                    $assessmentInfo = $assessment->assessmentInfo;
                    if ($assessmentInfo && $assessmentInfo->setting_fire_safety) {
                        $assessmentInfo->fire_safety = $data['fire_safety'] ?? null;
                        $assessmentInfo->fire_safety_other = $data['fire_safety_other'] ?? null;
                        $assessmentInfo->save();
                    }
                }

                // Create Areas/Locations
                $areas = $data['areas'];
                foreach ($areas as &$area) {
                    if (is_null($area['id']) || $area['id'] == 0) {
                        $createdArea = $this->createArea($area, $assessment);
                        $area['id'] = $createdArea->id;
                    }
                }

                $locations = $data['locations'];
                foreach ($locations as &$location) {
                    if (is_null($location['id']) || $location['id'] == 0) {
                        $createdLocation = $this->createLocation($location, $assessment, $areas);
                        $location['id'] = $createdLocation->id;
                    }
                }

                // Update questionnaires
                // delete before insert value
                AssessmentValue::where('assess_id', $assessment->id)->forcedelete();
                AssessmentResult::where('assess_id', $assessment->id)->forcedelete();
                // update question and answer
                foreach ($data['assessment_values'] as $question) {
                    if ($question != OTHER_HAZARD_IDENTIFIED_QUESTION_ID) {
                        $dataAssessmentAnswer = [
                            'assess_id' => $assessment->id,
                            'question_id' => $question['question_id'],
                            'answer_id' => $question['answer_id'] ?? 0,
                            'other' => $question['other'] ?? null,
                            'observations' => $question['observations'] ?? null,
                        ];
                        AssessmentValue::updateOrCreate([
                            'assess_id' => $assessment->id,
                            'question_id' => $question['question_id']
                        ], $dataAssessmentAnswer);
                    } else {
                        $other_hazard_answers = $question['answer_id'];
                        $other_hazard_comments = $question['other'];
                        if (count($other_hazard_answers)) {
                            foreach ($other_hazard_answers as $key => $other_hazard_answer) {
                                $dataAssessmentAnswer = [
                                    'assess_id' => $assessment->id,
                                    'question_id' => $question['question_id'],
                                    'answer_id' => $other_hazard_answer ?? 0,
                                    'other' => $other_hazard_comments[$key] ?? null,
                                ];
                                AssessmentValue::create($dataAssessmentAnswer);
                            }
                        }
                    }
                }
                // save Assessment Result Table
                $scoreResult = $this->sectionRepository->getAssessmentResultByAssessId($assessment->id);
                $resultData = [];
                foreach ($scoreResult as $score) {
                    if (is_null($score->sub_sec) && is_null($score->main_sec))
                        continue;
                    $resultData[] = [
                        'assess_id' => $assessment->id,
                        'section_id' => $score->sub_sec ?? $score->main_sec,
                        'total_question' => $score->total_question,
                        'total_yes' => $score->total_yes,
                        'total_no' => $score->total_no,
                        'total_score' => $score->total_score,
                    ];
                }
                AssessmentResult::insert($resultData);

                // Update Systems
                $systems = $data['systems'];
                foreach ($systems as &$system) {
                    if (is_null($system['id'])) {
                        $savedSystem = $this->systemRepository->create([
                            "name" => $system['name'] ?? null,
                            "property_id" => $system['property_id'] ?? null,
                            "assess_id" => $system['assess_id'] ?? null,
                            "decommissioned" => $system['decommissioned'] ?? 0,
                            "type" => $system['type'] ?? null,
                            "classification" => $system['classification'] ?? 0,
                            "comment" => $system['comment'] ?? null,
                        ]);
                        $savedSystem->reference = 'PS' . $savedSystem->id;
                        $savedSystem->record_id = $savedSystem->id;
                        $savedSystem->save();

                        $system['id'] = $savedSystem->id;
                    } else {
                        $savedSystem = $this->systemRepository->update([
                            "name" => $system['name'] ?? null,
                            "property_id" => $system['property_id'] ?? null,
                            "assess_id" => $system['assess_id'] ?? null,
                            "decommissioned" => $system['decommissioned'] ?? 0,
                            "type" => $system['type'] ?? null,
                            "classification" => $system['classification'] ?? 0,
                            "comment" => $system['comment'] ?? null,
                            'is_locked' => 0,
                        ], $system['id']);
                    }

                    // Save upload image
                    $this->saveUploadedImageToShineComplianceDocumentStorage($system['upload_image_id'], $savedSystem->id);
                }

                // Update Equipment
                $equipments = $data['equipments'];
                foreach ($equipments as &$equipment) {
                    if (is_null($equipment['id'])) {
                        // Create new
                        $savedEquipment = $this->equipmentRepository->create([
                            "property_id" => $equipment['property_id'] ?? null,
                            "assess_id" => $equipment['assess_id'] ?? null,
                            "area_id" => $this->getAreaId($equipment['app_area_id'], $areas),
                            "location_id" => $this->getLocationId($equipment['app_location_id'], $locations),
                            "type" => $equipment['type'] ?? null,
                            "decommissioned" => $equipment['decommissioned'] ?? 0,
                            "state" => $equipment['state'] ?? null,
                            "reason" => $equipment['reason'] ?? null,
                            "reason_other" => $equipment['reason_other'] ?? null,
                            "parent_id" => $equipment['app_parent_id'] ?? null, // Will correct after created
                            "hot_parent_id" => $equipment['app_hot_parent_id'] ?? null, // Will correct after created
                            "cold_parent_id" => $equipment['app_cold_parent_id'] ?? null, // Will correct after created
                            "system_id" => $this->getSystemId($equipment['app_system_id'], $systems),
                            "frequency_use" => $equipment['frequency_use'] ?? null,
                            "extent" => $equipment['extent'] ?? null,
                            "operational_use" => $equipment['operational_use'] ?? null,
                            "name" => $equipment['name'] ?? null,
                            "has_sample" => $equipment['has_sample'] ?? null,
                            "sample_reference" => $equipment['sample_reference'] ?? null,
                        ]);

                        $savedEquipment->reference = 'EQ' . $savedEquipment->id;
                        $savedEquipment->record_id = $savedEquipment->id;
                        $savedEquipment->save();

                        $equipment['id'] = $savedEquipment->id;
                    } else {
                        $savedEquipment = $this->equipmentRepository->update([
                            "property_id" => $equipment['property_id'] ?? null,
                            "assess_id" => $equipment['assess_id'] ?? null,
                            "area_id" => $this->getAreaId($equipment['app_area_id'], $areas),
                            "location_id" => $this->getLocationId($equipment['app_location_id'], $locations),
                            "type" => $equipment['type'] ?? null,
                            "decommissioned" => $equipment['decommissioned'] ?? 0,
                            "state" => $equipment['state'] ?? null,
                            "reason" => $equipment['reason'] ?? null,
                            "reason_other" => $equipment['reason_other'] ?? null,
                            "parent_id" => $equipment['app_parent_id'] ?? null, // Will correct after updated
                            "hot_parent_id" => $equipment['app_hot_parent_id'] ?? null, // Will correct after updated
                            "cold_parent_id" => $equipment['app_cold_parent_id'] ?? null, // Will correct after updated
                            "system_id" => $this->getSystemId($equipment['app_system_id'], $systems),
                            "frequency_use" => $equipment['frequency_use'] ?? null,
                            "extent" => $equipment['extent'] ?? null,
                            "operational_use" => $equipment['operational_use'] ?? null,
                            "name" => $equipment['name'] ?? null,
                            'is_locked' => 0,
                        ], $equipment['id']);
                    }

                    // Create all relationships
                    //specific_location_value
                    if (count($equipment['specific_location_value'])) {
                        if (isset($equipment['specific_location_value']['value'])) {
                            $values = explode(',', $equipment['specific_location_value']['value']);
                            $values_spec = [];
                            foreach ($values as $spec_val){
                                $spec_data = $this->equipmentRepository->getEquipmentSpecificLocationChild($spec_val);
                                if(isset($spec_data->childrens) && count($spec_data->childrens) > 0) {
                                }else{
                                    $values_spec[] = $spec_val;
                                }
                            }
                            $this->equipmentRepository->insertDropdownValue($equipment['id'], $parent = 0, implode(',', $values_spec),
                                $equipment['specific_location_value']['other'] ?? null);
                        }
                    }
                    //equipment_model
                    $this->equipmentRepository->updateOrCreateModel($equipment['id'], $equipment['equipment_model']);
                    //equipment_construction
                    $this->equipmentRepository->updateOrCreateConstruction($equipment['id'], $equipment['equipment_construction']);
                    //temp_and_ph
                    // mapping attribute API - database
                    if (count($equipment['temp_and_ph'])) {
                        $equipment['temp_and_ph']['return_temp_gauge_value'] = $equipment['temp_and_ph']['return_temp_gauge'] ?? null;
                        $equipment['temp_and_ph']['flow_temp_gauge_value'] = $equipment['temp_and_ph']['flow_temp_gauge'] ?? null;
                    }
                    $this->equipmentRepository->updateOrCreateTemp($equipment['id'], $equipment['temp_and_ph']);
                    //cleaning
                    $this->equipmentRepository->updateOrCreateCleaning($equipment['id'], $equipment['cleaning']);

                    // save to shineComplianceDocumentStorage
                    $this->saveUploadedImageToShineComplianceDocumentStorage($equipment['upload_location_image_id'], $savedEquipment->id);
                    $this->saveUploadedImageToShineComplianceDocumentStorage($equipment['upload_equipment_image_id'], $savedEquipment->id);
                    $this->saveUploadedImageToShineComplianceDocumentStorage($equipment['upload_additional_image_id'], $savedEquipment->id);
                }

                // Correct parent equipment
                $savedEquipments = $this->equipmentRepository->getEquipmentsByAssessId($assessment->id);
                $this->correctParentEquipment($equipments, $savedEquipments);

                // Update Hazards
                $hazards = $data['hazards'];
                foreach ($hazards as &$hazard) {
                    if (is_null($hazard['id'])) {
                        // Create new
                        $savedHazard = $this->hazardRepository->create([
                            "name" => $hazard['name'] ?? null,
                            "assess_id" => $hazard['assess_id'] ?? null,
                            "assess_type" => $assessment->classification,
                            "created_date" => $hazard['created_date'] ?? Carbon::now()->format('d/m/Y'),
                            "property_id" => $hazard['property_id'] ?? null,
                            "equipment_id" => $this->getEquipmentId($hazard['app_equipment_id'], $equipments),
                            "area_id" => $this->getAreaId($hazard['app_area_id'], $areas),
                            "location_id" => $this->getLocationId($hazard['app_location_id'], $locations),
                            "decommissioned" => $hazard['decommissioned'] ?? 0,
                            "type" => $hazard['type'] ?? null,
                            "linked_question_id" => $hazard['linked_question_id'] ?? null,
                            "likelihood_of_harm" => $hazard['likelihood_of_harm'] ?? null,
                            "photo_override" => $hazard['photo_override'] ?? null,
                            "hazard_potential" => $hazard['hazard_potential'] ?? null,
                            "total_risk" => $hazard['total_risk'] ?? null,
                            "extent" => $hazard['extent'] ?? null,
                            "measure_id" => $hazard['measure_id'] ?? null,
                            "act_recommendation_verb" => $hazard['act_recommendation_verb'] ?? null,
                            "act_recommendation_verb_other" => $hazard['act_recommendation_verb_other'] ?? null,
                            "act_recommendation_noun" => $hazard['act_recommendation_noun'] ?? null,
                            "act_recommendation_noun_other" => $hazard['act_recommendation_noun_other'] ?? null,
                            "action_responsibility" => $hazard['action_responsibility'] ?? null,
                            "decommissioned_reason" => $hazard['decommissioned_reason'] ?? null,
                            "is_temp" => $hazard['is_temp'] ?? 0,
                            "is_deleted" => $hazard['is_deleted'] ?? 0,
                            "comment" => $hazard['comment'] ?? null,
                        ]);

                        if ($assessment->classification == ASSESSMENT_FIRE_TYPE) {
                            $savedHazard->reference = 'FH' . $savedHazard->id;
                        } elseif ($assessment->classification == ASSESSMENT_WATER_TYPE) {
                            $savedHazard->reference = 'WH' . $savedHazard->id;
                        } else {
                            $savedHazard->reference = 'HZ' . $savedHazard->id;
                        }

                        $savedHazard->record_id = $savedHazard->id;
                        $savedHazard->save();

                        $hazard['id'] = $savedHazard->id;
                    } else {
                        // Update
                        $savedHazard = $this->hazardRepository->update([
                            "name" => $hazard['name'] ?? null,
                            "assess_id" => $hazard['assess_id'] ?? null,
                            "assess_type" => $assessment->classification,
//                            "created_date" => $hazard['created_date'] ?? null,
                            "property_id" => $hazard['property_id'] ?? null,
                            "equipment_id" => $this->getEquipmentId($hazard['app_equipment_id'], $equipments),
                            "area_id" => $this->getAreaId($hazard['app_area_id'], $areas),
                            "location_id" => $this->getLocationId($hazard['app_location_id'], $locations),
                            "decommissioned" => $hazard['decommissioned'] ?? 0,
                            "type" => $hazard['type'] ?? null,
                            "likelihood_of_harm" => $hazard['likelihood_of_harm'] ?? null,
                            "photo_override" => $hazard['photo_override'] ?? null,
                            "hazard_potential" => $hazard['hazard_potential'] ?? null,
                            "total_risk" => $hazard['total_risk'] ?? null,
                            "extent" => $hazard['extent'] ?? null,
                            "measure_id" => $hazard['measure_id'] ?? null,
                            "act_recommendation_verb" => $hazard['act_recommendation_verb'] ?? null,
                            "act_recommendation_verb_other" => $hazard['act_recommendation_verb_other'] ?? null,
                            "act_recommendation_noun" => $hazard['act_recommendation_noun'] ?? null,
                            "act_recommendation_noun_other" => $hazard['act_recommendation_noun_other'] ?? null,
                            "action_responsibility" => $hazard['action_responsibility'] ?? null,
                            "decommissioned_reason" => $hazard['decommissioned_reason'] ?? null,
                            "is_temp" => $hazard['is_temp'] ?? 0,
                            "is_deleted" => $hazard['is_deleted'] ?? 0,
                            "comment" => $hazard['comment'] ?? null,
                            'is_locked' => 0,
                        ], $hazard['id']);
                    }

                    // Update specific location
                    if (array_key_exists('hazard_specific_location_value', $hazard) && count($hazard['hazard_specific_location_value'])) {
                        if (isset($hazard['hazard_specific_location_value']['value'])) {
                            $values = explode(',', $hazard['hazard_specific_location_value']['value']);
                            $values_spec = [];
                            foreach ($values as $spec_val){
                                $spec_data = $this->hazardRepository->getHazardSpecificLocationChild($spec_val);
                                if(isset($spec_data->childrens) && count($spec_data->childrens) > 0) {
                                }else{
                                    $values_spec[] = $spec_val;
                                }
                            }

                            HazardSpecificLocationValue::updateOrCreate(['hazard_id' => $hazard['id']], [
                                'parent_id' => 0,
                                'value' => implode(',', $values_spec),
                                'other' => $hazard['hazard_specific_location_value']['other'] ?? null
                            ]);
                        }
                    }
                    if (isset($hazard['photo_override']) and ($hazard['photo_override'] == 0)) {
                        $this->saveUploadedImageToShineDocumentStorage($hazard['upload_location_image_id'], $hazard['id']);
                        $this->saveUploadedImageToShineDocumentStorage($hazard['upload_hazard_image_id'], $hazard['id']);
                        $this->saveUploadedImageToShineDocumentStorage($hazard['upload_additional_image_id'], $hazard['id']);
                    }
                }

                // Update Nonconformities
                foreach ($data['nonconformities'] as $nonconformity) {
                    if (is_null($nonconformity['id'])) {
                        // Create new
                        $savedNonconformity = $this->nonconformityRepository->create([
                            "property_id" => $nonconformity['property_id'] ?? null,
                            "assess_id" => $nonconformity['assess_id'] ?? null,
                            "equipment_id" => $this->getEquipmentId($nonconformity['app_equipment_id'], $equipments),
                            "hazard_id" => $this->getHazardId($nonconformity['app_hazard_id'], $hazards),
                            "field" => $nonconformity['field'] ?? null,
                            "type" => $nonconformity['type'] ?? null,
                            "created_by" => \Auth::user()->id ?? null,
                            "is_deleted" => $nonconformity['is_deleted'] ?? 0,
                        ]);

                        $savedNonconformity->reference = 'NC' . $savedNonconformity->id;
                        $savedNonconformity->record_id = $savedNonconformity->id;
                        $savedNonconformity->save();
                    } else {
                        // Update
                        $savedNonconformity = $this->nonconformityRepository->update([
                            "property_id" => $nonconformity['property_id'] ?? null,
                            "assess_id" => $nonconformity['assess_id'] ?? null,
                            "equipment_id" => $this->getEquipmentId($nonconformity['app_equipment_id'], $equipments),
                            "hazard_id" => $this->getHazardId($nonconformity['app_hazard_id'], $hazards),
                            "field" => $nonconformity['field'] ?? null,
                            "type" => $nonconformity['type'] ?? null,
                            "updated_by" => \Auth::user()->id,
                            "is_deleted" => $nonconformity['is_deleted'] ?? 0,
                        ], $nonconformity['id']);
                    }
                }

                // Update fire exits / assembly points / vehicle parking
                foreach ($data['assembly_points'] as $assembly_point) {
                    if (is_null($assembly_point['id'])) {
                        // Create new
                        $assembly = $this->assemblyPointRepository->create([
                            'name' => $assembly_point['name'] ?? null,
                            'property_id' => $assembly_point['property_id'] ?? null,
                            'assess_id' => $assembly_point['assess_id'] ?? null,
                            'area_id' => $this->getAreaId($assembly_point['app_area_id'], $areas),
                            'location_id' => $this->getLocationId($assembly_point['app_location_id'], $locations),
                            'hazard_id' => $this->getHazardId($assembly_point['app_hazard_id'], $hazards),
                            'decommissioned' => $assembly_point['decommissioned'] ?? 0,
                            'decommissioned_reason' => $assembly_point['decommissioned_reason'] ?? null,
                            'accessibility' => $assembly_point['accessibility'] ?? null,
                            'reason_na' => $assembly_point['reason_na'] ?? null,
                            'reason_na_other' => $assembly_point['reason_na_other'] ?? null,
                            'comment' => $assembly_point['comment'] ?? null,
                        ]);
                        $assembly->reference = 'AS' . $assembly->id;
                        $assembly->record_id = $assembly->id;
                        $assembly->save();
                    } else {
                        // Update
                        $assembly = $this->assemblyPointRepository->update([
                            'name' => $assembly_point['name'] ?? null,
                            'property_id' => $assembly_point['property_id'] ?? null,
                            'assess_id' => $assembly_point['assess_id'] ?? null,
                            'area_id' => $this->getAreaId($assembly_point['app_area_id'], $areas),
                            'location_id' => $this->getLocationId($assembly_point['app_location_id'], $locations),
                            'hazard_id' => $this->getHazardId($assembly_point['app_hazard_id'], $hazards),
                            'decommissioned' => $assembly_point['decommissioned'] ?? 0,
                            'decommissioned_reason' => $assembly_point['decommissioned_reason'] ?? null,
                            'accessibility' => $assembly_point['accessibility'] ?? null,
                            'reason_na' => $assembly_point['reason_na'] ?? null,
                            'reason_na_other' => $assembly_point['reason_na_other'] ?? null,
                            'comment' => $assembly_point['comment'] ?? null,
                            'is_locked' => 0,
                        ], $assembly_point['id']);
                    }

                    $this->saveUploadedImageToShineDocumentStorage($assembly_point['upload_image_id'], $assembly->id);
                }

                foreach ($data['fire_exits'] as $fire_exits) {
                    if (is_null($fire_exits['id'])) {
                        // Create new
                        $fireExit = $this->exitRepository->create([
                            'name' => $fire_exits['name'] ?? null,
                            'type' => $fire_exits['type'] ?? null,
                            'property_id' => $fire_exits['property_id'] ?? null,
                            'assess_id' => $fire_exits['assess_id'] ?? null,
                            'area_id' => $this->getAreaId($fire_exits['app_area_id'], $areas),
                            'location_id' => $this->getLocationId($fire_exits['app_location_id'], $locations),
                            'hazard_id' => $this->getHazardId($fire_exits['app_hazard_id'], $hazards),
                            'decommissioned' => $fire_exits['decommissioned'] ?? 0,
                            'decommissioned_reason' => $fire_exits['decommissioned_reason'] ?? null,
                            'accessibility' => $fire_exits['accessibility'] ?? null,
                            'reason_na' => $fire_exits['reason_na'] ?? null,
                            'reason_na_other' => $fire_exits['reason_na_other'] ?? null,
                            'comment' => $fire_exits['comment'] ?? null,
                        ]);
                        $fireExit->reference = 'FE' . $fireExit->id;
                        $fireExit->record_id = $fireExit->id;
                        $fireExit->save();
                    } else {
                        // Update
                        $fireExit = $this->exitRepository->update([
                            'name' => $fire_exits['name'] ?? null,
                            'type' => $fire_exits['type'] ?? null,
                            'property_id' => $fire_exits['property_id'] ?? null,
                            'assess_id' => $fire_exits['assess_id'] ?? null,
                            'area_id' => $this->getAreaId($fire_exits['app_area_id'], $areas),
                            'location_id' => $this->getLocationId($fire_exits['app_location_id'], $locations),
                            'hazard_id' => $this->getHazardId($fire_exits['app_hazard_id'], $hazards),
                            'decommissioned' => $fire_exits['decommissioned'] ?? 0,
                            'decommissioned_reason' => $fire_exits['decommissioned_reason'] ?? null,
                            'accessibility' => $fire_exits['accessibility'] ?? null,
                            'reason_na' => $fire_exits['reason_na'] ?? null,
                            'reason_na_other' => $fire_exits['reason_na_other'] ?? null,
                            'comment' => $fire_exits['comment'] ?? null,
                            'is_locked' => 0,
                        ], $fire_exits['id']);
                    }

                    $this->saveUploadedImageToShineDocumentStorage($fire_exits['upload_image_id'], $fireExit->id);
                }

                foreach ($data['vehicle_parking'] as $vehicle_parking) {
                    if (is_null($vehicle_parking['id'])) {
                        // Create new
                        $parking = $this->parkingRepository->create([
                            'name' => $vehicle_parking['name'] ?? null,
                            'property_id' => $vehicle_parking['property_id'] ?? null,
                            'assess_id' => $vehicle_parking['assess_id'] ?? null,
                            'area_id' => $this->getAreaId($vehicle_parking['app_area_id'], $areas),
                            'location_id' => $this->getLocationId($vehicle_parking['app_location_id'], $locations),
                            'hazard_id' => $this->getHazardId($vehicle_parking['app_hazard_id'], $hazards),
                            'decommissioned' => $vehicle_parking['decommissioned'] ?? 0,
                            'decommissioned_reason' => $vehicle_parking['decommissioned_reason'] ?? null,
                            'accessibility' => $vehicle_parking['accessibility'] ?? null,
                            'reason_na' => $vehicle_parking['reason_na'] ?? null,
                            'reason_na_other' => $vehicle_parking['reason_na_other'] ?? null,
                            'comment' => $vehicle_parking['comment'] ?? null,
                        ]);
                        $parking->reference = 'VP' . $parking->id;
                        $parking->record_id = $parking->id;
                        $parking->save();
                    } else {
                        // Update
                        $parking = $this->parkingRepository->update([
                            'name' => $vehicle_parking['name'] ?? null,
                            'property_id' => $vehicle_parking['property_id'] ?? null,
                            'assess_id' => $vehicle_parking['assess_id'] ?? null,
                            'area_id' => $this->getAreaId($vehicle_parking['app_area_id'], $areas),
                            'location_id' => $this->getLocationId($vehicle_parking['app_location_id'], $locations),
                            'hazard_id' => $this->getHazardId($vehicle_parking['app_hazard_id'], $hazards),
                            'decommissioned' => $vehicle_parking['decommissioned'] ?? 0,
                            'decommissioned_reason' => $vehicle_parking['decommissioned_reason'] ?? null,
                            'accessibility' => $vehicle_parking['accessibility'] ?? null,
                            'reason_na' => $vehicle_parking['reason_na'] ?? null,
                            'reason_na_other' => $vehicle_parking['reason_na_other'] ?? null,
                            'comment' => $vehicle_parking['comment'] ?? null,
                            'is_locked' => 0,
                        ], $vehicle_parking['id']);
                    }

                    $this->saveUploadedImageToShineDocumentStorage($vehicle_parking['upload_image_id'], $parking->id);
                }

                // Update Assessor notes
                foreach ($data['assessor_notes'] as $note) {
                    $uploadImage = $this->uploadImageRepository->where('id', $note['upload_image_id'])->first();
                    $assessmentNote = $this->noteDocumentRepository->create([
                        'property_id' => $assessment->property_id ?? 0,
                        'plan_reference' => $note['name'] ?? null,
                        'assess_id' => $assessment->id,
//                        'category' => $assessment->id,
                        'description' => $note['comment'] ?? null,
                        'type' => 0,
                        'size' => $uploadImage->size ?? null,
                        'filename' => $uploadImage->filename ?? null,
                        'mime' => $uploadImage->mime ?? null,
                        'path' => $uploadImage->path ?? null,
                        'plan_date' => Carbon::now()->timestamp,
                    ]);
                    $assessmentNote->reference = 'PP' . $assessmentNote->id;
                    $assessmentNote->save();
                    $this->saveUploadedImageToShineDocumentStorage($note['upload_image_id'], $assessmentNote->id);
                }

                // Management Info & Other Info
                if ($assessment->classification == ASSESSMENT_FIRE_TYPE) {
                    foreach ($data['management_info_values'] as $management_info_value) {
                        $this->managementValueRepository->updateOrCreate(['id' => $management_info_value['id']], [
                            'assess_id' => $assessment->id,
                            'question_id' => $management_info_value['question_id'],
                            'answer_id' => $management_info_value['answer_id'],
                            'answer_other' => $management_info_value['answer_other'],
                        ]);
                    }

                    foreach ($data['other_info_values'] as $other_info_value) {
                        $this->otherValueRepository->updateOrCreate(['id' => $other_info_value['id']], [
                            'assess_id' => $assessment->id,
                            'question_id' => $other_info_value['question_id'],
                            'answer_id' => $other_info_value['answer_id'],
                            'answer_other' => $other_info_value['answer_other'],
                        ]);
                    }
                }
            }

            // Update manifest
            $this->manifestRepository->where('id', $data['manifest_id'])->update(['status' => self::STATUS_PROCESSED]);
            $uploadData->status = self::STATUS_PROCESSED;

            $uploadData->save();

            DB::commit();

            return \CommonHelpers::successResponse('Upload Data successfully', []);
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->manifestRepository->update(['status'=> self::STATUS_ERROR], $data['manifest_id']);
            Log::error($exception);
            return \CommonHelpers::failResponse(STATUS_FAIL, $exception->getMessage());
        }
    }

    private function updatePropertyInfo($propertyInfo, $assessmentInfoId)
    {
        try {
            if ($this->assessmentInfoRepository->update(['property_information' => $propertyInfo], $assessmentInfoId)) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

    private function updateExecutiveSummary($executive_summary, $assessmentInfoId)
    {
        try {
            if ($this->assessmentInfoRepository->update(['executive_summary' => $executive_summary], $assessmentInfoId)) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

    private function createArea($areaData, $assessment)
    {
        $create_area_data = [
            'property_id' => $assessment->property_id,
            'survey_id' => HAZARD_SURVEY_ID_DEFAULT,
            'assess_id' => $assessment->id,
            'area_reference' => $areaData['area_reference'] ?? '',
            'description' => $areaData['description']  ?? '',
            'state' => AREA_ACCESSIBLE_STATE,
            'decommissioned' => 0,
            'created_by' => \Auth::user()->id
        ];

        $area = $this->areaRepository->create($create_area_data);
        $area->record_id = $area->id;
        $area->reference = 'AF' . $area->id;
        $area->save();

        return $area;
    }

    private function createLocation($locationData, $assessment, $areas)
    {
        $create_data_location = [
            'area_id'               => $this->getAreaId($locationData['app_area_id'], $areas),
            'survey_id'             => HAZARD_SURVEY_ID_DEFAULT,
            'property_id'           => $assessment->property_id,
            'is_locked'             => 0,
            'assess_id'             => $assessment->id,
            'state'                 => LOCATION_STATE_ACCESSIBLE,
            'version'               => 1,
            'description'           => $locationData['description'] ?? '',
            'location_reference'    => $locationData['location_reference'] ?? '',
            'created_by'            => \Auth::user()->id
        ];

        $location = $this->locationRepository->create($create_data_location);
        $id = $location->id;
        $refLocation = 'RL' . $id;
        $location->reference = $refLocation;
        $location->record_id =  $id;
        $location->save();
        // update or create relation
        $this->locationRepository->updateOrCreateLocationInfo($id, []);
        $this->locationRepository->updateOrCreateLocationVoid($id, []);
        $this->locationRepository->updateOrCreateLocationConstruction($id, []);

        return $location;
    }

    private function getAreaId($app_area_id, $areas)
    {
        foreach ($areas as $area) {
            if ($area['app_id'] == $app_area_id) {
                return $area['id'];
            }
        }

        return 0;
    }

    private function getLocationId($app_location_id, $locations)
    {
        foreach ($locations as $location) {
            if ($location['app_id'] == $app_location_id) {
                return $location['id'];
            }
        }

        return 0;
    }

    private function getSystemId($app_system_id, $systems)
    {
        if (is_null($app_system_id)) {
            return null;
        }

        foreach ($systems as $system) {
            if ($system['app_id'] == $app_system_id) {
                return $system['id'];
            }
        }

        return 0;
    }

    private function getEquipmentId($app_equipment_id, $equipments)
    {
        if (is_null($app_equipment_id)) {
            return null;
        }

        foreach ($equipments as $equipment) {
            if ($equipment['app_id'] == $app_equipment_id) {
                return $equipment['id'];
            }
        }

        return 0;
    }

    private function getHazardId($app_hazard_id, $hazards)
    {
        if (is_null($app_hazard_id)) {
            return null;
        }

        foreach ($hazards as $hazard) {
            if ($hazard['app_id'] == $app_hazard_id) {
                return $hazard['id'];
            }
        }

        return 0;
    }

    private function correctParentEquipment($uploadedEquipments, $savedEquipments)
    {
        foreach ($savedEquipments as $savedEquipment) {
            foreach ($uploadedEquipments as $uploadedEquipment) {
                if ($savedEquipment->parent_id == $uploadedEquipment['app_id']) {
                    $savedEquipment->parent_id = $uploadedEquipment['id'];
                    $savedEquipment->save();
                }
                if ($savedEquipment->hot_parent_id == $uploadedEquipment['app_id']) {
                    $savedEquipment->hot_parent_id = $uploadedEquipment['id'];
                    $savedEquipment->save();
                }
                if ($savedEquipment->cold_parent_id == $uploadedEquipment['app_id']) {
                    $savedEquipment->cold_parent_id = $uploadedEquipment['id'];
                    $savedEquipment->save();
                }
            }
        }
    }

    private function saveUploadedImageToShineDocumentStorage($uploadImageId, $object_id)
    {
        try {
            if (!$uploadImage = $this->uploadImageRepository->where('id', $uploadImageId)->first()) {
                return false;
            }
//            //create thumbnail and crop img for items, losing quality for locations/property
//            if(in_array($uploadImage->image_type, [
//                    HAZARD_PHOTO,
//                    HAZARD_LOCATION_PHOTO,
//                    HAZARD_ADDITION_PHOTO,
//                    ASSEMBLY_POINT_PHOTO,
//                    FIRE_EXIT_PHOTO,
//                    VEHICLE_PARKING_PHOTO,
//            ])){
//                CommonHelpers::createThumbnail($uploadImage->path, true);
//            }
            ShineDocumentStorage::updateOrCreate(
                [ 'object_id' => $object_id, 'type' => $uploadImage->image_type],
                [
                    'path' => $uploadImage->path,
                    'file_name' => $uploadImage->file_name,
                    'mime' => $uploadImage->mime,
                    'size' => $uploadImage->size,
                    'addedBy' => \Auth::user()->id,
                    'addedDate' =>  Carbon::now()->timestamp,
                ]);

            return true;
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

    private function saveUploadedImageToShineComplianceDocumentStorage($uploadImageId, $object_id)
    {
        try {
            if (!$uploadImage = $this->uploadImageRepository->where('id', $uploadImageId)->first()) {
                return false;
            }

            ComplianceDocumentStorage::updateOrCreate(
                [ 'object_id' => $object_id, 'type' => $uploadImage->image_type],
                [
                    'path' => $uploadImage->path,
                    'file_name' => $uploadImage->file_name,
                    'mime' => $uploadImage->mime,
                    'size' => $uploadImage->size,
                    'addedBy' => \Auth::user()->id,
                    'addedDate' =>  Carbon::now()->timestamp,
                ]);

            return true;
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }
}
