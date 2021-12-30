<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\RoleUpdate;
use App\Models\Property;
use App\Models\Project;
use App\Models\PrivilegeChild;
use App\Models\Client;
use App\Models\Template;
use App\Models\ProjectType;
use App\Models\SummaryType;

class PrivilegeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public static function checkPermission($option, $param = '') {

        // if contractor user
        if( \Auth::user()->clients->client_type != 0) {
            return true;
        }

        $role = \Auth::user()->userRole;
        if (is_null($role)) {
            return true;
        }
        // view everything
        if ($role->is_everything == 1) {
            return true;
        }

        switch ($option) {
            case PROPERTY_PERMISSION:
                $property_list = explode(",",$role->property);
                if (in_array($param, $property_list)) {
                    return true;
                } else {
                    return false;
                }
                break;

            case DYNAMIC_WARNING_BANNERS:
                if (in_array(WARNING_BANNERS, $role->view_privilege)) {
                    return true;
                } else {
                    return false;
                }
                break;

            case PROJECT_TYPE_PERMISSION:
                if (in_array($param, $role->project_type)) {
                    return true;
                } else {
                    return false;
                }
                break;

            case PROJECT_DOCUMENT_TYPE_PERMISSION:
                if (in_array($param, $role->project_information)) {
                    return true;
                } else {
                    return false;
                }
                break;

            case REPORT_PERMISSION:

                if (in_array($param, $role->report)) {
                    return true;
                } else {
                    return false;
                }
                break;

            case CATEGORY_BOX_PERMISSION:

                if (in_array($param, $role->category_box)) {
                    return true;
                } else {
                    return false;
                }
                break;

            case DATA_CENTRE_VIEW_PRIV:
                $data_center_arr = [SURVEYS_VIEW_PRIV, PROJECTS_VIEW_PRIV, CRITICAL_VIEW_PRIV, URGENT_VIEW_PRIV, IMPORTANT_VIEW_PRIV, ATTENTION_VIEW_PRIV, DEADLINE_VIEW_PRIV, APPROVAL_VIEW_PRIV, REJECTED_VIEW_PRIV];
                $check = array_intersect($data_center_arr, $role->view_privilege);
                return empty($check) ? false : true;
                break;

            case PROPERTY_LISTING_VIEW_PRIV:
                if (is_null($role->property) || empty($role->property)) {
                    return false;
                } else {
                    return true;
                }
                break;

            case ORGANISATION_VIEW_PRIV:
                if (is_null($role->contractor) || empty($role->contractor)) {
                    return false;
                } else {
                    return true;
                }
                break;

            case REPORT_VIEW_PRIV:
                if (is_null($role->report) || empty($role->report)) {
                    return false;
                } else {
                    return true;
                }
                break;

            case CATEGORY_BOX_VIEW_PRIV:

                if (is_null($role->category_box) || empty($role->category_box)) {
                    return false;
                } else {
                    return true;
                }
                break;

            case RESOURCES_VIEW_PRIV:
                $other_resouce = [BLUE_LIGHT_SERVICES_VIEW_PRIV, RESOURCE_E_LEARNING_VIEW_PRIV];
                $check = array_intersect($other_resouce, $role->view_privilege);
                $other_check =  empty($check) ? false : true;

                if ( (is_null($role->category_box) || empty($role->category_box) ) and !$other_check) {
                    return false;
                } else {
                    return true;
                }
                break;

            default:
                if (in_array($option, $role->view_privilege)) {
                    return true;
                } else {
                    return false;
                }
                break;
        }
    }

    public static function checkUpdatePermission($option, $param = '') {

        // if contractor user
        if( \Auth::user()->clients->client_type != 0) {
            return true;
        }

        $role = \Auth::user()->userRoleUpdate;
        if (is_null($role)) {
            return true;
        }
        //view everything
        if ($role->is_everything == 1) {
            return true;
        }

        switch ($option) {
            case PROPERTY_PERMISSION:
                $property_list = explode(",",$role->property);

                if (in_array($param, $property_list)) {
                    return true;
                } else {
                    return false;
                }
                break;

            case PROJECTS_TYPE_UPDATE_PRIV:

                if (in_array($param, $role->project_type)) {
                    return true;
                } else {
                    return false;
                }
                break;

            case PROJECT_INFORMATIONS_UPDATE_PRIV:
                if (in_array($param, $role->project_information)) {
                    return true;
                } else {
                    return false;
                }
                break;

            case DATA_CENTRE_UPDATE_PRIV:

                if (in_array($param, $role->data_center)) {
                    return true;
                } else {
                    return false;
                }
                break;

            case CATEGORY_BOX_PERMISSION:

                if (in_array($param, $role->category_box)) {
                    return true;
                } else {
                    return false;
                }
                break;

            default:
                if (in_array($option, $role->update_privilege)) {
                    return true;
                } else {
                    return false;
                }
                break;
        }
    }

    public static function getPermission($option, $type = 'array') {
        $role = \Auth::user()->userRole;
        if (is_null($role)) {
            if ($type == 'sql') {
                return '(0)';
            } else{
                return [];
            }
        }

        switch ($option) {
            case PROPERTY_PERMISSION:
                $all_property_id = Property::pluck('id')->toArray();

                // for contractor
                if (!\CommonHelpers::isSystemClient() || $role->is_everything == 1) {
                    if ($type == 'sql') {
                        if (empty($all_property_id)) {
                            return '(0)';
                        }
                        $all_property_id =  '('.implode(',',$all_property_id).')';
                        return $all_property_id;
                    } else{
                        return $all_property_id;
                    }
                }

                if ($type == 'sql') {
                    return '(' .($role->property ?? 0). ')';
                } else{
                    return  explode(",",$role->property);
                }

                break;

            case PROJECT_TYPE_PERMISSION:
                if ($role->is_everything == 1) {
                    $all_type = ProjectType::pluck('id')->toArray();
                    if ($type == 'sql') {
                        if (is_null($all_type)) {
                            return '(0)';
                        }
                        return '('.implode(',',$all_type).')';
                    } else{
                        return $all_type;
                    }
                }

                if ($type == 'sql') {
                    return '(' .($role->project_type_raw ?? 0). ')';
                } else{
                    return (is_null($role->project_type_raw)) ? [] : explode(",",$role->project_type_raw);
                }

                break;

            case CONTRACTOR_PERMISSION:
                if ($role->is_everything == 1) {
                    return Client::pluck('id')->toArray();
                }

                return (is_null($role->contractor)) ? [] : array_keys($role->contractor);
                break;

            case CATEGORY_BOX_PERMISSION:
                if ($role->is_everything == 1) {
                    return Template::pluck('id')->toArray();
                }
                return $role->category_box;
                break;

            case PROJECT_DOCUMENT_TYPE_PERMISSION:
                if ($role->is_everything == 1) {
                    $all_type = PrivilegeChild::where('type',1)->pluck('id')->toArray();
                    return $all_type;
                }

                $project_type_list = self::convertProjectDocumentTypeId(explode(",",$role->project_information_raw));
                if ($type == 'sql') {
                    return $project_type_list = '(' .implode(',',$project_type_list). ')';
                } else{
                    if (is_null($role->project_information_raw)) {
                        return [];
                    } else {
                        return $project_type_list;
                    }
                }
                break;

            default:
                break;
        }
    }

    public static function getUpdatePermission($option, $type = 'array') {
        $role = \Auth::user()->userRoleUpdate;
        if (is_null($role)) {
            if ($type == 'sql') {
                return '(0)';
            } else{
                return [];
            }
        }

        switch ($option) {
            case PROPERTY_PERMISSION:
                $all_property_id = Property::pluck('id')->toArray();
                // for contractor
                if (!\CommonHelpers::isSystemClient() || $role->is_everything == 1) {
                    if ($type == 'sql') {
                        $all_property_id =  '('.implode(',',$all_property_id).')';
                        return $all_property_id;
                    } else{
                        return $all_property_id;
                    }
                }

                if ($type == 'sql') {
                    return '(' .($role->property ?? 0). ')';
                } else{
                    return  explode(",",$role->property);
                }

                break;

            case PROJECTS_TYPE_UPDATE_PRIV:
                if ($role->is_everything == 1) {
                    $all_type = ProjectType::pluck('id')->toArray();
                    if ($type == 'sql') {
                        return '('.implode(',',$all_type).')';
                    } else{
                        return $all_type;
                    }
                }

                if ($type == 'sql') {
                    return '(' .($role->project_type_raw ?? 0). ')';
                } else{
                    return (is_null($role->project_type_raw)) ? [] : explode(",",$role->project_type_raw);
                }

                break;


            case CATEGORY_BOX_UPDATE_PRIV:
                if ($role->is_everything == 1) {
                    return Template::pluck('id')->toArray();
                }
                return $role->category_box;
                break;

            case PROJECT_DOCUMENT_TYPE_PERMISSION:
                if ($role->is_everything == 1) {
                    $all_type = PrivilegeChild::where('type',1)->pluck('id')->toArray();
                    return $all_type;
                }

                $project_type_list = self::convertProjectDocumentTypeId(explode(",",$role->project_information_raw));
                if ($type == 'sql') {
                    return $project_type_list = '(' .implode(',',$project_type_list). ')';
                } else{
                    if (is_null($role->project_information_raw)) {
                        return [];
                    } else {
                        return $project_type_list;
                    }
                }
                break;

            default:
                break;
        }
    }

    public static function getPropertyContractorPermission($type = false) {
        $client_id = \Auth::user()->client_id;
        $property_ids = \DB::select("
                SELECT DISTINCT property_id from tbl_project where client_id = $client_id
                or FIND_IN_SET('$client_id', REPLACE(contractors, ' ', ''))
            ");
        $data = [];
        if (count($property_ids)) {
            foreach ($property_ids as $key => $value) {
                $data[] = $value->property_id;
            }
        }
        if ($type == "sql") {
            if (count($data)) {
                return '(' .implode(",",$data). ')';
            } else {
                return '(0)';
            }
        }
        return $data;
    }

    public static function getContractorPermission($contractor_id,$param) {
        $role = \Auth::user()->userRole;
        if (is_null($role)) {
            return true;
        }

        if ($role->is_everything == 1) {
            return true;
        }

        if (isset($role->contractor[$contractor_id])) {
            if (in_array($param, $role->contractor[$contractor_id])) {
               return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function getContractorUpdatePermission($contractor_id,$param) {
        $role = \Auth::user()->userRoleUpdate;
        if (is_null($role)) {
            return true;
        }

        if ($role->is_everything == 1) {
            return true;
        }

        if (isset($role->contractor[$contractor_id])) {
            if (in_array($param, $role->contractor[$contractor_id])) {
               return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function convertProjectDocumentTypeId($data) {
        $dataConvert = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if ($value == TENDER_DOC_PRIVILEGE) {
                    $dataConvert[] = TENDER_DOC_CATEGORY;
                } elseif($value == CONTRACTOR_DOC_PRIVILEGE) {
                    $dataConvert[] = CONTRACTOR_DOC_CATEGORY;
                } elseif($value == PLANNING_DOC_PRIVILEGE) {
                    $dataConvert[] = PLANNING_DOC_CATEGORY;
                } elseif($value == PRE_START_DOC_PRIVILEGE) {
                    $dataConvert[] = PRE_START_DOC_CATEGORY;
                } elseif($value == SITE_RECORDS_DOC_PRIVILEGE) {
                    $dataConvert[] = SITE_RECORDS_DOC_CATEGORY;
                } elseif($value == COMPLETION_DOC_DOC_PRIVILEGE) {
                    $dataConvert[] = COMPLETION_DOC_CATEGORY;
                }
            }
        }
        return $dataConvert;
    }

    public static function setViewEMP($type, $param){

        $roles = Role::all();
        if (count($roles)) {
            foreach ($roles as $role) {

                if ($role->is_everything != 1) {
                    switch ($type) {
                        case PROPERTY_EMP_VIEW_PRIV:
                            // update for all emp role
                            if (in_array(PROPERTY_EMP_VIEW_PRIV, $role->view_privilege)) {
                                $property_ids = $role->property;

                                if (is_null($property_ids) || empty($property_ids)) {
                                    $property_ids_emp = $param;
                                } else{
                                    $property_ids_emp = $property_ids. ',' .$param;
                                }

                                Role::where('id', $role->id)->update(['property' => $property_ids_emp]);
                            }
                            break;

                        case ORGANISATION_EMP_VIEW_PRIV:
                            if (in_array(ORGANISATION_EMP_VIEW_PRIV, $role->view_privilege)) {
                                $client = $role->contractor;
                                $client[$param] = [4,5,6,7];

                                Role::where('id', $role->id)->update(['contractor' => json_encode($client)]);
                            }
                            break;

                        case RESOURCE_EMP_VIEW_PRIV:
                            if (in_array(RESOURCE_EMP_VIEW_PRIV, $role->view_privilege)) {
                                $doc_id = $role->category_box_raw;
                                if (is_null($doc_id) || empty($doc_id)) {
                                    $doc_id_emp = $param;
                                } else{
                                    $doc_id_emp = $doc_id. ',' .$param;
                                }

                                Role::where('id', $role->id)->update(['category_box' => $doc_id_emp]);
                            }
                            break;

                        default:
                            # code...
                            break;
                    }
                }
            }
        }
        return true;
    }

    public static function setUpdateEMP($type, $param){


        $roles = RoleUpdate::all();
        if (count($roles)) {
            foreach ($roles as $role) {
                if ($role->is_everything != 1) {
                    switch ($type) {
                        case PROPERTY_EMP_UPDATE_PRIV:
                             // update for all emp role
                            if (in_array(PROPERTY_EMP_UPDATE_PRIV, $role->update_privilege)) {
                                $property_ids = $role->property;
                                if (is_null($property_ids) || empty($property_ids)) {
                                    $property_ids_emp = $param;
                                } else{
                                    $property_ids_emp = $property_ids. ',' .$param;
                                }

                                RoleUpdate::where('id', $role->id)->update(['property' => $property_ids_emp]);
                            }
                            break;

                        case ORGANISATION_EMP_UPDATE_PRIV:
                             if (in_array(ORGANISATION_EMP_UPDATE_PRIV, $role->update_privilege)) {
                                $client = $role->contractor;
                                $client[$param] = [4,5,6,7];
                                RoleUpdate::where('id', $role->id)->update(['contractor' => json_encode($client)]);
                            }
                            break;

                        case RESOURCE_EMP_UPDATE_PRIV:
                            if (in_array(RESOURCE_EMP_UPDATE_PRIV, $role->update_privilege)) {
                                $doc_id = $role->category_box_raw;
                                if (is_null($doc_id) || empty($doc_id)) {
                                    $doc_id_emp = $param;
                                } else{
                                    $doc_id_emp = $doc_id. ',' .$param;
                                }
                            }

                            RoleUpdate::where('id', $role->id)->update(['category_box' => $doc_id_emp]);

                            break;

                        default:
                            # code...
                            break;
                    }
                }
            }
        }

        return true;
    }

    public static function getAvailableSummary(){
        $role = Role::find(\Auth::user()->role);
        $summaries = $role->report[0] ?? 0;
        $summary_type = SummaryType::find($summaries);
        return (is_null($summary_type)) ? 'summary.riskassessment' :  'summary.'.$summary_type->value;
    }

    public static function getPropertyPermission($type = null) {
        if (\CommonHelpers::isSystemClient()) {
            $role_id = \Auth::user()->role  ?? 0;
            $role = Role::find($role_id);
            if ($role->is_everything == 1) {
                return $table_join = '(SELECT id as prop_id from tbl_property) as permission';
            }
            if ($type == 'property') {
                return $table_join = 'role_view_property';
            } else {
                $table_join  = "(SELECT id as prop_id from tbl_property
                                    join role_view_property v on v.property_id = id
                                    where v.role_id = $role_id) as permission";
            }
        } else {
            $client_id = \Auth::user()->client_id  ?? 0;
            $table_join = "(SELECT DISTINCT property_id as prop_id from tbl_project
                            where client_id = $client_id or FIND_IN_SET('$client_id', REPLACE(contractors, ' ', '')) ) as permission";
        }

        return $table_join;
    }
}
