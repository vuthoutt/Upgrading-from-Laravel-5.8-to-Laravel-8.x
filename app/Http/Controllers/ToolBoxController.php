<?php

namespace App\Http\Controllers;

use App\Models\ShineCompliance\PropertyInfoDropdownData;
use Illuminate\Http\Request;
use App\Repositories\ClientRepository;
use App\Repositories\SurveyRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\DocumentRepository;
use App\Http\Request\ToolBox\RemoveRequest;
use App\Http\Request\ToolBox\UploadRequest;
use App\Models\Zone;
use App\Models\AdminTool;
use App\Models\AuditTrail;
use App\Models\LocationInfo;
use App\Models\LocationVoid;
use App\Models\LocationConstruction;
use App\Models\LocationComment;
use App\Models\ShineDocumentStorage;
use App\Models\AdminToolRollback;
use App\Models\PropertyDropdown;
use App\Models\DropdownDataProperty;
use App\Models\Property;
use App\Models\Area;
use App\Models\Location;
use App\Models\Item;
use App\Models\Client;
use App\Models\Role;
use App\Models\AssetClass;
use App\Models\CommunalArea;
use App\Models\PropertyInfo;
use App\Models\PropertyProgrammeType;
use App\Models\PropertySurvey;
use App\Models\Responsibility;
use App\Models\ServiceArea;
use App\Models\Department;
use App\Models\DepartmentContractor;
use App\Models\TenureType;
use App\Models\Contact;
use App\Models\ShineCompliance\IncidentReportDocument;
use App\User;
use Exception;
use App\CustomClass\SimpleXLSX;
use App\Services\ShineCompliance\AdminToolService;

class ToolBoxController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $route;

    public function __construct(AdminToolService $adminToolService,ClientRepository $clientRepository, SurveyRepository $surveyRepository, ProjectRepository $projectRepository,DocumentRepository $documentRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->surveyRepository = $surveyRepository;
        $this->projectRepository = $projectRepository;
        $this->documentRepository = $documentRepository;
        $this->route = (object)[];
        $this->route->name  = \Route::currentRouteName();
        $this->adminToolService = $adminToolService;
    }

    /**
     * Show my organisation by id.
     *
     */
    public function index()
    {
        if (!\CommonHelpers::isSystemClient()) {
            abort(404);
        }
    }

    public function remove() {
        if (!\CommonHelpers::isSystemClient()) {
            abort(404);
        }
        return view('admin_tool.remove');
    }

    public function move() {
        if (!\CommonHelpers::isSystemClient()) {
            abort(404);
        }
        return view('admin_tool.move');
    }

    public function merge() {
        if (!\CommonHelpers::isSystemClient()) {
            abort(404);
        }
        return view('admin_tool.merge');
    }

    public function unlock() {
        if (!\CommonHelpers::isSystemClient()) {
            abort(404);
        }
        return view('admin_tool.unlock');
    }

    public function revert() {
        if (!\CommonHelpers::isSystemClient()) {
            abort(404);
        }
        return view('admin_tool.revert');
    }

    public function postRevert(Request $request) {
        try {
                \DB::beginTransaction();
                $survey_id =  $request->survey_search_old ?? null;
                $input = [
                    'description' => $request->description ?? '',
                    'reason' => $request->reason ?? ''
                ];

                $input = json_encode($input);
                $action = 'revertSurvey';
                //delete query
                $sql_unlock = [
                    "delete_area" => "DELETE FROM a1
                    USING tbl_area a1, tbl_area a2
                    WHERE a1.survey_id = 0 AND a1.record_id = a2.record_id AND a2.survey_id = $survey_id AND a2.id = a2.record_id;",
                    "delete_location" => "DELETE FROM a1
                    USING tbl_location a1, tbl_location a2
                    WHERE a1.survey_id = 0 AND a1.record_id = a2.record_id AND a2.survey_id = $survey_id AND a2.id = a2.record_id;",
                    "delete_item" => "DELETE FROM a1
                    USING tbl_items a1, tbl_items a2
                    WHERE a1.survey_id = 0 AND a1.record_id = a2.record_id AND a2.survey_id = $survey_id AND a2.id = a2.record_id;",
                    "unlock_area" => "UPDATE tbl_area set is_locked = 0 WHERE survey_id = $survey_id;",
                    "unlock_location" => "UPDATE tbl_location set is_locked = 0 WHERE survey_id = $survey_id;",
                    "unlock_item" => "UPDATE tbl_items set is_locked = 0 WHERE survey_id = $survey_id;",
                    "unlock_survey" => "UPDATE tbl_survey set is_locked = 0, `status` = 6 WHERE id = $survey_id;"
                ];
                $comment_audit = \Auth::user()->full_name  . " " . $request->description ?? '';
                \CommonHelpers::logAudit(ADMIN_TOOL_TYPE, 0, AUDIT_ACTION_REVERT_BACK, $request->type ?? '', 0 ,$comment_audit, 0 ,0);
                foreach($sql_unlock as $sql){
                    $removeGroup = \DB::select($sql);
                }
                $flag = true;

                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollBack();
                $error = $e->getMessage();
                $flag = false;
            }
            if ($flag) {
                $toolbox = AdminTool::create([
                    'action' => $action,
                    'input' => $input,
                    'result' => 'success',
                    'roll_back' => 0,
                    'created_by' => \Auth::user()->id
                ]);
                return redirect()->back()->with('msg', 'Revert Completed Survey Successfully!');
            } else {
                $toolbox = AdminTool::create([
                    'action' => $action,
                    'input' => $input,
                    'result' => $error,
                    'roll_back' => 0,
                    'created_by' => \Auth::user()->id
                ]);
                return redirect()->back()->with('err', 'Failed to Revert Completed Survey. Please try later later');
            }
    }

    public function postMove(Request $request) {

        $input = [
            'description' => $request->description ?? '',
            'reason' => $request->reason ?? ''
        ];
        $input = json_encode($input);

        $survey_old = $request->survey_old ?? null;
        $survey_new = $request->survey_new ?? null;
        $item_old = $request->item_old ?? null;
        $location_old = $request->location_old ?? null;
        $location_new = $request->location_new ?? null;
        $area_old = $request->area_old ?? null;
        $area_new = $request->area_new ?? null;
        $project_id = $request->project_old ?? null;
        $property_old = $request->property_old ?? null;
        $property_new = $request->property_new ?? null;
        try {
            \DB::beginTransaction();
            switch ($request->type) {
                case 'survey':
                    $action = 'moveSurvey';

                    $sql_move = [
                        "item" => "UPDATE `tbl_items` SET `property_id` = $property_new WHERE `survey_id` = $survey_old",
                        "location" => "UPDATE `tbl_location` SET `property_id` = $property_new WHERE `survey_id` = $survey_old",
                        "area" => "UPDATE `tbl_area` SET `property_id` = $property_new WHERE `survey_id` = $survey_old",
                        "siteplan" => "UPDATE `tbl_siteplan_documents` SET `property_id` = $property_new WHERE `survey_id` = $survey_old",
                        "survey" => "UPDATE `tbl_survey` SET `property_id`= $property_new WHERE `id` =  $survey_old",
                    ];
                    $dataRevert = [
                            'survey_id' => $survey_old,
                            'property_old' => $property_old,
                            'property_new' => $property_new,
                        ];
                        foreach($sql_move as $sql){
                            $removeGroup = \DB::select($sql);
                        }
                        $msg = 'Moved Survey Successfully!';
                        $flag = true;
                break;

                case 'project':
                    $action = 'moveProject';
                    $sql_move = [
                        "project" => "UPDATE  tbl_project set property_id = $property_new where id = $project_id ",
                    ];
                    $dataRevert = [
                            'project_id' => $project_id,
                            'property_old' => $property_old,
                            'property_new' => $property_new,
                        ];
                        foreach($sql_move as $sql){
                            $removeGroup = \DB::select($sql);
                        }
                        $msg = 'Moved Project Successfully!';
                        $flag = true;
                break;

                case 'survey_location':
                case 'register_location':
                    $action = 'moveLocation';
                    $sql_move = [
                        "item" => "UPDATE `tbl_items` SET `area_id` = $area_new WHERE `location_id` = $location_old",
                        "location" => "UPDATE `tbl_location` SET `area_id` = $area_new WHERE `id` = $location_old",
                    ];
                    $dataRevert = [
                            'location_old' => $location_old,
                            'area_old' => $area_old,
                            'area_new' => $area_new,
                        ];
                        foreach($sql_move as $sql){
                            $removeGroup = \DB::select($sql);
                        }
                        $msg = 'Moved Location Successfully!';
                        $flag = true;
                break;

                case 'survey_item':
                case 'register_item':
                    $action = 'moveItem';
                    $sql_move = [
                        "item" => "UPDATE `tbl_items` SET location_id = $location_new, area_id = $area_new WHERE `id` = $item_old",
                    ];
                    $dataRevert = [
                            'item_old' => $item_old,
                            'location_old' => $location_old,
                            'location_new' => $location_new,
                            'area_new' => $area_new,
                            'area_old' => $area_old
                        ];
                        foreach($sql_move as $sql){
                            $removeGroup = \DB::select($sql);
                        }
                        $msg = 'Moved Item Successfully!';
                        $flag = true;
                break;

                default:
                break;
            }
            $comment_audit = \Auth::user()->full_name  . " " . $request->description ?? '';
            \CommonHelpers::logAudit(ADMIN_TOOL_TYPE, 0, AUDIT_ACTION_MOVE, $request->type ?? '', 0 ,$comment_audit, 0 ,0);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            $error = $e->getMessage();
            $flag = false;
        }
        if ($flag) {
                $toolbox = AdminTool::create([
                    'action' => $action,
                    'input' => $input,
                    'result' => 'success',
                    'roll_back' => 0,
                    'created_by' => \Auth::user()->id
                ]);
                if ($toolbox) {
                    AdminToolRollback::create([
                        'admin_tool_id' => $toolbox->id,
                        'data' => json_encode($dataRevert)
                    ]);
                }
                return redirect()->back()->with('msg',$msg);
        } else {
                $toolbox = AdminTool::create([
                    'action' => $action,
                    'input' => $input,
                    'result' => $error,
                    'roll_back' => 0,
                    'created_by' => \Auth::user()->id
                ]);
                return redirect()->back()->with('err', 'Have something wrong. Please try later later');
            }

        return true;
    }

    public function postRemove(Request $request) {
        $input = [
            'description' => $request->description ?? '',
            'reason' => $request->reason ?? ''
        ];
        $input = json_encode($input);
        $zone_id = $request->group_old ?? null;

        $item_id = $request->item_old ?? null;
        $survey_id = $request->survey_old ?? null;
        $property_id = $request->property_old ?? null;
        $location_id = $request->location_old ?? null;
        $area_id = $request->area_old ?? null;
        $survey_search_old = $request->survey_search_old ?? null;
        $project_search_old = $request->project_search_old ?? null;
        $project_id = $request->project_old ?? null;
        $document_category = $request->document_category_old ?? null;
        $document_type = $request->document_type_old ?? null;
        $document_id = $request->document_old ?? null;

        try {
            \DB::beginTransaction();
            switch ($request->type) {
                case 'group':
                        $action = 'removeGroup';
                        //delete query
                        $sql_delete = [
                            "history" => "DELETE c from tbl_historicdocs_categories c LEFT JOIN tbl_property p ON c.property_id = p.id WHERE p.zone_id = $zone_id",
                            "history_doc" => "DELETE c from tbl_historicdocs c LEFT JOIN tbl_property p ON c.property_id = p.id WHERE p.zone_id = $zone_id",
                            "sample_certificate" => "DELETE c from tbl_sample_certificates c LEFT JOIN tbl_survey s ON c.survey_id = s.id LEFT JOIN tbl_property p ON s.property_id = p.id WHERE p.zone_id = $zone_id",
                            "airtest_certificate" => "DELETE c from tbl_air_test_certificates c LEFT JOIN tbl_survey s ON c.survey_id = s.id  LEFT JOIN tbl_property p ON s.property_id = p.id WHERE p.zone_id = $zone_id",
                            "siteplane" => "DELETE c from tbl_siteplan_documents  c LEFT JOIN tbl_property p ON c.property_id = p.id WHERE p.zone_id = $zone_id ",
                            "notification" => "DELETE n from tbl_notifications n LEFT JOIN tbl_project pj ON n.project_id = pj.id LEFT JOIN tbl_property p ON pj.property_id = p.id WHERE p.zone_id = $zone_id ;",
                            "document" => "DELETE d from tbl_documents d LEFT JOIN tbl_project pj ON d.project_id = pj.id LEFT JOIN tbl_property p ON pj.property_id = p.id WHERE p.zone_id = $zone_id ",
                            "project" => "DELETE pj from tbl_project pj LEFT JOIN tbl_property p ON pj.property_id = p.id WHERE p.zone_id = $zone_id ",
                            "item_accessibility_vulnerability" => "DELETE n from tbl_item_accessibility_vulnerability_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "item_action_recommendation" => "DELETE n from tbl_item_action_recommendation_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id",
                            "item_additional_information" => "DELETE n from tbl_item_additional_information_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "item_asbestos_type" => "DELETE n from tbl_item_asbestos_type_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id",
                            "item_extent" => "DELETE n from tbl_item_extent_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id",
                            "item_no_access" => "DELETE n from tbl_item_no_access_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "item_licensed_non_licensed" => "DELETE n from tbl_item_licensed_non_licensed_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "item_material_assessment_risk" => "DELETE n from tbl_item_material_assessment_risk_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "item_no_acm_comment" => "DELETE n from tbl_item_no_acm_comments_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id",
                            "item_priority_assessment_risk" => "DELETE n from tbl_item_priority_assessment_risk_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id= $zone_id;",
                            "item_product_debris" => "DELETE n from tbl_item_product_debris_type_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id",
                            "item_sample_comment" => "DELETE n from tbl_item_sample_comment_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id",
                            "item_sample_id" => "DELETE n from tbl_item_sample_id_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id",
                            "item_specific_location" => "DELETE n from tbl_item_specific_location_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "item_sub_sample" => "DELETE n from tbl_item_sub_sample_id_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id",
                            "item_unable_to_sample" => "DELETE n from tbl_item_unable_to_sample_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "item_info" => "DELETE n from tbl_items_info n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id",
                            "item_comment" => "DELETE n from tbl_item_comment n LEFT JOIN tbl_items i ON n.record_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "items" => "DELETE i from tbl_items i LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id",
                            "location_info" => "DELETE n from tbl_location_info n LEFT JOIN tbl_location l ON n.location_id = l.id LEFT JOIN tbl_property p ON l.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "location_comment" => "DELETE n from tbl_location_comment n LEFT JOIN tbl_location l ON n.record_id = l.id LEFT JOIN tbl_property p ON l.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "location_construction" => "DELETE n from tbl_location_construction n LEFT JOIN tbl_location l ON n.location_id = l.id LEFT JOIN tbl_property p ON l.property_id = p.id WHERE p.zone_id =$zone_id;",
                            "location_void" => "DELETE n from tbl_location_void n LEFT JOIN tbl_location l ON n.location_id = l.id LEFT JOIN tbl_property p ON l.property_id = p.id WHERE p.zone_id = $zone_id",
                            "location" => "DELETE l from tbl_location  l LEFT JOIN tbl_property p ON l.property_id = p.id WHERE p.zone_id = $zone_id",
                            "area" => "DELETE a from tbl_area a LEFT JOIN tbl_property p ON a.property_id = p.id WHERE p.zone_id = $zone_id",
                            "survey_date" => "DELETE n from tbl_survey_date n LEFT JOIN tbl_survey s ON n.survey_id = s.id  LEFT JOIN tbl_property p ON s.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "survey_setting" => "DELETE n from tbl_survey_setting n LEFT JOIN tbl_survey s ON n.survey_id = s.id  LEFT JOIN tbl_property p ON s.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "survey_info" => "DELETE n from tbl_survey_info n LEFT JOIN tbl_survey s ON n.survey_id = s.id  LEFT JOIN tbl_property p ON s.property_id = p.id WHERE p.zone_id = $zone_id",
                            "survey" => "DELETE s from tbl_survey s LEFT JOIN tbl_property p ON s.property_id = p.id WHERE p.zone_id = $zone_id",
                            "property_survey" => "DELETE ps from tbl_property_survey ps LEFT JOIN tbl_property p ON ps.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "property_info" => "DELETE pi from tbl_property_info pi LEFT JOIN tbl_property p ON pi.property_id = p.id WHERE p.zone_id = $zone_id",
                            "property_type" => "DELETE pt from property_property_type pt LEFT JOIN tbl_property p ON pt.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "property_comment" => "DELETE pc from tbl_property_comment pc LEFT JOIN tbl_property p ON pc.record_id = p.id WHERE p.zone_id = $zone_id",
                            "property" => "DELETE  from tbl_property WHERE zone_id = $zone_id",
                            "zone" => "DELETE  FROM tbl_zones WHERE id = $zone_id"
                        ];

                        // backup query
                        $sql_reverts = [
                            "history" => "SELECT c.* from tbl_historicdocs_categories c LEFT JOIN tbl_property p ON c.property_id = p.id WHERE p.zone_id = $zone_id",
                            "history_doc" => "SELECT c.* from tbl_historicdocs c LEFT JOIN tbl_property p ON c.property_id = p.id WHERE p.zone_id = $zone_id",
                            "siteplane" => "SELECT c.* from tbl_siteplan_documents  c LEFT JOIN tbl_property p ON c.property_id = p.id WHERE p.zone_id = $zone_id ",
                            "sample_certificate" => "SELECT c.* from tbl_sample_certificates c  LEFT JOIN tbl_survey s ON c.survey_id = s.id LEFT JOIN tbl_property p ON s.property_id = p.id WHERE p.zone_id = $zone_id",
                            "airtest_certificate" => "SELECT c.* from tbl_air_test_certificates c  LEFT JOIN tbl_survey s ON c.survey_id = s.id LEFT JOIN tbl_property p ON s.property_id = p.id WHERE p.zone_id = $zone_id",
                            "notification" => "SELECT n.* from tbl_notifications n LEFT JOIN tbl_project pj ON n.project_id = pj.id LEFT JOIN tbl_property p ON pj.property_id = p.id WHERE p.zone_id = $zone_id ;",
                            "document" => "SELECT d.* from tbl_documents d LEFT JOIN tbl_project pj ON d.project_id = pj.id LEFT JOIN tbl_property p ON pj.property_id = p.id WHERE p.zone_id = $zone_id ",
                            "project" => "SELECT pj.* from tbl_project pj LEFT JOIN tbl_property p ON pj.property_id = p.id WHERE p.zone_id = $zone_id ",
                            "item_accessibility_vulnerability" => "SELECT n.* from tbl_item_accessibility_vulnerability_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "item_action_recommendation" => "SELECT n.* from tbl_item_action_recommendation_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id",
                            "item_additional_information" => "SELECT n.* from tbl_item_additional_information_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "item_asbestos_type" => "SELECT n.* from tbl_item_asbestos_type_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id",
                            "item_extent" => "SELECT n.* from tbl_item_extent_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id",
                            "item_no_access" => "SELECT n.* from tbl_item_no_access_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "item_licensed_non_licensed" => "SELECT n.* from tbl_item_licensed_non_licensed_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "item_material_assessment_risk" => "SELECT n.* from tbl_item_material_assessment_risk_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "item_no_acm_comment" => "SELECT n.* from tbl_item_no_acm_comments_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id",
                            "item_priority_assessment_risk" => "SELECT n.* from tbl_item_priority_assessment_risk_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id= $zone_id;",
                            "item_product_debris" => "SELECT n.* from tbl_item_product_debris_type_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id",
                            "item_sample_comment" => "SELECT n.* from tbl_item_sample_comment_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id",
                            "item_sample_id" => "SELECT n.* from tbl_item_sample_id_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id",
                            "item_specific_location" => "SELECT n.* from tbl_item_specific_location_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "item_sub_sample" => "SELECT n.* from tbl_item_sub_sample_id_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id",
                            "item_unable_to_sample" => "SELECT n.* from tbl_item_unable_to_sample_value n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "item_info" => "SELECT n.* from tbl_items_info n LEFT JOIN tbl_items i ON n.item_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id",
                            "item_comment" => "SELECT n.* from tbl_item_comment n LEFT JOIN tbl_items i ON n.record_id = i.id LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "items" => "SELECT i.* from tbl_items i LEFT JOIN tbl_property p ON i.property_id = p.id WHERE p.zone_id = $zone_id",
                            "location_info" => "SELECT n.* from tbl_location_info n LEFT JOIN tbl_location l ON n.location_id = l.id LEFT JOIN tbl_property p ON l.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "location_comment" => "SELECT n.* from tbl_location_comment n LEFT JOIN tbl_location l ON n.record_id = l.id LEFT JOIN tbl_property p ON l.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "location_construction" => "SELECT n.* from tbl_location_construction n LEFT JOIN tbl_location l ON n.location_id = l.id LEFT JOIN tbl_property p ON l.property_id = p.id WHERE p.zone_id =$zone_id;",
                            "location_void" => "SELECT n.* from tbl_location_void n LEFT JOIN tbl_location l ON n.location_id = l.id LEFT JOIN tbl_property p ON l.property_id = p.id WHERE p.zone_id = $zone_id",
                            "location" => "SELECT l.* from tbl_location  l LEFT JOIN tbl_property p ON l.property_id = p.id WHERE p.zone_id = $zone_id",
                            "area" => "SELECT a.* from tbl_area a LEFT JOIN tbl_property p ON a.property_id = p.id WHERE p.zone_id = $zone_id",
                            "survey_date" => "SELECT n.* from tbl_survey_date n LEFT JOIN tbl_survey s ON n.survey_id = s.id  LEFT JOIN tbl_property p ON s.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "survey_setting" => "SELECT n.* from tbl_survey_setting n LEFT JOIN tbl_survey s ON n.survey_id = s.id  LEFT JOIN tbl_property p ON s.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "survey_info" => "SELECT n.* from tbl_survey_info n LEFT JOIN tbl_survey s ON n.survey_id = s.id  LEFT JOIN tbl_property p ON s.property_id = p.id WHERE p.zone_id = $zone_id",
                            "survey" => "SELECT s.* from tbl_survey s LEFT JOIN tbl_property p ON s.property_id = p.id WHERE p.zone_id = $zone_id",
                            "property" => "SELECT * from tbl_property WHERE zone_id = $zone_id",
                            "property_survey" => "SELECT ps.* from tbl_property_survey ps LEFT JOIN tbl_property p ON ps.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "property_info" => "SELECT pi.* from tbl_property_info pi LEFT JOIN tbl_property p ON pi.property_id = p.id WHERE p.zone_id = $zone_id",
                            "property_type" => "SELECT pt.* from property_property_type pt LEFT JOIN tbl_property p ON pt.property_id = p.id WHERE p.zone_id = $zone_id;",
                            "property_comment" => "SELECT pc.* from tbl_property_comment pc LEFT JOIN tbl_property p ON pc.record_id = p.id WHERE p.zone_id = $zone_id",
                            "zone" => "SELECT * FROM tbl_zones WHERE id = $zone_id"
                        ];
                        $dataRevert = [];
                        foreach ($sql_reverts as $key => $sqlRevert) {
                            $dataRevert[$key] = \DB::select($sqlRevert);
                        }

                        foreach($sql_delete as $sql){
                            $removeGroup = \DB::select($sql);
                        }
                        $flag = true;
                        $msg = 'Removed Property Group Successfully!';

                        \DB::commit();
                break;

                case 'properties':
                    $action = 'removeProperty';
                    //delete query
                    $sql_delete = [
                        "history" => "DELETE c from tbl_historicdocs_categories c WHERE c.property_id = $property_id",
                        "history_doc" => "DELETE c from tbl_historicdocs c WHERE c.property_id = $property_id",
                        "siteplane" => "DELETE c from tbl_siteplan_documents  c WHERE c.property_id = $property_id ",
                        "sample_certificate" => "DELETE c from tbl_sample_certificates c LEFT JOIN tbl_survey s ON c.survey_id = s.id where s.property_id = $property_id",
                        "airtest_certificate" => "DELETE c from tbl_air_test_certificates c LEFT JOIN tbl_survey s ON c.survey_id = s.id WHERE s.property_id = $property_id",
                        "notification" => "DELETE n from tbl_notifications n LEFT JOIN tbl_project pj ON n.project_id = pj.id WHERE pj.property_id = $property_id ;",
                        "document" => "DELETE d from tbl_documents d LEFT JOIN tbl_project pj ON d.project_id = pj.id  WHERE pj.property_id = $property_id ",
                        "project" => "DELETE pj from tbl_project pj  WHERE pj.id = $property_id ",
                        "item_accessibility_vulnerability" => "DELETE n from tbl_item_accessibility_vulnerability_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id;",
                        "item_action_recommendation" => "DELETE n from tbl_item_action_recommendation_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id",
                        "item_additional_information" => "DELETE n from tbl_item_additional_information_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id;",
                        "item_asbestos_type" => "DELETE n from tbl_item_asbestos_type_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id",
                        "item_extent" => "DELETE n from tbl_item_extent_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id",
                        "item_no_access" => "DELETE n from tbl_item_no_access_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.property_id = $property_id;",
                        "item_licensed_non_licensed" => "DELETE n from tbl_item_licensed_non_licensed_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id;",
                        "item_material_assessment_risk" => "DELETE n from tbl_item_material_assessment_risk_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id;",
                        "item_no_acm_comment" => "DELETE n from tbl_item_no_acm_comments_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id",
                        "item_priority_assessment_risk" => "DELETE n from tbl_item_priority_assessment_risk_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id= $property_id;",
                        "item_product_debris" => "DELETE n from tbl_item_product_debris_type_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id",
                        "item_sample_comment" => "DELETE n from tbl_item_sample_comment_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id",
                        "item_sample_id" => "DELETE n from tbl_item_sample_id_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id",
                        "item_specific_location" => "DELETE n from tbl_item_specific_location_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.property_id = $property_id;",
                        "item_sub_sample" => "DELETE n from tbl_item_sub_sample_id_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id",
                        "item_unable_to_sample" => "DELETE n from tbl_item_unable_to_sample_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.property_id = $property_id;",
                        "item_info" => "DELETE n from tbl_items_info n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.property_id = $property_id",
                        "item_comment" => "DELETE n from tbl_item_comment n LEFT JOIN tbl_items i ON n.record_id = i.id WHERE i.property_id = $property_id;",
                        "items" => "DELETE i from tbl_items i  WHERE i.property_id = $property_id",
                        "location_info" => "DELETE n from tbl_location_info n LEFT JOIN tbl_location l ON n.location_id = l.id  WHERE l.property_id = $property_id;",
                        "location_comment" => "DELETE n from tbl_location_comment n LEFT JOIN tbl_location l ON n.record_id = l.id  WHERE l.property_id = $property_id;",
                        "location_construction" => "DELETE n from tbl_location_construction n LEFT JOIN tbl_location l ON n.location_id = l.id  WHERE l.property_id =$property_id;",
                        "location_void" => "DELETE n from tbl_location_void n LEFT JOIN tbl_location l ON n.location_id = l.id  WHERE l.property_id = $property_id",
                        "location" => "DELETE l from tbl_location  l  WHERE l.property_id  = $property_id",
                        "area" => "DELETE a from tbl_area a WHERE a.property_id = $property_id",
                        "survey_date" => "DELETE n from tbl_survey_date n LEFT JOIN tbl_survey s ON n.survey_id = s.id  WHERE s.property_id = $property_id;",
                        "survey_setting" => "DELETE n from tbl_survey_setting n LEFT JOIN tbl_survey s ON n.survey_id = s.id  WHERE s.property_id = $property_id;",
                        "survey_info" => "DELETE n from tbl_survey_info n LEFT JOIN tbl_survey s ON n.survey_id = s.id  WHERE s.property_id = $property_id",
                        "survey" => "DELETE s from tbl_survey s  WHERE s.property_id = $property_id",
                        "property_survey" => "DELETE ps from tbl_property_survey ps  WHERE ps.property_id = $property_id;",
                        "property_info" => "DELETE pi from tbl_property_info pi  WHERE  pi.property_id = $property_id",
                        "property_type" => "DELETE pt from property_property_type pt WHERE pt.property_id = $property_id;",
                        "property_comment" => "DELETE pc from tbl_property_comment pc  WHERE pc.record_id = $property_id",
                        "property" => "DELETE  from tbl_property WHERE id = $property_id",
                    ];
                    // backup query
                    $sql_reverts = [
                        "history" => "SELECT c.* from tbl_historicdocs_categories c WHERE c.property_id = $property_id",
                        "history_doc" => "SELECT c.* from tbl_historicdocs c WHERE c.property_id = $property_id",
                        "siteplane" => "SELECT c.* from tbl_siteplan_documents  c WHERE c.property_id = $property_id ",
                        "sample_certificate" => "SELECT c.* from tbl_sample_certificates c LEFT JOIN tbl_survey s ON c.survey_id = s.id where s.property_id = $property_id",
                        "airtest_certificate" => "SELECT c.* from tbl_air_test_certificates c LEFT JOIN tbl_survey s ON c.survey_id = s.id WHERE s.property_id = $property_id",
                        "notification" => "SELECT n.* from tbl_notifications n LEFT JOIN tbl_project pj ON n.project_id = pj.id WHERE pj.property_id = $property_id ;",
                        "document" => "SELECT d.* from tbl_documents d LEFT JOIN tbl_project pj ON d.project_id = pj.id  WHERE pj.property_id = $property_id ",
                        "project" => "SELECT pj.* from tbl_project pj  WHERE pj.id = $property_id ",
                        "item_accessibility_vulnerability" => "SELECT n.* from tbl_item_accessibility_vulnerability_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id;",
                        "item_action_recommendation" => "SELECT n.* from tbl_item_action_recommendation_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id",
                        "item_additional_information" => "SELECT n.* from tbl_item_additional_information_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id;",
                        "item_asbestos_type" => "SELECT n.* from tbl_item_asbestos_type_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id",
                        "item_extent" => "SELECT n.* from tbl_item_extent_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id",
                        "item_no_access" => "SELECT n.* from tbl_item_no_access_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.property_id = $property_id;",
                        "item_licensed_non_licensed" => "SELECT n.* from tbl_item_licensed_non_licensed_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id;",
                        "item_material_assessment_risk" => "SELECT n.* from tbl_item_material_assessment_risk_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id;",
                        "item_no_acm_comment" => "SELECT n.* from tbl_item_no_acm_comments_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id",
                        "item_priority_assessment_risk" => "SELECT n.* from tbl_item_priority_assessment_risk_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id= $property_id;",
                        "item_product_debris" => "SELECT n.* from tbl_item_product_debris_type_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id",
                        "item_sample_comment" => "SELECT n.* from tbl_item_sample_comment_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id",
                        "item_sample_id" => "SELECT n.* from tbl_item_sample_id_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id",
                        "item_specific_location" => "SELECT n.* from tbl_item_specific_location_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.property_id = $property_id;",
                        "item_sub_sample" => "SELECT n.* from tbl_item_sub_sample_id_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.property_id = $property_id",
                        "item_unable_to_sample" => "SELECT n.* from tbl_item_unable_to_sample_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.property_id = $property_id;",
                        "item_info" => "SELECT n.* from tbl_items_info n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.property_id = $property_id",
                        "item_comment" => "SELECT n.* from tbl_item_comment n LEFT JOIN tbl_items i ON n.record_id = i.id WHERE i.property_id = $property_id;",
                        "items" => "SELECT i.* from tbl_items i  WHERE i.property_id = $property_id",
                        "location_info" => "SELECT n.* from tbl_location_info n LEFT JOIN tbl_location l ON n.location_id = l.id  WHERE l.property_id = $property_id;",
                        "location_comment" => "SELECT n.* from tbl_location_comment n LEFT JOIN tbl_location l ON n.record_id = l.id  WHERE l.property_id = $property_id;",
                        "location_construction" => "SELECT n.* from tbl_location_construction n LEFT JOIN tbl_location l ON n.location_id = l.id  WHERE l.property_id =$property_id;",
                        "location_void" => "SELECT n.* from tbl_location_void n LEFT JOIN tbl_location l ON n.location_id = l.id  WHERE l.property_id = $property_id",
                        "location" => "SELECT l.* from tbl_location  l  WHERE l.property_id  = $property_id",
                        "area" => "SELECT a.* from tbl_area a WHERE a.property_id = $property_id",
                        "survey_date" => "SELECT n.* from tbl_survey_date n LEFT JOIN tbl_survey s ON n.survey_id = s.id  WHERE s.property_id = $property_id;",
                        "survey_setting" => "SELECT n.* from tbl_survey_setting n LEFT JOIN tbl_survey s ON n.survey_id = s.id  WHERE s.property_id = $property_id;",
                        "survey_info" => "SELECT n.* from tbl_survey_info n LEFT JOIN tbl_survey s ON n.survey_id = s.id  WHERE s.property_id = $property_id",
                        "survey" => "SELECT s.* from tbl_survey s  WHERE s.property_id = $property_id",
                        "property_survey" => "SELECT ps.* from tbl_property_survey ps  WHERE ps.property_id = $property_id;",
                        "property_info" => "SELECT pi.* from tbl_property_info pi  WHERE  pi.property_id = $property_id",
                        "property_type" => "SELECT pt.* from property_property_type pt WHERE pt.property_id = $property_id;",
                        "property_comment" => "SELECT pc.* from tbl_property_comment pc  WHERE pc.record_id = $property_id",
                        "property" => "SELECT * from tbl_property WHERE id = $property_id",
                    ];
                    $dataRevert = [];
                    foreach ($sql_reverts as $key => $sqlRevert) {
                        $dataRevert[$key] = \DB::select($sqlRevert);
                    }
                    foreach($sql_delete as $sql){
                        $removeGroup = \DB::select($sql);
                    }
                    $flag = true;
                    $msg = 'Removed Property Successfully!';
                break;

                case 'survey':
                    $action = 'removeSurvey';
                    //delete query
                    $sql_delete = [
                        "siteplane" => "DELETE c from tbl_siteplan_documents  c WHERE c.survey_id = $survey_id ",
                        "sample_certificate" => "DELETE c from tbl_sample_certificates c where c.survey_id = $survey_id",
                        "airtest_certificate" => "DELETE c from tbl_air_test_certificates c WHERE c.survey_id = $survey_id",
                        "item_accessibility_vulnerability" => "DELETE n from tbl_item_accessibility_vulnerability_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id;",
                        "item_action_recommendation" => "DELETE n from tbl_item_action_recommendation_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id",
                        "item_additional_information" => "DELETE n from tbl_item_additional_information_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id;",
                        "item_asbestos_type" => "DELETE n from tbl_item_asbestos_type_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id",
                        "item_extent" => "DELETE n from tbl_item_extent_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id",
                        "item_no_access" => "DELETE n from tbl_item_no_access_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.survey_id = $survey_id;",
                        "item_licensed_non_licensed" => "DELETE n from tbl_item_licensed_non_licensed_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id;",
                        "item_material_assessment_risk" => "DELETE n from tbl_item_material_assessment_risk_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id;",
                        "item_no_acm_comment" => "DELETE n from tbl_item_no_acm_comments_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id",
                        "item_priority_assessment_risk" => "DELETE n from tbl_item_priority_assessment_risk_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id= $survey_id;",
                        "item_product_debris" => "DELETE n from tbl_item_product_debris_type_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id",
                        "item_sample_comment" => "DELETE n from tbl_item_sample_comment_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id",
                        "item_sample_id" => "DELETE n from tbl_item_sample_id_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id",
                        "item_specific_location" => "DELETE n from tbl_item_specific_location_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.survey_id = $survey_id;",
                        "item_sub_sample" => "DELETE n from tbl_item_sub_sample_id_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id",
                        "item_unable_to_sample" => "DELETE n from tbl_item_unable_to_sample_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.survey_id = $survey_id;",
                        "item_info" => "DELETE n from tbl_items_info n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.survey_id = $survey_id",
                        "item_comment" => "DELETE n from tbl_item_comment n LEFT JOIN tbl_items i ON n.record_id = i.id WHERE i.survey_id = $survey_id;",
                        "items" => "DELETE i from tbl_items i WHERE i.survey_id = $survey_id",
                        "location_info" => "DELETE n from tbl_location_info n LEFT JOIN tbl_location l ON n.location_id = l.id  WHERE l.survey_id = $survey_id;",
                        "location_comment" => "DELETE n from tbl_location_comment n LEFT JOIN tbl_location l ON n.record_id = l.id  WHERE l.survey_id = $survey_id;",
                        "location_construction" => "DELETE n from tbl_location_construction n LEFT JOIN tbl_location l ON n.location_id = l.id  WHERE l.survey_id =$survey_id;",
                        "location_void" => "DELETE n from tbl_location_void n LEFT JOIN tbl_location l ON n.location_id = l.id  WHERE l.survey_id = $survey_id",
                        "location" => "DELETE l from tbl_location  l  WHERE l.survey_id  = $survey_id",
                        "area" => "DELETE a from tbl_area a WHERE a.survey_id = $survey_id",
                        "survey_date" => "DELETE n from tbl_survey_date n  WHERE n.survey_id = $survey_id;",
                        "survey_setting" => "DELETE n from tbl_survey_setting n   WHERE n.survey_id = $survey_id;",
                        "survey_info" => "DELETE n from tbl_survey_info n  WHERE n.survey_id = $survey_id",
                        "survey" => "DELETE s from tbl_survey s WHERE s.id = $survey_id"
                    ];

                    // backup query
                    $sql_reverts = [
                        "siteplane" => "SELECT c.* from tbl_siteplan_documents  c WHERE c.survey_id = $survey_id ",
                        "sample_certificate" => "SELECT c.* from tbl_sample_certificates c where c.survey_id = $survey_id",
                        "airtest_certificate" => "SELECT c.* from tbl_air_test_certificates c WHERE c.survey_id = $survey_id",
                        "item_accessibility_vulnerability" => "SELECT n.* from tbl_item_accessibility_vulnerability_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id;",
                        "item_action_recommendation" => "SELECT n.* from tbl_item_action_recommendation_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id",
                        "item_additional_information" => "SELECT n.* from tbl_item_additional_information_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id;",
                        "item_asbestos_type" => "SELECT n.* from tbl_item_asbestos_type_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id",
                        "item_extent" => "SELECT n.* from tbl_item_extent_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id",
                        "item_no_access" => "SELECT n.* from tbl_item_no_access_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.survey_id = $survey_id;",
                        "item_licensed_non_licensed" => "SELECT n.* from tbl_item_licensed_non_licensed_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id;",
                        "item_material_assessment_risk" => "SELECT n.* from tbl_item_material_assessment_risk_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id;",
                        "item_no_acm_comment" => "SELECT n.* from tbl_item_no_acm_comments_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id",
                        "item_priority_assessment_risk" => "SELECT n.* from tbl_item_priority_assessment_risk_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id= $survey_id;",
                        "item_product_debris" => "SELECT n.* from tbl_item_product_debris_type_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id",
                        "item_sample_comment" => "SELECT n.* from tbl_item_sample_comment_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id",
                        "item_sample_id" => "SELECT n.* from tbl_item_sample_id_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id",
                        "item_specific_location" => "SELECT n.* from tbl_item_specific_location_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.survey_id = $survey_id;",
                        "item_sub_sample" => "SELECT n.* from tbl_item_sub_sample_id_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.survey_id = $survey_id",
                        "item_unable_to_sample" => "SELECT n.* from tbl_item_unable_to_sample_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.survey_id = $survey_id;",
                        "item_info" => "SELECT n.* from tbl_items_info n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.survey_id = $survey_id",
                        "item_comment" => "SELECT n.* from tbl_item_comment n LEFT JOIN tbl_items i ON n.record_id = i.id WHERE i.survey_id = $survey_id;",
                        "items" => "SELECT i.* from tbl_items i WHERE i.survey_id = $survey_id",
                        "location_info" => "SELECT n.* from tbl_location_info n LEFT JOIN tbl_location l ON n.location_id = l.id  WHERE l.survey_id = $survey_id;",
                        "location_comment" => "SELECT n.* from tbl_location_comment n LEFT JOIN tbl_location l ON n.record_id = l.id  WHERE l.survey_id = $survey_id;",
                        "location_construction" => "SELECT n.* from tbl_location_construction n LEFT JOIN tbl_location l ON n.location_id = l.id  WHERE l.survey_id =$survey_id;",
                        "location_void" => "SELECT n.* from tbl_location_void n LEFT JOIN tbl_location l ON n.location_id = l.id  WHERE l.survey_id = $survey_id",
                        "location" => "SELECT l.* from tbl_location  l  WHERE l.survey_id  = $survey_id",
                        "area" => "SELECT a.* from tbl_area a WHERE a.survey_id = $survey_id",
                        "survey_date" => "SELECT n.* from tbl_survey_date n  WHERE n.survey_id = $survey_id;",
                        "survey_setting" => "SELECT n.* from tbl_survey_setting n   WHERE n.survey_id = $survey_id;",
                        "survey_info" => "SELECT n.* from tbl_survey_info n  WHERE n.survey_id = $survey_id",
                        "survey" => "SELECT s.* from tbl_survey s WHERE s.id = $survey_id",
                    ];

                    \DB::update("UPDATE tbl_area a,
                                             (SELECT record_id FROM tbl_area WHERE id != record_id AND survey_id = $survey_id) a2
                                            SET a.is_locked = 0
                                            WHERE
                                                a.record_id = a2.record_id
                                            AND a.survey_id = 0");

                    // Unlock register items
                    \DB::update("UPDATE tbl_items a,
                                         (SELECT record_id FROM tbl_items WHERE id != record_id AND survey_id = $survey_id) a2
                                        SET a.is_locked = 0
                                        WHERE
                                            a.record_id = a2.record_id
                                        AND a.survey_id = 0 ");

                    // Unlock register locations
                    \DB::update("UPDATE tbl_location a,
                                                     (SELECT record_id FROM tbl_location WHERE id != record_id AND survey_id = $survey_id) a2
                                            SET a.is_locked = 0
                                            WHERE
                                                a.record_id = a2.record_id
                                            AND a.survey_id = 0");
                    $area_sql = \DB::select("SELECT
                                            group_concat(a1.id) as id
                                            FROM tbl_area a1
                                            JOIN (SELECT * FROM tbl_area WHERE id != record_id AND survey_id = $survey_id) a2 ON a1.record_id = a2.record_id
                                            WHERE a1.survey_id = 0");

                    $area_id = $area_sql[0]->id ?? '';
                    $location_sql = \DB::select("SELECT
                                            group_concat(a1.id) as id
                                            FROM tbl_location a1
                                            JOIN (SELECT * FROM tbl_location WHERE id != record_id AND survey_id = $survey_id) a2 ON a1.record_id = a2.record_id
                                            WHERE a1.survey_id = 0");

                    $location_id = $location_sql[0]->id ?? '';
                    $item_sql = \DB::select("SELECT
                                            group_concat(a1.id) as id
                                            FROM tbl_items a1
                                            JOIN (SELECT * FROM tbl_items WHERE id != record_id AND survey_id = $survey_id) a2 ON a1.record_id = a2.record_id
                                            WHERE a1.survey_id = 0");

                    $item_id = $item_sql[0]->id ?? '';

                    $dataRevert = [];
                    foreach ($sql_reverts as $key => $sqlRevert) {
                        $dataRevert[$key] = \DB::select($sqlRevert);
                    }

                    $dataRevert['area_id'] = $area_id;
                    $dataRevert['location_id'] = $location_id;
                    $dataRevert['item_id'] = $item_id;

                    foreach($sql_delete as $sql){
                        $removeGroup = \DB::select($sql);
                    }
                    $flag = true;
                    $msg = 'Removed Survey Successfully!';
                break;

                case 'project':
                    $action = 'removeProject';
                    //delete query
                    $sql_delete = [
                        "notification" => "DELETE n from tbl_notifications n WHERE n.project_id = $project_id ;",
                        "document" => "DELETE d from tbl_documents d  WHERE d.project_id = $project_id ",
                        "project" => "DELETE pj from tbl_project pj WHERE pj.id = $project_id ",
                    ];

                    // backup query
                    $sql_reverts = [
                        "notification" => "SELECT n.* from tbl_notifications n WHERE n.project_id = $project_id ;",
                        "document" => "SELECT d.* from tbl_documents d  WHERE d.project_id = $project_id ",
                        "project" => "SELECT pj.* from tbl_project pj WHERE pj.id = $project_id ",
                    ];
                    $dataRevert = [];
                    foreach ($sql_reverts as $key => $sqlRevert) {
                        $dataRevert[$key] = \DB::select($sqlRevert);
                    }

                    foreach($sql_delete as $sql){
                        $removeGroup = \DB::select($sql);
                    }
                    $flag = true;
                    $msg = 'Removed Project Successfully!';
                break;

                case 'document':
                    $action = 'remove_'.$document_type;

                    switch ($document_type) {
                        case 'property_plan':
                            //delete query
                            $sql_delete = [
                                "siteplane" => "DELETE from tbl_siteplan_documents WHERE id = $document_id ",
                            ];

                            // backup query
                            $sql_reverts = [
                                "siteplane" => "SELECT * from tbl_siteplan_documents WHERE id = $document_id ",
                            ];
                            break;

                        case 'property_historical':
                            $sql_delete = [
                                "history_doc" => "DELETE from compliance_documents WHERE  id = $document_id",
                            ];

                            // backup query
                            $sql_reverts = [
                                "history_doc" => "SELECT * from compliance_documents WHERE  id = $document_id",
                            ];
                            break;

                        case 'survey_sc':
                            $sql_delete = [
                                "sample_certificate" => "DELETE from tbl_sample_certificates WHERE  id = $document_id",
                            ];

                            // backup query
                            $sql_reverts = [
                                "sample_certificate" => "SELECT * from tbl_sample_certificates WHERE  id = $document_id",
                            ];
                            break;

                        case 'survey_ac':
                            $sql_delete = [
                                "airtest_certificate" => "DELETE from tbl_air_test_certificates WHERE  id = $document_id",
                            ];

                            // backup query
                            $sql_reverts = [
                                "airtest_certificate" => "SELECT * from tbl_air_test_certificates WHERE  id = $document_id",
                            ];
                            break;

                        case 'survey_plan':
                            $sql_delete = [
                                "siteplane" => "DELETE from tbl_siteplan_documents  WHERE  id = $document_id ",
                            ];

                            // backup query
                            $sql_reverts = [
                                "siteplane" => "SELECT * from tbl_siteplan_documents WHERE id = $document_id ",
                            ];
                            break;

                        case 'incident_doc':
                            $sql_delete = [
                                "incident_doc" => "DELETE from incident_report_documents  WHERE  id = $document_id ",
                            ];

                            // backup query
                            $sql_reverts = [
                                "incident_doc" => "SELECT * from incident_report_documents WHERE id = $document_id ",
                            ];
                            break;

                        case 'tender_doc':
                        case 'contractor_doc':
                        case 'gsk_doc':
                        case 'preconstruction_doc':
                        case 'design_doc':
                        case 'commercial_doc':
                        case 'planning_doc':
                        case 'prestart_doc':
                        case 'site_rec_doc':
                        case 'completion_doc':

                            //delete query
                            $sql_delete = [
                                "document" => "DELETE  from tbl_documents WHERE id = $document_id ",
                            ];

                            // backup query
                            $sql_reverts = [
                                "document" => "SELECT * from tbl_documents WHERE id = $document_id ",
                            ];
                            break;

                        default:
                            # code...
                            break;
                    }

                    $dataRevert = [];
                    foreach ($sql_reverts as $key => $sqlRevert) {
                        $dataRevert[$key] = \DB::select($sqlRevert);
                    }

                    foreach($sql_delete as $sql){
                        $removeGroup = \DB::select($sql);
                    }
                    $flag = true;
                    $msg = 'Removed Document Successfully!';
                break;

                case 'register_area':
                    $action = 'removeRegisterArea';
                    //delete query
                    $sql_delete = [
                        "item_accessibility_vulnerability" => "DELETE n from tbl_item_accessibility_vulnerability_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id;",
                        "item_action_recommendation" => "DELETE n from tbl_item_action_recommendation_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id",
                        "item_additional_information" => "DELETE n from tbl_item_additional_information_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id;",
                        "item_asbestos_type" => "DELETE n from tbl_item_asbestos_type_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id",
                        "item_extent" => "DELETE n from tbl_item_extent_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id",
                        "item_no_access" => "DELETE n from tbl_item_no_access_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.area_id = $area_id;",
                        "item_licensed_non_licensed" => "DELETE n from tbl_item_licensed_non_licensed_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id;",
                        "item_material_assessment_risk" => "DELETE n from tbl_item_material_assessment_risk_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id;",
                        "item_no_acm_comment" => "DELETE n from tbl_item_no_acm_comments_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id",
                        "item_priority_assessment_risk" => "DELETE n from tbl_item_priority_assessment_risk_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id= $area_id;",
                        "item_product_debris" => "DELETE n from tbl_item_product_debris_type_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id",
                        "item_sample_comment" => "DELETE n from tbl_item_sample_comment_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id",
                        "item_sample_id" => "DELETE n from tbl_item_sample_id_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id",
                        "item_specific_location" => "DELETE n from tbl_item_specific_location_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.area_id = $area_id;",
                        "item_sub_sample" => "DELETE n from tbl_item_sub_sample_id_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id",
                        "item_unable_to_sample" => "DELETE n from tbl_item_unable_to_sample_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.area_id = $area_id;",
                        "item_info" => "DELETE n from tbl_items_info n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.area_id = $area_id",
                        "item_comment" => "DELETE n from tbl_item_comment n LEFT JOIN tbl_items i ON n.record_id = i.id WHERE i.area_id = $area_id;",
                        "items" => "DELETE i from tbl_items i  WHERE i.area_id = $area_id",
                        "location_info" => "DELETE n from tbl_location_info n LEFT JOIN tbl_location l ON n.location_id = l.id  WHERE l.area_id = $area_id;",
                        "location_comment" => "DELETE n from tbl_location_comment n LEFT JOIN tbl_location l ON n.record_id = l.id  WHERE l.area_id = $area_id;",
                        "location_construction" => "DELETE n from tbl_location_construction n LEFT JOIN tbl_location l ON n.location_id = l.id  WHERE l.area_id =$area_id;",
                        "location_void" => "DELETE n from tbl_location_void n LEFT JOIN tbl_location l ON n.location_id = l.id  WHERE l.area_id = $area_id",
                        "location" => "DELETE l from tbl_location  l  WHERE l.area_id  = $area_id",
                        "area" => "DELETE a from tbl_area a WHERE a.id = $area_id",
                    ];

                    // backup query
                    $sql_reverts = [
                        "item_accessibility_vulnerability" => "SELECT n.* from tbl_item_accessibility_vulnerability_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id;",
                        "item_action_recommendation" => "SELECT n.* from tbl_item_action_recommendation_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id",
                        "item_additional_information" => "SELECT n.* from tbl_item_additional_information_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id;",
                        "item_asbestos_type" => "SELECT n.* from tbl_item_asbestos_type_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id",
                        "item_extent" => "SELECT n.* from tbl_item_extent_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id",
                        "item_no_access" => "SELECT n.* from tbl_item_no_access_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.area_id = $area_id;",
                        "item_licensed_non_licensed" => "SELECT n.* from tbl_item_licensed_non_licensed_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id;",
                        "item_material_assessment_risk" => "SELECT n.* from tbl_item_material_assessment_risk_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id;",
                        "item_no_acm_comment" => "SELECT n.* from tbl_item_no_acm_comments_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id",
                        "item_priority_assessment_risk" => "SELECT n.* from tbl_item_priority_assessment_risk_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id= $area_id;",
                        "item_product_debris" => "SELECT n.* from tbl_item_product_debris_type_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id",
                        "item_sample_comment" => "SELECT n.* from tbl_item_sample_comment_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id",
                        "item_sample_id" => "SELECT n.* from tbl_item_sample_id_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id",
                        "item_specific_location" => "SELECT n.* from tbl_item_specific_location_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.area_id = $area_id;",
                        "item_sub_sample" => "SELECT n.* from tbl_item_sub_sample_id_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.area_id = $area_id",
                        "item_unable_to_sample" => "SELECT n.* from tbl_item_unable_to_sample_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.area_id = $area_id;",
                        "item_info" => "SELECT n.* from tbl_items_info n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.area_id = $area_id",
                        "item_comment" => "SELECT n.* from tbl_item_comment n LEFT JOIN tbl_items i ON n.record_id = i.id WHERE i.area_id = $area_id;",
                        "items" => "SELECT i.* from tbl_items i  WHERE i.area_id = $area_id",
                        "location_info" => "SELECT n.* from tbl_location_info n LEFT JOIN tbl_location l ON n.location_id = l.id  WHERE l.area_id = $area_id;",
                        "location_comment" => "SELECT n.* from tbl_location_comment n LEFT JOIN tbl_location l ON n.record_id = l.id  WHERE l.area_id = $area_id;",
                        "location_construction" => "SELECT n.* from tbl_location_construction n LEFT JOIN tbl_location l ON n.location_id = l.id  WHERE l.area_id =$area_id;",
                        "location_void" => "SELECT n.* from tbl_location_void n LEFT JOIN tbl_location l ON n.location_id = l.id  WHERE l.area_id = $area_id",
                        "location" => "SELECT l.* from tbl_location  l  WHERE l.area_id  = $area_id",
                        "area" => "SELECT a.* from tbl_area a WHERE a.id = $area_id",
                    ];
                    $dataRevert = [];
                    foreach ($sql_reverts as $key => $sqlRevert) {
                        $dataRevert[$key] = \DB::select($sqlRevert);
                    }

                    foreach($sql_delete as $sql){
                        $removeGroup = \DB::select($sql);
                    }
                    $flag = true;
                    $msg = 'Removed Register Area Successfully!';
                 break;

                case 'register_location':
                    $action = 'removeRegisterLocation';
                    //delete query
                    $sql_delete = [
                            "item_accessibility_vulnerability" => "DELETE n from tbl_item_accessibility_vulnerability_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id;",
                            "item_action_recommendation" => "DELETE n from tbl_item_action_recommendation_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id",
                            "item_additional_information" => "DELETE n from tbl_item_additional_information_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id;",
                            "item_asbestos_type" => "DELETE n from tbl_item_asbestos_type_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id",
                            "item_extent" => "DELETE n from tbl_item_extent_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id",
                            "item_no_access" => "DELETE n from tbl_item_no_access_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.location_id = $location_id;",
                            "item_licensed_non_licensed" => "DELETE n from tbl_item_licensed_non_licensed_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id;",
                            "item_material_assessment_risk" => "DELETE n from tbl_item_material_assessment_risk_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id;",
                            "item_no_acm_comment" => "DELETE n from tbl_item_no_acm_comments_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id",
                            "item_priority_assessment_risk" => "DELETE n from tbl_item_priority_assessment_risk_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id= $location_id;",
                            "item_product_debris" => "DELETE n from tbl_item_product_debris_type_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id",
                            "item_sample_comment" => "DELETE n from tbl_item_sample_comment_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id",
                            "item_sample_id" => "DELETE n from tbl_item_sample_id_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id",
                            "item_specific_location" => "DELETE n from tbl_item_specific_location_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.location_id = $location_id;",
                            "item_sub_sample" => "DELETE n from tbl_item_sub_sample_id_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id",
                            "item_unable_to_sample" => "DELETE n from tbl_item_unable_to_sample_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.location_id = $location_id;",
                            "item_info" => "DELETE n from tbl_items_info n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.location_id = $location_id",
                            "item_comment" => "DELETE n from tbl_item_comment n LEFT JOIN tbl_items i ON n.record_id = i.id WHERE i.location_id = $location_id;",
                            "items" => "DELETE i from tbl_items i  WHERE i.location_id = $location_id",
                            "location_info" => "DELETE n from tbl_location_info n  WHERE n.location_id = $location_id;",
                            "location_comment" => "DELETE n from tbl_location_comment n WHERE n.record_id = $location_id;",
                            "location_construction" => "DELETE n from tbl_location_construction n WHERE n.location_id =$location_id;",
                            "location_void" => "DELETE n from tbl_location_void n  WHERE n.location_id = $location_id",
                            "location" => "DELETE l from tbl_location  l  WHERE l.id  = $location_id",
                    ];

                    // backup query
                    $sql_reverts = [
                        "item_accessibility_vulnerability" => "SELECT n.* from tbl_item_accessibility_vulnerability_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id;",
                        "item_action_recommendation" => "SELECT n.* from tbl_item_action_recommendation_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id",
                        "item_additional_information" => "SELECT n.* from tbl_item_additional_information_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id;",
                        "item_asbestos_type" => "SELECT n.* from tbl_item_asbestos_type_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id",
                        "item_extent" => "SELECT n.* from tbl_item_extent_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id",
                        "item_no_access" => "SELECT n.* from tbl_item_no_access_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.location_id = $location_id;",
                        "item_licensed_non_licensed" => "SELECT n.* from tbl_item_licensed_non_licensed_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id;",
                        "item_material_assessment_risk" => "SELECT n.* from tbl_item_material_assessment_risk_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id;",
                        "item_no_acm_comment" => "SELECT n.* from tbl_item_no_acm_comments_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id",
                        "item_priority_assessment_risk" => "SELECT n.* from tbl_item_priority_assessment_risk_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id= $location_id;",
                        "item_product_debris" => "SELECT n.* from tbl_item_product_debris_type_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id",
                        "item_sample_comment" => "SELECT n.* from tbl_item_sample_comment_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id",
                        "item_sample_id" => "SELECT n.* from tbl_item_sample_id_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id",
                        "item_specific_location" => "SELECT n.* from tbl_item_specific_location_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.location_id = $location_id;",
                        "item_sub_sample" => "SELECT n.* from tbl_item_sub_sample_id_value n LEFT JOIN tbl_items i ON n.item_id = i.id  WHERE i.location_id = $location_id",
                        "item_unable_to_sample" => "SELECT n.* from tbl_item_unable_to_sample_value n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.location_id = $location_id;",
                        "item_info" => "SELECT n.* from tbl_items_info n LEFT JOIN tbl_items i ON n.item_id = i.id WHERE i.location_id = $location_id",
                        "item_comment" => "SELECT n.* from tbl_item_comment n LEFT JOIN tbl_items i ON n.record_id = i.id WHERE i.location_id = $location_id;",
                        "items" => "SELECT i.* from tbl_items i  WHERE i.location_id = $location_id",
                        "location_info" => "SELECT n.* from tbl_location_info n WHERE n.location_id = $location_id;",
                        "location_comment" => "SELECT n.* from tbl_location_comment n WHERE n.record_id = $location_id;",
                        "location_construction" => "SELECT n.* from tbl_location_construction n  WHERE n.location_id = $location_id;",
                        "location_void" => "SELECT n.* from tbl_location_void n WHERE n.location_id = $location_id",
                        "location" => "SELECT l.* from tbl_location  l  WHERE l.id  = $location_id",
                    ];

                    $dataRevert = [];
                    foreach ($sql_reverts as $key => $sqlRevert) {
                        $dataRevert[$key] = \DB::select($sqlRevert);
                    }

                    foreach($sql_delete as $sql){
                        $removeGroup = \DB::select($sql);
                    }
                    $flag = true;
                    $msg = 'Removed Register Location Successfully!';
                break;

                case 'register_item':
                    $action = 'removeRegisterItem';
                    //delete query
                    $sql_delete = [
                        "item_accessibility_vulnerability" => "DELETE n from tbl_item_accessibility_vulnerability_value n  WHERE n.item_id = $item_id;",
                        "item_action_recommendation" => "DELETE n from tbl_item_action_recommendation_value n  WHERE n.item_id = $item_id",
                        "item_additional_information" => "DELETE n from tbl_item_additional_information_value n  WHERE n.item_id = $item_id;",
                        "item_asbestos_type" => "DELETE n from tbl_item_asbestos_type_value n  WHERE n.item_id = $item_id",
                        "item_extent" => "DELETE n from tbl_item_extent_value n  WHERE n.item_id = $item_id",
                        "item_no_access" => "DELETE n from tbl_item_no_access_value n WHERE n.item_id = $item_id;",
                        "item_licensed_non_licensed" => "DELETE n from tbl_item_licensed_non_licensed_value n  WHERE n.item_id = $item_id;",
                        "item_material_assessment_risk" => "DELETE n from tbl_item_material_assessment_risk_value n  WHERE n.item_id = $item_id;",
                        "item_no_acm_comment" => "DELETE n from tbl_item_no_acm_comments_value n  WHERE n.item_id = $item_id",
                        "item_priority_assessment_risk" => "DELETE n from tbl_item_priority_assessment_risk_value n  WHERE n.item_id= $item_id;",
                        "item_product_debris" => "DELETE n from tbl_item_product_debris_type_value n  WHERE n.item_id = $item_id",
                        "item_sample_comment" => "DELETE n from tbl_item_sample_comment_value n  WHERE n.item_id = $item_id",
                        "item_sample_id" => "DELETE n from tbl_item_sample_id_value n  WHERE n.item_id = $item_id",
                        "item_specific_location" => "DELETE n from tbl_item_specific_location_value n WHERE n.item_id = $item_id;",
                        "item_sub_sample" => "DELETE n from tbl_item_sub_sample_id_value n  WHERE n.item_id = $item_id",
                        "item_unable_to_sample" => "DELETE n from tbl_item_unable_to_sample_value n WHERE n.item_id = $item_id;",
                        "item_info" => "DELETE n from tbl_items_info n WHERE n.item_id = $item_id",
                        "item_comment" => "DELETE n from tbl_item_comment n WHERE n.record_id = $item_id;",
                        "items" => "DELETE i from tbl_items i  WHERE i.id = $item_id"
                    ];

                    // backup query
                    $sql_reverts = [
                        "item_accessibility_vulnerability" => "SELECT n.* from tbl_item_accessibility_vulnerability_value n  WHERE n.item_id = $item_id;",
                        "item_action_recommendation" => "SELECT n.* from tbl_item_action_recommendation_value n  WHERE n.item_id = $item_id",
                        "item_additional_information" => "SELECT n.* from tbl_item_additional_information_value n  WHERE n.item_id = $item_id;",
                        "item_asbestos_type" => "SELECT n.* from tbl_item_asbestos_type_value n  WHERE n.item_id = $item_id",
                        "item_extent" => "SELECT n.* from tbl_item_extent_value n  WHERE n.item_id = $item_id",
                        "item_no_access" => "SELECT n.* from tbl_item_no_access_value n WHERE n.item_id = $item_id;",
                        "item_licensed_non_licensed" => "SELECT n.* from tbl_item_licensed_non_licensed_value n  WHERE n.item_id = $item_id;",
                        "item_material_assessment_risk" => "SELECT n.* from tbl_item_material_assessment_risk_value n  WHERE n.item_id = $item_id;",
                        "item_no_acm_comment" => "SELECT n.* from tbl_item_no_acm_comments_value n  WHERE n.item_id = $item_id",
                        "item_priority_assessment_risk" => "SELECT n.* from tbl_item_priority_assessment_risk_value n  WHERE n.item_id= $item_id;",
                        "item_product_debris" => "SELECT n.* from tbl_item_product_debris_type_value n  WHERE n.item_id = $item_id",
                        "item_sample_comment" => "SELECT n.* from tbl_item_sample_comment_value n  WHERE n.item_id = $item_id",
                        "item_sample_id" => "SELECT n.* from tbl_item_sample_id_value n  WHERE n.item_id = $item_id",
                        "item_specific_location" => "SELECT n.* from tbl_item_specific_location_value n WHERE n.item_id = $item_id;",
                        "item_sub_sample" => "SELECT n.* from tbl_item_sub_sample_id_value n  WHERE n.item_id = $item_id",
                        "item_unable_to_sample" => "SELECT n.* from tbl_item_unable_to_sample_value n WHERE n.item_id = $item_id;",
                        "item_info" => "SELECT n.* from tbl_items_info n WHERE n.item_id = $item_id",
                        "item_comment" => "SELECT n.* from tbl_item_comment n WHERE n.record_id = $item_id;",
                        "items" => "SELECT i.* from tbl_items i  WHERE i.id = $item_id"
                    ];

                    $dataRevert = [];
                    foreach ($sql_reverts as $key => $sqlRevert) {
                        $dataRevert[$key] = \DB::select($sqlRevert);
                    }

                    foreach($sql_delete as $sql){
                        $removeGroup = \DB::select($sql);
                    }
                    $flag = true;
                    $msg = 'Removed Register Item Successfully!';
                break;

                default:
                    break;
            }
            $comment_audit = \Auth::user()->full_name  . " " . $request->description ?? '';
            \CommonHelpers::logAudit(ADMIN_TOOL_TYPE, 0, AUDIT_ACTION_REMOVE, $request->type ?? '', 0 ,$comment_audit, 0 ,0);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            $error = $e->getMessage();
            $flag = false;
        }

        if ($flag) {
            $toolbox = AdminTool::create([
                'action' => $action,
                'input' => $input,
                'result' => 'success',
                'roll_back' => 0,
                'created_by' => \Auth::user()->id
            ]);
            if ($toolbox) {
                AdminToolRollback::create([
                    'admin_tool_id' => $toolbox->id,
                    'data' => json_encode($dataRevert)
                ]);
            }
            return redirect()->back()->with('msg', $msg);
        } else {
            $toolbox = AdminTool::create([
                'action' => $action,
                'input' => $input,
                'result' => $error,
                'roll_back' => 0,
                'created_by' => \Auth::user()->id
            ]);
            return redirect()->back()->with('err', 'Failed to removing item. Please try later later');
        }
    }

    public function postUnlock(Request $request) {
        try {
                \DB::beginTransaction();
                $survey_id =  $request->survey_search_old ?? null;
                $input = [
                    'description' => $request->description ?? '',
                    'reason' => $request->reason ?? ''
                ];

                $input = json_encode($input);
                $action = 'unlockSurvey';
                //delete query
                $sql_unlock = [
                    "updateItem" => "UPDATE tbl_items SET is_locked = 0 WHERE survey_id = $survey_id",
                    "updateLocation" => "UPDATE tbl_location SET is_locked = 0 WHERE survey_id = $survey_id",
                    "updateArea" => "UPDATE tbl_area SET is_locked = 0 WHERE survey_id = $survey_id",
                    "survey" => "UPDATE tbl_survey SET is_locked = 0, status = 3 WHERE id = $survey_id"
                ];

                // backup query
                $dataRevert["surveyUnlock"] =  $survey_id;

                foreach($sql_unlock as $sql){
                    $removeGroup = \DB::select($sql);
                }
                $flag = true;

                $comment_audit = \Auth::user()->full_name  . " " . $request->description ?? '';
                \CommonHelpers::logAudit(ADMIN_TOOL_TYPE, 0, AUDIT_ACTION_UNLOCKED, $request->type ?? '', 0 ,$comment_audit, 0 ,0);
                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollBack();
                $error = $e->getMessage();
                $flag = false;
            }
            if ($flag) {
                $toolbox = AdminTool::create([
                    'action' => $action,
                    'input' => $input,
                    'result' => 'success',
                    'roll_back' => 0,
                    'created_by' => \Auth::user()->id
                ]);
                if ($toolbox) {
                    AdminToolRollback::create([
                        'admin_tool_id' => $toolbox->id,
                        'data' => json_encode($dataRevert)
                    ]);
                }
                return redirect()->back()->with('msg', 'Unlock Survey Successfully!');
            } else {
                $toolbox = AdminTool::create([
                    'action' => $action,
                    'input' => $input,
                    'result' => $error,
                    'roll_back' => 0,
                    'created_by' => \Auth::user()->id
                ]);
                return redirect()->back()->with('err', 'Failed to Unlock Survey. Please try later later');
            }
    }

    public function postMerge(Request $request) {
        $input = [
            'description' => $request->description ?? '',
            'reason' => $request->reason ?? ''
        ];
        $input = json_encode($input);

        $survey_old = $request->survey_old ?? null;
        $survey_new = $request->survey_new ?? null;
        $location_old = $request->location_old ?? null;
        $location_new = $request->location_new ?? null;
        $area_old = $request->area_old ?? null;
        $area_new = $request->area_new ?? null;
        try {
            \DB::beginTransaction();
            switch ($request->type) {
                case 'survey':
                    $action = 'mergeSurvey';
                        $sql_merge = [
                            "area" => "UPDATE tbl_area SET survey_id = $survey_new WHERE survey_id = $survey_old",
                            "location" => "UPDATE tbl_location SET survey_id = $survey_new WHERE survey_id = $survey_old",
                            "item" => "UPDATE tbl_items SET survey_id = $survey_new WHERE survey_id = $survey_old",
                            "siteplan" => "UPDATE tbl_siteplan_documents SET survey_id = $survey_new WHERE survey_id = $survey_old",
                            "sample" => "UPDATE tbl_sample_certificates SET survey_id = $survey_new WHERE survey_id = $survey_old",
                            "airtest" => "UPDATE tbl_air_test_certificates SET survey_id = $survey_new WHERE survey_id = $survey_old",
                            "survey_date" => "DELETE n from tbl_survey_date n  WHERE n.survey_id = $survey_old",
                            "survey_setting" => "DELETE n from tbl_survey_setting n   WHERE n.survey_id = $survey_old",
                            "survey_info" => "DELETE n from tbl_survey_info n  WHERE n.survey_id = $survey_old",
                            "delsurvey" => "DELETE FROM tbl_survey WHERE id = $survey_old",
                        ];

                        $sql_revert = [
                            "area" => "SELECT GROUP_CONCAT(id) as id FROM tbl_area WHERE survey_id = $survey_old GROUP BY survey_id",
                            "location" => "SELECT GROUP_CONCAT(id) as id FROM tbl_location  WHERE survey_id = $survey_old GROUP BY survey_id",
                            "item" => "SELECT GROUP_CONCAT(id) as id FROM tbl_items  WHERE survey_id = $survey_old GROUP BY survey_id",
                            "siteplan" => "SELECT GROUP_CONCAT(id) as id FROM tbl_siteplan_documents  WHERE survey_id = $survey_old GROUP BY survey_id",
                            "sample" => "SELECT GROUP_CONCAT(id) as id FROM tbl_sample_certificates   WHERE survey_id = $survey_old GROUP BY survey_id",
                            "airtest" => "SELECT GROUP_CONCAT(id) as id FROM tbl_air_test_certificates  WHERE survey_id = $survey_old GROUP BY survey_id",
                            "survey_date" => "SELECT n.* from tbl_survey_date n  WHERE n.survey_id = $survey_old",
                            "survey_setting" => "SELECT n.* from tbl_survey_setting n  WHERE n.survey_id = $survey_old",
                            "survey_info" => "SELECT n.* from tbl_survey_info n  WHERE n.survey_id = $survey_old",
                            "survey" => "SELECT * FROM tbl_survey WHERE id = $survey_old",
                        ];

                        $dataRevert = [];
                        foreach ($sql_revert as $key => $sqlRevert) {
                            $dataRevert[$key] = \DB::select($sqlRevert);
                        }
                        $dataRevert['survey_old'] = $survey_old;

                        foreach($sql_merge as $sql){
                            $removeGroup = \DB::select($sql);
                        }

                        $msg = 'Merge Survey Successfully!';
                        $flag = true;
                    break;

                case 'register_area':
                    $action = 'mergeArea';
                        $sql_merge = [
                            "location" => "UPDATE tbl_location SET area_id = $area_new WHERE area_id = $area_old",
                            "item" => "UPDATE tbl_items SET area_id = $area_new WHERE area_id = $area_old",
                            "del_area" => "DELETE FROM tbl_area WHERE id = $area_old",
                        ];

                        $sql_revert = [
                            "location" => "SELECT GROUP_CONCAT(id) as id FROM tbl_location  WHERE area_id = $area_old GROUP BY area_id",
                            "item" => "SELECT GROUP_CONCAT(id) as id FROM tbl_items  WHERE area_id = $area_old GROUP BY area_id",
                            "area" => "SELECT * FROM tbl_area WHERE id = $area_old",
                        ];

                        $dataRevert = [];
                        foreach ($sql_revert as $key => $sqlRevert) {
                            $dataRevert[$key] = \DB::select($sqlRevert);
                        }
                        $dataRevert['area_old'] = $area_old;

                        foreach($sql_merge as $sql){
                            $removeGroup = \DB::select($sql);
                        }

                        $msg = 'Merge Area Successfully!';
                        $flag = true;
                    break;

                case 'register_location':
                    $action = 'mergeRoom';
                        $sql_reverts = [
                            "old_location_info" => "SELECT n.* from tbl_location_info n  WHERE n.location_id = $location_old;",
                            "old_location_comment" => "SELECT n.* from tbl_location_comment n  WHERE n.record_id = $location_old;",
                            "old_location_construction" => "SELECT n.* from tbl_location_construction n WHERE n.location_id =$location_old;",
                            "old_location_void" => "SELECT n.* from tbl_location_void n  WHERE n.location_id = $location_old",
                            "old_location" => "SELECT l.* from tbl_location  l  WHERE l.id  = $location_old",
                            "new_location_info" => "SELECT n.* from tbl_location_info n WHERE n.location_id = $location_new;",
                            "new_location_comment" => "SELECT n.* from tbl_location_comment n  WHERE n.record_id = $location_new;",
                            "new_location_construction" => "SELECT n.* from tbl_location_construction n WHERE n.location_id =$location_new;",
                            "new_location_void" => "SELECT n.* from tbl_location_void n  WHERE n.location_id = $location_new",
                            "new_location" => "SELECT l.* from tbl_location  l  WHERE l.id  = $location_new",
                            "item" => "SELECT GROUP_CONCAT(id) as id from tbl_items where location_id  = $location_old GROUP BY location_id",
                        ];


                        $dataRevert = [];
                        foreach ($sql_reverts as $key => $sqlRevert) {
                            $dataRevert[$key] = \DB::select($sqlRevert);
                        }
                        $dataRevert['location_old_id'] = $location_old;
                        $dataRevert['location_new_id'] = $location_new;

                        $this->mergeLocation($location_old, $location_new);
                        Item::where('location_id',$location_old)->update(['location_id' => $location_new]);
                        \DB::select("UPDATE `tbl_items` SET location_id = $location_new WHERE `location_id` = $location_old");
                        //delete old location
                        Location::where('id', $location_old)->delete();
                        LocationInfo::where('location_id', $location_old)->delete();
                        LocationComment::where('record_id', $location_old)->delete();
                        LocationConstruction::where('location_id', $location_old)->delete();
                        LocationVoid::where('location_id', $location_old)->delete();

                        $msg = 'Merge Location Successfully!';
                        $flag = true;
                    break;

                default:
                    # code...
                    break;
            }
            $comment_audit = \Auth::user()->full_name  . " " . $request->description ?? '';
            \CommonHelpers::logAudit(ADMIN_TOOL_TYPE, 0, AUDIT_ACTION_MERGE, $request->type ?? '', 0 ,$comment_audit, 0 ,0);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            dd($e);
            $error = $e->getMessage();
            $flag = false;
        }
        if ($flag) {
                $toolbox = AdminTool::create([
                    'action' => $action,
                    'input' => $input,
                    'result' => 'success',
                    'roll_back' => 0,
                    'created_by' => \Auth::user()->id
                ]);
                if ($toolbox) {
                    AdminToolRollback::create([
                        'admin_tool_id' => $toolbox->id,
                        'data' => json_encode($dataRevert)
                    ]);
                }
                return redirect()->back()->with('msg',$msg);
        } else {
                $toolbox = AdminTool::create([
                    'action' => $action,
                    'input' => $input,
                    'result' => $error,
                    'roll_back' => 0,
                    'created_by' => \Auth::user()->id
                ]);
                return redirect()->back()->with('err', 'Have something wrong. Please try later later');
            }

        return true;
    }

    public function mergeLocation($location_old,$location_new) {
        $location = Location::with('locationInfo', 'locationVoid', 'locationConstruction','shineDocumentStorage')->find($location_old);
        $location_new = Location::with('locationInfo', 'locationVoid', 'locationConstruction','shineDocumentStorage')->find($location_new);

        $dataUpdateLocationNew = [
            'survey_id'                    => $location->survey_id,
            'is_locked'                    => $location->is_locked,
            'state'                        => $location->state,
            'decommissioned'               => $location->decommissioned,
            'decommissioned_reason'         => $location->decommissioned_reason ,
            'not_assessed'                  => $location->not_assessed ,
            'not_assessed_reason'           => $location->not_assessed_reason ,
            'version'                      => $location->version,
            'description'                  => $location->description,
            'location_reference'           => $location->location_reference,
            'updated_by'                    => $location->updated_by ?? $location->created_by,
            'updated_at'                   =>  $location->updated_at ?? $location->created_at
        ];
        if (!is_null($location_new)) {
            Location::where('id', $location_new->id)->update($dataUpdateLocationNew);

            // store comment history
            \CommentHistory::storeCommentHistory('location', $location_new->id ?? 0, $location_new->locationInfo->comments ?? '');

            LocationInfo::where('location_id', $location_new->id)->update($location->locationInfo->toArray());

                $data_void = [
                    'ceiling_other' => ($location->locationVoid->ceiling != CELLING_VOID_OOS) ? $location->locationVoid->ceiling_other : $location_new->locationVoid->ceiling_other,
                    'ceiling' => ($location->locationVoid->ceiling != CELLING_VOID_OOS) ?
                                $this->locationMergeVoid($location->locationVoid->ceiling,$location_new->locationVoid->ceiling) : $location_new->locationVoid->ceiling,
                    'cavities_other' => ($location->locationVoid->cavities != CAVITY_OOS) ? $location->locationVoid->cavities_other : $location_new->locationVoid->cavities_other,
                    'cavities' => ($location->locationVoid->cavities != CAVITY_OOS) ?
                                $this->locationMergeVoid($location->locationVoid->cavities,$location_new->locationVoid->cavities) : $location_new->locationVoid->cavities,
                    'risers_other' => ($location->locationVoid->risers != RISER_OOS) ? $location->locationVoid->risers_other : $location_new->locationVoid->risers_other,
                    'risers' => ($location->locationVoid->risers != RISER_OOS) ?
                                $this->locationMergeVoid($location->locationVoid->risers,$location_new->locationVoid->risers) : $location_new->locationVoid->risers,
                    'ducting_other' => ($location->locationVoid->ducting != DUCTING_OOS) ? $location->locationVoid->ducting_other : $location_new->locationVoid->ducting_other,
                    'ducting' => ($location->locationVoid->ducting != DUCTING_OOS) ?
                                $this->locationMergeVoid($location->locationVoid->ducting,$location_new->locationVoid->ducting) : $location_new->locationVoid->ducting,
                    'boxing_other' =>($location->locationVoid->boxing != BOXING_OOS) ? $location->locationVoid->boxing_other : $location_new->locationVoid->boxing_other,
                    'boxing' => ($location->locationVoid->boxing != BOXING_OOS) ?
                                $this->locationMergeVoid($location->locationVoid->boxing,$location_new->locationVoid->boxing) : $location_new->locationVoid->boxing,
                    'pipework_other' => ($location->locationVoid->pipework != PIPE_WORK_OOS) ? $location->locationVoid->pipework_other : $location_new->locationVoid->pipework_other,
                    'pipework' => ($location->locationVoid->pipework != PIPE_WORK_OOS) ?
                                $this->locationMergeVoid($location->locationVoid->pipework,$location_new->locationVoid->pipework) : $location_new->locationVoid->pipework,
                    'floor_other' => ($location->locationVoid->floor != FLOOR_VOID_OOS) ? $location->locationVoid->floor_other : $location_new->locationVoid->floor_other,
                    'floor' => ($location->locationVoid->floor != FLOOR_VOID_OOS) ?
                                $this->locationMergeVoid($location->locationVoid->floor,$location_new->locationVoid->floor) : $location_new->locationVoid->floor,
                ];
                LocationVoid::where('location_id', $location_new->id)->update($data_void);


                $celing  = $this->stringToArray($location->locationConstruction->ceiling ?? null);
                $walls  = $this->stringToArray($location->locationConstruction->walls ?? null);
                $floor  = $this->stringToArray($location->locationConstruction->floor ?? null);
                $doors  = $this->stringToArray($location->locationConstruction->doors ?? null);
                $windows  = $this->stringToArray($location->locationConstruction->windows ?? null);
                $data_construction = [
                    'ceiling' => !in_array(CELLING_CONSTRUCTION_OOS, $celing) ?
                                $this->locationMergeConstruction($location->locationConstruction->ceiling,$location_new->locationConstruction->ceiling) : $location_new->locationConstruction->ceiling ?? '',
                    'ceiling_other' => !in_array(CELLING_CONSTRUCTION_OOS, $celing) ? $location->locationConstruction->ceiling_other ?? '' : $location_new->locationConstruction->ceiling_other ?? '',
                    'walls' => !in_array(WALL_CONSTRUCTION_OOS, $walls) ?
                                $this->locationMergeConstruction($location->locationConstruction->walls,$location_new->locationConstruction->walls): $location_new->locationConstruction->walls ?? '',
                    'walls_other' => !in_array(WALL_CONSTRUCTION_OOS, $walls) ? $location->locationConstruction->walls_other ?? '' : $location_new->locationConstruction->walls_other ?? '',
                    'floor' => !in_array(FLOOR_CONSTRUCTION_OOS, $floor) ?
                                $this->locationMergeConstruction($location->locationConstruction->floor,$location_new->locationConstruction->floor) : $location_new->locationConstruction->floor ?? '',
                    'floor_other' => !in_array(FLOOR_CONSTRUCTION_OOS, $floor) ? $location->locationConstruction->floor_other ?? '' : $location_new->locationConstruction->floor_other ?? '',
                    'doors' => !in_array(DOOR_CONSTRUCTION_OOS, $doors) ?
                                $this->locationMergeConstruction($location->locationConstruction->doors,$location_new->locationConstruction->doors) : $location_new->locationConstruction->doors ?? '',
                    'doors_other' => !in_array(DOOR_CONSTRUCTION_OOS, $doors) ? $location->locationConstruction->doors_other ?? '' : $location_new->locationConstruction->doors_other ?? '',
                    'windows' => !in_array(WINDOWN_CONSTRUCTION_OOS, $windows) ?
                                $this->locationMergeConstruction($location->locationConstruction->windows,$location_new->locationConstruction->windows) : $location_new->locationConstruction->windows ?? '',
                    'windows_other' => !in_array(WINDOWN_CONSTRUCTION_OOS, $windows) ? $location->locationConstruction->windows_other ?? '' : $location_new->locationConstruction->windows_other ?? '',
                ];
                LocationConstruction::where('location_id', $location_new->id)->update($data_construction);


            // for One to Many relation
            if (isset($location->shineDocumentStorage) and !is_null($location->shineDocumentStorage)) {
                ShineDocumentStorage::updateOrCreate(
                                            ['object_id' => $location_new->id, 'type' => LOCATION_IMAGE],
                                            [
                                                "path" => $location->shineDocumentStorage->path ,
                                                "file_name" => $location->shineDocumentStorage->file_name ,
                                                "mime" => $location->shineDocumentStorage->mime ,
                                                "size" => $location->shineDocumentStorage->size ,
                                                "addedBy" => $location->shineDocumentStorage->addedBy ,
                                                "addedDate" => $location->shineDocumentStorage->addedDate ,
                                            ]);
            }

        }
        return true;
    }

    public function locationMergeVoid($new_data, $old_data) {
        $new_data_array = explode(",",$new_data);
        $old_data_array = explode(",",$old_data);
        if (count($new_data_array) > 0 and count($old_data_array) > 0) {
            // same parent select , merge old data with new data
            if ($new_data_array[0] == $old_data_array[0]) {
                $data = array_merge($new_data_array,$old_data_array);
                $data = array_unique($data);
                return implode(",",$data);
            }
        }
        return $new_data;
    }

    public function locationMergeConstruction($new_data, $old_data){
        $new_data_array = explode(",",$new_data);
        $old_data_array = explode(",",$old_data);
        $data = array_merge($new_data_array,$old_data_array);
        $data = array_unique($data);
        return implode(",",$data);
    }

    public function stringToArray($string) {
        if (isset($string)) {
            if (!is_null($string)) {
                return explode(",",$string);
            }
        }
        return [];
    }

    public function upload() {
        if (!\CommonHelpers::isSystemClient()) {
            abort(404);
        }
        return view('admin_tool.upload');
    }

    public function template(Request $request) {
        if ($request->has('type')) {
            switch ($request->type) {
                case 'property':
                    return \Storage::download('Property Upload CSV.csv');
                    break;
                case 'users':
                    return \Storage::download('UploadUsersTemplate.csv');
                    break;
                case 'programmes':
                    return \Storage::download('Upload Programmes.xlsx');
                    break;
                case 'systems':
                    return \Storage::download('Upload Systems and Programmes.xlsx');
                    break;

                default:
                    return redirect()->back()->with('err', 'No Template found');
                    break;
            }
        }
         return redirect()->back()->with('err', 'No Template found');
    }

    public function postUpload(UploadRequest $uploadRequest) {
        $data = $uploadRequest->validated();
        if ($uploadRequest->type == 'programmes') {
            $data = $this->adminToolService->uploadProgramme($uploadRequest->document);
            if (is_null($data)) {
                return redirect()->back()->with('err', 'Please upload a document');
            } else {
                if ($data['status_code'] == STATUS_OK) {
                    return redirect()->back()->with('msg', $data['msg']);
                } else {
                    return redirect()->back()->with('err', $data['msg']);
                }
            }
        } elseif($uploadRequest->type == 'systems') {
            $data = $this->adminToolService->uploadSystemAndProgramme($uploadRequest->document);
            if (is_null($data)) {
                return redirect()->back()->with('err', 'Please upload a document');
            } else {
                if ($data['status_code'] == STATUS_OK) {
                    return redirect()->back()->with('msg', $data['msg']);
                } else {
                    return redirect()->back()->with('err', $data['msg']);
                }
            }
        }

        // for old toolbox
        try {
            $prop_reference = [];
            // Get uploaded CSV file

            // $path = $uploadRequest->file('document')->getRealPath();
            $file_upload = \Storage::disk('local')->putFileAs('', $uploadRequest->file('document'),'Fileupload'.time().'.csv');

            // dd();
            // $file = \SimpleCSV::import($path);
            // dd($file_upload);
            // \Config::set('excel.import.encoding.input', 'windows-1252'); \Config::set('excel.import.encoding.output', 'windows-1252');
            // $file = \Excel::toArray([], $file_upload);
            // $file = $file[0];
            // remove last wrong row
            $file = $this->readCSV(storage_path().'/app/'.$file_upload,array('delimiter' => ','));

            array_pop($file);

            //remove header
            array_shift($file);
            if (count($file)) {

                if($data['type'] == 'property') {

                    $service_area_dropdown =  ServiceArea::all();
                    $all_zones = Zone::all();
                    $zone_childs = Zone::where('parent_id','!=',0)->get();
                    $asset_class_dropdown = AssetClass::where('is_deleted', '!=', 1)->get();
                    $tenure_type_dropdown = TenureType::all();
                    $communal_area_dropdown = CommunalArea::where('is_deleted', '!=', 1)->get();
                    $responsibility_dropdown = Responsibility::all();
                    $user = User::all();
                    $clients = Client::all();
                    $property = Property::all();
                    $property_access_type_dropdown = PropertyProgrammeType::where('is_deleted', '!=', 1)->get();
                    $property_info_dropdown_data = PropertyInfoDropdownData::all();
                    $electric_dropdowns = PropertyDropdown::where('dropdown_id', 1)->get();
                    $gas_dropdowns = PropertyDropdown::where('dropdown_id', 2)->get();
                    $loft_dropdowns = PropertyDropdown::where('dropdown_id', 3)->get();
                    $primary_dropdowns = DropdownDataProperty::where('dropdown_property_id', PRIMARY_AND_SECONDARY_USE_ID)->get();
                    \DB::beginTransaction();

                    foreach($file as $prop_data){
//                        if(count($prop_data) < 40) {
//                            return redirect()->back()->with('err', 'The uploaded document is invalid format!');
//                        }
                        $property_reference           =      utf8_encode(trim($prop_data[0] ?? ''));
                        $pblock         =      utf8_encode(trim($prop_data[1] ?? ''));
                        $parent_name     =     utf8_encode(trim($prop_data[2] ?? ''));
                        $service_area   =      utf8_encode(trim($prop_data[3] ?? ''));
                        $prop_name    =      utf8_encode(trim($prop_data[4] ?? ''));
                        $estate_code    =      utf8_encode(trim($prop_data[5] ?? ''));
                        $group_1      =      utf8_encode(trim($prop_data[6] ?? ''));
                        $asset_class     =      utf8_encode(trim($prop_data[7] ?? ''));
                        $asset_type     =      utf8_encode(trim($prop_data[8] ?? ''));
                        $property_access_type    =      utf8_encode(trim($prop_data[9] ?? ''));
                        $communal_area  =      utf8_encode(trim($prop_data[10] ?? ''));
                        $responsibility =      utf8_encode(trim($prop_data[11] ?? ''));
                        $flat_number       =      utf8_encode(trim($prop_data[12] ?? ''));
                        $building_name  =      utf8_encode(trim($prop_data[13] ?? ''));
                        $street_number     =      utf8_encode(trim($prop_data[14] ?? ''));
                        $street_name    =      utf8_encode(trim($prop_data[15] ?? ''));
                        $town           =      utf8_encode(trim($prop_data[16] ?? ''));
                        $county         =      utf8_encode(trim($prop_data[17] ?? ''));
                        $post_code      =      utf8_encode(trim($prop_data[18] ?? ''));
                        $decommission      =      utf8_encode(trim($prop_data[19] ?? ''));
                        $property_contact      =      utf8_encode(trim($prop_data[20] ?? ''));
                        $property_status      =      utf8_encode(trim($prop_data[21] ?? ''));
                        $property_occupied      =      utf8_encode(trim($prop_data[22] ?? ''));
                        $primary      =      utf8_encode(trim($prop_data[23] ?? ''));
                        $secondary      =      utf8_encode(trim($prop_data[24] ?? ''));
                        $construction_age    =      utf8_encode(trim($prop_data[25] ?? ''));
                        $stair_contruction    =      utf8_encode(trim($prop_data[26] ?? ''));
                        $floor_contruction  =    utf8_encode(trim($prop_data[27] ?? ''));
                        $external_con =    utf8_encode(trim($prop_data[28] ?? ''));
                        $external_finish =    utf8_encode(trim($prop_data[29] ?? ''));
                        $listed_building =    utf8_encode(trim($prop_data[30] ?? ''));
                        $floor_above_ground = utf8_encode(trim($prop_data[31] ?? ''));
                        $floor_below_ground = utf8_encode(trim($prop_data[32] ?? ''));
                        $no_stair =   utf8_encode(trim($prop_data[33] ?? ''));
                        $no_lift =    utf8_encode(trim($prop_data[34] ?? ''));
                        $loft_void =    utf8_encode(trim($prop_data[35] ?? ''));
                        $net_area =    utf8_encode(trim($prop_data[36] ?? ''));
                        $gross_area =    utf8_encode(trim($prop_data[37] ?? ''));
                        $comment =    utf8_encode(trim($prop_data[38] ?? ''));

//                        $uprn = $uprn ? str_pad($uprn, 6, '0', STR_PAD_LEFT) : '';
                        \DB::enableQueryLog();
                        $service_area_id = $service_area_dropdown->where('description', $service_area)->first()->id ?? NULL;

//                        $client_id = $clients->where('name',$client)->first()->id ?? 1;
                        $asset_class_id = $asset_class_dropdown->where('description' , $asset_class)->where('parent_id', 0)->first()->id ?? NULL;
                        $asset_type_id = $asset_class_dropdown->where('description' , $asset_type)->where('parent_id', $asset_class_id)->first()->id ?? NULL;
//                        $tenure_type_id = $tenure_type_dropdown->where('description' , $tenure_type)->first()->id ?? NULL;
                        $communal_area_id = $communal_area_dropdown->where('description' , $communal_area)->first()->id ?? NULL;
                        $parent_id = $property->where('name' , $parent_name)->first()->id ?? NULL;
                        $responsibility_id = $responsibility_dropdown->where('description' , $responsibility)->first()->id ?? NULL;
                        switch ($service_area){
                            case ("Central Area Service Centre"):
                                $parent_zone_id = 2;
                                break;
                            case ("North Area Service Centre"):
                                $parent_zone_id = 3;
                                break;
                            case ("South Area Service Centre"):
                                $parent_zone_id = 4;
                                break;
                            case ("West Area Service Centre"):
                                $parent_zone_id = 5;
                                break;
                            default:
                                $parent_zone_id = 6;
                                break;
                        }

                        $parent_zone_data = Zone::where('parent_id' , $parent_zone_id)->get() ?? NULL;
                        foreach ($parent_zone_data as $key => $value) {
                            $sim = similar_text($group_1, $value->zone_name, $percent);
                            $percents[] = $percent;
                            if ($percent > 88) {
                                $zone = $value->zone_name;
                            }
                        }
                        $zone_id =  $all_zones->where('zone_name', $zone ?? '')->where('parent_id' , $parent_zone_id)->first()->id ?? NULL;
                        $property_contact_data = explode(",", $property_contact);
                        $property_status_id = $property_info_dropdown_data->where('description' , $property_status)->first()->id ?? NULL;
                        $property_occupied_id = $property_info_dropdown_data->where('description' , $property_occupied)->first()->id ?? NULL;
                        $property_access_type_id = $property_access_type_dropdown->where('description' , $property_access_type)->first()->id ?? NULL;

                        $property_access_type_other = $property_access_type && !$property_access_type_id ? $property_access_type : NULL ;
                        $property_access_type_id = $property_access_type_other ? 25 : $property_access_type_id;

                        $primary_data = $primary_dropdowns->where('description', $primary)->first();
                        if (!is_null($primary_data)) {
                            $primary_id = $primary_data->id ?? 0;
                            $primary_other = '';
                        } else {
                            $primary_id = 13;
                            $primary_other = $primary;
                        }
                        $secondary_data = $primary_dropdowns->where('description', $secondary)->first();
                        if (!is_null($secondary_data)) {
                            $secondary_id = $secondary_data->id ?? 0;
                            $secondary_other = '';
                        } else {
                            $secondary_id = 13;
                            $secondary_other = $secondary;
                        }

                        $stair_contruction_data = $property_info_dropdown_data->where('description', $stair_contruction)->where('property_info_dropdown_id' , 7)->first();
                        if (!is_null($stair_contruction_data)) {
                            $stair_contruction_id = $stair_contruction_data->id ?? 0;
                            $stair_contruction_orther = '';
                        } else {
                            $stair_contruction_id = 21;
                            $stair_contruction_orther = $stair_contruction;
                        }

                        $floor_contruction_data = $property_info_dropdown_data->where('description', $floor_contruction)->where('property_info_dropdown_id' , 8)->first();
                        if (!is_null($floor_contruction_data)) {
                            $floor_contruction_id = $floor_contruction_data->id ?? 0;
                            $floor_contruction_other = '';
                        } else {
                            $floor_contruction_id = 24;
                            $floor_contruction_other = $stair_contruction;
                        }

                        $external_con_data = $property_info_dropdown_data->where('description', $external_con)->where('property_info_dropdown_id' , 9)->first();
                        if (!is_null($external_con_data)) {
                            $external_con_id = $external_con_data->id ?? 0;
                            $external_con_other = '';
                        } else {
                            $external_con_id = 28;
                            $external_con_other = $external_con;
                        }

                        $external_finish_data = $property_info_dropdown_data->where('description', $external_finish)->where('property_info_dropdown_id' , 10)->first();
                        if (!is_null($external_finish_data)) {
                            $external_finish_id = $external_finish_data->id ?? 0;
                            $external_finish_other = '';
                        } else {
                            $external_finish_id = 36;
                            $external_finish_other = $external_con;
                        }

                        $listed_building_data = $property_info_dropdown_data->where('description', $listed_building)->where('property_info_dropdown_id' , 3)->first();
                        if (!is_null($listed_building_data)) {
                            $listed_building_id = $listed_building_data->id ?? 0;
                            $listed_building_other = '';
                        } else {
                            $listed_building_id = 9;
                            $listed_building_other = $external_con;
                        }

//                        $electric_id = $electric_dropdowns->where('description', $electric)->first()->id ?? 0;
//                        $gas_id = $gas_dropdowns->where('description', $gas)->first()->id ?? 0;
                        $loft_void_id = $loft_dropdowns->where('description', $loft_void)->first()->id ?? 0;


                        $array_dropdown = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15];

//                        if (in_array($no_floor, $array_dropdown)) {
//                            $no_floor_text = $no_floor;
//                            $no_floor_other = null;
//                        } else {
//                            $no_floor_text = 'Other';
//                            $no_floor_other = $no_floor;
//                        }
                        if (in_array($no_stair, $array_dropdown)) {
                            $no_stair_text = $no_stair;
                            $no_stair_other = null;
                        } else {
                            $no_stair_text = 'Other';
                            $no_stair_other = $no_stair;
                        }
                        if (in_array($no_lift, $array_dropdown)) {
                            $no_lift_text = $no_lift;
                            $no_lift_other = null;
                        } else {
                            $no_lift_text = 'Other';
                            $no_lift_other = $no_lift;
                        }

                        if (in_array($floor_above_ground, $array_dropdown)) {
                            $floor_above_ground_text = $floor_above_ground;
                            $floor_above_ground_other = null;
                        } else {
                            $floor_above_ground_text = 'Other';
                            $floor_above_ground_other = $floor_above_ground;
                        }

                        if (in_array($floor_below_ground, $array_dropdown)) {
                            $floor_below_ground_text = $floor_below_ground;
                            $floor_below_ground_other = null;
                        } else {
                            $floor_below_ground_text = 'Other';
                            $floor_below_ground_other = $floor_below_ground;
                        }
                        $decommission_id = 0;
                        if($decommission == "Live"){
                            $decommission_id == 0;
                        }else{
                            $decommission_id == 1;
                        }
                        $data_property = [
                            'property_reference' => $property_reference,
                            'pblock' => $pblock,
                            'parent_id' => $parent_id ?? 0,
                            'client_id' => 1,
                            'service_area_id' => $service_area_id,
                            'zone_id' => $zone_id,
                            'name' => $prop_name,
                            'estate_code' => $estate_code,
                            'asset_class_id' => $asset_class_id,
                            'asset_type_id' => $asset_type_id,
                            'decommssioned' => $decommission_id,
//                                    'tenure_type_id' => $tenure_type_id,
                            'communal_area_id' => $communal_area_id,
                            'responsibility_id' => $responsibility_id
                        ];

                        $data_property_info = [
                            'flat_number' => $flat_number,
                            'building_name' => $building_name,
                            'street_number' => $street_number,
                            'street_name' => $street_name,
                            'team1' => $property_contact_data[0] ?? 0,
                            'team2' => $property_contact_data[1] ?? 0,
                            'team3' => $property_contact_data[2] ?? 0,
                            'team4' => $property_contact_data[3] ?? 0,
                            'team5' => $property_contact_data[4] ?? 0,
                            'team6' => $property_contact_data[5] ?? 0,
                            'team7' => $property_contact_data[6] ?? 0,
                            'team8' => $property_contact_data[7] ?? 0,
                            'team9' => $property_contact_data[8] ?? 0,
                            'team10' => $property_contact_data[9] ?? 0,
//                                    'address_1' => $address_1,
//                                    'address_2' => $address_2,
//                                    'city' => $city,
                            'town' => $town,
//                                    'telephone' => $telephone,
//                                    'email' => $email,
//                                    'mobile' => $mobile,
                            'address5' => $county,//county
                            'postcode' => $post_code
                        ];

                        $data_property_survey = [
                            "asset_use_primary" => $primary_id,
                            "asset_use_primary_other" => $primary_other,
                            "asset_use_secondary" => $secondary_id,
                            "asset_use_secondary_other" => $secondary_other,
                            'construction_age' => $construction_age,
                            'property_status' => $property_status_id,
                            'property_occupied' => $property_occupied_id,
//                                    'construction_type' => $construction_type,
//                                    "size_floors" => $no_floor_text,
//                                    "size_floors_other" => $no_floor_other,
                            "size_staircases" => $no_stair_text,
                            "size_staircases_other" => $no_stair_other,
                            "size_lifts" => $no_lift_text,
                            "size_lifts_other" => $no_lift_other,
//                                    "electrical_meter" => $electric_id,
//                                    "gas_meter" => $gas_id,
                            "loft_void" => $loft_void_id,
                            'programme_type' => $property_access_type_id,
                            'programme_type_other' => $property_access_type_other,
                            "size_net_area" => $net_area,
//                                    "size_bedrooms" => $bed_room,
                            'size_gross_area' => $gross_area,
                            'size_comments' => $comment,
                            'stairs' => $stair_contruction_id,
                            'stairs_other' => $stair_contruction_orther,
                            'floors' => $floor_contruction_id,
                            'floors_other' => $floor_contruction_other,
                            'wall_construction' => $external_con_id,
                            'wall_construction_other' => $external_con_other,
                            'wall_finish' => $external_finish_id,
                            'wall_finish_other' => $external_finish_other,
                            "listed_building" => $listed_building_id,
                            "listed_building_other" => $listed_building_other,
                            'floors_above' => $floor_above_ground_text,
                            'floors_above_other' => $floor_above_ground_other,
                            'floors_below' => $floor_below_ground_text,
                            'floors_below_other' => $floor_below_ground_other,
                        ];


                        $property =  Property::create($data_property);

                        if ($property) {
                            $reference = 'PL'.$property->id;
                            Property::where('id', $property->id)->update(['reference' => $reference]);
                            $property->propertyType()->sync(\CommonHelpers::checkArrayKey($data,'riskType'));

                            PropertyInfo::updateOrCreate(['property_id' => $property->id], $data_property_info);
                            PropertySurvey::updateOrCreate(['property_id' => $property->id], $data_property_survey);
                            //log audit
                            $comment = \Auth::user()->full_name . " bulk upload new Property " . $property->name;
                            \CommonHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_ADD, $property->property_reference, $property->client_id, $comment, 0 , $property->id);

                            // set view property EMP
                            \CompliancePrivilege::setViewEMP(JR_PROPERTY_EMP, $property->id );

                            // set update property EMP
                            \CompliancePrivilege::setUpdateEMP(JR_UPDATE_PROPERTY_EMP, $property->id );

                        }
                    }
                    \DB::commit();
                    return redirect()->back()->with('msg', 'Uploaded Properties Successfully!');
                } else {
                    // 0 => "[User Name]"
                    // 1 => "[Forename] [Surname]"
                    // 2 => "[Email Address]"
                    // 3 => "[Job Role]"
                    // 4 => "[Telephone]"
                    // 5 => "[Mobile]"
                    // 6 => "[Organisation]"
                    // 7 => "[Department]"

                    $departments = Department::all();
                    $departmentContractors = DepartmentContractor::all();
                    $clients = Client::all();
                    $roles = Role::all();
                    \DB::beginTransaction();
                    foreach($file as $user_data){
                        if(count($user_data) < 7) {
                            return redirect()->back()->with('err', 'The uploaded document is invalid format!');
                        }
                        // get name from Forename and Surname
                        $name = explode(" ",trim($user_data[1] ?? ''));

                        $user_name           =     trim($user_data[0] ?? '');
                        $first_name         =     $name[0] ?? '';
                        $last_name         =     $name[1] ?? '';
                        $email     =         trim($user_data[2] ?? '');
                        $role   =     trim($user_data[3] ?? '');
                        $telephone      =     trim($user_data[4] ?? '');
                        $mobile       =     trim($user_data[5] ?? '');
                        $organisation  =     trim($user_data[6] ?? '');
                        $department     =     trim($user_data[7] ?? '');

                        $role_id = $roles->where('name', $role)->first()->id ?? 0;
                        $client_id = $clients->where('reference', $organisation)->first()->id ?? NULL;
                        $client_type = $clients->where('reference', $organisation)->first()->client_type ?? 0;
                        if ($client_type == 0) {
                            $department_id = $departments->where('name', $department)->first()->id ?? NULL;
                        } else {
                            $department_id = $departmentContractors->where('name', $department)->first()->id ?? NULL;
                        }
                        // default password
                        $password = 'ShineVisionX';
                        $data_user = [
                            "username" => $user_name ,
                            "password" => \Hash::make($password) ,
                            "client_id" => $client_id,
                            "department_id" => $department_id,
                            "first_name" => $first_name,
                            "last_name" => $last_name,
                            "email" => $email,
                            "role" => $role_id,
                            "is_site_operative" => 0,
                            "joblead" => 0,
                            "is_locked" => 1
                        ];
                        $user = User::create($data_user);
                        $refUser = "ID" . $user->id;
                        User::where('id', $user->id )->update(['shine_reference' => $refUser]);
                        $data_contact = [
                            'user_id' => $user->id,
                            'telephone' => $telephone,
                            "mobile" => $mobile
                        ];
                        Contact::create($data_contact);
                    }
                    \DB::commit();
                    return redirect()->back()->with('msg', 'Uploaded Users Successfully!');
                }
            } else {
                return redirect()->back()->with('err', 'The uploaded document is empty!');
            }

        } catch (\Exception $e) {
            \DB::rollback();
            dd($e);
            return redirect()->back()->with('err', 'Failed to upload bulk. Please try again!');
        }

    }
    public function readCSV($csvFile, $array)
    {
        $file_handle = fopen($csvFile, 'r');
        while (!feof($file_handle)) {
            $line_of_text[] = fgetcsv($file_handle, 0, $array['delimiter']);
        }
        fclose($file_handle);
        return $line_of_text;
    }
    public function parse_csv($file, $options = null) {
        $delimiter = empty($options['delimiter']) ? "," : $options['delimiter'];
        $to_object = empty($options['to_object']) ? false : true;
        $str = file_get_contents($file);
        $lines = explode("\n", $str);
        // pr($lines);
        $field_names = explode($delimiter, array_shift($lines));
        $res = [];
        foreach ($lines as $line) {
            // Skip the empty line
            if (empty($line)) continue;
            $fields = explode($delimiter, $line);
            $_res = $to_object ? new stdClass : array();
            foreach ($field_names as $key => $f) {
                if ($to_object) {
                    $_res->{$f} = $fields[$key];
                } else {
                    $_res[$f] = $fields[$key] ?? 0;
                }
            }
            $res[] = $_res;
        }
        return $res;
    }

}
