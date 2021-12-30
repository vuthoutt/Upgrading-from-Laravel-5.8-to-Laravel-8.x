<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ProjectType;
use App\Models\ShineCompliance\Document;
use Prettus\Repository\Eloquent\BaseRepository;

class DocumentRepository extends BaseRepository
{

    public function model()
    {
        return Document::class;
    }

    public function documentCreate($data){
        return $this->model->create($data);
    }

    public function updateDocument($id,$data){
        return $this->model->where('id',$id)->create($data);
    }

    public function getDocumentbyCategory($category){
        return $this->model->where('category', $category)->orderBy('id','desc')->first();
    }

    public function getSurveyTypes(){
        $survey_types = $this->model->orderBy('order', 'asc')->get();
        return $survey_types;
    }

    public function getTenderBoxDocsContractor($project_id, $category, $client_id) {
        $documents = $this->model->where('project_id', $project_id)
            ->where('category', $category)
            ->whereRaw("FIND_IN_SET('$client_id', REPLACE(contractors, ' ', ''))")
            ->orderBy('added', 'desc')
            ->get();
        return $documents;
    }

    public function getTenderBoxDocs($project_id, $category) {
        $documents = $this->model->where('project_id', $project_id)
            ->where('category', $category)
            ->orderBy('added', 'desc')
            ->get();
        return $documents;
    }

    public function getContractorBoxDocs($project_id, $category, $contractor_id) {
        $documents = $this->model->where('project_id', $project_id)
            ->where('category', $category)
            ->where('contractor', $contractor_id)
            ->orderBy('added', 'desc')
            ->get();
        return $documents;
    }

    public function getDocmentApprovalProject($project_id){
        return $this->model->whereIn('status',[1,3])->where('category', 5)->whereNotNull('type')->where('project_id', $project_id)->first();
    }

    public function reapproveDocument($id) {
        return $this->model->where('id',$id)->update(['status' => 1]);
    }

    public function getListDocumentsOverDue($project_types, $datacentreRisk, $category, $user_id = null,$status = "1,3", $page = 0, $limit = 400) {

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
            $join_privs = '';
        } else {
            //privilege
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();
            $join_privs = "JOIN $table_join_privs on permission.prop_id = pj.property_id";
        }


        $sqlQuery = "SELECT `tbl_documents`.`id`, `tbl_documents`.`project_id` as project_id, `dt`.`doc_type`, `tbl_documents`.`deadline`,
                                `tbl_documents`.`added`,`tbl_documents`.`reference` as doc_reference ,pj.name as property_name,
                                pj.property_id as 'property_id', pj.reference as 'project_reference', pj.`lead_key` as 'lead_key',
                                pj.second_lead_key as 'second_lead_key', pj.title as 'project_title', s.published_date,
                                pj.pblock,tbl_documents.name as doc_name,pj.property_reference as prop_uprn,pj.prop_id,tbl_documents.category,
                                tbl_documents.note
                        FROM `tbl_documents`
                        LEFT JOIN `tbl_refurb_doc_types` as dt
                            ON `tbl_documents`.type = dt.id
                        LEFT JOIN (SELECT tbl_project.*,  tbl_property.pblock,tbl_property.name,tbl_property.property_reference,tbl_property.id as prop_id
                            FROM tbl_project LEFT JOIN tbl_property ON tbl_project.property_id = tbl_property.id
                            where tbl_project.project_type in $project_types
                            )
                            as pj
                        ON `tbl_documents`.project_id = pj.id
                        LEFT JOIN (SELECT sv.project_id, MAX( sd.published_date ) as published_date FROM tbl_survey sv LEFT JOIN tbl_survey_date sd ON sv.id = sd.survey_id WHERE sd.published_date IS NOT NULL AND sd.published_date > 0 GROUP BY sv.project_id) s ON s.project_id= pj.id
                        $join_privs
                        WHERE `tbl_documents`.`status` IN ($status) AND `tbl_documents`.category IN (1,2,3,6,5,9,10,11)
                            AND pj.`status` NOT IN (1,2,5) AND `tbl_documents`.category = pj.`progress_stage`
                            $timeSQL
                        ORDER BY pj.id desc, `deadline` DESC
                        LIMIT $page, $limit";

        $results = \DB::select($sqlQuery);
        return $results;
    }

    public function getListUserProjectDocuments($status = "1,3", $page = 0, $limit = 400) {
        $all_project_types = ProjectType::pluck('id')->toArray();
        $project_types = '('.implode(',',$all_project_types).')';
        $timeSQL = '';
        if (!\CommonHelpers::isSystemClient()) {
            $timeSQL .= " AND tbl_documents.contractor = " .\Auth::user()->client_id;
            $join_privs = '';
        } else {
            //privilege
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();
            $join_privs = "JOIN $table_join_privs on permission.prop_id = pj.property_id";
        }

        $sqlQuery = "SELECT `tbl_documents`.`id`, `tbl_documents`.`project_id` as project_id, `dt`.`doc_type`, `tbl_documents`.`deadline`,
                                `tbl_documents`.`added`,`tbl_documents`.`reference` as doc_reference ,pj.name as property_name,
                                pj.property_id as 'property_id', pj.reference as 'project_reference', pj.`lead_key` as 'lead_key',
                                pj.second_lead_key as 'second_lead_key', pj.title as 'project_title', s.published_date,
                                pj.pblock,tbl_documents.name as doc_name,pj.property_reference as prop_uprn,pj.prop_id,tbl_documents.category,
                                tbl_documents.note
                        FROM `tbl_documents`
                        LEFT JOIN `tbl_refurb_doc_types` as dt
                            ON `tbl_documents`.type = dt.id
                        LEFT JOIN (SELECT tbl_project.*,  tbl_property.pblock,tbl_property.name,tbl_property.property_reference,tbl_property.id as prop_id
                            FROM tbl_project LEFT JOIN tbl_property ON tbl_project.property_id = tbl_property.id
                            where tbl_project.project_type in $project_types
                            )
                            as pj
                        ON `tbl_documents`.project_id = pj.id
                        LEFT JOIN (SELECT sv.project_id, MAX( sd.published_date ) as published_date FROM tbl_survey sv LEFT JOIN tbl_survey_date sd ON sv.id = sd.survey_id WHERE sd.published_date IS NOT NULL AND sd.published_date > 0 GROUP BY sv.project_id) s ON s.project_id= pj.id
                        $join_privs
                        WHERE `tbl_documents`.`status` IN ($status) AND `tbl_documents`.category IN (1,2,3,6,5,9,10,11)
                            AND pj.`status` NOT IN (1,2,5)
                            AND pj.`progress_stage` = `tbl_documents`.category
                            AND pj.lead_key = '". \Auth::user()->id ."'
                            $timeSQL
                        ORDER BY pj.id desc, `deadline` DESC
                        LIMIT $page, $limit";

        $results = \DB::select($sqlQuery);
        return $results;
    }
}
