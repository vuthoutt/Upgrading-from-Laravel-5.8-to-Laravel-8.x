<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\Survey;
use App\Models\ShineCompliance\Project;
use Prettus\Repository\Eloquent\BaseRepository;

class SurveyRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Survey::class;
    }

    public function getAllSurveyByProperty($property_id,$decommissioned){
        $client_id = \Auth::user()->client_id;
        if ($client_id == 1 || $client_id == 13) {
            return $this->model->with('surveyDate','project')->where('property_id',$property_id)->where('decommissioned', $decommissioned)->get();
        } else {
            $user_id = \Auth::user()->id;
            $surveys1 = Survey::with('project','surveyDate','publishedSurvey')
                                ->where('property_id', $property_id)
                                 ->where('decommissioned', $decommissioned)
                                 ->whereRaw("(client_id = $client_id OR created_by = $user_id)")
                                 ->orderBy('id','desc')
                                 ->get();
            $project_ids = Project::where('property_id', $property_id)->where('status', '!=' , 5)
                                    ->whereRaw("FIND_IN_SET('$client_id', REPLACE(contractors, ' ', ''))")
                                    ->where('risk_classification_id', 1)
                                    ->orderBy('id','desc')
                                    ->pluck('id')
                                    ->toArray();

            $surveys2 = Survey::with('project','surveyDate','publishedSurvey')
                                ->where('property_id', $property_id)
                                 ->where('decommissioned', $decommissioned)
                                 ->whereIn('project_id', $project_ids)
                                 ->orderBy('id','desc')
                                 ->get();

            return $surveys1->merge($surveys2);
        }
    }

    public function getFirstSurveyInProgress($property_id){
        return $this->model->whereNotIn('status', [COMPLETED_SURVEY_STATUS, REJECTED_SURVEY_STATUS])->where(['decommissioned' => SURVEY_UNDECOMMISSION, 'property_id' => $property_id])->first();
    }

    public function decommission($property_id, $type){
        return $this->model->where('property_id',$property_id)->update(['decommissioned' => $type]);
    }

    public function getFindSurvey($survey_id){
        return $this->model->find($survey_id);
    }
    public function getPropertySurvey($id,$decommissioned,$client_id)
    {
        if (!is_null($client_id)) {
            $user_id = \Auth::user()->id;
            $surveys = $this->model->with('project', 'surveyDate', 'publishedSurvey')
                ->where('property_id', $id)
                ->where('decommissioned', $decommissioned)
                ->whereRaw("(client_id = $client_id OR created_by = $user_id)")
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $surveys = $this->model->with('project', 'surveyDate', 'publishedSurvey')->where('property_id', $id)
                ->where('decommissioned', $decommissioned)
                ->orderBy('id', 'desc')
                ->get();
        }
        return is_null($surveys) ? [] : $surveys;
    }

    public function getSurveyLink($survey_linked,$project_id){
        return $this->model->whereIn('id', $survey_linked)->update(['project_id' => $project_id]);
    }

    public function getSurveyWhereIn($surveys){
        return $this->model->whereIn('id', $surveys)->where('decommissioned', 0)->get();
    }

    public function getSurvey($survey_id) {
        $survey = $this->model->with('property','project','publishedSurvey','sampleCertificate','sitePlanDocuments','clients','surveyArea','surveyReason',
            'airTestCertificate','surveyAnswer.dropdownQuestionData','surveyAnswer.dropdownAnswerData', 'surveyInfo','surveySetting','surveyDate')
            ->where('id', $survey_id)->first();
        return is_null($survey) ? [] : $survey;
    }

    public function getSurveyPublish($relation ,$survey_id) {
        $survey = $this->model->with($relation)->where('id', $survey_id)->first();
        return is_null($survey) ? [] : $survey;
    }

    public function getPropertyFromSurvey($survey_id) {
        $survey = $this->model->find($survey_id);
        return is_null($survey) ? 0 : $survey->property_id;
    }

    public function updateSurvey($survey_id,$data) {
        $survey = $this->model->where('id', $survey_id)->update($data);
        return $survey ?? [];
    }

    public function getActiveSamplesTable($property_id, $survey_id) {

        $survey_samples = \DB::select("

                                SELECT
                                    tmp.id,
                                    tmp.reference,
                                    tmp.description,
                                    tmp.original_item_id,
                                    tmp.sample_id,
                                    GROUP_CONCAT( IF ( tmp.record_id = tmp.original_item_id, CONCAT( tmp.item_ref, '(OS)' ), tmp.item_ref ) ORDER BY tmp.is_os desc) AS item_reference,
                                    GROUP_CONCAT( tmp.item_id ORDER BY tmp.is_os desc) AS item_ids,
                                    tmp.product_debris ,
                                    tmp.asbestos_type,
                                    tmp.location_reference,
                                    tmp.location_id,
                                    tmp.survey_id,
                                    tmp.is_real FROM
                                        (   SELECT
                                            sv.dropdown_data_item_id as id,
                                            sp.reference,
                                            sp.description,
                                            sp.original_item_id,
                                            sv.dropdown_data_item_id as sample_id,
                                            i.record_id,
                                            i.reference as item_ref,
                                            i.id as item_id,
                                            ipd.product_debris ,
                                            iab.asbestos_type,
                                            l.location_reference AS location_reference,
                                            l.id AS location_id,
                                            i.survey_id as survey_id,
                                            sp.is_real,
                                            IF(i.record_id = sp.original_item_id, 1, 0) as is_os
                                        FROM
                                            tbl_items AS i
                                            JOIN tbl_item_sample_id_value AS sv ON i.id = sv.item_id
                                            JOIN tbl_sample sp ON sp.id = sv.dropdown_data_item_id
                                            JOIN tbl_items i2 on i2.record_id = sp.original_item_id
                                            JOIN tbl_location l ON i2.location_id = l.id
                                            JOIN tbl_item_product_debris_view ipd ON ipd.item_id = i2.id
                                            JOIN tbl_item_asbestos_type_view iab ON iab.item_id = i2.id
                                        WHERE
                                            i.survey_id = $survey_id
                                        AND
                                            i2.survey_id = $survey_id
                                        AND
                                            i.decommissioned = 0
                                        AND
                                            i2.decommissioned = 0
                                        AND
                                            (sp.is_real = -1 OR sp.is_real = 1)
                                        ORDER BY is_os DESC
                                        ) tmp

                                GROUP BY
                                    tmp.sample_id ORDER BY tmp.description asc");
        foreach ($survey_samples as $survey_sample) {
            $survey_sample->item_reference = (explode(",",$survey_sample->item_reference));
            $survey_sample->item_ids = (explode(",",$survey_sample->item_ids));
        }

        return $survey_samples;
    }

    public function getSampleById($property_id,$survey_id,$sample_id){
        $survey_samples = \DB::select("

                                SELECT
                                    tmp.id,
                                    tmp.reference,
                                    tmp.description,
                                    tmp.original_item_id,
                                    tmp.sample_id,
                                    GROUP_CONCAT( IF ( tmp.record_id = tmp.original_item_id, CONCAT( tmp.item_ref, '(OS)' ), tmp.item_ref ) ORDER BY tmp.is_os desc) AS item_reference,
                                    GROUP_CONCAT( tmp.item_id ORDER BY tmp.is_os desc) AS item_ids,
                                    tmp.product_debris ,
                                    tmp.asbestos_type,
                                    tmp.location_reference,
                                    tmp.location_id,
                                    tmp.survey_id,
                                    tmp.is_real FROM
                                        (   SELECT
                                            sv.dropdown_data_item_id as id,
                                            sp.reference,
                                            sp.description,
                                            sp.original_item_id,
                                            sv.dropdown_data_item_id as sample_id,
                                            i.record_id,
                                            i.reference as item_ref,
                                            i.id as item_id,
                                            ipd.product_debris ,
                                            iab.asbestos_type,
                                            l.location_reference AS location_reference,
                                            l.id AS location_id,
                                            i.survey_id as survey_id,
                                            sp.is_real,
                                            IF(i.record_id = sp.original_item_id, 1, 0) as is_os
                                        FROM
                                            tbl_items AS i
                                            JOIN tbl_item_sample_id_value AS sv ON i.id = sv.item_id
                                            JOIN tbl_sample sp ON sp.id = sv.dropdown_data_item_id
                                            JOIN tbl_items i2 on i2.record_id = sp.original_item_id
                                            JOIN tbl_location l ON i2.location_id = l.id
                                            JOIN tbl_item_product_debris_view ipd ON ipd.item_id = i2.id
                                            JOIN tbl_item_asbestos_type_view iab ON iab.item_id = i2.id
                                        WHERE
                                            i.survey_id = $survey_id
                                        AND
                                            i2.survey_id = $survey_id
                                        AND
                                            i.decommissioned = 0
                                        AND
                                            i2.decommissioned = 0
                                        AND
                                            sp.id = $sample_id
                                        ORDER BY is_os DESC
                                        ) tmp

                                GROUP BY
                                    tmp.sample_id");

        foreach ($survey_samples as $survey_sample) {
            $survey_sample->item_reference = (explode(",",$survey_sample->item_reference));
            $survey_sample->item_ids = (explode(",",$survey_sample->item_ids));
        }

        return $survey_samples;
    }

    public function missingSurvey($client_id = 0, $limit = 500, $offset = 0, $order_by = "", $search = "") {
        $checkClientType = ($client_id != 0) ? " AND p.`client_id` = $client_id " : " ";

        $sqlQuery = "SELECT DISTINCT
                            p.`id`,
                            p.`property_reference`,
                            p.`reference`,
                            p.`name`,
                            p.`pblock`,
                            p.`client_id`,
                            IF(p.created_at > 0 , Date_Format(p.created_at,'%d/%m/%Y'), '') created_at
                    FROM
                        tbl_property AS p
                    LEFT JOIN tbl_survey AS s ON p.id = s.property_id
                    AND s.survey_type = 1 AND s.decommissioned = 0
                    LEFT JOIN `tbl_historicdocs` AS his ON p.id = his.property_id
                    AND his.historical_type = 1
                    LEFT JOIN
                    (SELECT property_id from property_property_type pp1
                    JOIN tbl_property_type pp2 ON pp1.property_type_id = pp2.id AND pp2.ms_level = 1
                    GROUP BY pp1.property_id) pp3 ON pp3.property_id = p.id
                    WHERE pp3.property_id IS NOT NULL
                    AND p.decommissioned = 0
                    AND s.id IS NULL
                    AND his.id IS NULL
                    $checkClientType
                    $search
                    ORDER BY $order_by
                    LIMIT $offset, $limit";

        $sqlQueryCount = "SELECT DISTINCT
                            count(*) as `count`
                    FROM
                        tbl_property AS p
                    LEFT JOIN tbl_survey AS s ON p.id = s.property_id
                    AND s.survey_type = 1 AND s.decommissioned = 0
                    LEFT JOIN
                    (SELECT property_id from property_property_type pp1
                    JOIN tbl_property_type pp2 ON pp1.property_type_id = pp2.id AND pp2.ms_level = 1
                    GROUP BY pp1.property_id) pp3 ON pp3.property_id = p.id
                    WHERE pp3.property_id IS NOT NULL
                    AND p.decommissioned = 0
                    AND s.id IS NULL
                    $checkClientType
                    $search";
        $count = \DB::select($sqlQueryCount)[0]->count?? 0;
        return [\DB::select($sqlQuery) ?? [], $count];
    }

    public function missingAssessment($client_id = 0, $limit = 500, $offset = 0, $order_by = "", $search = "") {
        $checkClientType = ($client_id != 0) ? " AND p.`client_id` = $client_id " : " ";

        $sqlQuery = "SELECT DISTINCT
                            p.`id`,
                            p.`property_reference`,
                            p.`reference`,
                            p.`name`,
                            p.`pblock`,
                            p.`client_id`,
                            IF(p.created_at > 0 , Date_Format(p.created_at,'%d/%m/%Y'), '') created_at
                    FROM
                        tbl_property AS p
                    LEFT JOIN cp_assessments AS s ON p.id = s.property_id
                    AND s.decommissioned = 0
                    LEFT JOIN
                    (SELECT property_id from property_property_type pp1
                    JOIN tbl_property_type pp2 ON pp1.property_type_id = pp2.id AND pp2.ms_level = 1
                    WHERE pp1.property_type_id = 1
                    GROUP BY pp1.property_id) pp3 ON pp3.property_id = p.id
                    WHERE pp3.property_id IS NOT NULL
                    AND p.decommissioned = 0
                    AND s.id IS NULL
                    $checkClientType
                    $search
                    ORDER BY $order_by
                    LIMIT $offset, $limit";

        $sqlQueryCount = "SELECT count(*) as `count`
                    FROM
                        tbl_property AS p
                    LEFT JOIN cp_assessments AS s ON p.id = s.property_id
                    AND s.decommissioned = 0
                    LEFT JOIN
                    (SELECT property_id from property_property_type pp1
                    JOIN tbl_property_type pp2 ON pp1.property_type_id = pp2.id AND pp2.ms_level = 1
                    WHERE pp1.property_type_id = 1
                    GROUP BY pp1.property_id) pp3 ON pp3.property_id = p.id
                    WHERE pp3.property_id IS NOT NULL
                    AND p.decommissioned = 0
                    AND s.id IS NULL
                    $checkClientType
                    $search";

        $count = \DB::select($sqlQueryCount)[0]->count?? 0;
        return [\DB::select($sqlQuery) ?? [], $count];
    }

    public function listOverdueSurveys($type, $limit) {
        $time = "";
        switch ($type) {
            case "deadline":
                $botTime = 120 * 86400 + time();
                $timeSQL = " AND sd.due_date > $botTime ";
                break;
            case "attention":
                $topTime = 120 * 86400 + time();
                $botTime = 60 * 86400 + time();
                $timeSQL = " AND sd.due_date <= $topTime AND sd.due_date > $botTime ";
                break;
            case "important":
                $topTime = 60 * 86400 + time();
                $botTime = 30 * 86400 + time();
                $timeSQL = " AND sd.due_date <= $topTime AND sd.due_date > $botTime ";
                break;
            case "urgent":
                $topTime = 30 * 86400 + time();
                $botTime = 15 * 86400 + time();
                $timeSQL = " AND sd.due_date <= $topTime AND sd.due_date > $botTime ";
                break;
            case "critical":
                $topTime = 15 * 86400 + time();
                $timeSQL = " AND sd.due_date <= $topTime ";
                break;
            default:
                break;
        }
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

        $result = \DB::select("
                        SELECT s.id, sd.due_date, s.reference, p.pblock ,
                        pj.title as project_title, p.reference as uprn, p.id as prop_id,
                        pj.id as project_id,p.name as prop_name,
                        pj.reference as project_reference,
                        s.status, s.survey_type as type
                        FROM tbl_survey s
                        LEFT JOIN tbl_survey_date as sd ON sd.survey_id = s.id
                        LEFT JOIN tbl_project as pj ON pj.id = s.project_id
                        LEFT JOIN tbl_property as p ON p.id = s.property_id
                        JOIN $table_join_privs on permission.prop_id = s.property_id
                        WHERE s.decommissioned = 0
                        AND s.status !=5
                        $timeSQL
                        ORDER BY sd.due_date DESC");
        return $result;
    }

    public function listOverdueAudits($type, $limit) {
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
                        SELECT s.id, s.due_date, s.reference, p.pblock ,
                        pj.title as project_title, p.reference as uprn, p.id as prop_id,
                        pj.id as project_id,p.name as prop_name,
                        pj.reference as project_reference,
                        s.status, s.type
                        FROM tbl_audit s
                        LEFT JOIN tbl_project as pj ON pj.id = s.project_id
                        LEFT JOIN tbl_property as p ON p.id = s.property_id
                        JOIN $table_join_privs on permission.prop_id = s.property_id
                        WHERE s.decommissioned = 0
                        AND s.status !=5
                          $timeSQL
                        ORDER BY s.due_date DESC");
        return $result;
    }

    public  function getOverdueSurveySites2($datacentreRisk, $clientID = 0, $page = 0, $limit = 2000) {

        $daymarker = 730;
        if (is_numeric($datacentreRisk)) {

            $sqlCheck = " AND site.zone_id = " . $datacentreRisk;
            $sqlHaving = " HAVING completed_date > 0 ";
        } else {
            switch ($datacentreRisk) {
                case "deadline":
                    $botTime = time() - (($daymarker - 120) * 86400);
                    $sqlHaving = "HAVING completed_date > $botTime ";
                    break;
                case "attention":
                    $topTime = time() - (($daymarker - 120) * 86400);
                    $botTime = time() - (($daymarker - 60) * 86400);
                    $sqlHaving = " HAVING completed_date <= $topTime AND completed_date > $botTime ";
                    break;
                case "important":
                    $topTime = time() - (($daymarker - 60) * 86400);
                    $botTime = time() - (($daymarker - 30) * 86400);
                    $sqlHaving = " HAVING completed_date <= $topTime AND completed_date > $botTime ";
                    break;
                case "urgent":
                    $topTime = time() - (($daymarker - 30) * 86400);
                    $botTime = time() - (($daymarker - 15) * 86400);
                    $sqlHaving = " HAVING completed_date <= $topTime AND completed_date > $botTime ";
                    break;
                case "critical":
                    $topTime = time() - (($daymarker - 15) * 86400);
                    //                    $sqlHaving = " HAVING MAX(s.completed_date) <= $topTime OR MAX(s.completed_date) IS NULL ";
                    $sqlHaving = " HAVING completed_date <= $topTime ";
                    break;
                default:
                    $sqlHaving = "failed";
                    break;
            }

            $sqlCheck = ($clientID) ? " AND site.clientKey= " . $clientID : "";
            //            $sqlCheck .= " AND ((MSID IS NOT NULL AND mscomplete < $topTime AND mscomplete > $botTime) OR (MSID IS NULL AND OSID IS NOT NULL AND oscomplete < $topTime AND oscomplete > $botTime)) ";

        }
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();
        // Get management surveys
        $sqlQuery = "SELECT site.id,
                            site.zone_id,
                            site.reference AS UPRN,
                            site.pblock AS BlockName,
                            site.`name`,
                            site.`client_id`,
                            IF(GREATEST(IFNULL(s.surveying_finish_date, 0), IFNULL(sM.surveying_finish_date, 0), IFNULL(h.added, 0)) > 0, GREATEST(IFNULL(s.surveying_finish_date, 0), IFNULL(sM.surveying_finish_date, 0), IFNULL(h.added, 0)), UNIX_TIMESTAMP(NOW())) as 'completed_date',
                            z.zone_name
                    FROM tbl_property AS site
                    LEFT JOIN tbl_location as l ON l.property_id = site.id and l.survey_id = 0 and l.decommissioned = 0 and l.state = 0
                    LEFT JOIN tbl_items as i ON i.property_id = site.id and i.survey_id = 0 and i.decommissioned = 0
                    LEFT JOIN (SELECT MIN(date) added, property_id FROM compliance_documents WHERE property_id > 0 AND `is_external_ms` = 1 GROUP BY property_id) as h ON site.id = h.property_id
                    LEFT JOIN ( SELECT reference, sd.completed_date, sd.surveying_finish_date, sd.started_date, property_id FROM tbl_survey LEFT JOIN tbl_survey_date sd ON tbl_survey.id = sd.survey_id  WHERE survey_type = 1 AND decommissioned = 0 AND `status`= 5 GROUP BY property_id) AS sM ON site.id = sM.property_id
                    LEFT JOIN ( SELECT MAX(tbl_survey_date.surveying_finish_date) surveying_finish_date, property_id FROM tbl_survey LEFT JOIN tbl_survey_date ON tbl_survey.id = tbl_survey_date.survey_id WHERE survey_type = 3 AND decommissioned = 0 AND `status`= 5 GROUP BY property_id) AS s ON site.id= s.property_id
                    LEFT JOIN (SELECT COUNT(DISTINCT id) countACM, property_id FROM tbl_items WHERE survey_id = 0 AND state != 1 AND decommissioned = 0 GROUP BY property_id) AS i2 ON i2.property_id= site.id
                    JOIN (SELECT ppt.property_id from property_property_type ppt
                            JOIN tbl_property_type pt ON ppt.property_type_id = pt.id
                            WHERE pt.ms_level = 1
                            GROUP BY ppt.property_id) AS r ON site.id = r.property_id
                    LEFT JOIN tbl_zones as z ON z.id = site.zone_id
                    JOIN $table_join_privs ON permission.prop_id = site.id
                    WHERE site.decommissioned = 0 AND i2.countACM > 0  " . $sqlCheck
            . " GROUP BY site.id " . $sqlHaving . " AND (COUNT(i.id) > 0 OR COUNT(l.ID) > 0) ";
        // order
        if ($page == -1) {
            $sqlQuery .= " ORDER BY completed_date";
        } else {
            $sqlQuery .= " ORDER BY completed_date LIMIT $page, $limit ";
        }
        $results = \DB::select($sqlQuery);

        if ($results)
            return $results;
        else
            return [];
    }

    /**
     * Get survey with all relation from id
     */

    public function getApprovalSurvey($user_id = null) {
        if (\CommonHelpers::isSystemClient()) {
            // property privilege
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();

            $survey = $this->model->with('project','property', 'surveyDate','publishedSurvey')
                ->where('decommissioned', SURVEY_UNDECOMMISSION)
                ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id');
            if(!empty($user_id)) {
                $survey = $survey->where(function ($query) use ($user_id) {
                    $query->where(['lead_by' => $user_id]);
                    $query->orWhere(['second_lead_by' => $user_id]);
                    $query->orWhere(['quality_id' => $user_id]);
                });
            }
            $survey = $survey->where('status', PULISHED_SURVEY_STATUS)->get();
        } else {
            $survey = $this->model->with('project','property', 'surveyDate','publishedSurvey')
                ->where('decommissioned', SURVEY_UNDECOMMISSION)
                ->where('status', PULISHED_SURVEY_STATUS)
                ->where('client_id', \Auth::user()->client_id);
            if(!empty($user_id)) {
                $survey = $survey->where(function ($query) use ($user_id) {
                    $query->where(['lead_by' => $user_id]);
                    $query->orWhere(['second_lead_by' => $user_id]);
                    $query->orWhere(['quality_id' => $user_id]);
                });
            }

            $survey = $survey->get();

        }
        return $survey;
    }

    public function getRejectedSurvey($user_id = null)
    {
        $user_sql = "";
        if (\CommonHelpers::isSystemClient()) {
            // property privilege
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();
            if(!empty($user_id)) {
                $user_sql = " AND (s.lead_by = '". $user_id ."' OR s.second_lead_by ='". $user_id ."' OR s.quality_id ='". $user_id ."')";
            }

            $survey = \DB::select("SELECT s.id, s.status,p.pblock ,
                    s.reference as survey_reference, pj.title, pj.reference  as project_reference,
                    p.name as property_name,
                    pj.id as project_id,
                    s.property_id,
                    si.comments, sd.due_date
                    FROM tbl_survey s
                    LEFT JOIN tbl_survey_info as si ON si.survey_id = s.id
                    LEFT JOIN tbl_survey_date as sd ON sd.survey_id = s.id
                    LEFT JOIN tbl_project as pj ON pj.id = s.project_id
                    LEFT JOIN tbl_property as p ON p.id = s.property_id
                    JOIN $table_join_privs ON permission.prop_id = s.property_id
                    WHERE s.decommissioned = 0
                    AND (s.status = 6 OR (si.comments IS NOT NULL AND s.status NOT IN (4,5)))
                        $user_sql
                    ORDER BY s.id DESC");
        } else {
            $client_id = \Auth::user()->client_id;
            if(!empty($user_id)) {
                $user_sql = " AND (s.lead_by = '". $user_id ."' OR s.second_lead_by ='". $user_id ."' OR s.quality_id ='". $user_id ."')";
            }
            $survey = \DB::select("SELECT s.id, s.status,p.pblock ,
                    s.reference as survey_reference, pj.title, pj.reference  as project_reference,
                    p.name as property_name,
                    pj.id as project_id,
                    s.property_id,
                    si.comments, sd.due_date
                    FROM tbl_survey s
                    LEFT JOIN tbl_survey_info as si ON si.survey_id = s.id
                    LEFT JOIN tbl_survey_date as sd ON sd.survey_id = s.id
                    LEFT JOIN tbl_project as pj ON pj.id = s.project_id
                    LEFT JOIN tbl_property as p ON p.id = s.property_id
                    WHERE s.decommissioned = 0
                    AND s.client_id = $client_id
                    AND (s.status = 6 OR (si.comments IS NOT NULL AND s.status NOT IN (4,5)))
                        $user_sql
                    ORDER BY s.id DESC ");
        }
        return $survey;
    }

    public function getSurveyNotCompleteByClient($client_id = null, $type = null)
    {
        return $this->model->with('project', 'property', 'surveyDate','clients')->where([
            'client_id' => $client_id,
            'survey_type' => $type,
        ])->where('status','!=',COMPLETED_SURVEY_STATUS)->where('decommissioned', 0)->get();
    }

    public function getSurveyNotCompleteByPropertyPrivilege($table_join_privs, $type = null)
    {
        return $this->model->with('project', 'property', 'surveyDate','clients')->where([
            'survey_type' => $type
        ])->where('status','!=',COMPLETED_SURVEY_STATUS)->where('decommissioned', 0)
        ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')->get();
    }
}
