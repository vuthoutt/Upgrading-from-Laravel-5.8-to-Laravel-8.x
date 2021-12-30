<?php

namespace App\Repositories\ShineCompliance;

use App\Models\ShineCompliance\Assessment;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class AssessmentRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */

    function model()
    {
        return Assessment::class;
    }

    public function getAssessmentDetail($assess_id, $relations){
        return $this->model->with($relations)->where('id', $assess_id)->first();
    }

    public function getAssessmentsByPropertyIdAndClassificationAndDecommissionedAndClientId($property_id, $classification, int $decommissioned, $client_id)
    {
        $query = $this->model->where('property_id', $property_id)
                             ->where('classification', $classification)
                             ->where('decommissioned', $decommissioned);
        if ($client_id) {
            $query->where('client_id', $client_id);
        }

        return $query->get();
    }

    public function getAssessmentsByAssessorId($assessorId)
    {
        return $this->model->where('assessor_id', $assessorId)
                           ->where('decommissioned', COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED)
                           ->where('status', ASSESSMENT_STATUS_LOCKED)
                           ->get();
    }

    public function getWaitingApprovalAssessments($user_id = null)
    {
        // property privilege
        $permission_join = \CompliancePrivilege::getPropertyPermission();
        $risk_classification_ids = \CompliancePrivilege::getDataCentreAssessmentProjectPermission('assessment', 'approval');

        $query = $this->model->where('status', ASSESSMENT_STATUS_PUBLISHED)
            ->join(\DB::raw("$permission_join"), 'permission.prop_id', 'property_id')
            ->where('decommissioned', SURVEY_UNDECOMMISSION)
            ->whereIn('classification', $risk_classification_ids);
        if(!empty($user_id)) {
            $query = $query->where(['lead_by' => $user_id]);
        }
        return $query->get();
    }

    public function getRejectedAssessments($user_id = null)
    {
        // property privilege
        $permission_join = \CompliancePrivilege::getPropertyPermission();
        $risk_classification_ids = \CompliancePrivilege::getDataCentreAssessmentProjectPermission('assessment', 'rejected');

        $query = $this->model->where('status', ASSESSMENT_STATUS_REJECTED)
            ->join(\DB::raw("$permission_join"), 'permission.prop_id', 'property_id')
            ->where('decommissioned', SURVEY_UNDECOMMISSION)
            ->whereIn('classification', $risk_classification_ids);
        if(!empty($user_id)) {
            $query = $query->where(['lead_by' => $user_id]);
        }
        return $query->get();
    }

    public function listFireRiskDataCentre() {

        if (\CommonHelpers::isSystemClient()) {
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();
            return $this->model->with(['property'])
                                                ->where('classification', ASSESSMENT_FIRE_TYPE)
                                                ->where('status', '!=', 5)
                                                ->where('decommissioned', 0)
                                                ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
                                                ->get();

        } else {
            return $this->model->with(['property'])
                        ->where('classification', ASSESSMENT_FIRE_TYPE)
                        ->where('status', '!=', 5)
                        ->where('decommissioned', 0)
                        ->where('client_id', \Auth::user()->client_id)
                        ->get();
        }
    }

    public function listHSDataCentre() {

        if (\CommonHelpers::isSystemClient()) {
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();
            return $this->model->with(['property'])
                                                ->where('classification', ASSESSMENT_HS_TYPE)
                                                ->where('status', '!=', 5)
                                                ->where('decommissioned', 0)
                                                ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
                                                ->get();

        } else {
            return $this->model->with(['property'])
                        ->where('classification', ASSESSMENT_HS_TYPE)
                        ->where('status', '!=', 5)
                        ->where('decommissioned', 0)
                        ->where('client_id', \Auth::user()->client_id)
                        ->get();
        }
    }

    public function listFireRiskAssessor() {
        if (\CommonHelpers::isSystemClient()) {
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();
            return $this->model->with(['property'])
                ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
                ->where('classification', ASSESSMENT_FIRE_TYPE)
                ->where('status', '!=', 5)
                ->where('decommissioned', 0)
                ->where(function ($query) {
                    $query->where('lead_by', \Auth::user()->id);
                    $query->orWhere('second_lead_by', \Auth::user()->id);
                    $query->orWhere('quality_checker', \Auth::user()->id);
                    $query->orWhere('assessor_id', \Auth::user()->id);
                })
                ->get();

        } else {
            return $this->model->with(['property'])
                ->where('classification', ASSESSMENT_FIRE_TYPE)
                ->where('status', '!=', 5)
                ->where('client_id', \Auth::user()->client_id)
                ->where('decommissioned', 0)
                ->where(function ($query) {
                    $query->where('lead_by', \Auth::user()->id);
                    $query->orWhere('second_lead_by', \Auth::user()->id);
                    $query->orWhere('quality_checker', \Auth::user()->id);
                    $query->orWhere('assessor_id', \Auth::user()->id);
                })
                ->get();
        }
    }

    public function listHSAssessor() {
        if (\CommonHelpers::isSystemClient()) {
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();

            return $this->model->with(['property'])
                ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
                ->where('classification', ASSESSMENT_HS_TYPE)
                ->where('status', '!=', 5)
                ->where('decommissioned', 0)
                ->where(function ($query) {
                    $query->where('lead_by', \Auth::user()->id);
                    $query->orWhere('second_lead_by', \Auth::user()->id);
                    $query->orWhere('quality_checker', \Auth::user()->id);
                    $query->orWhere('assessor_id', \Auth::user()->id);
                })
                ->get();

        } else {
            return $this->model->with(['property'])
                ->where('classification', ASSESSMENT_HS_TYPE)
                ->where('status', '!=', 5)
                ->where('client_id', \Auth::user()->client_id)
                ->where('decommissioned', 0)
                ->where(function ($query) {
                    $query->where('lead_by', \Auth::user()->id);
                    $query->orWhere('second_lead_by', \Auth::user()->id);
                    $query->orWhere('quality_checker', \Auth::user()->id);
                    $query->orWhere('assessor_id', \Auth::user()->id);
                })
                ->get();
        }
    }

    public function listWaterRiskDataCentre() {
        if (\CommonHelpers::isSystemClient()) {
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();
            return $this->model->with(['property'])
                                                ->where('classification', ASSESSMENT_WATER_TYPE)
                                                ->where('status', '!=', 5)
                                                ->where('decommissioned', 0)
                                                ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
                                                ->get();

        } else {
            return $this->model->with(['property'])
                        ->where('classification', ASSESSMENT_WATER_TYPE)
                        ->where('status', '!=', 5)
                        ->where('client_id', \Auth::user()->client_id)
                        ->where('decommissioned', 0)
                        ->get();
        }

    }

    public function listWaterRiskAssessor() {
        if (\CommonHelpers::isSystemClient()) {
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();
            return $this->model->with(['property'])
                ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
                ->where('classification', ASSESSMENT_WATER_TYPE)
                ->where('status', '!=', 5)
                ->where('decommissioned', 0)
                ->where(function ($query) {
                    $query->where('lead_by', \Auth::user()->id);
                    $query->orWhere('second_lead_by', \Auth::user()->id);
                    $query->orWhere('quality_checker', \Auth::user()->id);
                    $query->orWhere('assessor_id', \Auth::user()->id);
                })
                ->get();

        } else {
            return $this->model->with(['property'])
                ->where('classification', ASSESSMENT_WATER_TYPE)
                ->where('status', '!=', 5)
                ->where('client_id', \Auth::user()->client_id)
                ->where('decommissioned', 0)
                ->where(function ($query) {
                    $query->where('lead_by', \Auth::user()->id);
                    $query->orWhere('second_lead_by', \Auth::user()->id);
                    $query->orWhere('quality_checker', \Auth::user()->id);
                    $query->orWhere('assessor_id', \Auth::user()->id);
                })
                ->get();
        }
    }

    public function listOverdueAssessments($assessment_types, $type, $limit) {
        $time = "";
        switch ($type) {
            case "deadline":
                $botTime = 120 * 86400 + time();
                $timeSQL = " AND s.due_date > $botTime ";
                break;
            case "attention":
                $topTime = 120 * 86400 + time();
                $botTime = 60 * 86400 + time();
                $timeSQL = " AND s.due_date <= $topTime AND s.due_date > $botTime ";
                break;
            case "important":
                $topTime = 60 * 86400 + time();
                $botTime = 30 * 86400 + time();
                $timeSQL = " AND s.due_date <= $topTime AND s.due_date > $botTime ";
                break;
            case "urgent":
                $topTime = 30 * 86400 + time();
                $botTime = 15 * 86400 + time();
                $timeSQL = " AND s.due_date <= $topTime AND s.due_date > $botTime ";
                break;
            case "critical":
                $topTime = 15 * 86400 + time();
                $timeSQL = " AND s.due_date <= $topTime ";
                break;
            default:
                break;
        }
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

        $result = \DB::select("
                        SELECT s.id, s.due_date, s.reference, p.pblock ,s.type,
                        pj.title as project_title, p.reference as uprn, p.id as prop_id,
                        pj.id as project_id,p.name as prop_name,
                        pj.reference as project_reference,
                        s.status, s.classification
                        FROM cp_assessments s
                        LEFT JOIN tbl_project as pj ON pj.id = s.project_id
                        LEFT JOIN tbl_property as p ON p.id = s.property_id
                        JOIN $table_join_privs on permission.prop_id = s.property_id
                        WHERE s.decommissioned = 0
                        AND s.status !=5
                        AND s.classification IN $assessment_types
                          $timeSQL
                        ORDER BY s.due_date DESC");
        return $result;
    }

    public  function getAsessmentReInspection($assessment_types ,$datacentreRisk, $clientID = 0, $page = 0, $limit = 2000) {
        $clientID = \Auth::user()->client_id;
        $daymarker = 730;

        switch ($datacentreRisk) {
            case "deadline":
                $botTime = time() - (($daymarker - 120) * 86400);
                $sqlHaving = " HAVING MAX(s.assess_finish_date) > $botTime ";
                break;
            case "attention":
                $topTime = time() - (($daymarker - 120) * 86400);
                $botTime = time() - (($daymarker - 60) * 86400);
                $sqlHaving = " HAVING MAX(s.assess_finish_date) <= $topTime AND MAX(s.assess_finish_date) > $botTime ";
                break;
            case "important":
                $topTime = time() - (($daymarker - 60) * 86400);
                $botTime = time() - (($daymarker - 30) * 86400);
                $sqlHaving = " HAVING MAX(s.assess_finish_date) <= $topTime AND MAX(s.assess_finish_date) > $botTime ";
                break;
            case "urgent":
                $topTime = time() - (($daymarker - 30) * 86400);
                $botTime = time() - (($daymarker - 15) * 86400);
                $sqlHaving = " HAVING MAX(s.assess_finish_date) <= $topTime AND MAX(s.assess_finish_date) > $botTime ";
                break;
            case "critical":
                $topTime = time() - (($daymarker - 15) * 86400);
                //                    $sqlHaving = " HAVING MAX(s.completed_date) <= $topTime OR MAX(s.completed_date) IS NULL ";
                $sqlHaving = " HAVING MAX(s.assess_finish_date) <= $topTime ";
                break;
            default:
                $sqlHaving = "failed";
                break;
        }

        $sqlCheck = ($clientID) ? " AND site.client_id= " . $clientID : "";


        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();
        // Get management surveys
        $sqlQuery = "SELECT site.id,
                            site.zone_id,
                            site.reference AS UPRN,
                            site.pblock AS BlockName,
                            site.`name`,
                            site.`client_id`,
                            MAX(s.completed_date) as 'completed_date',
                            MAX(s.assess_finish_date) as 'assess_finish_date',
                            z.zone_name
                    FROM tbl_property AS site
                    LEFT JOIN ( SELECT s.id, s.started_date, s.property_id,s.completed_date, MAX(s.assess_finish_date) assess_finish_date FROM
                                    cp_assessments s
                                    WHERE s.decommissioned = 0 AND `status` = 5 AND s.classification IN $assessment_types  GROUP BY property_id )
                                    AS s ON site.id = s.property_id
                    JOIN (SELECT ppt.property_id from property_property_type ppt
                            JOIN tbl_property_type pt ON ppt.property_type_id = pt.id
                            WHERE pt.ms_level = 1
                            GROUP BY ppt.property_id) AS r ON site.id = r.property_id
                    LEFT JOIN tbl_zones as z ON z.id = site.zone_id
                    JOIN $table_join_privs ON permission.prop_id = site.id
                    WHERE site.decommissioned = 0" . $sqlCheck
            . " GROUP BY site.id " . $sqlHaving . " ";

        // order

        $sqlQuery .= " ORDER BY assess_finish_date LIMIT $page, $limit ";

        $results = \DB::select($sqlQuery);

        return $results;

    }

    public function getAssessmentByStatus($status)
    {
        return $this->model->with(['property'])
                ->where('status', $status)
                ->where('decommissioned', 0)
                ->where('client_id', \Auth::user()->client_id)
                ->get();
    }

    public function getLastestAssessmentRiskRating($property_id, $classification, $risk_rating)
    {
        $last_completed_assessment = \DB::select("SELECT id from cp_assessments
                                                    where classification = $classification
                                                    and property_id = $property_id
                                                    and decommissioned = 0
                                                    ORDER by id desc limit 1");
        $last_completed_assessment_id = $last_completed_assessment[0]->id ?? false;
        if ($last_completed_assessment_id) {
            $risk_rating_data = \DB::select("SELECT s.id from cp_assessments s
                join cp_assessment_info si on si.assess_id = s.id
                where s.id = $last_completed_assessment_id
                and JSON_EXTRACT (si.property_information, '$.fra_overall') = $risk_rating");
            $risk_rating = $risk_rating_data[0]->id ?? false;
            if ($risk_rating) {
                return true;
            }
        }
        return false;

    }

    public function searchAssessment($query_string, $type = 0) {
        $checkType = ($type) ? "AND asm.`classification` = " . $type : "";

//        if (\Auth::user()->clients->client_type == 1) {
//            $checkType .= " AND asm.client_id = " . \Auth::user()->client_id;
//        }

        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

    $sql = "SELECT asm.id as id, asm.reference as reference, p.`name` AS 'property_name', p.property_reference
                FROM `cp_assessments` as asm
                LEFT JOIN tbl_property as p ON p.id = asm.property_id
                JOIN $table_join_privs ON permission.prop_id = asm.property_id
                WHERE
                    asm.`reference` LIKE '%" . $query_string . "%'
                    $checkType
                    AND asm.`decommissioned` = 0
                order by LENGTH(asm.reference) ASC,  asm.reference ASC
                LIMIT 0,20";

        $list = DB::select($sql);

        return $list;
    }
}
