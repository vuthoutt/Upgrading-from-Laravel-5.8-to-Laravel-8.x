<?php
namespace App\Repositories\ShineCompliance;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\SummaryType;
use Illuminate\Support\Facades\DB;

class SummaryRepository extends BaseRepository {
    public $col = 0;
    function model()
    {
        return SummaryType::class;
    }

    public function getAllSummaries() {
        return SummaryType::orderBy('order')->get();
    }

    public function getSummary($route) {

        $route = str_replace('summary.','',$route);
        $summary = SummaryType::where('value', $route)->first();

        return $summary;
    }

    public function getFireDocumentsSummary() {
        if (\CommonHelpers::isClientUser()) {
            $client_id = \Auth::user()->client_id;
            $condition = "AND z.client_id = $client_id";
        } else {
            $condition = '';
        }
        $sql = "SELECT
                    d.reference as document_reference,
                    d.name as document_title,
                    IF(dc.id > 0, dc.name, '') as document_category,
                    dt.description as document_type,
                    (CASE WHEN d.parent_type = 1 THEN 'No Parent Required'
                         WHEN d.parent_type = 2 THEN IF(cp.id > 0, cp.name, IF(cs.id > 0, cs.name, ''))
                         WHEN d.parent_type = 3 THEN IF(cp.id > 0, cp.name, IF(ce.id > 0, ce.name, ''))
                         ELSE ''
                    END) as parent,
                    IF(d.date <> '', FROM_UNIXTIME(d.date, \"%d/%m/%Y\"), '') as document_date,
                    IF(d.enforcement_deadline <> '', FROM_UNIXTIME(d.enforcement_deadline, \"%d/%m/%Y\"), '') as enforcement_date,
                    IF(ds.id > 0, ds.description, '') as document_status,
                    IF(dst.id > 0, DATE_FORMAT(dst.updated_at, \"%d/%m/%Y\"), '') as document_upload_date,
                    IF(us.id > 0, CONCAT(us.first_name, ' ', us.last_name), '') as user,
                    p.reference,
                    p.property_reference,
                    p.pblock,
                    p.name,
                    IF(z.id > 0, z.zone_name, '') as property_group
                FROM compliance_documents as d
                LEFT JOIN tbl_property p ON  p.id = d.property_id
                LEFT JOIN tbl_zones z ON  p.zone_id = z.id
                LEFT JOIN compliance_document_category dc ON  dc.id = d.category_id
                LEFT JOIN compliance_document_types dt ON  dt.id = d.type
                LEFT JOIN compliance_programmes cp ON  cp.id = d.programme_id
                LEFT JOIN compliance_systems cs ON  cs.id = d.system_id
                LEFT JOIN cp_equipments ce ON  ce.id = d.equipment_id
                LEFT JOIN cp_document_status_dropdown ds ON  ds.id = d.document_status
                LEFT JOIN compliance_document_storage dst ON  d.id = dst.object_id AND dst.type = '". COMPLIANCE_DOCUMENT_PHOTO ."'
                LEFT JOIN tbl_users us on d.created_by = us.id
                WHERE d.compliance_type = '". FIRE_DOCUMENT_TYPE ."' $condition
                    ORDER BY  d.`id` ASC";
        $data = DB::select($sql);
        return $data;
    }

    public function getFireAssessmentSummary() {
        if (!\CommonHelpers::isSystemClient()) {
            $client_id = \Auth::user()->client_id;
            $condition = " AND p.`client_id` = $client_id";
        } else {
            $condition = '';
        }
        $now = time();
        $daymarker = 730;

        $sql = "SELECT
                ca.reference 'assess_ref',
                CASE
                    WHEN ca.classification = 2 && ca.`type` = 1 THEN 'Fire Equipment Assessment'
                    WHEN ca.classification = 2 && ca.`type` != 1 THEN 'Fire Risk Assessment'
                    WHEN ca.classification = 4 && ca.`type` = 1 THEN 'Water Equipment Assessment'
                    WHEN ca.classification = 4 && ca.`type` = 2 THEN 'Water Risk Assessment'
                    WHEN ca.classification = 4 && ca.`type` = 3 THEN 'Water Temperature Assessment'
                    WHEN ca.classification = 5 && ca.`type` = 1 THEN 'Health & Safety Assessment'
                    ELSE ''
                END 'assess_type',
                CASE
                    WHEN ca.decommissioned = 1 THEN 'Decommissioned'
                    WHEN ca.`status` = 1  THEN 'New Assessment'
                    WHEN ca.`status` = 2  THEN 'Locked'
                    WHEN ca.`status` = 3  THEN 'Ready for QA'
                    WHEN ca.`status` = 4  THEN 'Published'
                    WHEN ca.`status` = 5  THEN 'Completed'
                    WHEN ca.`status` = 6  THEN 'Rejected'
                    WHEN ca.`status` = 7  THEN 'Sent back'
                    WHEN ca.`status` = 8  THEN 'NewAborted'
                    ELSE ''
                END 'assess_stt',
                ar.description as 'assess_abort_reason',
                CASE
                    WHEN ca.`status` = 5  THEN 'N/A'
                    WHEN ca.assess_finish_date > ($now - (($daymarker - 120) * 86400)) THEN 'Deadline'
                    WHEN ca.assess_finish_date <= ($now - (($daymarker - 120) * 86400)) && ca.assess_finish_date > ($now - (($daymarker - 60) * 86400)) THEN 'Attention'
                    WHEN ca.assess_finish_date <= ($now - (($daymarker - 60) * 86400)) && ca.assess_finish_date > ($now - (($daymarker - 30) * 86400)) THEN 'Important'
                    WHEN ca.assess_finish_date <= ($now - (($daymarker - 30) * 86400)) && ca.assess_finish_date > ($now - (($daymarker - 15) * 86400)) THEN 'Urgent'
                    WHEN ca.assess_finish_date <= ($now - (($daymarker - 15) * 86400)) THEN 'Critical'
                    ELSE 'N/A'
                END 'assess_risk_warining',
                p.property_reference 'prop_reference',
                p.pblock 'prop_block',
                p.`name` 'prop_name',
                p.`reference` 'shine_reference',
                z.zone_name 'property_group',
                IF(p.asset_class_id IS NOT NULL, ac.description, '') as 'asset_class',
                IF(p.asset_type_id IS NOT NULL, ast.description, '') as 'asset_type',
                IF(p.tenure_type_id IS NOT NULL, ten.description, '') as 'tenure_type',
                DATE_FORMAT(ca.created_at, '%d/%m/%Y') 'created_date',
                FROM_UNIXTIME(started_date,'%d/%m/%Y') 'assess_started_date',
                FROM_UNIXTIME(ca.assess_finish_date, '%d/%m/%Y') 'assess_finished',
                FROM_UNIXTIME(ca.due_date, '%d/%m/%Y') 'assess_due_date',
                FROM_UNIXTIME(ca.completed_date, '%d/%m/%Y') 'assess_completed_date',
                IF(ca.`status` = 5, 'N/A', IF(IFNULL(ca.due_date, 0) - IFNULL(ca.assess_start_date, 0) > 0, FLOOR((IFNULL(ca.due_date, 0) - IFNULL(ca.assess_start_date, 0))/86400), ''))  'assess_days_remaining',
                IF(IFNULL(ca.completed_date, 0) - IFNULL(ca.assess_start_date, 0) > 0, FLOOR((IFNULL(ca.completed_date, 0) - IFNULL(ca.assess_start_date, 0))/86400), '') 'assess_turnaround',
                hz.no_hz 'assess_no_hazard',
                IF(au_reject.no_reject IS NOT NULL AND au_reject.no_reject > 0, au_reject.no_reject, '' ) 'assess_no_reject',
                CONCAT(us.first_name, ' ', us.last_name) 'assess_commissioned_by',
                CONCAT(us2.first_name, ' ', us2.last_name) 'assess_fire_lead',
                CONCAT(us3.first_name, ' ', us3.last_name) 'assess_fire_second_lead',
                CONCAT(us4.first_name, ' ', us4.last_name) 'assess_assessor',
                CONCAT(us5.first_name, ' ', us5.last_name) 'assess_quality_checked_by',
                pj.reference 'assess_linked_project',
                wr.reference 'assess_wr_reference',
                work_data.description 'assess_wr_type',
                CONCAT(us6.first_name, ' ', us6.last_name) 'assess_wr_requester'

                FROM cp_assessments ca
                JOIN tbl_property p ON ca.property_id = p.id
                LEFT JOIN tbl_property parent ON p.parent_id = parent.id
                LEFT JOIN tbl_zones z ON z.id = p.zone_id
                LEFT JOIN tbl_asset_class ac ON  ac.id = p.asset_class_id
                LEFT JOIN tbl_asset_class ast ON  ast.id = p.asset_type_id
                LEFT JOIN tbl_tenure_type ten ON  ten.id = p.tenure_type_id
                LEFT JOIN (
                    SELECT object_id, COUNT(object_id) as no_reject FROM tbl_audit_trail WHERE object_type = 'assessment' and action_type = 'rejected' GROUP BY object_id
                ) au_reject ON au_reject.object_id = ca.id
                LEFT JOIN tbl_users us ON  us.id = ca.created_by
                LEFT JOIN tbl_users us2 ON  us2.id = ca.lead_by
                LEFT JOIN tbl_users us3 ON  us3.id = ca.second_lead_by
                LEFT JOIN tbl_users us4 ON  us4.id = ca.assessor_id
                LEFT JOIN tbl_users us5 ON  us5.id = ca.quality_checker
                LEFT JOIN tbl_project pj ON  pj.id = ca.project_id
                LEFT JOIN cp_assessment_aborted_reason ar ON  ar.id = ca.aborted_reason
                LEFT JOIN tbl_work_request wr ON  wr.project_id = pj.id
                LEFT JOIN tbl_work_data work_data ON  work_data.id = wr.type
                LEFT JOIN tbl_users us6 ON  us6.id = wr.created_by
                LEFT JOIN (
                    SELECT assess_id, COUNT(assess_id) no_hz FROM cp_hazards WHERE assess_id > 0 AND decommissioned = 0 AND is_deleted = 0 and is_temp = 0 GROUP BY assess_id
                ) hz ON hz.assess_id = ca.id
                WHERE ca.classification = 2 $condition";
        $data = DB::select($sql);
        return $data;
    }

    public function getFireHazardARSummary() {
        if (\CommonHelpers::isClientUser()) {
            $client_id = \Auth::user()->client_id;
            $condition = "AND z.client_id = $client_id";
        } else {
            $condition = '';
        }
        $sql = "SELECT
                    h.reference as hazard_reference,
                    h.name as hazard_name,
                    IF(h.type > 0, ht.description, '') as hazard_type,
                    IF(h.hazard_potential > 0, hp.description, '') as hazard_potential,
                    IF(h.likelihood_of_harm > 0, hlh.description, '') as hazard_likelihood_of_harm,
                    h.total_risk as score,
                    h.total_risk as risk_text,
                    CONCAT(h.extent, ' ', IF(h.measure_id IS NOT NULL, hm.description, '')) as extent,
                    h.created_date as created_date,
                    IF(a.due_date IS NOT NULL, FROM_UNIXTIME(a.due_date, '%d/%m/%Y'), '') as due_date,
                    IF(a.due_date IS NOT NULL, a.due_date, '') as risk_warning,
                    CASE WHEN h.act_recommendation_verb = 71 THEN IF(h.act_recommendation_verb_other IS NOT NULL, h.act_recommendation_verb_other, '')
                        ELSE IF(h.act_recommendation_verb IS NOT NULL, hv.description, '')
                        END hazard_recommendation_verb,
                    CASE WHEN h.act_recommendation_noun = 71 THEN IF(h.act_recommendation_noun_other IS NOT NULL, h.act_recommendation_noun_other, '')
                        ELSE IF(h.act_recommendation_noun IS NOT NULL, hn.description, '')
                        END hazard_recommendation_noun,
                    CONCAT(
                        CASE WHEN h.act_recommendation_verb = 71 THEN IF(h.act_recommendation_verb_other IS NOT NULL, h.act_recommendation_verb_other, '')
                        ELSE IF(h.act_recommendation_verb IS NOT NULL, hv.description, '')
                        END, ' ',
                        CASE WHEN h.act_recommendation_noun = 71 THEN IF(h.act_recommendation_noun_other IS NOT NULL, h.act_recommendation_noun_other, '')
                        ELSE IF(h.act_recommendation_noun IS NOT NULL, hn.description, '')
                        END
                    ) as action_recommendation,
                    IF(h.action_responsibility > 0, hr.description, '') as hazard_action_responsibility,
                    h.comment,
                    p.reference,
                    p.name,
                    p.property_reference,
                    IF(p.parent_id IS NOT NULL, p2.name, '') as property_parent,
                    p.pblock,
                    p.estate_code,
                    IF(z.id > 0, z.zone_name, '') as property_group,
                    IF(p.asset_class_id IS NOT NULL, ac.description, '') as asset_class,
                    IF(p.asset_type_id IS NOT NULL, ast.description, '') as asset_type,
                    IF(h.area_id IS NOT NULL, tbl_area.area_reference, '') as area_reference,
                    IF(h.area_id IS NOT NULL, tbl_area.description, '') as area_description,
                    IF(h.location_id IS NOT NULL, tbl_location.location_reference, '') as location_reference,
                    IF(h.location_id IS NOT NULL, tbl_location.description, '') as location_description
                FROM cp_hazards h
                LEFT JOIN tbl_property p ON  p.id = h.property_id
                LEFT JOIN tbl_zones z ON  p.zone_id = z.id
                LEFT JOIN tbl_property p2 ON  p.parent_id = p2.id
                LEFT JOIN tbl_asset_class ac ON  ac.id = p.asset_class_id
                LEFT JOIN tbl_asset_class ast ON  ast.id = p.asset_type_id
                LEFT JOIN tbl_area ON  tbl_area.id = h.area_id
                LEFT JOIN tbl_location ON  tbl_location.id = h.location_id
                LEFT JOIN cp_assessments a ON  a.id = h.assess_id
                LEFT JOIN cp_hazard_type ht ON  ht.id = h.type
                LEFT JOIN cp_hazard_potential hp ON  hp.id = h.hazard_potential
                LEFT JOIN cp_hazard_likelihood_harm hlh ON  hlh.id = h.likelihood_of_harm
                LEFT JOIN cp_hazard_measurement hm ON  hm.id = h.measure_id
                LEFT JOIN cp_hazard_action_recommendation_verb hv ON  hv.id = h.act_recommendation_verb
                LEFT JOIN cp_hazard_action_recommendation_noun hn ON  hn.id = h.act_recommendation_noun
                LEFT JOIN cp_hazard_action_responsibilities hr ON  hr.id = h.action_responsibility
                LEFT JOIN tbl_users us on h.created_by = us.id
                WHERE h.assess_type = '". FIRE_CLASSIFICATION ."'
                    -- AND a.status = '". ASSESSMENT_STATUS_COMPLETED ."'
                    AND h.assess_id = 0
                    AND h.is_deleted = 0 and h.is_temp = 0 and h.decommissioned = 0 and h.assess_type != 0
                    $condition
                    ORDER BY  h.`id` ASC";
        $results = DB::select($sql);
        foreach ($results as $result) {
            $result->risk_text = !is_null($result->risk_text) ? \CommonHelpers::getTotalHazardText($result->risk_text)['risk'] : '';
            $int_due_date = $this->getHazardDueDate($result->score, $result->created_date) ?? 0;
            if ($int_due_date) {
                $result->due_date = \CommonHelpers::convertTimeStampToTime($int_due_date);
            } else {
                $result->due_date = '';
            }
            if ($int_due_date) {
                $result->risk_warning = $this->getHazardRiskWarning($int_due_date) . ' Days Remaining';
            } else {
                $result->risk_warning = 0 . ' Days Remaining';
            }
        }
        return $results;
    }
    private function getHazardDueDate($score, $created_date) {
        $inspection = 0;
        switch (TRUE) {
            case $score == 0:
                return 0;
                break;
            case $score < 4:
                $inspection = 86400*365;
                break;

            case $score < 10:
                $inspection = 86400*180;
                break;
            case $score < 16:
                $inspection = 86400*90;
                break;
            case $score < 21:
               $inspection = 86400*30;
                break;
            case $score < 26:
                $inspection = 0;
                break;
            default:
                return 0;
                break;

        }
       $created_date = \ComplianceHelpers::toTimeStamp($created_date);
       return $created_date + $inspection;
    }


    private function getHazardRiskWarning($due_date) {
        $risk_warning =  $due_date - time();
        return floor($risk_warning / 86400);
    }
}
