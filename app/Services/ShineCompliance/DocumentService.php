<?php

namespace App\Services\ShineCompliance;

use App\Jobs\SendClientEmailNotification;
use App\Jobs\SendSampleEmail;
use App\Repositories\ShineCompliance\ClientRepository;
use App\Repositories\ShineCompliance\ClientInfoRepository;
use App\Repositories\ShineCompliance\ClientAddressRepository;
use App\Repositories\ShineCompliance\TrainingRecordRepository;
use App\Repositories\ShineCompliance\DocumentRepository;
use App\Repositories\ShineCompliance\PolicyRepository;
use App\Repositories\ShineCompliance\GSKDocumentRepository;
use App\Repositories\ShineCompliance\SurveyDateRepository;
use App\Repositories\ShineCompliance\SampleCertificateRepository;
use App\Repositories\ShineCompliance\AirTestCertificateRepository;
use App\Repositories\ShineCompliance\SurveyRepository;
use Carbon\Carbon;

class DocumentService{

    private $clientRepository;

    public function __construct(
        ClientRepository $clientRepository,
        ClientInfoRepository $clientInfoRepository,
        ClientAddressRepository $clientAddressRepository,
        TrainingRecordRepository $trainingRecordRepository,
        DocumentRepository $documentRepository,
        PolicyRepository $policyRepository,
        GSKDocumentRepository $gskDocumentRepository,
        SurveyDateRepository $surveyDateRepository,
        SampleCertificateRepository $sampleCertificateRepository,
        AirTestCertificateRepository $airTestCertificateRepository,
        SurveyRepository $surveyRepository
    ){
        $this->clientRepository = $clientRepository;
        $this->clientInfoRepository = $clientInfoRepository;
        $this->clientAddressRepository = $clientAddressRepository;
        $this->trainingRecordRepository = $trainingRecordRepository;
        $this->policyRepository = $policyRepository;
        $this->documentRepository = $documentRepository;
        $this->gskDocumentRepository = $gskDocumentRepository;
        $this->surveyDateRepository = $surveyDateRepository;
        $this->sampleCertificateRepository = $sampleCertificateRepository;
        $this->surveyRepository = $surveyRepository;
        $this->airTestCertificateRepository = $airTestCertificateRepository;

    }

    public function updateOrCreateTrainingRecord ($data,$doc_type, $id = null) {
        if (!empty($data)) {
            $dataDocument = [
                "name" => \CommonHelpers::checkArrayKey3($data,'name'),
                "client_id" => \CommonHelpers::checkArrayKey3($data,'client_id'),
                "added" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'traning_date'))
            ];
            try {
                \DB::beginTransaction();
                if ($doc_type == 'training') {
                    $path = TRAINING_RECORD_FILE;
                    if (is_null($id)) {
                        $dataDocument["added_by"] = \Auth::user()->id;
                        $dataDoc = $this->trainingRecordRepository->createTrainingRecord($dataDocument);
                        $response = \CommonHelpers::successResponse('Training Record Created successfully !');
                        //log audit
                        \CommonHelpers::logAudit(TRAINING_RECORD_TYPE, $dataDoc->id, AUDIT_ACTION_ADD, $dataDoc->name, $dataDoc->client_id);
                    } else {
                        $this->trainingRecordRepository->updateTrainingRecord($id,$dataDocument);
                        $dataDoc = $this->trainingRecordRepository->getTrainingRecordFist($id);
                        $response = CommonHelpers::successResponse('Training Record Updated successfully !');

                        //log audit
                        \CommonHelpers::logAudit(TRAINING_RECORD_TYPE, $dataDoc->id, AUDIT_ACTION_EDIT, $dataDoc->name, $dataDoc->client_id);
                    }
                } else {
                    $path = POLICY_FILE;
                    if (is_null($id)) {
                        $dataDocument["added_by"] = \Auth::user()->id;
                        $dataDoc = $this->policyRepository->createPolicy($dataDocument);
                        $response = \CommonHelpers::successResponse('Policy Created successfully !');
                    } else {
                        $this->policyRepository->updatePolicy($id,$dataDocument);
                        $dataDoc = $this->policyRepository->getPolicyFist($id);
                        $response = \CommonHelpers::successResponse('Policy Updated successfully !');
                    }
                }

                if (isset($data['document'])) {
                    $saveImg = \CommonHelpers::saveFileShineDocumentStorage($data['document'], $dataDoc->id, $path);
                }
                \DB::commit();
                return $response;
            } catch (\Exception $e) {
                \Log::debug($e);
                \DB::rollBack();
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to upload training record or policy. Please try again !');
            }
        }
    }

    public function updateOrCreateDocument($data, $id = null) {
        if (!empty($data)) {
            if (\CommonHelpers::checkArrayKey3($data,'contractors') != 0) {
                $contractors = implode(",", \CommonHelpers::checkArrayKey3($data,'contractors'));
            } else {
                $contractors = \CommonHelpers::checkArrayKey3($data,'contractor_key');
            }
            $status = 1;
            if (\CommonHelpers::checkArrayKey3($data,'doc_cat') == 7) {
                $status = 2;
            }
            if (\CommonHelpers::checkArrayKey3($data,'doc_cat') == 5) {
                $reType = "contractor";
            } else {
                $reType = "project";
            }
            $dataDocument = [
                "client_id" => \CommonHelpers::checkArrayKey3($data,'client_id'),
                "name" => \CommonHelpers::checkArrayKey3($data,'name'),
                "project_id" => \CommonHelpers::checkArrayKey3($data,'project_id'),
                "type" => \CommonHelpers::checkArrayKey3($data,'type'),
                "re_type" => $reType,
                "contractor" => \CommonHelpers::checkArrayKey3($data,'contractor_key'), // who added
                "contractors" => $contractors, // permission
                "rejected" => 0,
                "deleted" => 0,
                "category" => \CommonHelpers::checkArrayKey3($data,'doc_cat'),
                "authorised" => Carbon::now()->timestamp,
                "authorised_by" => \CommonHelpers::checkArrayKey3($data,'added_by'),
                "deadline" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'deadline')),
                "value" => \CommonHelpers::checkArrayKey3($data,'doc_value'),
            ];

            try {
                if (is_null($id)) {
                    $dataDocument['added'] = Carbon::now()->timestamp;
                    $dataDocument['status'] = $status;
                    $dataDocument['added_by'] = \CommonHelpers::checkArrayKey3($data,'added_by');
                    $document = $this->documentRepository->documentCreate($dataDocument);
                    if ($document) {
                        if ($dataDocument['category'] == GSK_DOC_CATEGORY) {

                            $docRef = \CommonHelpers::getDocumentReference($document->category, $this->countGSKDoc());
                        } else {
                            $docRef = \CommonHelpers::getDocumentReference($document->category, $document->id);
                        }
                        $this->documentRepository->updateDocument($document->id,['reference' => $docRef]);
                    }
                    $response = \CommonHelpers::successResponse('Uploaded Project Document Successfully!');

                    //log audit
                    \CommonHelpers::logAudit(DOCUMENT_TYPE, $document->id, AUDIT_ACTION_ADD, $docRef, $document->project_id, null , 0 , $document->project->property_id ?? 0);
                } else {
                    $document = $this->documentRepository->updateDocument($id,['reference' => $dataDocument]);
//                    $document = Document::where('id', $id)->first();
                    $response = CommonHelpers::successResponse('Project Document Updated Successfully!');

                    //log audit
                    \CommonHelpers::logAudit(DOCUMENT_TYPE, $document->id, AUDIT_ACTION_EDIT, $document->reference, $document->project_id, null , 0 , $document->project->property_id ?? 0);
                }
                if (isset($data['document_file'])) {
                    $saveLocationImage = \CommonHelpers::saveFileShineDocumentStorage($data['document_file'], $document->id, DOCUMENT_FILE);
                    $dataUpdateImg = [
                        'document_present' => 1,
                        "added" => Carbon::now()->timestamp
                    ];
                    $this->documentRepository->updateDocument($document->id,$dataUpdateImg);
                }
                $contractors_doc = explode(",",$contractors);
                $project =  $document->project;
                if ($project->contractor_not_required != 1) {
                    // NOTIFICATION
                    if (!in_array($document->category, [TENDER_DOC_CATEGORY, PLANNING_DOC_CATEGORY, PRE_START_DOC_CATEGORY, SITE_RECORDS_DOC_CATEGORY, COMPLETION_DOC_CATEGORY]) and $document->category != APPROVAL_DOC_CATEGORY) {
                        if ($project->lead_key == $project->second_lead_key) {
                            \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendClientEmailNotification(
                                $project->client->name,
                                CONTRACTOR_PROJECT_EMAILTYPE,
                                $project->lead_key,
                                $project->property->property_reference ?? '',
                                $project->property->name ?? '',
                                $project->property->propertyInfo->pblock ?? '',
                                $document->reference,
                                \Auth::user()->clients->name,
                                $document->name,
                                $project->reference
                            ));
                        } else {
                            \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendClientEmailNotification(
                                $project->client->name,
                                CONTRACTOR_PROJECT_EMAILTYPE,
                                $project->lead_key,
                                $project->property->property_reference ?? '',
                                $project->property->name ?? '',
                                $project->property->propertyInfo->pblock ?? '',
                                $document->reference,
                                \Auth::user()->clients->name,
                                $document->name,
                                $project->reference
                            ));

                            \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendClientEmailNotification(
                                $project->client->name,
                                CONTRACTOR_PROJECT_EMAILTYPE,
                                $project->second_lead_key,
                                $project->property->property_reference ?? '',
                                $project->property->name ?? '',
                                $project->property->propertyInfo->pblock ?? '',
                                $document->reference,
                                \Auth::user()->clients->name,
                                $document->name,
                                $project->reference
                            ));
                        }

                        if ($contractors_doc) {

                            foreach (array_filter($contractors_doc) as  $contractor) {
                                // SLOT ADD
                                \Notifications::checkNotification(5, $contractor, $project->id, $document->id);
                                // REJECTED
                                \Notifications::checkNotification(6, $contractor, $project->id, $document->id);
                            }
                        }

                        if ($document->status == 3) {
                            $this->documentRepository->reapproveDocument($document->id);
                        }
                    } elseif($document->category == CONTRACTOR_DOC_CATEGORY) {
                        if ($project->lead_key == $project->second_lead_key) {
                            \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendClientEmailNotification(
                                $project->client->name,
                                CONTRACTOR_PROJECT_EMAILTYPE,
                                $project->lead_key,
                                $project->property->property_reference ?? '',
                                $project->property->name ?? '',
                                $project->property->propertyInfo->pblock ?? '',
                                $document->reference,
                                \Auth::user()->clients->name,
                                $document->name,
                                $project->reference
                            ));
                        } else {
                            \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendClientEmailNotification(
                                $project->client->name,
                                CONTRACTOR_PROJECT_EMAILTYPE,
                                $project->lead_key,
                                $project->property->property_reference ?? '',
                                $project->property->name ?? '',
                                $project->property->propertyInfo->pblock ?? '',
                                $document->reference,
                                \Auth::user()->clients->name,
                                $document->name,
                                $project->reference
                            ));

                            \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendClientEmailNotification(
                                $project->client->name,
                                CONTRACTOR_PROJECT_EMAILTYPE,
                                $project->second_lead_key,
                                $project->property->property_reference ?? '',
                                $project->property->name ?? '',
                                $project->property->propertyInfo->pblock ?? '',
                                $document->reference,
                                \Auth::user()->clients->name,
                                $document->name,
                                $project->reference
                            ));
                        }
                    }
                } else {
                    if (\CommonHelpers::isSystemClient() || \Auth::user()->client_id == $document->client_id) {
                        if (!in_array($document->category, [TENDER_DOC_CATEGORY, PLANNING_DOC_CATEGORY, PRE_START_DOC_CATEGORY, SITE_RECORDS_DOC_CATEGORY, COMPLETION_DOC_CATEGORY]) and $document->category != APPROVAL_DOC_CATEGORY) {
                            if ($contractors_doc) {
                                $existingNotifications = \Notifications::checkAvailableNotification($document->id, 5);
                                // CHECKING EXISTING CONTRACTORS
                                if (!is_null($existingNotifications)) {
                                    $existContractors = [];
                                    foreach ($existingNotifications as $existNoti) {
                                        $existContractors[] = $existNoti->contractor_id;
                                    }
                                    foreach (array_filter($contractors_doc) as  $contractor_id) {
                                        if (!in_array($contractor_id, $existContractors))
                                            \Notifications::sendMailNotification(5, $project->id, $contractor_id);
                                    }
                                } else {
                                    foreach (array_filter($contractors_doc) as  $contractor_id) {
                                        \Notifications::sendMailNotification(5, $project->id, $contractor_id);
                                    }
                                }
                            }
                        }
                    }
                }

                return $response;
            } catch (\Exception $e) {
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to upload document. Please try again !');
            }
        }
    }

    public function countGSKDoc() {
        $document = $this->documentRepository->getDocumentbyCategory(GSK_DOC_CATEGORY);
        $gsk_doc = $this->gskDocumentRepository->getAllGSKDocument();
        $count = $document->id + 1 + count($gsk_doc);
        return $count;
    }

    public function rejectDocument($data) {
        if (isset($data['id'])) {
            $id = $data['id'];
            $dataReject = [
                'status' => 3,
                'deadline' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'deadline')),
                'note' => \CommonHelpers::checkArrayKey3($data,'note'),
                'rejected' => Carbon::now()->timestamp ?? '',
                'rejected_by' => \Auth::user()->id ?? '',
            ];
            try {
                $reject =  $this->documentRepository->updateDocument($id,$dataReject);
                //log audit
                $document =  $reject->find($id);
                \CommonHelpers::logAudit(DOCUMENT_TYPE, $document->id, AUDIT_ACTION_REJECTED, $document->reference, $document->project_id, null , 0 , $document->project->property_id ?? 0);
                return \CommonHelpers::successResponse('Document Rejected Successfully!');
            } catch (\Exception $e) {
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to reject document. Please try again !');
            }
        }
    }

    public function updateOrCreateSampleCertificate ($data, $id = null) {
        $survey =  $this->surveyRepository->where('id',$data['assess_id'])->first();
        if (!empty($data)) {
            $dataSampleCertificate = [
                "sample_reference" => \CommonHelpers::checkArrayKey3($data,'name'),
                "description" => \CommonHelpers::checkArrayKey3($data,'description'),
                "survey_id" => \CommonHelpers::checkArrayKey3($data,'assess_id'),
                "updated_date" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'plan_date')),
            ];
            try {
                \DB::beginTransaction();
                if (is_null($id)) {
                    $sampleCertificate = $this->sampleCertificateRepository->create($dataSampleCertificate);
                    if ($sampleCertificate) {
                        $sampleCertificateRef = "SC" . $sampleCertificate->id;
                        $this->sampleCertificateRepository->where('id', $sampleCertificate->id)->update(['reference' => $sampleCertificateRef]);
                    }
                    $response = \CommonHelpers::successResponse('Sample Certificate Created successfully !');

                    //work flow update sample logic
                    $can_upload_sample = false;
                    // Organisation* = London Borough of Hackney
                    if ($survey->client_id == 1) {
                        // External Laboratory = Yes
                        if ($survey->surveySetting->external_laboratory == 1) {
                            // Linked Project = Linked to a Survey Only Project created by the Work Request Feature with the Workflow Dropdown = Tersus Group.
                            if (isset($survey->project) and !is_null($survey->project)) {
                                $project_type =  $survey->project->project_type ?? 0;
                                $project_status = $survey->project->status ?? 0;
                                $work_request = $survey->project->workRequest ?? null;
                                $work_flow = $work_request->work_flow ?? 0;
                                if (($project_type == PROJECT_SURVEY_ONLY) and !is_null($work_request) and ($work_flow == WORK_FLOW_HACKNEY) and ($survey->status != COMPLETED_SURVEY_STATUS) and ($project_status != PROJECT_COMPLETE_STATUS)  and (\Auth::user()->client_id == 2)) {
                                    $can_upload_sample = true;
                                }
                            }
                        }
                    }

                    // check permission bulk sample work flow
                    if ($can_upload_sample) {
                        $datasampleEmail = [
                            'assess_id' => $survey->id ?? 0,
                            'client_id' => $survey->property->client_id ?? 0,
                            "survey_reference" => $survey->reference ?? null,
                            "contractor_name" => $survey->client->name ?? null,
                            "block_reference" => $survey->property->pblock ?? null,
                            "property_uprn" => $survey->property->reference ?? null,
                            "property_name" => $survey->property->name ?? null,
                            "postcode" => $survey->property->propertyInfo->postcode ?? null,
                            "domain" => \Config::get('app.url')
                        ];
                        $cad_tech_id = $survey->cad_tech_id ?? 0;
                        $surveyor_id = $survey->surveyor_id ?? 0;

                        $datasampleEmail['subject'] = 'Bulk Sample Certificate Uploaded';

                        // log time when send sample results updated send to CAD tech only
                        $this->surveyDateRepository->where('assess_id', $survey->id ?? 0)->update(['sample_results_updated' => time()]);
                        \Queue::pushOn(SURVEY_APPROVAL_EMAIL_QUEUE, new SendSampleEmail($datasampleEmail, EMAIL_SAMPLE_EMAIL_QUEUE, $cad_tech_id));
                    }

                    //log audit
                    \CommonHelpers::logAudit(SAMPLE_CERTIFICATE_TYPE, $sampleCertificate->id, AUDIT_ACTION_ADD, $sampleCertificateRef, $sampleCertificate->assess_id, null , 0, $sampleCertificate->survey->property_id ?? 0);
                } else {
                    $this->sampleCertificateRepository->where('id', $id)->update($dataSampleCertificate);
                    $sampleCertificate = $this->sampleCertificateRepository->where('id', $id)->first();
                    $response = \CommonHelpers::successResponse('Sample Certificate Updated successfully !');

                    //log audit
                    \CommonHelpers::logAudit(SAMPLE_CERTIFICATE_TYPE, $sampleCertificate->id, AUDIT_ACTION_EDIT, $sampleCertificate->sample_reference, $sampleCertificate->assess_id, null , 0, $sampleCertificate->survey->property_id ?? 0);
                }

                if (isset($data['document'])) {
                    $saveLocationImage = \CommonHelpers::saveFileShineDocumentStorage($data['document'], $sampleCertificate->id, SAMPLE_CERTIFICATE_FILE);
                }
                \DB::commit();
                return $response;
            } catch (\Exception $e) {
                \Log::debug($e);
                \DB::rollBack();
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to upload sample certificate. Please try again !');
            }
        }
    }

    public function updateOrCreateAirTestCertificate ($data, $id = null) {
        if (!empty($data)) {
            $dataSampleCertificate = [
                "air_test_reference" => \CommonHelpers::checkArrayKey3($data,'name'),
                "description" => \CommonHelpers::checkArrayKey3($data,'description'),
                "survey_id" => \CommonHelpers::checkArrayKey3($data,'assess_id'),
                "updated_date" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'plan_date')),
            ];
            try {
                \DB::beginTransaction();
                if (is_null($id)) {
                    $airTestCertificate = $this->airTestCertificateRepository->create($dataSampleCertificate);
                    if ($airTestCertificate) {
                        $airTestCertificateRef = "AC" . $airTestCertificate->id;
                        $this->airTestCertificateRepository->where('id', $airTestCertificate->id)->update(['reference' => $airTestCertificateRef]);
                    }
                    $response = \CommonHelpers::successResponse('AirTest Certificate Created successfully !');
                    //log audit
                    \CommonHelpers::logAudit(AIR_TEST_CERTIFICATE_TYPE, $airTestCertificate->id, AUDIT_ACTION_ADD, $airTestCertificateRef, $airTestCertificate->survey_id, null , 0, $airTestCertificate->survey->property_id ?? 0);
                } else {
                    $this->airTestCertificateRepository->where('id', $id)->update($dataSampleCertificate);
                    $airTestCertificate = $this->airTestCertificateRepository->where('id', $id)->first();
                    $response = \CommonHelpers::successResponse('AirTest Certificate Updated successfully !');

                    //log audit
                    \CommonHelpers::logAudit(AIR_TEST_CERTIFICATE_TYPE, $airTestCertificate->id, AUDIT_ACTION_EDIT, $airTestCertificate->air_test_reference, $airTestCertificate->survey_id, null , 0, $airTestCertificate->survey->property_id ?? 0);
                }

                if (isset($data['document'])) {
                    $saveLocationImage = \CommonHelpers::saveFileShineDocumentStorage($data['document'], $airTestCertificate->id, SAMPLE_CERTIFICATE_FILE);
                }
                \DB::commit();
                return $response;
            } catch (\Exception $e) {
                \Log::debug($e);
                \DB::rollBack();
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to upload air test certificate. Please try again !');
            }
        }
    }

    public function getListDocumentsOverDue($datacentreRisk, $category, $user_id = null,$status = "1,3", $page = 0, $limit = 400) {
        $project_types = \CompliancePrivilege::getDataCentreAssessmentProjectPermission('project',$datacentreRisk, 'sql');
        return $this->documentRepository->getListDocumentsOverDue($project_types, $datacentreRisk, $category, $user_id,$status, $page, $limit);
    }

    public function getListUserProjectDocuments($status = "1,3", $page = 0, $limit = 400) {
        return $this->documentRepository->getListUserProjectDocuments($status, $page, $limit);
    }
}
