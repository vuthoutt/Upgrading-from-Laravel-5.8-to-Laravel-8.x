<?php
namespace App\Repositories;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Document;
use App\Models\SampleCertificate;
use App\Models\AirTestCertificate;
use App\Models\TrainingRecord;
use App\Models\Policy;
use App\Jobs\SendClientEmailNotification;
use App\Models\ShineAsbestosLeadAdmin;
use App\Helpers\CommonHelpers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DocumentRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Document::class;
    }

    public function updateOrCreateDocument($data, $id = null) {
        if (!empty($data)) {
            if (\CommonHelpers::checkArrayKey3($data,'contractors') != 0) {
                $contractors = implode(",", \CommonHelpers::checkArrayKey3($data,'contractors'));
            } else {
                $contractors = \CommonHelpers::checkArrayKey3($data,'contractor_key');
            }
            $status = PROJECT_DOC_CREATED;
            if (CommonHelpers::checkArrayKey3($data,'doc_cat') == GSK_DOC_CATEGORY) {
                $status = PROJECT_DOC_PUBLISHED;
            }
            if (isset($data['document_file'])) {
                $status = PROJECT_DOC_PUBLISHED;
            }
            if (CommonHelpers::checkArrayKey3($data,'doc_cat') == CONTRACTOR_DOC_CATEGORY) {
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
                'status' => $status,
                "contractor" => \CommonHelpers::checkArrayKey3($data,'contractor_key'), // who added
                "contractors" => $contractors, // permission
                "rejected" => 0,
                "deleted" => 0,
                "category" => CommonHelpers::checkArrayKey3($data,'doc_cat'),
                "authorised" => Carbon::now()->timestamp,
                "authorised_by" => \CommonHelpers::checkArrayKey3($data,'added_by'),
                "deadline" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'deadline')),
                "value" => \CommonHelpers::checkArrayKey3($data,'doc_value'),
            ];

            try {
                if (is_null($id)) {
                    $dataDocument['added'] = Carbon::now()->timestamp;
                    $dataDocument['added_by'] = \CommonHelpers::checkArrayKey3($data,'added_by');
                    $document = Document::create($dataDocument);
                    if ($document) {
                        $docRef = CommonHelpers::getDocumentReference($document->category, $document->id);
                        Document::where('id', $document->id)->update(['reference' => $docRef]);
                    }
                    $response = CommonHelpers::successResponse('Project Document Created Successfully');

                    //log audit
                    \CommonHelpers::logAudit(DOCUMENT_TYPE, $document->id, AUDIT_ACTION_ADD, $docRef, $document->project_id, null , 0 , $document->project->property_id ?? 0);
                } else {
                    Document::where('id', $id)->update($dataDocument);
                    $document = Document::where('id', $id)->first();
                    $response = CommonHelpers::successResponse('Project Document Updated Successfully!');

                    //log audit
                    \CommonHelpers::logAudit(DOCUMENT_TYPE, $document->id, AUDIT_ACTION_EDIT, $document->reference, $document->project_id, null , 0 , $document->project->property_id ?? 0);
                }


                if (isset($data['document_file'])) {
                    $saveLocationImage = \CommonHelpers::saveFileShineDocumentStorage($data['document_file'], $document->id, DOCUMENT_FILE);
                    $dataUpdateImg = [
                        'document_present' => 1,
                        // update status if document is rejected
                        'status' => ($document->status == PROJECT_DOC_REJECTED) ? PROJECT_DOC_CREATED : $document->status,
                        "added" => Carbon::now()->timestamp
                    ];
                    Document::where('id', $document->id)->update($dataUpdateImg);
                $contractors_doc = explode(",",$contractors);
                $project =  $document->project;
                if ($project->lead_key == $project->second_lead_key) {
                    \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendClientEmailNotification(
                        $project->client->name,
                        CONTRACTOR_PROJECT_EMAILTYPE,
                        $project->lead_key,
                        $project->property->property_reference ?? '',
                        $project->property->name ?? '',
                        $project->property->propertyInfo->pblock ?? '',
                        $document->reference ?? $docRef,
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
                        $document->reference ?? $docRef,
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
                        $document->reference ?? $docRef,
                        \Auth::user()->clients->name,
                        $document->name,
                        $project->reference
                    ));
                }
            }

                return $response;
            } catch (\Exception $e) {
                return CommonHelpers::failResponse(STATUS_FAIL,'Failed to upload document. Please try again !');
            }
        }
    }

    public function reapproveDocument($id) {
        Document::where('id',$id)->update(['status' => PROJECT_DOC_PUBLISHED]);
    }

    public function updateOrCreateSampleCertificate ($data, $id = null) {
        if (!empty($data)) {
            $dataSampleCertificate = [
                "sample_reference" => \CommonHelpers::checkArrayKey3($data,'name'),
                "description" => \CommonHelpers::checkArrayKey3($data,'description'),
                "survey_id" => \CommonHelpers::checkArrayKey3($data,'survey_id'),
                "updated_date" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'plan_date')),
            ];
            try {
                if (is_null($id)) {
                    $sampleCertificate = SampleCertificate::create($dataSampleCertificate);
                    if ($sampleCertificate) {
                        $sampleCertificateRef = "SC" . $sampleCertificate->id;
                        SampleCertificate::where('id', $sampleCertificate->id)->update(['reference' => $sampleCertificateRef]);
                    }
                    $response = CommonHelpers::successResponse('Sample Certificate Created successfully !');

                    //log audit
                    \CommonHelpers::logAudit(SAMPLE_CERTIFICATE_TYPE, $sampleCertificate->id, AUDIT_ACTION_ADD, $sampleCertificateRef, $sampleCertificate->survey_id, null , 0, $sampleCertificate->survey->property_id ?? 0);
                } else {
                    SampleCertificate::where('id', $id)->update($dataSampleCertificate);
                    $sampleCertificate = SampleCertificate::where('id', $id)->first();
                    $response = CommonHelpers::successResponse('Sample Certificate Updated successfully !');

                    //log audit
                    \CommonHelpers::logAudit(SAMPLE_CERTIFICATE_TYPE, $sampleCertificate->id, AUDIT_ACTION_EDIT, $sampleCertificate->sample_reference, $sampleCertificate->survey_id, null , 0, $sampleCertificate->survey->property_id ?? 0);
                }

                if (isset($data['document'])) {
                    $saveLocationImage = \CommonHelpers::saveFileShineDocumentStorage($data['document'], $sampleCertificate->id, SAMPLE_CERTIFICATE_FILE);
                }

                return $response;
            } catch (\Exception $e) {
                return CommonHelpers::failResponse(STATUS_FAIL,'Failed to upload sample certificate. Please try again !');
            }
        }
    }

    public function updateOrCreateAirTestCertificate ($data, $id = null) {
        if (!empty($data)) {
            $dataSampleCertificate = [
                "air_test_reference" => \CommonHelpers::checkArrayKey3($data,'name'),
                "description" => \CommonHelpers::checkArrayKey3($data,'description'),
                "survey_id" => \CommonHelpers::checkArrayKey3($data,'survey_id'),
                "updated_date" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'plan_date')),
            ];
            try {
                if (is_null($id)) {
                    $airTestCertificate = AirTestCertificate::create($dataSampleCertificate);
                    if ($airTestCertificate) {
                        $airTestCertificateRef = "AC" . $airTestCertificate->id;
                        AirTestCertificate::where('id', $airTestCertificate->id)->update(['reference' => $airTestCertificateRef]);
                    }
                    $response = CommonHelpers::successResponse('AirTest Certificate Created successfully !');
                    //log audit
                    \CommonHelpers::logAudit(AIR_TEST_CERTIFICATE_TYPE, $airTestCertificate->id, AUDIT_ACTION_ADD, $airTestCertificateRef, $airTestCertificate->survey_id, null , 0, $airTestCertificate->survey->property_id ?? 0);
                } else {
                    AirTestCertificate::where('id', $id)->update($dataSampleCertificate);
                    $airTestCertificate = AirTestCertificate::where('id', $id)->first();
                    $response = CommonHelpers::successResponse('AirTest Certificate Updated successfully !');

                    //log audit
                    \CommonHelpers::logAudit(AIR_TEST_CERTIFICATE_TYPE, $airTestCertificate->id, AUDIT_ACTION_EDIT, $airTestCertificate->air_test_reference, $airTestCertificate->survey_id, null , 0, $airTestCertificate->survey->property_id ?? 0);
                }

                if (isset($data['document'])) {
                    $saveLocationImage = \CommonHelpers::saveFileShineDocumentStorage($data['document'], $airTestCertificate->id, SAMPLE_CERTIFICATE_FILE);
                }

                return $response;
            } catch (\Exception $e) {
                return CommonHelpers::failResponse(STATUS_FAIL,'Failed to upload air test certificate. Please try again !');
            }
        }
    }

    public function updateOrCreateTrainingRecord ($data,$doc_type, $id = null) {
        if (!empty($data)) {
            $dataDocument = [
                "name" => \CommonHelpers::checkArrayKey3($data,'name'),
                "client_id" => \CommonHelpers::checkArrayKey3($data,'client_id'),
                "added" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'traning_date'))
            ];
            try {
                if ($doc_type == 'training') {
                    $path = TRAINING_RECORD_FILE;
                    if (is_null($id)) {
                        $dataDocument["added_by"] = \Auth::user()->id;
                        $dataDoc = TrainingRecord::create($dataDocument);
                        $response = CommonHelpers::successResponse('Training Record Created successfully !');

                        //log audit
                        \CommonHelpers::logAudit(TRAINING_RECORD_TYPE, $dataDoc->id, AUDIT_ACTION_ADD, $dataDoc->name, $dataDoc->client_id);
                    } else {
                        TrainingRecord::where('id', $id)->update($dataDocument);
                        $dataDoc = TrainingRecord::where('id', $id)->first();
                        $response = CommonHelpers::successResponse('Training Record Updated successfully !');

                        //log audit
                        \CommonHelpers::logAudit(TRAINING_RECORD_TYPE, $dataDoc->id, AUDIT_ACTION_EDIT, $dataDoc->name, $dataDoc->client_id);
                    }
                } else {
                    $path = POLICY_FILE;
                    if (is_null($id)) {
                        $dataDocument["added_by"] = \Auth::user()->id;
                        $dataDoc = Policy::create($dataDocument);
                        $response = CommonHelpers::successResponse('Policy Created successfully !');
                    } else {
                        Policy::where('id', $id)->update($dataDocument);
                        $dataDoc = Policy::where('id', $id)->first();
                        $response = CommonHelpers::successResponse('Policy Updated successfully !');
                    }
                }

                if (isset($data['document'])) {
                    $saveImg = \CommonHelpers::saveFileShineDocumentStorage($data['document'], $dataDoc->id, $path);
                }

                return $response;
            } catch (\Exception $e) {
                return CommonHelpers::failResponse(STATUS_FAIL,'Failed to upload training record or policy. Please try again !');
            }
        }
    }

    public function getListDocumentsOverDue($datacentreRisk, $category, $user_id = null,$status = "1,3", $page = 0, $limit = 400) {

        switch ($datacentreRisk) {
            case "deadline":
                $botTime = 120 * 86400 + time();
                $timeSQL = " AND `deadline` > $botTime ";
                break;
            case "attention":
                $topTime = 120 * 86400 + time();
                $botTime = 60 * 86400 + time();
                $timeSQL = " AND `deadline` <= $topTime AND `deadline` > $botTime ";
                break;
            case "important":
                $topTime = 60 * 86400 + time();
                $botTime = 30 * 86400 + time();
                $timeSQL = " AND `deadline` <= $topTime AND `deadline` > $botTime ";
                break;
            case "urgent":
                $topTime = 30 * 86400 + time();
                $botTime = 15 * 86400 + time();
                $timeSQL = " AND `deadline` <= $topTime AND `deadline` > $botTime ";
                break;
            case "critical":
                $topTime = 15 * 86400 + time();
                $timeSQL = " AND `deadline` <= $topTime ";
                break;
            case "approval":
            case "reject":
                $timeSQL = " AND `tbl_documents`.`document_present` != 0 ";
                    if ($status == 'homepage') {
                        $timeSQL .= (in_array( \Auth::user()->id, $this->getGetAdminAsbestosLead() )) ? ""
                            : " AND pj.`lead_key` = " . \Auth::user()->id;
                        $status = 1;
                    }
                break;
            default:
                $timeSQL = "";
                break;
        }
        if (!\CommonHelpers::isSystemClient()) {
            $timeSQL .= " AND tbl_documents.contractor = " .\Auth::user()->client_id;
        } else {
            //privilege
            $timeSQL .= " AND pj.property_id IN " .\CompliancePrivilege::getPermission(PROPERTY_PERMISSION,'sql').
                        " AND pj.project_type IN ".\CompliancePrivilege::getPermission(PROJECT_TYPE_PERMISSION,'sql');
        }


        $sqlQuery = "SELECT `tbl_documents`.`id`, `tbl_documents`.`project_id` as project_id, `dt`.`doc_type`, `tbl_documents`.`deadline`,
                                `tbl_documents`.`added`,pj.name as property_name,
                                pj.property_id as 'property_id', pj.reference as 'project_reference', pj.`lead_key` as 'lead_key',
                                pj.second_lead_key as 'second_lead_key', pj.title as 'project_title', s.published_date, pj.pblock,tbl_documents.name as doc_name,
                                tbl_documents.note
                        FROM `tbl_documents`
                        LEFT JOIN `tbl_refurb_doc_types` as dt
                            ON `tbl_documents`.type = dt.id
                        LEFT JOIN (SELECT tbl_project.*,  tbl_property.pblock,tbl_property.name
                            FROM tbl_project LEFT JOIN tbl_property ON tbl_project.property_id = tbl_property.id
                            )
                            as pj
                        ON `tbl_documents`.project_id = pj.id
                        LEFT JOIN (SELECT sv.project_id, MAX( sd.published_date ) as published_date FROM tbl_survey sv LEFT JOIN tbl_survey_date sd ON sv.id = sd.survey_id WHERE sd.published_date IS NOT NULL AND sd.published_date > 0 GROUP BY sv.project_id) s ON s.project_id= pj.id
                        WHERE `tbl_documents`.`status` IN ($status) AND `tbl_documents`.category IN ($category)
                            AND pj.`status` NOT IN (1,2,5)
                            $timeSQL
                        ORDER BY pj.id desc, `deadline` DESC";

        $results = DB::select($sqlQuery);
        return $results;
    }

    public function getMyApprovalDocument() {
            // property privilege
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();
            $project_types = \CompliancePrivilege::getPermission(PROJECT_TYPE_PERMISSION,'sql');
            $user_id = \Auth::user()->id;

            $sqlQuery = "SELECT pj.id as project_id, pj.reference,pj.title, d.id, pj.property_id, d.status, pr.pblock,d.status,d.added as added, pr.property_reference pref, pr.name pname,
                                 d.added as published_date, d.deadline as completed_date, `dt`.`doc_type`, d.name as doc_name
                                 FROM tbl_documents d
                                LEFT JOIN `tbl_refurb_doc_types` as dt ON `d`.type = dt.id
                                LEFT JOIN tbl_project pj ON pj.id = d.project_id
                                LEFT JOIN tbl_property pr ON pr.id = pj.property_id
                                JOIN $table_join_privs ON permission.prop_id = pj.property_id
                                WHERE d.status = 1
                                and d.category NOT IN (4,7)
                                and pj.project_type IN $project_types
                                AND pj.status NOT IN (1,5)
                                AND pj.lead_key = $user_id
                                ";
        $results = DB::select($sqlQuery);
        return $results;
    }

    public function getGetAdminAsbestosLead() {
        $user_ids =  ShineAsbestosLeadAdmin::all()->pluck('user_id')->toArray();
        return $user_ids;
    }

    public function rejectDocument($data) {
        if (isset($data['id'])) {
            $id = $data['id'];
            $dataReject = [
                'status' => 3,
                'deadline' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'deadline')),
                'note' => \CommonHelpers::checkArrayKey3($data,'note'),
                'rejected' => Carbon::now()->timestamp,
                'rejected_by' => \Auth::user()->id,
            ];
            try {
                $reject = Document::where('id', $id)->update($dataReject);
                //log audit
                $document =  Document::find($id);
                \CommonHelpers::logAudit(DOCUMENT_TYPE, $document->id, AUDIT_ACTION_REJECTED, $document->reference, $document->project_id, null , 0 , $document->project->property_id ?? 0);
                $project =  $document->project;
                \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendClientEmailNotification(
                    $project->client->name,
                    CONTRACTOR_REJECT_EMAILTYPE,
                    $document->added_by,
                    $project->property->property_reference ?? '',
                    $project->property->name ?? '',
                    $project->property->propertyInfo->pblock ?? '',
                    $document->reference,
                    \Auth::user()->clients->name,
                    $document->name,
                    $project
                ));
                return \CommonHelpers::successResponse('Document Rejected Successfully!');
            } catch (\Exception $e) {
                return CommonHelpers::failResponse(STATUS_FAIL,'Failed to reject document. Please try again !');
            }
        }
    }

    public function approvalDocument($id) {
        if (isset($id)) {
            $dataApproval = [
                'status' => 2,
                'approved' => Carbon::now()->timestamp,
                'approved_by' => \Auth::user()->id,
            ];
            try {
                $reject = Document::where('id', $id)->update($dataApproval);

                //log audit
                $document =  Document::find($id);
                \CommonHelpers::logAudit(DOCUMENT_TYPE, $document->id, AUDIT_ACTION_APPROVED, $document->reference, $document->project_id, null , 0 , $document->project->property_id ?? 0);
                $project =  $document->project;
                    \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendClientEmailNotification(
                        $project->client->name,
                        CONTRACTOR_APPROVED_EMAILTYPE,
                        $document->added_by,
                        $project->property->property_reference ?? '',
                        $project->property->name ?? '',
                        $project->property->propertyInfo->pblock ?? '',
                        $document->reference,
                        \Auth::user()->clients->name,
                        $document->name,
                        $project
                    ));
                return \CommonHelpers::successResponse('Document Approved Successfully!');
            } catch (\Exception $e) {
                return CommonHelpers::failResponse(STATUS_FAIL,'Failed to approval document. Please try again !');
            }
        }
    }
    public function cancelDocument($id) {
        if (isset($id)) {
            $dataApproval = [
                'status' => PROJECT_DOC_CANCELLED,
            ];
            try {
               Document::where('id', $id)->update($dataApproval);

                //log audit
                $document =  Document::find($id);
                \CommonHelpers::logAudit(DOCUMENT_TYPE, $document->id, AUDIT_ACTION_CANCELED, $document->reference, $document->project_id, null , 0 , $document->project->property_id ?? 0);
                return \CommonHelpers::successResponse('Document Cancel Successfully!');
            } catch (\Exception $e) {
                return CommonHelpers::failResponse(STATUS_FAIL,'Failed to cancel document. Please try again !');
            }
        }
    }

    public function searchDocument($q, $doctype = 0){
        $checkPrivilege = "AND id > 0";
        if (\Auth::user()->clients->client_type == 2) {
            $checkPrivilege = " AND `client_id` = " . \Auth::user()->client_id;
        } elseif (\Auth::user()->clients->client_type == 1) {
            $checkPrivilege = " AND `contractors` LIKE '%" . \Auth::user()->client_id . "%' ";
        }

        return $this->model->whereRaw("(reference LIKE '%$q%' OR name LIKE '%$q%') ".$checkPrivilege)
                ->where('category','=',$doctype)->orderBy('name','asc')->limit(LIMIT_SEARCH)->get();
    }

     public function searchDocumentForm($q){

        return $this->model->whereRaw("(reference LIKE '%$q%' OR name LIKE '%$q%') ")->orderBy('name','asc')->limit(LIMIT_SEARCH)->get();
    }
}
