<?php
namespace App\Repositories;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\SummaryType;
use App\Models\Location;
use App\Models\Item;
use App\Models\Client;
use Carbon\Carbon;
use App\Models\Department;
use App\Models\DepartmentContractor;
use App\Models\Zone;
use App\Models\Counter;
use App\Models\Property;
use App\Models\AppAuditTrail;
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

    public function getAllClient() {
        return Client::orderBy('name')->get();
    }

    public function listZones() {
        return Zone::all();
    }

    public function getAllDepartments($type = 'client', $client_id = 0) {
        if ($type == 'contractor') {
            $table = 'tbl_departments_contractor';
        } else {
            $table = 'tbl_departments';
        }

        $sql = "SELECT * FROM `$table` WHERE parent_id = 0";;
        $results = DB::select($sql);
        return $results;
    }

    public function getChildDepartments( $parent_id = 0) {

        $sql = "SELECT * FROM `tbl_departments` WHERE parent_id = $parent_id";;
        $results = DB::select($sql);
        return $results;
    }

    public function getAllDepartmentClient($type = 'client', $client_id = 0) {
        if ($type == 'contractor') {
           return DepartmentContractor::with('childrens', 'allChildrens')->where('parent_id',0)->get();
        } else {
            return Department::with('childrens', 'allChildrens')->where('parent_id',0)->get();
        }

        // $sql = "SELECT * FROM `$table` WHERE parent_id = 0";;
        // $results = DB::select($sql);
        // return $results;
    }

    public function getDepartment($type = 'client', $id) {
        if ($type == 'contractor') {
            return DepartmentContractor::with('childrens', 'allChildrens')->find($id);
        } else {
            return Department::with('childrens', 'allChildrens')->find($id);
        }

    }

    public function decommissionedItem() {
        $sql = "SELECT
                    z.`zone_name` '" . $this->col++ . "',
                    ac.`description` '" . $this->col++ . "',
                    at.`description` '" . $this->col++ . "',
                    tt.`description` '" . $this->col++ . "',
                    p.`reference` '" . $this->col++ . "',
                    p.`name` '" . $this->col++ . "',
                    ar.`reference` '" . $this->col++ . "',
                    l.`reference` '" . $this->col++ . "',
                    i.`reference` '" . $this->col++ . "',
                    REPLACE(REPLACE(ipd.product_debris,'Other---', ' ' ),'---', ' ') '" . $this->col++ . "',
                    IF(i.decommissioned_reason > 0,tbl_decommission_reasons.description, tbl_items_info.comment) '" . $this->col++ . "',
                    IF(ui.id > 0, CONCAT(ui.first_name, ' ', ui.last_name),
                    IF(ui2.id > 0, CONCAT(ui2.first_name, ' ', ui2.last_name),
                    IF(ul.id > 0, CONCAT(ul.first_name, ' ', ul.last_name),
                    IF(ua.id > 0, CONCAT(ua.first_name, ' ', ua.last_name),
                    IF(up.id > 0, CONCAT(up.first_name, ' ', up.last_name),
                    IF(us.id > 0, CONCAT(us.first_name, ' ', us.last_name),
                    IF(usd.id > 0, CONCAT(usd.first_name, ' ', usd.last_name),
                       '') ))))))
                       '" . $this->col++ . "',
                    IF(ui.id > 0, cui.name,
                        IF(ui2.id > 0, cui2.name,
                        IF(ul.id > 0, cul.name,
                        IF(ua.id > 0, cua.name,
                        IF(up.id > 0, cup.name,
                        IF(us.id > 0, cus.name,
                        IF(usd.id > 0, cusd.name,
                           '') ))))))
                           '" . $this->col++ . "',
                    if(ai.date > 0 , FROM_UNIXTIME(ai.date, \"%d/%m/%Y\"),
                    if(ai2.date > 0 , FROM_UNIXTIME(ai2.date, \"%d/%m/%Y\"),
                    if(al.date > 0 ,  FROM_UNIXTIME(al.date, \"%d/%m/%Y\"),
                    if(aa.date > 0 ,FROM_UNIXTIME(aa.date, \"%d/%m/%Y\"),
                    if(ap.date > 0 ,FROM_UNIXTIME(ap.date, \"%d/%m/%Y\"),
                    if(sa.date > 0 ,FROM_UNIXTIME(sa.date, \"%d/%m/%Y\"),
                    if(svd.sent_back_date > 0 ,FROM_UNIXTIME(svd.sent_back_date, \"%d/%m/%Y\"),
                    'Decommission by developers at client request!') ))))))
                      '" . $this->col++ . "'
                FROM `tbl_items` as i
                LEFT JOIN tbl_location l ON  l.id = i.location_id
                LEFT JOIN tbl_items_info ON tbl_items_info.item_id = i.id
                LEFT JOIN tbl_area ar ON  ar.id = i.area_id
                LEFT JOIN tbl_property p ON  p.id = i.property_id
                LEFT JOIN tbl_asset_class ac ON p.asset_class_id = ac.id
                LEFT JOIN tbl_asset_class at ON p.asset_type_id = at.id
                LEFT JOIN tbl_tenure_type tt ON p.tenure_type_id = tt.id
                LEFT JOIN tbl_zones z ON  p.zone_id = z.id
                LEFT JOIN tbl_items i2 ON i2.record_id = i.record_id and i2.survey_id > 0 and i2.decommissioned != 0
                LEFT JOIN tbl_survey s ON s.id = i2.survey_id
                LEFT JOIN tbl_survey_date svd ON  s.id = svd.survey_id
                LEFT JOIN tbl_item_decommission_type ON tbl_item_decommission_type.id = i.decommissioned
                LEFT JOIN tbl_decommission_reasons ON tbl_decommission_reasons.id = i.decommissioned_reason
                JOIN tbl_item_product_debris_view ipd ON ipd.item_id = i.id
                LEFT JOIN (SELECT MAX(date) AS date, object_id, user_id FROM tbl_audit_trail  WHERE action_type = 'decommission' AND object_type = 'item' GROUP BY object_id) ai ON i.id = ai.object_id
                LEFT JOIN (SELECT MAX(date) AS date, object_id, user_id FROM tbl_audit_trail  WHERE action_type = 'decommission' AND object_type = 'location' GROUP BY object_id) al ON l.id = al.object_id
                LEFT JOIN (SELECT MAX(date) AS date, object_id, user_id FROM tbl_audit_trail  WHERE action_type = 'decommission' AND object_type = 'area' GROUP BY object_id) aa ON ar.id = aa.object_id
                LEFT JOIN (SELECT MAX(date) AS date, object_id, user_id FROM tbl_audit_trail  WHERE action_type = 'decommission' AND object_type = 'property' GROUP BY object_id) ap ON p.id = ap.object_id
                LEFT JOIN (SELECT MAX(date) AS date, object_id, user_id FROM tbl_audit_trail  WHERE action_type = 'decommission' AND object_type = 'survey' GROUP BY object_id) sa ON s.id = sa.object_id
                LEFT JOIN (SELECT MAX(date) AS date, object_id, user_id FROM tbl_audit_trail  WHERE action_type = 'decommission' AND object_type = 'item' GROUP BY object_id) ai2 ON i2.id = ai2.object_id
                LEFT JOIN tbl_users ui on ai.user_id = ui.id
                LEFT JOIN tbl_users ul on al.user_id = ul.id
                LEFT JOIN tbl_users ua on aa.user_id = ua.id
                LEFT JOIN tbl_users up on ap.user_id = up.id
                LEFT JOIN tbl_users us on sa.user_id = us.id
                LEFT JOIN tbl_users ui2 on ai2.user_id = ui2.id
                LEFT JOIN tbl_users usd on s.surveyor_id = usd.id
                LEFT JOIN tbl_clients cui on ui.client_id = cui.id
                LEFT JOIN tbl_clients cui2 on ui2.client_id = cui2.id
                LEFT JOIN tbl_clients cul on ul.client_id = cul.id
                LEFT JOIN tbl_clients cua on ua.client_id = cua.id
                LEFT JOIN tbl_clients cup on up.client_id = cup.id
                LEFT JOIN tbl_clients cus on us.client_id = cus.id
                LEFT JOIN tbl_clients cusd on usd.client_id = cusd.id
                WHERE i.decommissioned != 0 AND i.survey_id = 0
                    ORDER BY  p.`name` ASC,
                     ar.`reference` ASC, l.`reference` ASC";
        $data = DB::select($sql);
        return $data;
    }

    public function inaccessibleSummary($type) {
        switch ($type) {
            case 'room':
                $title = [
                    'Property Block', 'Property Name', 'Property Group', 'Asset Class', 'Asset Type','Tenure Type', 'Area/floor Reference',
                    'Area/floor Description', 'Room/location Reference', 'Room/location Description', 'Reason for No Access'
                ];
                $sql = "SELECT p.`pblock` '" . $this->col++ . "',
                                    p.`name` '" . $this->col++ . "',
                                    z.`zone_name` '" . $this->col++ . "',
                                    ac.`description` '" . $this->col++ . "',
                                    at.`description` '" . $this->col++ . "',
                                    tt.`description` '" . $this->col++ . "',
                                    a.`area_reference` '" . $this->col++ . "',
                                    a.`description` '" . $this->col++ . "',
                                    l.`location_reference` '" . $this->col++ . "',
                                    l.`description` '" . $this->col++ . "',
                                    IF(d.other = 0, d.description, li.reason_inaccess_other)  '" . $this->col++ . "'
                                    FROM tbl_location l
                                    LEFT JOIN tbl_location_info li ON l.id = li.location_id
                                    LEFT JOIN tbl_dropdown_data_location as d on d.id = li.reason_inaccess_key
                                    LEFT JOIN tbl_property p ON p.id = l.property_id
                                    LEFT JOIN tbl_asset_class ac ON p.asset_class_id = ac.id
                                    LEFT JOIN tbl_asset_class at ON p.asset_type_id = at.id
                                    LEFT JOIN tbl_tenure_type tt ON p.tenure_type_id = tt.id
                                    LEFT JOIN tbl_zones z ON p.zone_id = z.id
                                    LEFT JOIN tbl_area a ON a.id = l.area_id
                                    WHERE l.`survey_id` = 0
                                    AND l.`decommissioned` = '0'
                                    AND l.`state` = 0
                                    AND p.id > 0
                                        ORDER BY p.id, l.`location_reference`";
                break;
            case 'void':
                $title = [
                    'Property Block', 'Property Name', 'Property Group', 'Asset Class', 'Asset Type','Tenure Type', 'Area/floor Reference',
                    'Area/floor Description', 'Room/location Reference', 'Room/location Description', 'Void', 'Reason for No Access'
                ];
                $results = [];
                foreach (Location::$voidInvestigatorTables as $dropdown_id => $voidColumn) {
                    $voidColumnOther = $voidColumn . "_other";
                    $voidName = Location::$voidInvestigator[$dropdown_id];
                    $voidInaccessibleColumn = Location::$voidInvestigatorInaccessible[$dropdown_id];
                    $sql = "SELECT p.`pblock` ,
                                     p.`property_reference` pref ,
                                            p.`name` pname,
                                            z.`zone_name` ,
                                            ac.`description` '" . $this->col++ . "',
                                            at.`description` '" . $this->col++ . "',
                                            tt.`description` '" . $this->col++ . "',
                                            a.`reference` aref,
                                            a.`description` ades,
                                            l.`location_reference` lref,
                                            l.`description` ldes,
                                            lv.$voidColumn as 'dropdownDescription',
                                            lv.$voidColumnOther as 'other'
                                            FROM tbl_location l
                                            LEFT JOIN tbl_property p ON p.id = l.property_id
                                            LEFT JOIN tbl_asset_class ac ON p.asset_class_id = ac.id
                                            LEFT JOIN tbl_asset_class at ON p.asset_type_id = at.id
                                            LEFT JOIN tbl_tenure_type tt ON p.tenure_type_id = tt.id
                                            LEFT JOIN tbl_zones z ON p.zone_id = z.id
                                            LEFT JOIN tbl_area a ON a.id = l.area_id
                                            LEFT JOIN tbl_location_void lv ON l.id = lv.location_id
                                            WHERE l.`survey_id` = 0
                                            AND l.`decommissioned` = '0'
                                            AND p.`decommissioned` = '0'
                                            AND lv.$voidColumn LIKE '%$voidInaccessibleColumn%'
                                            AND p.id > 0";
                        $locations = DB::select($sql);
                        $locations = collect($locations)->map(function($x){ return (array) $x; })->toArray();
                        if (count($locations)) {
                            foreach ($locations as $location) {
                                $results[] = [
                                    $location['pref'],
                                    $location['pblock'],
                                    $location['pname'],
                                    $location['zone_name'],
                                    $location['aref'],
                                    $location['ades'],
                                    $location['lref'],
                                    $location['ldes'],
                                    $voidName,
                                    \CommonHelpers::getLocationVoidDetails($location['dropdownDescription'], $location['other'])
                                ];
                            }
                        }
                    }
                    return $data = [
                        'title' => $title,
                        'data' => $results,
                    ];
                break;

            case 'item':
                $title = [
                    'Property Block', 'Property Name', 'Property Group', 'Asset Class', 'Asset Type','Tenure Type', 'Area/floor Reference',
                    'Area/floor Description', 'Room/location Reference', 'Room/location Description', 'Item Reference',
                    'Reason for No Access'
                ];

                $sql = "SELECT p.`pblock` '" . $this->col++ . "',
                                p.`name` '" . $this->col++ . "',
                                z.`zone_name` '" . $this->col++ . "',
                                ac.`description` '" . $this->col++ . "',
                                at.`description` '" . $this->col++ . "',
                                tt.`description` '" . $this->col++ . "',
                                a.`area_reference` '" . $this->col++ . "',
                                a.`description` '" . $this->col++ . "',
                                l.`location_reference` '" . $this->col++ . "',
                                l.`description` '" . $this->col++ . "',
                                i.reference  '" . $this->col++ . "',
                                IF(d.other = 0, d.description, v.dropdown_other) '" . $this->col++ . "'
                                FROM `tbl_items` as i
                                LEFT JOIN tbl_location l ON l.id = i.location_id
                                LEFT JOIN tbl_item_no_access_value as v on v.item_id = i.id
                                LEFT JOIN tbl_item_no_access as d on d.id = v.dropdown_data_item_id
                                LEFT JOIN tbl_property p ON p.id = i.property_id
                                LEFT JOIN tbl_asset_class ac ON p.asset_class_id = ac.id
                                LEFT JOIN tbl_asset_class at ON p.asset_type_id = at.id
                                LEFT JOIN tbl_tenure_type tt ON p.tenure_type_id = tt.id
                                LEFT JOIN tbl_zones z ON p.zone_id = z.id
                                LEFT JOIN tbl_area a ON a.id = i.area_id
                                WHERE i.`survey_id` = 0
                                AND i.`decommissioned` = 0
                                AND i.`state` = 2
                                AND p.id > 0
                                    ORDER BY p.id, l.`reference`";
                break;

            default:
                $sql = "";
                $title = "";
                break;
        }

        $results = DB::select($sql);
        return $data = [
            'title' => $title,
            'data' => $results,
        ];
    }

    public function actionRecommendation() {
        $sql = "SELECT
            p.`pblock` '" . $this->col++ . "',
            p.`name` '" . $this->col++ . "',
            z.`zone_name` '" . $this->col++ . "',
            ac.`description` '" . $this->col++ . "',
            at.`description` '" . $this->col++ . "',
            tt.`description` '" . $this->col++ . "',
            a.`area_reference` '" . $this->col++ . "',
            a.`description` '" . $this->col++ . "',
            l.`location_reference` '" . $this->col++ . "',
            l.`description` '" . $this->col++ . "',
            i.reference  '" . $this->col++ . "',
            pd.product_debris 'product_debris',
            IF(i.state = 2, IF(tbl_items_info.assessment = 1, 12, i.total_mas_risk), i.total_mas_risk) '" . $this->col++ . "',
            IF(i.state = 2, IF(tbl_items_info.assessment = 1, 'High Risk',
            CASE
                WHEN i.total_mas_risk = 0 THEN 'No Risk'
                WHEN i.total_mas_risk < 5 THEN 'Very Low Risk'
                WHEN i.total_mas_risk < 7 THEN 'Low Risk'
                WHEN i.total_mas_risk < 10 THEN 'Medium Risk'
                WHEN i.total_mas_risk < 13 THEN 'High Risk'
            END),
            CASE
                WHEN i.total_mas_risk = 0 THEN 'No Risk'
                WHEN i.total_mas_risk < 5 THEN 'Very Low Risk'
                WHEN i.total_mas_risk < 7 THEN 'Low Risk'
                WHEN i.total_mas_risk < 10 THEN 'Medium Risk'
                WHEN i.total_mas_risk < 13 THEN 'High Risk'
            END) '" . $this->col++ . "',
            CASE
                WHEN ar.id IS NULL THEN ''
                WHEN arv.dropdown_data_item_id IN (" . implode(',', ACTION_RECOMMENDATION_LIST_ID) . ") THEN 'Remedial or Removal Works'
                WHEN arv.dropdown_data_item_parent_id IN (" . implode(',', ACTION_RECOMMENDATION_LIST_ID) . ") THEN 'Remedial or Removal Works'
                ELSE 'Management' END '" . $this->col++ . "',
            arvv.action_recommendation '" . $this->col++ . "'
            FROM `tbl_items` as i
            LEFT JOIN tbl_location l ON l.id = i.location_id
            LEFT JOIN tbl_items_info  ON i.id = tbl_items_info.item_id
            LEFT JOIN tbl_item_product_debris_view pd ON i.id = pd.item_id
            LEFT JOIN tbl_item_action_recommendation_view arvv ON i.id = arvv.item_id
            LEFT JOIN tbl_item_action_recommendation_value arv ON i.id = arv.item_id
            LEFT JOIN tbl_item_action_recommendation ar ON ar.id = arv.dropdown_data_item_id
            LEFT JOIN tbl_property p ON p.id = i.property_id
            LEFT JOIN tbl_asset_class ac ON p.asset_class_id = ac.id
            LEFT JOIN tbl_asset_class at ON p.asset_type_id = at.id
            LEFT JOIN tbl_tenure_type tt ON p.tenure_type_id = tt.id
            LEFT JOIN tbl_zones z ON p.zone_id = z.id
            LEFT JOIN tbl_area a ON a.id = i.area_id
            WHERE i.`survey_id` = 0
            AND i.`state` != 1
            AND i.`decommissioned` = 0
            AND p.id > 0
            GROUP BY i.id
            ORDER BY i.total_mas_risk DESC";

            $results = DB::select($sql);
            foreach ($results as $key => $result) {
                $result->product_debris = \CommonHelpers::getProductDebisText($result->product_debris);
            }
            return $results;
    }

    public function projectSummary($client_id, $time) {
        //FIND_IN_SET(sur.id, pro.survey_id)
        $condition = "";
        if (!\CommonHelpers::isSystemClient()) {
            $user_client_id = \Auth::user()->client_id;
            $condition = " AND FIND_IN_SET($user_client_id, pj.checked_contractors)";
        }
        $sql = "SELECT
                    pj.reference '" . $this->col++ . "',
                    p.property_reference '" . $this->col++ . "',
                    p.`pblock` '" . $this->col++ . "',
                    p.estate_code '" . $this->col++ . "',
                    sa.description '" . $this->col++ . "',
                    TRIM(p.`name`) '" . $this->col++ . "',
                    pi.flat_number '" . $this->col++ . "',
                    pi.building_name '" . $this->col++ . "',
                    pi.street_number '" . $this->col++ . "',
                    pi.street_name '" . $this->col++ . "',
                    pi.town '" . $this->col++ . "',
                    pi.address5 '" . $this->col++ . "',
                    pi.postcode '" . $this->col++ . "',
                    z.`zone_name` '" . $this->col++ . "',
                    ac.`description` '" . $this->col++ . "',
                    at.`description` '" . $this->col++ . "',
                    tt.`description` '" . $this->col++ . "',
                    pj.title '" . $this->col++ . "',
                    pt.description '" . $this->col++ . "',
                    IF(u.id > 0, CONCAT(u.first_name, ' ', u.last_name), '') '" . $this->col++ . "',
                    IF(u4.id > 0, CONCAT(u4.first_name, ' ', u4.last_name), '') '" . $this->col++ . "',
                    CASE pj.`status`
                            WHEN 1 THEN
                                'Project Created'
                            WHEN 2 THEN
                                'Tendering in Progress'
                            WHEN 3 THEN
                                'Technical in Progress'
                            WHEN 4 THEN
                                'Ready for Archive'
                            WHEN 5 THEN
                                'Completed'
                            ELSE
                                'Rejected'
                            END '" . $this->col++ . "',
                    IF(pj.contractor_not_required = 1, 'Contractor Not Required', GROUP_CONCAT(c.`name`)) '" . $this->col++ . "',
                    IF(wr.id > 0, wr.reference ,wr2.reference),
                    IF(wr.id > 0, wd.description, wd2.description),
                    IF(wr.id > 0, CONCAT(uw.first_name,' ',uw.last_name), CONCAT(uw2.first_name,' ',uw2.last_name)),
                    IF(wr.id > 0, IF(uw.client_id = 1, TRIM(dp.`name`), TRIM(dc.`name`)), IF(uw2.client_id = 1, TRIM(dp2.`name`), TRIM(dc2.`name`)) ),
                    IF(pj.project_type = 2, st.description, '') '" . $this->col++ . "',
                    s.reference '" . $this->col++ . "',
                    ss.reference '" . $this->col++ . "',
                    pt1.description '" . $this->col++ . "',
                    GROUP_CONCAT(DISTINCT pj1.reference ) '" . $this->col++ . "',
                    IF(pj.enquiry_date > 0, FROM_UNIXTIME(pj.enquiry_date,\"%d/%m/%Y\"), '') '" . $this->col++ . "',
                    IF(pj.date > 0, FROM_UNIXTIME(pj.date,\"%d/%m/%Y\"), '') '" . $this->col++ . "',
                    IF(pj.due_date > 0, FROM_UNIXTIME(pj.due_date,\"%d/%m/%Y\"), '') '" . $this->col++ . "',
                    IF( pj.`status` != 5, IF(pj.due_date > $time , FLOOR((pj.due_date - $time) / 86400), 0), 0) '" . $this->col++ . "',
                    IF(u3.id > 0, CONCAT(u3.first_name, ' ', u3.last_name), '') '" . $this->col++ . "',
                    IF( pj.`status` = 5, IF(pj.completed_date >0 , FROM_UNIXTIME(pj.completed_date,\"%d/%m/%Y\"), ''), '') '" . $this->col++ . "'

                    from tbl_project as pj
                    LEFT JOIN tbl_property as p on p.id = pj.property_id
                    LEFT JOIN tbl_asset_class ac ON p.asset_class_id = ac.id
                    LEFT JOIN tbl_asset_class at ON p.asset_type_id = at.id
                    LEFT JOIN tbl_tenure_type tt ON p.tenure_type_id = tt.id
                    LEFT JOIN tbl_property_info as pi on pi.property_id = p.id
                    LEFT JOIN tbl_zones z ON p.zone_id = z.id
                    LEFT JOIN tbl_users u ON pj.lead_key = u.id
                    LEFT JOIN tbl_users u3 ON pj.created_by = u3.id
                    LEFT JOIN tbl_users u4 ON pj.second_lead_key = u4.id
                    LEFT JOIN tbl_clients as c on FIND_IN_SET(c.id, pj.checked_contractors)
                    LEFT JOIN tbl_project_types as pt on pt.id = pj.project_type
                    LEFT JOIN (
                        SELECT GROUP_CONCAT(sur.reference) reference, pro.id FROM tbl_project pro
                        JOIN tbl_survey sur ON FIND_IN_SET(sur.id, pro.survey_id)
                        GROUP BY pro.id
                    ) as s on s.id = pj.id
                    LEFT JOIN (
                        SELECT GROUP_CONCAT(reference) reference, project_id FROM tbl_survey GROUP BY project_id
                    ) as ss on ss.project_id = pj.id
                    LEFT JOIN tbl_survey_type as st on st.id = pj.survey_type
                    LEFT JOIN tbl_project as pj1 on FIND_IN_SET(pj1.id, pj.linked_project_id)
                    LEFT JOIN tbl_project_types as pt1 on pt1.id = pj.linked_project_type
                    LEFT JOIN tbl_service_area sa ON p.service_area_id = sa.id
                    LEFT JOIN tbl_work_request wr on wr.project_id = pj.id
                    LEFT JOIN tbl_work_data wd on wr.type = wd.id
                    LEFT JOIN tbl_users uw on uw.id = wr.created_by
                    LEFT JOIN tbl_work_request wr2 on FIND_IN_SET(pj.id, wr2.project_id_major)
                    LEFT JOIN tbl_work_data wd2 on wr2.type = wd2.id
                    LEFT JOIN tbl_users uw2 on uw2.id = wr2.created_by
                    LEFT JOIN tbl_departments dp ON uw.department_id = dp.id
                    LEFT JOIN tbl_departments_contractor dc ON uw.department_id = dc.id
                    LEFT JOIN tbl_departments dp2 ON uw2.department_id = dp2.id
                    LEFT JOIN tbl_departments_contractor dc2 ON uw2.department_id = dc2.id
                    WHERE p.decommissioned = 0 and p.zone_id > 0
                                            AND p.client_id = $client_id $condition
                    GROUP BY pj.id
                    ORDER BY pj.date DESC";
        $data = DB::select($sql);
        return $data;
    }

    public function registerItemChange($fromAuditTime) {
        $sql = "SELECT
                    i.`name` '" . $this->col++ . "',
                    i.reference  '" . $this->col++ . "',
                    FROM_UNIXTIME(audit.date, '%d/%m/%Y')  '" . $this->col++ . "',
                    FROM_UNIXTIME(audit.date, '%H:%i')  '" . $this->col++ . "',
                    audit.ip  '" . $this->col++ . "',
                    CONCAT(UCASE(LEFT(audit.action_type, 1)), SUBSTRING(audit.action_type, 2))  '" . $this->col++ . "',
                    audit.user_name  '" . $this->col++ . "',
                    p.`pblock` '" . $this->col++ . "',
                    p.`name` '" . $this->col++ . "',
                    z.`zone_name` '" . $this->col++ . "',
                    ac.`description` '" . $this->col++ . "',
                    at.`description` '" . $this->col++ . "',
                    tt.`description` '" . $this->col++ . "',
                    a.`area_reference` '" . $this->col++ . "',
                    a.`description` '" . $this->col++ . "',
                    l.`location_reference` '" . $this->col++ . "',
                    l.`description` '" . $this->col++ . "'
                    FROM `tbl_items` as i
                    LEFT JOIN tbl_location l ON l.id = i.location_id
                    LEFT JOIN tbl_audit_trail audit ON audit.object_id = i.id AND audit.object_type = 'item' AND audit.action_type != 'view' AND audit.date > $fromAuditTime
                    LEFT JOIN tbl_property p ON p.id = i.property_id
                    LEFT JOIN tbl_asset_class ac ON p.asset_class_id = ac.id
                    LEFT JOIN tbl_asset_class at ON p.asset_type_id = at.id
                    LEFT JOIN tbl_tenure_type tt ON p.tenure_type_id = tt.id
                    LEFT JOIN tbl_zones z ON p.zone_id = z.id
                    LEFT JOIN tbl_area a ON a.id = i.area_id
                    WHERE i.`survey_id` = 0
                    AND i.`decommissioned` = '0'
                    AND p.id > 0
                    AND audit.id > 0
                        ORDER BY audit.date ASC";
        $data = DB::select($sql);
        return $data;
    }

    public function hdDocuments() {
        $sql = "SELECT
                    h.reference '" . $this->col++ . "',
                    h.name '" . $this->col++ . "',
                    dt.description '" . $this->col++ . "',
                    sd.file_name '" . $this->col++ . "',
                    FROM_UNIXTIME(h.date, '%d/%m/%Y') '" . $this->col++ . "',
                    IF(doc.addedDate > 0, FROM_UNIXTIME(doc.addedDate, '%d/%m/%Y'), FROM_UNIXTIME(h.date, '%d/%m/%Y')) '" . $this->col++ . "',
                    IF(u.id > 0, CONCAT(u.first_name, ' ', u.last_name), CONCAT('ID', h.created_by)) '" . $this->col++ . "',
                    p.`property_reference` '" . $this->col++ . "',
                    p.`pblock` '" . $this->col++ . "',
                    p.`name` '" . $this->col++ . "',
                    z.`zone_name` '" . $this->col++ . "'
                FROM
                    compliance_documents h
                LEFT JOIN compliance_document_types dt on dt.id = h.type
                LEFT JOIN compliance_document_storage sd on sd.object_id = h.id and sd.type = 'doc_photo'
                LEFT JOIN tbl_property p ON h.property_id = p.id
                LEFT JOIN tbl_users u ON h.created_by = u.id
                LEFT JOIN (SELECT object_id, addedDate FROM compliance_document_storage WHERE  `type` = 'doc_photo') doc ON doc.object_id = h.id
                LEFT JOIN tbl_zones z ON p.zone_id = z.id
                WHERE
                    p.decommissioned = 0
                AND p.id > 0
                ORDER BY
                    h.id asc";
        $data = DB::select($sql);
        return $data;
    }

    public function surveySummary() {
        $condition = "";
        if (!\CommonHelpers::isSystemClient()) {
            $client_id = \Auth::user()->client_id;
            $condition = " AND s.client_id = $client_id";
        }
        $sql = "SELECT
                s.reference '" . $this->col++ . "',
                st.description '" . $this->col++ . "',
                CASE s.`status`
                    WHEN 1 THEN
                        'New Survey'
                    WHEN 2 THEN
                        'Locked'
                    WHEN 3 THEN
                        'Ready for QA'
                    WHEN 4 THEN
                        'Published'
                    WHEN 5 THEN
                        'Completed'
                    WHEN 6 THEN
                        'Rejected'
                    WHEN 7 THEN
                        'Sent back'
                    WHEN 8 THEN
                        'Aborted'
                    ELSE
                        ''
                    END '" . $this->col++ . "',
                IF(s.`status` = 5, 'N/A',
                    IF(sd.due_date >0, DATEDIFF(FROM_UNIXTIME(sd.due_date), NOW()), 'Missing due date')
                ) '" . $this->col++ . "',
                TRIM(p.pblock) '" . $this->col++ . "',
                TRIM(p.`name`) '" . $this->col++ . "',
                TRIM(z.zone_name) '" . $this->col++ . "',
                ac.`description` '" . $this->col++ . "',
                at.`description` '" . $this->col++ . "',
                tt.`description` '" . $this->col++ . "',
                IF(s.created_at > 0 , Date_Format(s.created_at,'%d/%m/%Y'), '') '" . $this->col++ . "',
                IF(sd.started_date > 0 , FROM_UNIXTIME(sd.started_date,\"%d/%m/%Y\"), '') '" . $this->col++ . "',
                IF(sd.surveying_finish_date > 0 , FROM_UNIXTIME(sd.surveying_finish_date,\"%d/%m/%Y\"), '') '" . $this->col++ . "',
                COUNT(a.id) '" . $this->col++ . "',
                IF(sd.completed_date > 0 , FROM_UNIXTIME(sd.completed_date,\"%d/%m/%Y\"), '') '" . $this->col++ . "',
                CONCAT(abl.first_name,' ',abl.last_name) '" . $this->col++ . "',
                CONCAT(uc.first_name,' ',uc.last_name) '" . $this->col++ . "',
                TRIM(c.`name`) '" . $this->col++ . "',
                CONCAT(us.first_name,' ',us.last_name) '" . $this->col++ . "',
                CONCAT(ut.first_name,' ',ut.last_name) '" . $this->col++ . "',
                s.cost '" . $this->col++ . "',
                s.organisation_reference '" . $this->col++ . "',
                IF(wr.id > 0, wr.reference ,wr2.reference),
                IF(wr.id > 0, wd.description, wd2.description),
                IF(wr.id > 0, CONCAT(uw.first_name,' ',uw.last_name), CONCAT(uw2.first_name,' ',uw2.last_name)),
                IF(wr.id > 0, IF(uw.client_id = 1, TRIM(dp.`name`), TRIM(dc.`name`)), IF(uw2.client_id = 1, TRIM(dp2.`name`), TRIM(dc2.`name`)) ),
                IF(ia.count > 0, ia.count, 'N') '" . $this->col++ . "',
                IF(lia.count > 0, lia.count, 'N') '" . $this->col++ . "',
                IF(lv.count > 0, lv.count, 'N') '" . $this->col++ . "',
                IF(iia.count > 0, iia.count, 'N') '" . $this->col++ . "',
                IF(sd.due_date > 0, FLOOR((sd.due_date - UNIX_TIMESTAMP()) / 86400), '') '" . $this->col++ . "'

                FROM tbl_survey as s
                LEFT JOIN (SELECT survey_id, COUNT(*) count FROM tbl_location WHERE decommissioned = 0 AND state = ".LOCATION_STATE_INACCESSIBLE." AND survey_id > 0 GROUP BY survey_id) lia ON lia.survey_id = s.id
                LEFT JOIN (SELECT survey_id, COUNT(*) count FROM tbl_location LEFT JOIN tbl_location_void lcv ON tbl_location.id = lcv.location_id
                                                                            WHERE decommissioned = 0
                                                                            AND ( lcv.ceiling like '1108%'
                                                                            OR lcv.floor LIKE '1453%'
                                                                            OR lcv.cavities LIKE '1216%'
                                                                            OR lcv.risers LIKE '1280%'
                                                                            OR lcv.ducting LIKE '1344%'
                                                                            OR lcv.boxing LIKE '1733%'
                                                                            OR lcv.pipework LIKE '1606%'
                                                                            )
                                                                            AND tbl_location.survey_id > 0 GROUP BY tbl_location.survey_id ) lv ON lv.survey_id = s.id
                LEFT JOIN tbl_audit_trail a ON a.object_id = s.id and a.object_type = 'survey' and a.action_type = 'rejected'
                LEFT JOIN (SELECT survey_id, COUNT(*) count FROM tbl_items WHERE decommissioned = 0 AND state = ".ITEM_ACCESSIBLE_STATE." AND survey_id > 0 GROUP BY survey_id) ia ON ia.survey_id = s.id
                LEFT JOIN (SELECT survey_id, COUNT(*) count FROM tbl_items WHERE decommissioned = 0 AND state = ".ITEM_INACCESSIBLE_STATE." AND survey_id > 0 GROUP BY survey_id) iia ON iia.survey_id = s.id
                LEFT JOIN tbl_survey_date as sd on sd.survey_id = s.id
                LEFT JOIN tbl_property as p on p.id = s.property_id
                LEFT JOIN tbl_asset_class ac ON p.asset_class_id = ac.id
                LEFT JOIN tbl_asset_class at ON p.asset_type_id = at.id
                LEFT JOIN tbl_tenure_type tt ON p.tenure_type_id = tt.id
                LEFT JOIN tbl_clients as c on c.id = s.client_id
                LEFT JOIN tbl_users as us on us.id = s.surveyor_id
                LEFT JOIN tbl_users as abl on abl.id = s.lead_by
                LEFT JOIN tbl_users as ut on ut.id = s.consultant_id
                LEFT JOIN tbl_users as uc on uc.id = s.created_by
                LEFT JOIN tbl_zones as z on z.id = p.zone_id
                LEFT JOIN tbl_survey_type as st ON st.id = s.survey_type
                LEFT JOIN tbl_project pj on pj.id = s.project_id
                LEFT JOIN tbl_work_request wr on wr.project_id = pj.id
                LEFT JOIN tbl_work_data wd on wr.type = wd.id
                LEFT JOIN tbl_users uw on uw.id = wr.created_by
                LEFT JOIN tbl_work_request wr2 on FIND_IN_SET(pj.id, wr2.project_id_major)
                LEFT JOIN tbl_work_data wd2 on wr2.type = wd2.id
                LEFT JOIN tbl_users uw2 on uw2.id = wr2.created_by
                LEFT JOIN tbl_departments dp ON uw.department_id = dp.id
                LEFT JOIN tbl_departments_contractor dc ON uw.department_id = dc.id
                LEFT JOIN tbl_departments dp2 ON uw2.department_id = dp2.id
                LEFT JOIN tbl_departments_contractor dc2 ON uw2.department_id = dc2.id
                WHERE p.decommissioned = 0 and p.zone_id > 0 and s.decommissioned = 0 $condition
                GROUP BY s.id
                ORDER BY p.id";
        $data = DB::select($sql);
        return $data;
    }

    public function projectDocumentSummary($client_id) {
        $sql = "SELECT
                d.reference '" . $this->col++ . "',
                pj.reference '" . $this->col++ . "',
                p.property_reference '" . $this->col++ . "',
                p.`pblock` '" . $this->col++ . "',
                p.estate_code '" . $this->col++ . "',
                sa.description '" . $this->col++ . "',
                TRIM(p.`name`) '" . $this->col++ . "',
                pi.flat_number '" . $this->col++ . "',
                pi.building_name '" . $this->col++ . "',
                pi.street_number '" . $this->col++ . "',
                pi.street_name '" . $this->col++ . "',
                pi.town '" . $this->col++ . "',
                pi.address5 '" . $this->col++ . "',
                pi.postcode '" . $this->col++ . "',
                z.`zone_name` '" . $this->col++ . "',
                ac.`description` '" . $this->col++ . "',
                at.`description` '" . $this->col++ . "',
                tt.`description` '" . $this->col++ . "',
                dt.doc_type '" . $this->col++ . "',
                GROUP_CONCAT(DISTINCT cl.`name` ) '" . $this->col++ . "',
                IF(u2.id > 0, CONCAT(u2.first_name, ' ', u2.last_name), '') '" . $this->col++ . "',
                TRIM(d.name) '" . $this->col++ . "',
                IF(au1.date > 0 , FROM_UNIXTIME(au1.date, \"%d/%m/%Y\"), '')'" . $this->col++ . "',
                IF(au2.max_date > 0 , FROM_UNIXTIME(au2.max_date, \"%d/%m/%Y\"), FROM_UNIXTIME(au1.date, \"%d/%m/%Y\"))'" . $this->col++ . "',
                IF(au3.max_date > 0 , FROM_UNIXTIME(au3.max_date, \"%d/%m/%Y\"), '') '" . $this->col++ . "',
                IFNULL(au4.no_reject, '') '" . $this->col++ . "',
                IF(d.added > 0, FROM_UNIXTIME(d.added,\"%d/%m/%Y\"), '') '" . $this->col++ . "',
                IF(d.category NOT IN (4,5), IF(d.`status` = 2, 'Approved', IF(d.`status` = 3, 'Rejected', 'Published (Awaiting for Approval)')), 'N/A') '" . $this->col++ . "',
                IF(u.client_id = 1, TRIM(dp.`name`), TRIM(dc.`name`)) '" . $this->col++ . "'
                from tbl_documents as d
                LEFT JOIN tbl_project as pj  on d.project_id = pj.id
                LEFT JOIN tbl_property as p on p.id = pj.property_id
                LEFT JOIN tbl_asset_class ac ON p.asset_class_id = ac.id
                LEFT JOIN tbl_asset_class at ON p.asset_type_id = at.id
                LEFT JOIN tbl_tenure_type tt ON p.tenure_type_id = tt.id
                LEFT JOIN tbl_zones z ON p.zone_id = z.id
                LEFT JOIN tbl_users u ON u.id = d.added_by
                LEFT JOIN tbl_departments dp ON u.department_id = dp.id
                LEFT JOIN tbl_departments_contractor dc ON u.department_id = dc.id
                LEFT JOIN tbl_clients as c on c.id = d.client_id
                LEFT JOIN tbl_refurb_doc_types as dt on dt.id = d.type
                LEFT JOIN tbl_property_info as pi on pi.property_id = p.id
                LEFT JOIN tbl_service_area sa ON p.service_area_id = sa.id
                LEFT JOIN tbl_clients as cl on FIND_IN_SET(cl.id, pj.contractors)
                LEFT JOIN tbl_users u2 ON pj.lead_key = u2.id
                LEFT JOIN tbl_audit_trail au1 ON au1.object_id = d.id AND au1.action_type = 'add' AND au1.object_type = 'document'
                LEFT JOIN (
                        SELECT MAX(date) as max_date, object_id FROM tbl_audit_trail WHERE action_type = 'edit' AND object_type = 'document' GROUP BY object_id
                    ) au2 ON au2.object_id = d.id
                LEFT JOIN (
                        SELECT MAX(date) as max_date, object_id FROM tbl_audit_trail WHERE action_type = 'approved' AND object_type = 'document' GROUP BY object_id
                    ) au3 ON au3.object_id = d.id
                LEFT JOIN (
                    SELECT COUNT(*) as no_reject, object_id FROM tbl_audit_trail WHERE action_type = 'rejected' AND object_type = 'document' GROUP BY object_id
                ) au4 ON au4.object_id = d.id
                WHERE p.decommissioned = 0 and p.zone_id > 0 AND d.type > 0
                                        AND p.client_id = $client_id
                GROUP BY d.id
                ORDER BY pj.date DESC";
        $data = DB::select($sql);
        return $data;
    }

    //to do
    public function reinspectionProgramme() {
        $reinspectionTotal = 365 * 86400;
        $time = Carbon::now()->timestamp;
        $sql = "SELECT
                p.`pblock` '" . $this->col++ . "',
                p.`name` '" . $this->col++ . "',
                z.zone_name '" . $this->col++ . "',
                ac.`description` '" . $this->col++ . "',
                at.`description` '" . $this->col++ . "',
                tt.`description` '" . $this->col++ . "',
                sM.`reference` '" . $this->col++ . "',
                IF(sM.surveying_finish_date > 0, FROM_UNIXTIME(sM.surveying_finish_date, '%d/%m/%Y'), '') '" . $this->col++ . "',
                IF(sM.completed_date > 0, FROM_UNIXTIME(sM.completed_date, '%d/%m/%Y'), '') '" . $this->col++ . "',
                i.countACM '" . $this->col++ . "',
                sL.`reference` '" . $this->col++ . "',
                IF(sL.id is null, 'No Survey Created', CASE sL.`status`
                                                WHEN 1 THEN
                                                    'New Survey'
                                                WHEN 2 THEN
                                                    'Locked'
                                                WHEN 3 THEN
                                                    'Ready for QA'
                                                WHEN 4 THEN
                                                    'Published'
                                                WHEN 5 THEN
                                                    'Completed'
                                                WHEN 6 THEN
                                                    'Rejected'
                                                WHEN 7 THEN
                                                    'Sent back'
                                                ELSE
                                                    ''
                                                END) '" . $this->col++ . "',
                IF(sL.surveying_finish_date > 0, FROM_UNIXTIME(sL.surveying_finish_date, '%d/%m/%Y'), '') '" . $this->col++ . "',
                IF(sL.completed_date > 0, FROM_UNIXTIME(sL.completed_date, '%d/%m/%Y'), '') '" . $this->col++ . "',
                IF(GREATEST(IFNULL(s.surveying_finish_date, 0), IFNULL(sM.surveying_finish_date, 0), IFNULL(h.added, 0)) >0, FROM_UNIXTIME(@lastReinspectionDate := GREATEST(IFNULL(s.surveying_finish_date, 0), IFNULL(sM.surveying_finish_date, 0), IFNULL(h.added, 0)) + $reinspectionTotal, '%d/%m/%Y'), DATE_FORMAT(CURDATE(), '%d/%m/%Y')) '" . $this->col++ . "',
                @lastReinspectionDay := DATEDIFF(FROM_UNIXTIME(@lastReinspectionDate, '%Y-%m-%d'),CONVERT(CURDATE(),DATETIME)) '" . $this->col++ . "',
                CASE
                            WHEN @lastReinspectionDay  >120 THEN 'Deadline'
                            WHEN @lastReinspectionDay  >60 THEN 'Attention'
                            WHEN @lastReinspectionDay  >30 THEN 'Important'
                            WHEN @lastReinspectionDay >14 THEN 'Urgent'
                            WHEN @lastReinspectionDay > 0 THEN 'Critical'
                            WHEN @lastReinspectionDay <= 0 THEN 'Overdue'
                     END '" . $this->col++ . "'
                FROM
                    tbl_property AS p
                LEFT JOIN tbl_asset_class ac ON p.asset_class_id = ac.id
                LEFT JOIN tbl_asset_class at ON p.asset_type_id = at.id
                LEFT JOIN tbl_tenure_type tt ON p.tenure_type_id = tt.id
                LEFT JOIN (SELECT MIN(date) added, property_id FROM compliance_documents WHERE property_id > 0 AND `is_external_ms` = 1 GROUP BY property_id) as h ON p.id = h.property_id
                LEFT JOIN ( SELECT reference, sd.completed_date, sd.surveying_finish_date, sd.started_date, property_id FROM tbl_survey LEFT JOIN tbl_survey_date sd ON tbl_survey.id = sd.survey_id  WHERE survey_type = 1 AND decommissioned = 0 AND `status`= 5 GROUP BY property_id) AS sM ON p.id = sM.property_id
                LEFT JOIN ( SELECT MAX(tbl_survey_date.surveying_finish_date) surveying_finish_date, property_id FROM tbl_survey LEFT JOIN tbl_survey_date ON tbl_survey.id = tbl_survey_date.survey_id WHERE survey_type = 3 AND decommissioned = 0 AND `status`= 5 GROUP BY property_id) AS s ON p.id= s.property_id
                LEFT JOIN (
                        SELECT se.id, se.reference, se.`status`, se.`survey_type`, se.property_id, sd.started_date, sd.completed_date, sd.surveying_finish_date
                        FROM tbl_survey se
                            LEFT JOIN tbl_survey_date sd ON se.id = sd.survey_id
                            LEFT JOIN tbl_survey s2 ON (
                            se.property_id = s2.property_id
                            AND se.id < s2.id
                            AND s2.survey_type IN ( 3 )
                            AND s2.decommissioned = 0 ) WHERE s2.id IS NULL AND se.decommissioned = 0 AND se.survey_type IN ( 3 ))
                        as sL ON p.id = sL.property_id
                LEFT JOIN tbl_survey_type sT ON sT.id = sL.survey_type
                LEFT JOIN (SELECT COUNT(DISTINCT id) countACM, property_id FROM tbl_items WHERE survey_id = 0 AND state != 1 AND decommissioned = 0 GROUP BY property_id) AS i ON i.property_id= p.id
                LEFT JOIN tbl_zones z ON p.zone_id = z.id
                LEFT JOIN tbl_property_survey ps ON p.id = ps.property_id
                LEFT JOIN tbl_property_programme_type AS pRiskType ON ps.programme_type = pRiskType.id
                JOIN (SELECT ppt.property_id from property_property_type ppt
                            JOIN tbl_property_type pt ON ppt.property_type_id = pt.id
                            WHERE pt.ms_level = 1
                            GROUP BY ppt.property_id) AS r ON p.id = r.property_id
                WHERE
                    p.decommissioned = 0
                AND z.id > 0
                AND i.countACM > 0
                GROUP BY p.id";

        $data = DB::select($sql);
        return $data;
   }

   public function userSummary($data) {

        // CHECK GROUP
        $auditCondition = $auditTimePicked = "";
        if ($data['user-group'] == "all" || ($data['user-group'] == "organisation" && $data['select_organisation'] == "all")) {
            $auditCondition ="";
        } elseif ($data['user-group'] == "organisation" && $data['select_organisation'] != "all") {
            if (\Auth::user()->clients->client_type == 0){
                $auditCondition .= " AND tbl_users.client_id = ".$data['select_organisation'];
            }
            if ($data['department'] != "all") {
                if($data['department'] == 5 && $data['department_child'] != "all" ){//support contractor
                    $auditCondition .= " AND tbl_users.department_id = ".$data['department_child'];
                } else {
                    $auditCondition .= " AND tbl_users.department_id = ".$data['department'];
                }
            }
        } elseif ($data['user-group'] == "user") {
            $auditCondition .= " AND tbl_audit_trail.user_id =". $data['user_id'];
        }
        // CHECK ACTION
        if ($data['user-action'] != "all") {
            $auditCondition .= " AND tbl_audit_trail.`action_type` = '".$data['user-action'] . "'";
        }

        $user_start_time = \CommonHelpers::dmytounixorblank($data['user-start-date']);
        $user_finish_time = \CommonHelpers::toTimeStamp($data['user-finish-date']) + 86400;
        $auditTimePicked = " WHERE (tbl_audit_trail.`date` >= $user_start_time && tbl_audit_trail.`date` <= $user_finish_time)  ";
        $groupCondition = isset($data['select-property-group']) && is_numeric($data['select-property-group'])
                            ? " AND p.zone_id =".$data['select-property-group'] : "";

        $sql = "SELECT
                tbl_audit_trail.`shine_reference` 'auditReference' ,
                `object_reference` ,
                `action_type`,
                 user_id,
                `user_name`,
                TRIM(c.`name`) 'cname',
                tbl_audit_trail.department,
                FROM_UNIXTIME(`date`,\"%d/%m/%Y\") as time_1,
                FROM_UNIXTIME(`date`, '%H:%i') time_2,
                `ip`,
                TRIM(p.pblock) 'pblock',
                TRIM(p.name) 'pName',
                tbl_audit_trail.`comments`
            FROM `tbl_audit_trail`
            LEFT JOIN tbl_users ON tbl_audit_trail.user_id = tbl_users.id
            LEFT JOIN tbl_departments d ON tbl_users.department_id = d.id
            LEFT JOIN tbl_departments_contractor dc ON tbl_users.department_id = dc.id
            LEFT JOIN tbl_property p ON tbl_audit_trail.property_id = p.id
            LEFT JOIN tbl_clients c ON tbl_audit_trail.user_client_id = c.id
            $auditTimePicked $auditCondition $groupCondition
            ORDER BY `tbl_audit_trail`.`date` DESC LIMIT 0,20000";
        $results =  DB::select($sql);
        $tmp = [];
        if(count($results))
        {
            foreach($results as $result) {
                $department = $result->department;
                $department = explode(" --- ",$department);

                $result_tmp = [
                    'auditReference' => $result->auditReference,
                    'object_reference' => $result->object_reference,
                    'action_type' => $result->action_type,
                    'user_id' => $result->user_id,
                    'user_name' => $result->user_name,
                    'cname' => $result->cname,
                    'department1' => $department[0] ?? '',
                    'department2' => $department[1] ?? '',
                    'department3' => $department[2] ?? '',
                    'department4' => $department[3] ?? '',
                    'time_1' => $result->time_1,
                    'time_2' => $result->time_2,
                    'ip' => $result->ip,
                    'pblock' => $result->pblock,
                    'pName' => $result->pName,
                    'comments' => $result->comments
                ];
                $tmp[] = $result_tmp;
            }
        }
        return $tmp;
   }

   public function propertyInformation($request) {
        $export_type = $request->export_type;
        $condition = 'TRUE';
        $zone_ids = [];
        switch($export_type){
            case 'all':
                break;
            case 'service_area':
                if($request->service_area_type == '1'){
                    //Central Area Service Centre = 2
                    $parent_id = 2;
                } else if($request->service_area_type == '2'){
                    //North Area Service Centre = 3
                    $parent_id = 3;
                } else if($request->service_area_type == '3'){
                    //South Area Service Centre = 4
                    $parent_id = 4;
                } else if($request->service_area_type == '4'){
                    //West Area Service Centre = 5
                    $parent_id = 5;
                }
                if($parent_id){
                    $zone_ids = Zone::where('parent_id', $parent_id)->get()->pluck('id')->toArray();
                }
                break;
            case 'property_type':
                $addition_condition = $parent_id = '';
                if($request->service_area_type == '1'){
                    //Central Area Service Centre = 2
                    $parent_id = 2;
                } else if($request->service_area_type == '2'){
                    //North Area Service Centre = 3
                    $parent_id = 3;
                } else if($request->service_area_type == '3'){
                    //South Area Service Centre = 4
                    $parent_id = 4;
                } else if($request->service_area_type == '4'){
                    //West Area Service Centre = 5
                    $parent_id = 5;
                }
                if($parent_id){
                    $addition_condition = " AND parent_id = $parent_id";
                }
                $zone_name = '';
                if($request->property_type == '1'){
                    $zone_name = 'Corporate Properties';
                } else if($request->property_type == '2'){
                    $zone_name = 'Commercial Properties';
                } else if($request->property_type == '3'){
                    $zone_name = 'Housing – Communal';
                } else if($request->property_type == '4'){
                    $zone_name = 'Housing – Domestic';
                }
                if($zone_name){
                    $zone_ids = Zone::whereRaw("zone_name = '$zone_name' $addition_condition")->get()->pluck('id')->toArray();
                }
                break;
        }
        if(count($zone_ids)){
            $condition = 'zone_id IN('.implode(",", $zone_ids).')';
        }
        $properties =  Property::with('propertyInfo','propertySurvey','zone','communalArea','responsibility',
                                        'propertyType','serviceArea','assetType','tenureType','propertySurvey.propertyProgrammeType','parents'
                                    )->whereRaw($condition)->get();
        $data = [];
        foreach ($properties as $property) {
            $temp = [];
            if(isset($property->propertyType) && !$property->propertyType->isEmpty()){
                foreach ($property->propertyType as $p_risk_type){
                    $temp[] = $p_risk_type->description;
                }
            }
            if ($property->decommissioned == 0) {
                $status = "Live";
            } else {
                $status =  $property->decommissionedReason->description ?? "Property No Longer under Management";
            }
            if(env('APP_DOMAIN') == 'LBHC'){
                $tmp = [
                    'reference' => $property->reference,
                    'uprn' => $property->property_reference,
                    'block_code' => $property->pblock,
                    'parent_ref' => $property->parents->name ?? '',
                    'serviceArea' => $property->serviceArea->description ?? '',
                    'ward' => $property->ward->description ?? '',
                    'name' => $property->name ?? '',
                    'flat_number' => $property->propertyInfo->flat_number ?? '',
                    'building_name' => $property->propertyInfo->building_name ?? '',
                    'street_number' =>  $property->propertyInfo->street_number ?? '',
                    'street_name' => $property->propertyInfo->street_name ?? '' ,
                    'estate_code' => $property->estate_code ?? '',
                    'town' => $property->propertyInfo->town ?? '',
                    'county' => $property->propertyInfo->address5 ?? '',
                    'postcode' => $property->propertyInfo->postcode ?? '',
                    'status' => $status,
                    'assetType' =>  $property->assetType->description ?? '',
                    'tenureType' => $property->tenureType->description ?? '',
                    'communalArea' => $property->communalArea->description ?? '',
                    'responsibility' => $property->responsibility->description ?? '',
                    'zone' =>  $property->zone->zone_name ?? '',
                    'construction_age' => $property->propertySurvey->construction_age ?? '',
                    'access_type' => $property->propertySurvey->propertyProgrammeType->description ?? '',
                ];
            } else {
                $tmp = [
                    'reference' => $property->reference,
                    'uprn' => $property->property_reference,
                    'block_code' => $property->pblock,
                    'parent_ref' => $property->parents->name ?? '',
                    'serviceArea' => $property->serviceArea->description ?? '',
                    'name' => $property->name ?? '',
                    'flat_number' => $property->propertyInfo->flat_number ?? '',
                    'building_name' => $property->propertyInfo->building_name ?? '',
                    'street_number' =>  $property->propertyInfo->street_number ?? '',
                    'street_name' => $property->propertyInfo->street_name ?? '' ,
                    'estate_code' => $property->estate_code ?? '',
                    'town' => $property->propertyInfo->town ?? '',
                    'county' => $property->propertyInfo->address5 ?? '',
                    'postcode' => $property->propertyInfo->postcode ?? '',
                    'status' => $status,
                    'assetClass' =>  $property->assetClass->description ?? '',
                    'assetType' =>  $property->assetType->description ?? '',
                    'tenureType' => $property->tenureType->description ?? '',
                    'communalArea' => $property->communalArea->description ?? '',
                    'responsibility' => $property->responsibility->description ?? '',
                    'zone' =>  $property->zone->zone_name ?? '',
                    'construction_age' => $property->propertySurvey->construction_age ?? '',
                    'size_floors' => isset($property->propertySurvey->size_floors) ? ( $property->propertySurvey->size_floors == 'Other' ? $property->propertySurvey->size_floors_other : $property->propertySurvey->size_floors ) : '',
                    'access_type' => $property->propertySurvey->propertyProgrammeType->description ?? '',
                ];
            }

            $data[] = $tmp;
        }
        return $data;
    }

    public function propertyInfoTemplate() {
        $properties =  Property::with('propertyInfo','propertySurvey','zone', 'propertyType')->get();
        $data = [];
        foreach ($properties as $property) {
            $temp = [];
            if(isset($property->propertyType) && !$property->propertyType->isEmpty()){
                foreach ($property->propertyType as $p_risk_type){
                    $temp[] = $p_risk_type->description;
                }
            }
            if ($property->decommissioned == 0) {
                $status = "Live";
            } else {
                $status =  $property->decommissionedReason->description ?? "Property No Longer under Management";
            }
            $tmp = [
                'reference' => $property->reference,
                'block_code' => $property->pblock,
                'name' => $property->name,
                'group' => $property->zone->zone_name ?? '',
                'post_code' => $property->propertyInfo->post_code ?? '',
                'status' => $status,
                'risk_type' =>  count($temp) ? implode(",", $temp) : "",
                'access_type' => \CommonHelpers::getProgrammeType( $property->propertySurvey->programme_type  ?? null , $property->propertySurvey->programme_type_other  ?? null ),
                'construction_age' => $property->propertySurvey->construction_age ?? null,
                'construction_type' => $property->propertySurvey->construction_type ?? null,
                'size_floors' => \CommonHelpers::getSurveyPropertyInfoText($property->propertySurvey->size_floors ?? null),
                'size_staircases' => \CommonHelpers::getSurveyPropertyInfoText($property->propertySurvey->size_staircases ?? null),
                'size_lifts' => \CommonHelpers::getSurveyPropertyInfoText($property->propertySurvey->size_lifts ?? null)
            ];

            $data[] = $tmp;
        }
        return $data;
    }

    public function userCS() {
        $sql = "SELECT
                IF(u.id > 0, CONCAT(u.first_name, ' ', u.last_name), '') full_name,
                u.shine_reference,
                IF(u.is_admin < 0, 'Administrator', 'Basic User') type,
                IF(is_site_operative = 1, 'Yes', 'No') is_site_operative,
                IF(u.is_locked != 0, 'Locked', 'Unlocked') is_locked,
                TRIM(uc.job_title) job_title,
                TRIM(ro.name) role,
                uc.telephone,
                uc.mobile,
                TRIM(u.email) email,
                TRIM(c.name) name,
                c.reference ,
                IF(u.client_id = 1
                , CONCAT(IF(d4.name IS NOT NULL,CONCAT(d4.name, '---'),''),IF(d3.name IS NOT NULL,CONCAT(d3.name, '---'),''),
                    IF(d2.name IS NOT NULL,CONCAT(d2.name, '---'),''), IF(d1.name IS NOT NULL,CONCAT(d1.name, '---'),''))
                , CONCAT(IF(dc4.name IS NOT NULL,CONCAT(dc4.name, '---'),''),IF(dc3.name IS NOT NULL,CONCAT(dc3.name, '---'),''),
                    IF(dc2.name IS NOT NULL,CONCAT(dc2.name, '---'),''), IF(dc1.name IS NOT NULL,CONCAT(dc1.name, '---'),''))
                ) department,
                IF(l.logtime > 0, FROM_UNIXTIME(MAX(l.logtime), '%d/%m/%Y %h:%i:%s'), 'N/A') date_log,
                u.notes AS note,
                CASE
                    WHEN (u.last_asbestos_training IS NOT NULL && u.last_asbestos_training > 0)
                        THEN
                            CASE
                                WHEN ( UNIX_TIMESTAMP() - u.last_asbestos_training <= 60*60*24*365)
                                    THEN FROM_UNIXTIME(u.last_asbestos_training, '%d/%m/%Y')
                                    ELSE 'In Progress'
                                END
                        ELSE
                            CASE
                                WHEN (max_date IS NOT NULL && max_date > 0)
                                    THEN 'In Progress'
                                    ELSE 'Not Taken'
                                END
                    END last_asbestos_training,
                IF(u.last_site_operative_training > 0, FROM_UNIXTIME(u.last_site_operative_training, '%d/%m/%Y %h:%i:%s'), 'N/A') last_site_operative_training1,
                IF(u.last_site_operative_training > 0, FROM_UNIXTIME(u.last_site_operative_training + (365 * 86400), '%d/%m/%Y %h:%i:%s'), 'N/A') last_site_operative_training2,
                IF(u.last_site_operative_training > 0, FLOOR((u.last_site_operative_training + (365 * 86400) - UNIX_TIMESTAMP()) / 86400), 'N/A') last_site_operative_training3,
                IF(u.last_project_training > 0, FROM_UNIXTIME(u.last_project_training, '%d/%m/%Y %h:%i:%s'), 'N/A') last_project_training1,
                IF(u.last_project_training > 0, FROM_UNIXTIME(u.last_project_training + (365 * 86400), '%d/%m/%Y %h:%i:%s'), 'N/A') last_project_training2,
                IF(u.last_project_training > 0, FLOOR((u.last_project_training + (365 * 86400) - UNIX_TIMESTAMP()) / 86400), 'N/A') last_project_training3
            FROM tbl_users AS u
            LEFT JOIN tbl_role AS ro ON ro.id = u.role
            LEFT JOIN tbl_departments AS d1 ON u.department_id = d1.id
            LEFT JOIN tbl_departments AS d2 ON d1.parent_id = d2.id
            LEFT JOIN tbl_departments AS d3 ON d2.parent_id = d3.id
            LEFT JOIN tbl_departments AS d4 ON d3.parent_id = d4.id
            LEFT JOIN tbl_contact AS uc ON uc.user_id = u.id
            LEFT JOIN tbl_departments_contractor dc1 ON u.department_id = dc1.id
            LEFT JOIN tbl_departments_contractor dc2 ON dc1.parent_id = dc2.id
            LEFT JOIN tbl_departments_contractor dc3 ON dc2.parent_id = dc3.id
            LEFT JOIN tbl_departments_contractor dc4 ON dc3.parent_id = dc4.id
            LEFT JOIN tbl_clients AS c ON u.client_id = c.id
            LEFT JOIN tbl_log_login l ON u.id = l.user_id AND l.success = 1
            LEFT JOIN (SELECT MAX(created_date) AS max_date, user_id FROM `tbl_log_elearning` le
                        WHERE le.type = 2 AND le.status_code = '200'
                        GROUP BY le.user_id) AS le ON le.user_id = u.id
            WHERE u.id > 0
            GROUP BY u.id";

        $results =  DB::select($sql);

        $tmp = [];
        if(count($results))
        {
            foreach($results as $result) {
                $department = $result->department;
                $department = explode("---",$department);

                $result_tmp = [
                    'full_name' => $result->full_name,
                    'shine_reference' => $result->shine_reference,
                    'type' => $result->type,
                    'is_site_operative' => $result->is_site_operative,
                    'is_locked' => $result->is_locked,
                    'job_title' => $result->job_title,
                    'role' => $result->role,
                    'telephone' => $result->telephone,
                    'mobile' => $result->mobile,
                    'email' => $result->email,
                    'name' => $result->name,
                    'reference' => $result->reference,
                    'department1' => $department[0] ?? '',
                    'department2' => $department[1] ?? '',
                    'department3' => $department[2] ?? '',
                    'department4' => $department[3] ?? '',
                    'date_log' => $result->date_log,
                    'note' => $result->note,
                    'last_asbestos_training' => $result->last_asbestos_training,
                    'last_site_operative_training1' => $result->last_site_operative_training1,
                    'last_site_operative_training2' => $result->last_site_operative_training2,
                    'last_site_operative_training3' => $result->last_site_operative_training3,
                    'last_project_training1' => $result->last_project_training1,
                    'last_project_training2' => $result->last_project_training2,
                    'last_project_training3' => $result->last_project_training3
                ];
                $tmp[] = $result_tmp;
            }
        }
        return $tmp;
    }

    public function sampleSummary($property_id) {
        $sql = "SELECT
                p.name '" . $this->col++ . "',
                ac.`description` '" . $this->col++ . "',
                at.`description` '" . $this->col++ . "',
                tt.`description` '" . $this->col++ . "',
                a.area_reference '" . $this->col++ . "',
                a.description '" . $this->col++ . "',
                l.location_reference '" . $this->col++ . "',
                l.description '" . $this->col++ . "',
                i.reference '" . $this->col++ . "',
                i.id '" . $this->col++ . "',
                s.description '" . $this->col++ . "',
                av.asbestos_type as asbestos_type,
                FORMAT(i.total_mas_risk, 0) '" . $this->col++ . "',
                FORMAT(i.total_pas_risk, 0) '" . $this->col++ . "',
                FORMAT(i.total_risk, 0) '" . $this->col++ . "'
            FROM tbl_items i
            LEFT JOIN tbl_location l ON i.location_id = l.id
            LEFT JOIN tbl_area a ON i.area_id = a.id
            LEFT JOIN tbl_property p ON i.property_id = p.id
            LEFT JOIN tbl_asset_class ac ON p.asset_class_id = ac.id
            LEFT JOIN tbl_asset_class at ON p.asset_type_id = at.id
            LEFT JOIN tbl_tenure_type tt ON p.tenure_type_id = tt.id
            LEFT JOIN tbl_item_sample_id_value vs ON i.id = vs.item_id
            LEFT JOIN tbl_item_asbestos_type_view av ON i.id = av.item_id
            LEFT JOIN tbl_sample s ON vs.dropdown_data_item_id = s.id
            WHERE s.original_item_id = i.record_id
            AND i.decommissioned = 0
            AND i.property_id = $property_id
            GROUP BY i.record_id";
        $results = DB::select($sql);
        foreach ($results as $key => $result) {
            $result->asbestos_type = \CommonHelpers::getAsbestosTypeText($result->asbestos_type);
        }

        return $results;
    }

    public function duplicationChecker($type) {
        switch ($type) {
            case 'Room':
                $title = [
                    'Property Name', 'Area/Floor AF Number', 'Area/Floor Reference', 'Area/Floor Description', 'Room/Location RL Number', 'Room/Location Reference', 'Room/Location Description',' Voids', 'Construction', "ACM's"
                ];
                $sql = "SELECT
                    p.name '" . $this->col++ . "',
                    a.reference '" . $this->col++ . "',
                    a.area_reference '" . $this->col++ . "',
                    a.description '" . $this->col++ . "',
                    l.reference '" . $this->col++ . "',
                    l.location_reference '" . $this->col++ . "',
                    l.description '" . $this->col++ . "',

                    IF(CONCAT( IFNULL(lv.ceiling, ''),
                                IFNULL(lv.floor, ''),
                                IFNULL(lv.cavities, ''),
                                IFNULL(lv.risers, ''),
                                IFNULL(lv.ducting, ''),
                                IFNULL(lv.boxing, ''),
                                IFNULL(lv.pipework, '')
                                ) = '', 'No', 'Yes') 'Voids',
                    IF(CONCAT(IFNULL(lc.ceiling, ''),
                                IFNULL(lc.floor, ''),
                                IFNULL(lc.walls, ''),
                                IFNULL(lc.doors, ''),
                                IFNULL(lc.windows, '')
                                ) = '', 'No', 'Yes') 'Construction',

                    IF(i.id > 0, 'Yes', 'No') '" . $this->col++ . "'
                    from tbl_location l
                    LEFT JOIN (SELECT location_reference, property_id FROM tbl_location where survey_id = 0 AND decommissioned = 0 GROUP BY property_id, location_reference HAVING COUNT(DISTINCT ID) > 1) ldup ON ldup.property_id = l.property_id AND l.location_reference = ldup.location_reference
                    LEFT JOIN (SELECT id, location_id FROM tbl_items WHERE survey_id = 0 AND decommissioned = 0 GROUP BY location_id) i ON l.id = i.location_id
                    LEFT JOIN tbl_location_void lv ON l.id = lv.location_id
                    LEFT JOIN tbl_location_construction lc ON l.id = lc.location_id
                    LEFT JOIN tbl_area a ON l.area_id = a.id
                    LEFT JOIN tbl_property p ON l.property_id = p.id
                    WHERE
                    l.decommissioned = 0
                    and l.survey_id = 0
                    AND l.location_reference = ldup.location_reference AND l.property_id = ldup.property_id
                    ORDER BY p.name, l.location_reference";
                break;

            case 'Floor':
                $title = [
                    'Property Name', 'Area/Floor AF Number', 'Area/Floor Reference', 'Area/Floor Description' , "ACM's"
                ];
                $sql = "SELECT
                            p.name '" . $this->col++ . "',
                            a.reference '" . $this->col++ . "',
                            a.area_reference '" . $this->col++ . "',
                            a.description '" . $this->col++ . "',
                        IF
                            ( i.id > 0, 'Yes', 'No' ) '" . $this->col++ . "'
                        FROM
                            tbl_area a
                            LEFT JOIN (
                        SELECT
                            reference,
                            property_id,
                            area_reference,
                            description
                        FROM
                            tbl_area
                        WHERE
                            survey_id = 0
                            AND decommissioned = 0
                        GROUP BY
                            property_id,
                            area_reference
                        HAVING
                            COUNT( DISTINCT id ) > 1
                            ) adup ON adup.property_id = a.property_id
                            AND a.area_reference = adup.area_reference AND a.description = adup.description
                            LEFT JOIN ( SELECT id, location_id, area_id FROM tbl_items WHERE survey_id = 0 AND decommissioned = 0 GROUP BY location_id ) i ON a.id = i.area_id
                            LEFT JOIN tbl_property p ON a.property_id = p.id
                        WHERE
                            a.decommissioned = 0
                            AND a.survey_id = 0
                            AND a.area_reference = adup.area_reference
                            AND a.property_id = adup.property_id
                        ORDER BY
                            p.name,
                            a.area_reference";
                break;

            default:
                $title = "";
                $sql = "select * from tbl_area limit 1";
                break;
        }

        $results = DB::select($sql);
        return $data = [
            'title' => $title,
            'data' => $results,
        ];
    }
    public function orchardSummary($type) {
        switch ($type) {
            case 1://orchard
                $title = [ 'Property Sequence Number', 'Property Class Code', 'Property Address Line 1',
                    'Property Address Line 2', 'Property Address Line 3', 'Property Address Line 4',
                    'Property Postcode', 'Repair Responsibility', 'Service Area',
                    'Service Area Description', 'Tenure Type', 'Tenure Type Description', 'Right to Buy', 'Property User Code', 'Void', 'Display Address'];
                $sql = "SELECT
                            o.property_reference_id,
                            o.property_class_code,
                            o.property_address1,
                            o.property_address2,
                            o.property_address3,
                            o.property_address4,
                            o.property_post_code,
                            o.property_responsibility,
                            o.property_service_area_code,
                            o.property_service_area_description,
                            o.property_tenure_type_code,
                            o.property_tenure_type_description,
                            o.property_right_buy_code,
                            o.property_user_code,
                            o.property_void,
                            o.orchard_display_address
                        FROM tbl_orchard o
                        WHERE o.status = 2";
                break;
            case 2://shine
                $title = [
                    'PL Reference', 'Property Reference','Property Type','Property Address Line 1','Property Address Line 2','Property Address Line 3','Property Address Line 4','Postcode','Responsibility','Demolished'
                ];
                $sql = "SELECT
                            p.reference,
                            p.property_reference,
                            IF(asset_type_id > 0,
                                IF(asset_type_id = 10 OR asset_type_id = 11 OR asset_type_id = 12 OR asset_type_id = 13 OR asset_type_id = 14 OR asset_type_id = 15 OR asset_type_id = 16 OR asset_type_id = 17,
                                 'B',
                                 IF(asset_type_id = 19,'F',
                                  IF(asset_type_id = 20,'H',
                                   IF(asset_type_id = 52,'C',
                                    IF(asset_type_id = 23,'G','P'))))
                                 ),
                             ''),
                            pi.flat_number,
                            pi.building_name,
                            pi.street_number,
                            pi.street_name,
                            pi.postcode,
                            dr.`description`,
                            IF(p.decommissioned = 1, 'Property No Longer under Management', 'Live')
                        FROM tbl_property AS p
                        LEFT JOIN tbl_orchard o ON p.id = o.property_id
                        LEFT JOIN tbl_responsibility dr ON dr.id = p.responsibility_id
                        LEFT JOIN tbl_property_info pi ON pi.property_id = p.id
                        WHERE o.status = 1";
                break;
            default://3 common
                $title = [
                    'Property Reference number ', 'PL Number', 'Responsibility', '3rd Party Repair Responsibility', 'Property Type', 'Flat Number', 'Building Name', 'Street Number', 'Street Name', 'Town', 'County','Postcode'
                ];
                $sql = "SELECT
                            IF(p.property_reference != '' AND p.property_reference IS NOT NULL, p.property_reference, 'Missing'),
                                            p.reference,
                                            IF(dr.`description` IS NOT NULL, dr.`description`, 'Missing'),
                                            o.property_responsibility,
                                            IF(da.`description` IS NOT NULL, da.`description`, 'Missing'),
                                            pi.`flat_number`,
                                            pi.`building_name`,
                                            pi.`street_number`,
                                            pi.`street_name`,
                                            pi.`town`,
                                            pi.`address5`,
                                            pi.postcode
                        FROM tbl_orchard o
                        LEFT JOIN tbl_property AS p ON p.id = o.property_id
                        LEFT JOIN tbl_responsibility dr ON dr.id = p.responsibility_id
                        LEFT JOIN tbl_asset_class da ON da.id = p.asset_type_id
                        LEFT JOIN tbl_property_info pi ON pi.property_id = p.id
                        WHERE o.status = 3
                            AND (
                                    (dr.ID = 1916 AND o.property_responsibility = 'FALSE')
                                    OR (dr.ID = 1917 AND o.property_responsibility = 'FALSE')
                                    OR (dr.ID = 1918 AND o.property_responsibility = 'FALSE')
                                    OR (dr.ID = 1919 AND o.property_responsibility = 'TRUE')
                                    OR (dr.ID = 1920 AND o.property_responsibility = 'FALSE')
                                )";
                break;
        }
        $results = DB::select($sql);
        return $data = [
            'title' => $title,
            'data' => $results,
        ];
    }

    public function photographySize() {
        $data = [];
        $sql = "SELECT p.id,
        p.property_reference,
        IF(sds.addedDate > 0, FROM_UNIXTIME(sds.addedDate,\"%d/%m/%Y\"), '') AS addedDate,
        IF(us.id > 0, CONCAT(us.first_name, ' ', us.last_name), '') AS user_name,
        cl.`name` AS 'depart',
        sds.`size`,
        sds.`file_name`
        FROM tbl_property p
        LEFT JOIN tbl_shine_document_storage sds ON p.id = sds.object_id
        LEFT JOIN tbl_users us ON us.id = sds.addedBy
        LEFT JOIN tbl_clients cl ON cl.id = p.client_id
        WHERE sds.type = 'im'";
        $property_img =  DB::select($sql);
        $property_img = collect($property_img)->map(function($x){ return (array) $x; })->toArray();
        if(count($property_img) > 0){
            foreach($property_img as $p_img){
                if($p_img['file_name']){
                    $data[] = [
                        $this->isa_convert_bytes_to_specified($p_img['size'], 'M', 2),
                        $p_img['file_name'],
                        'Register',
                        'Property',
                        $p_img['property_reference'],
                        $p_img['addedDate'],
                        $p_img['user_name'],
                        $p_img['depart']
                    ];
                }
                $site_id = $p_img['id'];
                if($site_id) {
                    $sql_property_survey_img = "SELECT s.id,
                    s.reference,
                    IF(sds.addedDate > 0, FROM_UNIXTIME(sds.addedDate,\"%d/%m/%Y\"), '') AS addedDate,
                    IF(us.id > 0, CONCAT(us.first_name, ' ', us.last_name), '') AS user_name,
                    cl.`name` AS 'depart',
                    sds.`size`,
                    sds.`file_name`
                    FROM tbl_property s
                    LEFT JOIN tbl_survey ss ON s.id = ss.property_id
                    LEFT JOIN tbl_shine_document_storage sds ON ss.id = sds.object_id
                    LEFT JOIN tbl_users us ON us.id = sds.addedBy
                    LEFT JOIN tbl_clients cl ON cl.id = s.client_id
                    WHERE s.id = $site_id AND sds.type = 'psm'";
                    $property_survey_img = DB::select($sql_property_survey_img);
                    $property_survey_img = collect($property_survey_img)->map(function($x){ return (array) $x; })->toArray();
                    foreach ($property_survey_img as $psi) {
                        $data[] = [
                            $this->isa_convert_bytes_to_specified($psi['size'], 'M', 2),
                            $psi['file_name'],
                            'Survey',
                            'Property',
                            $psi['reference'],
                            $psi['addedDate'],
                            $psi['user_name'],
                            $psi['depart']
                        ];
                    }
                    $sql_location_img = "SELECT
                    l.ID as location_id,
                    l.survey_id,
                    l.reference,
                    IF(sds.addedDate > 0, FROM_UNIXTIME(sds.addedDate,\"%d/%m/%Y\"), '') AS addedDate,
                    IF(us.id > 0, CONCAT(us.first_name, ' ', us.last_name), '') AS user_name,
                    cl.`name` AS 'depart',
                    sds.`size`,
                    sds.`file_name`
                    FROM tbl_location l
                    LEFT JOIN tbl_shine_document_storage sds ON l.ID = sds.object_id
                    LEFT JOIN tbl_property s ON l.property_id = s.id
                    LEFT JOIN tbl_users us ON us.id = sds.addedBy
                    LEFT JOIN tbl_clients cl ON cl.id = s.client_id
                    WHERE l.property_id = $site_id AND sds.type = 'lm'";
                    $location_img = DB::select($sql_location_img);
                    $location_img = collect($location_img)->map(function($x){ return (array) $x; })->toArray();
                    if (count($location_img)) {
                        foreach ($location_img as $l_img) {
                            if($l_img['file_name']){
                                $data[] = [
                                    $this->isa_convert_bytes_to_specified($l_img['size'], 'M', 2),
                                    $l_img['file_name'],
                                    $l_img['survey_id'] == 0 ? 'Register' : 'Survey',
                                    'Room/location',
                                    $l_img['reference'],
                                    $l_img['addedDate'],
                                    $l_img['user_name'],
                                    $l_img['depart']
                                ];
                            }
                            $location_id = $l_img['location_id'];
                            if($location_id){
                                $sql_item_img = "SELECT
                                i.id as item_id,
                                i.survey_id,
                                i.reference,
                                IF(sds.addedDate > 0, FROM_UNIXTIME(sds.addedDate,\"%d/%m/%Y\"), '') AS addedDate,
                                IF(us.id > 0, CONCAT(us.first_name, ' ', us.last_name), '') AS user_name,
                                cl.`name` AS 'depart',
                                sds.`size`,
                                sds.`file_name`,
                                sds.`type`
                                FROM tbl_items i
                                LEFT JOIN tbl_shine_document_storage sds ON i.id = sds.object_id
                                LEFT JOIN tbl_property s ON i.property_id = s.id
                                LEFT JOIN tbl_users us ON us.id = sds.addedBy
                                LEFT JOIN tbl_clients cl ON cl.id = s.client_id
                                WHERE i.location_id = $location_id AND sds.path IS NOT NULL AND sds.type IN ('ipl','ip')";
                                $item_img = DB::select($sql_item_img);
                                $item_img = collect($item_img)->map(function($x){ return (array) $x; })->toArray();
                                if (count($item_img)) {
                                    foreach ($item_img as $i_img) {
                                        if($i_img['file_name']){
                                            $data[] = [
                                                $this->isa_convert_bytes_to_specified($i_img['size'], 'M', 2),
                                                $i_img['file_name'],
                                                $i_img['survey_id'] == 0 ? 'Register' : 'Survey',
                                                $i_img['type'] == 'ipl' ? 'Item Location' : 'Item Item',
                                                $i_img['reference'],
                                                $i_img['addedDate'],
                                                $i_img['user_name'],
                                                $i_img['depart']
                                            ];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $data;
    }

    public function projectDocument() {
        $sql = "SELECT
                ref,
                type,
                doc_name,
                last_revision,
                CASE `status`
                        WHEN 1 THEN
                                'New Survey'
                        WHEN 2 THEN
                                'Locked'
                        WHEN 3 THEN
                                'Ready for QA'
                        WHEN 4 THEN
                                'Published'
                        WHEN 5 THEN
                                'Completed'
                        WHEN 6 THEN
                                'Rejected'
                        WHEN 7 THEN
                                'Sent back'
                        ELSE
                                ''
                        END ,
                survey_ref,
                client_name,
                property_ref,
                pblock,
                property_name,
                group_name,
                access_class,
                access_type,
                tenure_type
            FROM (
                (SELECT
                    sc.reference AS ref,
                    'Survey Sample Certificate' AS type,
                    sc.sample_reference AS doc_name,
                    IF(sc.updated_date > 0,FROM_UNIXTIME(sc.updated_date,\"%d/%m/%Y\"),'') AS last_revision,
                    s.`status`,
                    s.reference AS survey_ref,
                    cl.`name` AS client_name,
                    ss.`reference`AS property_ref,
                    ss.`pblock`,
                    TRIM(ss.`name`) AS property_name,
                    TRIM(z.zone_name) AS group_name,
                    ac.`description` AS access_class,
                    at.`description` AS access_type,
                    tt.`description` AS tenure_type
                FROM tbl_survey s
                JOIN tbl_sample_certificates sc ON sc.survey_id = s.id
                LEFT JOIN tbl_clients cl ON cl.id = s.client_id
                LEFT JOIN tbl_property ss ON s.property_id = ss.id
                LEFT JOIN tbl_asset_class ac ON ss.asset_class_id = ac.id
                LEFT JOIN tbl_asset_class at ON ss.asset_type_id = at.id
                LEFT JOIN tbl_tenure_type tt ON ss.tenure_type_id = tt.id
                LEFT JOIN tbl_zones z ON ss.zone_id = z.id)
                UNION
                (
                SELECT
                    atc.reference AS ref,
                    'Survey Air Test Certificate' AS type,
                    atc.air_test_reference AS doc_name,
                    IF(atc.updated_date > 0,FROM_UNIXTIME(atc.updated_date,\"%d/%m/%Y\"),'') AS last_revision,
                    s.`status`,
                    s.reference AS survey_ref,
                    cl.`name` AS client_name,
                    ss.`reference`AS property_ref,
                    ss.`pblock`,
                    TRIM(ss.`name`) AS property_name,
                    TRIM(z.zone_name) AS group_name,
                    ac.`description` AS access_class,
                    at.`description` AS access_type,
                    tt.`description` AS tenure_type
                FROM tbl_survey s
                JOIN tbl_air_test_certificates atc ON atc.survey_id = s.id
                LEFT JOIN tbl_clients cl ON cl.id = s.client_id
                LEFT JOIN tbl_property ss ON s.property_id = ss.id
                LEFT JOIN tbl_asset_class ac ON ss.asset_class_id = ac.id
                LEFT JOIN tbl_asset_class at ON ss.asset_type_id = at.id
                LEFT JOIN tbl_tenure_type tt ON ss.tenure_type_id = tt.id
                LEFT JOIN tbl_zones z ON ss.zone_id = z.id)
                UNION
                (SELECT
                    sp.reference AS ref,
                    'Survey Plan' AS type,
                    sp.name AS doc_name,
                    IF(sp.added > 0,FROM_UNIXTIME(sp.added,\"%d/%m/%Y\"),'') AS last_revision,
                    s.`status`,
                    s.reference AS survey_ref,
                    cl.`name` AS client_name,
                    ss.`property_reference`AS property_ref,
                    ss.`pblock`,
                    TRIM(ss.`name`) AS property_name,
                    TRIM(z.zone_name) AS group_name,
                    ac.`description` AS access_class,
                    at.`description` AS access_type,
                    tt.`description` AS tenure_type
                FROM tbl_survey s
                JOIN tbl_siteplan_documents sp ON sp.survey_id = s.id
                LEFT JOIN tbl_clients cl ON cl.id = s.client_id
                LEFT JOIN tbl_property ss ON s.property_id = ss.id
                LEFT JOIN tbl_asset_class ac ON ss.asset_class_id = ac.id
                LEFT JOIN tbl_asset_class at ON ss.asset_type_id = at.id
                LEFT JOIN tbl_tenure_type tt ON ss.tenure_type_id = tt.id
                LEFT JOIN tbl_zones z ON ss.zone_id = z.id)
            ) AS temp ORDER BY survey_ref, type";

            $data =  DB::select($sql);
            return $data;
    }

    public function santiaSampleSummary($survey_id) {
        if ($survey_id == 0) {
            return [];
        }
        $survey_samples = DB::select("SELECT
                                    sp.description as reference,
                                    ipd.product_debris as product_debris,
                                    l.location_reference AS location_reference,
                                    l.description AS location_description
                                FROM
                                    tbl_items AS i
                                    JOIN tbl_item_sample_id_value AS sv ON i.id = sv.item_id
                                    JOIN tbl_sample sp ON sp.id = sv.dropdown_data_item_id
                                    JOIN tbl_item_product_debris_view ipd ON ipd.item_id = sp.original_item_id
                                    JOIN tbl_location l ON i.location_id = l.id
                                WHERE
                                    i.survey_id = $survey_id
                                AND
                                    i.decommissioned = 0
                                AND
                                    sp.is_real = 1
                                GROUP BY
                                    sv.dropdown_data_item_id
                                 ");
        $data = [];
        foreach ($survey_samples as $survey_sample) {
            $tmp['sample_reference'] = $survey_sample->reference;
            $tmp['location_reference'] = $survey_sample->location_reference;
            $tmp['location_description'] = $survey_sample->location_description;
            $tmp['product_debris'] = \CommonHelpers::getProductDebisText($survey_sample->product_debris);
            $data[] =  $tmp;
        }

        return $data;
    }

    public function isa_convert_bytes_to_specified($bytes, $to, $decimal_places = 1) {
        $formulas = array(
            'K' => number_format($bytes / 1024, $decimal_places),
            'M' => number_format($bytes / 1048576, $decimal_places),
            'G' => number_format($bytes / 1073741824, $decimal_places)
        );
        return isset($formulas[$to]) ? $formulas[$to] : 0;
    }

    public function projectMetaData() {
            $sql = "SELECT
                    pj.reference '" . $this->col++ . "',
                    TRIM(p.`name`) '" . $this->col++ . "',
                    z.`zone_name` '" . $this->col++ . "',
                    ac.`description` '" . $this->col++ . "',
                    at.`description` '" . $this->col++ . "',
                    tt.`description` '" . $this->col++ . "',
                    CASE `project_type`
                        WHEN 1 THEN
                                'Survey Only'
                        WHEN 2 THEN
                                'Remediation/Removal'
                        WHEN 3 THEN
                                'Demolition'
                        WHEN 4 THEN
                                'Analytical'
                        ELSE
                                ''
                        END ,
                    IF(pj.date > 0, FROM_UNIXTIME(pj.date,\"%d/%m/%Y\"), '') '" . $this->col++ . "',
                    IF(u.id > 0, CONCAT(u.first_name, ' ', u.last_name), '') '" . $this->col++ . "'

                    from tbl_project as pj
                    LEFT JOIN tbl_property as p on p.id = pj.property_id
                    LEFT JOIN tbl_asset_class ac ON p.asset_class_id = ac.id
                    LEFT JOIN tbl_asset_class at ON p.asset_type_id = at.id
                    LEFT JOIN tbl_tenure_type tt ON p.tenure_type_id = tt.id
                    LEFT JOIN tbl_zones z ON p.zone_id = z.id
                    LEFT JOIN tbl_users u ON pj.created_by = u.id
                    WHERE p.decommissioned = 0 and p.zone_id > 0
                    ORDER BY pj.date DESC";
        $data = DB::select($sql);
        return $data;
    }

    public function appUserSummary($data)
    {
       $sql = "SELECT
                concat('AAR' ,taat.`id`) 'auditReference' ,
                case
                    when taat.type = 1 then ts.reference
                    when taat.type = 2 then ''
                end object_reference,
                taaa.action_type,
                 user_id,
                concat(tu.first_name, ' ', tu.last_name) username,
                TRIM(c.`name`) 'cname',
                FROM_UNIXTIME(`timestamp`,'%d/%m/%Y') date,
                FROM_UNIXTIME(`timestamp`, '%H:%i') time,
                `ip`,
                device_id,
                device_soft_version,
                app_version,
               case
                    when taat.type = 1 then TRIM(ps.pblock)
                    when taat.type = 2 then TRIM(pa.pblock)
                end pblock,
               case
                    when taat.type = 1 then TRIM(ps.name)
                    when taat.type = 2 then TRIM(pa.name)
                end pName,
                taat.`comment`
            FROM `tbl_app_audit_trail` taat
            LEFT JOIN tbl_users tu ON taat.user_id = tu.id
            LEFT JOIN tbl_clients c ON tu.client_id = c.id
            LEFT JOIN tbl_app_audit_actions taaa ON taat.action_type = taaa.id
            LEFT JOIN tbl_survey ts on taat.type = 1 and ts.id = taat.object_id
            LEFT JOIN tbl_property ps ON ts.property_id = ps.id
            LEFT JOIN tbl_property pa ON ts.property_id = pa.id";

       if ($data['property_id'] == null && !isset($data['select_survey'])) {
           if ($data['select_organisation'] == 'all') {
               $sql .= ' WHERE taat.type = ' . $data['select_extension'] . ' ';
           } else {
               $sql .= ' WHERE c.id = ' . $data['select_organisation'] . 'taat.type = ' . $data['select_extension'] . ' ';
           }
       } else {
           if (isset($data['select_survey'])) {
               $sql .= ' WHERE taat.object_id = ' . $data['select_survey'] . ' AND taat.type = ' . $data['select_extension'] . ' ';
           } else {
               $sql .= ' WHERE taat.property_id = ' . $data['property_id'] . ' AND taat.type = ' . $data['select_extension'] . ' ';
           }
       }

       $sql .= " ORDER BY `taat`.`timestamp` DESC LIMIT 0,20000";
       return DB::select($sql);
    }

    public function getSurveyRejectionSummary() {
        $rejected_survey = \DB::select("
                    SELECT
                    s.reference,
                    c.name,
                    FROM_UNIXTIME(srt.date, \"%d/%m/%Y\"),
                    FROM_UNIXTIME(srt.date, \"%H:%i\"),
                    t.description,
                    srt.note
                    from tbl_survey_reject_history srt
                    JOIN tbl_survey s on s.id = srt.survey_id
                    LEFT JOIN tbl_rejection_type t on FIND_IN_SET(t.id,srt.rejection_type_ids)
                    LEFT JOIN tbl_clients c on c.id = srt.client_id
                    ORDER BY srt.id, t.id
            ");
        return $rejected_survey;
    }
}
