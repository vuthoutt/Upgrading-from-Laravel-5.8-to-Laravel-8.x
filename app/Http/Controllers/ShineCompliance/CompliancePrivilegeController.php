<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShineCompliance\JobRoleViewValue;
use App\Models\ProjectType;
use App\Models\Client;
use App\Models\Template;
use App\Models\PrivilegeChild;
use App\Models\WorkData;
use App\Models\SummaryType;
use App\Models\WorkFlow;
use App\Models\ShineCompliance\CPRoleUpdateProperty;
use App\Models\ShineCompliance\CPRoleViewProperty;
use App\Models\ShineCompliance\JobRole;
use App\Models\ShineCompliance\JobRoleEditValue;
use App\Models\ShineCompliance\Zone;

class CompliancePrivilegeController extends Controller
{

    public static function checkPermission($option, $param = '', $type = JOB_ROLE_GENERAL, $addition_param = null) {

        if(($type == JOB_ROLE_WATER) and !env('WATER_MODULE')) {
            return false;
        }

        // if contractor user
        if( \Auth::user()->clients->client_type != 0) {
            return true;
        }

        $role = \Auth::user()->userRole;
        if (is_null($role)) {
            return true;
        }

        // check everything
        $everything = $role->getCommonEverythingViewByType($type);

        if ($everything == 1) {
            return true;
        }
        $dynamic = json_decode($role->getCommonDynamicValueByType($type));
        $static = json_decode($role->getCommonStaticValueByType($type));


        switch ($option) {
            // for dynamic
            case PROPERTY_PERMISSION:
                $role_property = \DB::select("Select property_id as prop_id from cp_job_role_view_property where role_id = {$role->id}");

                if (count($role_property)) {
                    foreach ($role_property as $prop) {
                        if (isset($prop->prop_id)) {
                            if ($prop->prop_id == $param) {
                                return true;
                            }
                        }
                    }
                }
                return false;
                break;

            case JR_VIEW_TROUBLETICKET_ICON:
                $data = \Auth::user()->userRole->jobRoleViewValue->general_is_tt ?? 0;

                if ($data == 1) {
                    return true;
                } else {
                    return false;
                }
                break;

            case SYSTEM_OWNER_PERMISSION:
                $data = $dynamic->organisation ?? [];
                if (is_null($data) || empty($data)) {
                    return true;
                } else {
                    return false;
                }
                break;

            case CONTRACTOR_PERMISSION:
                $data = $dynamic->contractor ?? [];
                if (is_null($data) || empty($data)) {
                    return true;
                } else {
                    return false;
                }
                break;

            case CLIENT_PERMISSION:
                $data = $dynamic->client ?? [];
                if (is_null($data) || empty($data)) {
                    return true;
                } else {
                    return false;
                }
                break;

            // case EMAIL_NOTIFICATION_PERMISSION:
            //     $data = $dynamic->email_notification ?? [];
            //     if (in_array($param, $data)) {
            //         return true;
            //     } else {
            //         return false;
            //     }
            //     break;

            case JR_REPORTING:
                $data = $dynamic->reporting ?? [];

                if (in_array($param, $data)) {
                    return true;
                } else {
                    return false;
                }
                break;

            // for static
            default:

                if (!is_null($static) and in_array($param, $static)) {
                    return true;
                } else {
                    return false;
                }

                break;
        }

    }

    public static function checkUpdatePermission($option, $param = '', $type = JOB_ROLE_GENERAL, $addition_param = null) {
        if(($type == JOB_ROLE_WATER) and !env('WATER_MODULE')) {
            return false;
        }
        // if contractor user
        if( \Auth::user()->clients->client_type != 0) {
            return true;
        }

        $role = \Auth::user()->userRole;
        if (is_null($role)) {
            return true;
        }

        // check everything
        $everything = $role->getCommonEverythingUpdateByType($type);

        if ($everything == 1) {
            return true;
        }
        $dynamic = json_decode($role->getCommonDynamicValueUpdateByType($type)) ?? [];
        $static = json_decode($role->getCommonStaticValueUpdateByType($type)) ?? [];

        switch ($option) {
            // for dynamic
            case PROPERTY_PERMISSION:
                $role_property = \DB::select("Select property_id as prop_id from cp_job_role_edit_property where role_id = {$role->id}");
                if (count($role_property)) {
                    foreach ($role_property as $prop) {
                        if (isset($prop->prop_id)) {
                            if ($prop->prop_id == $param) {
                                return true;
                            }
                        }
                    }
                }
                return false;
                break;
            case SYSTEM_OWNER_PERMISSION:
                $data = $dynamic->organisation ?? [];
                if (is_null($data) || empty($data)) {
                    return true;
                } else {
                    return false;
                }
                break;

            case CONTRACTOR_PERMISSION:
                $data = $dynamic->contractor ?? [];
                if (is_null($data) || empty($data)) {
                    return true;
                } else {
                    return false;
                }
                break;

            case CLIENT_PERMISSION:
                $data = $dynamic->client ?? [];
                if (is_null($data) || empty($data)) {
                    return true;
                } else {
                    return false;
                }
                break;

            case JR_UPDATE_ALL_PROPERTIES:
                $client_listing = $role->jobRoleEditValue->client_listing ?? "";
                $client_listing_data = json_decode($client_listing);
                $group_edit_permission = $client_listing_data->{$addition_param} ?? [];

                if (is_null($group_edit_permission) || empty($group_edit_permission)) {
                    return false;
                } else {
                    if (in_array($param, $group_edit_permission)) {
                        return true;
                    }
                }
                break;

            case JR_UPDATE_CLIENTS:

                $add_group_permission = $role->jobRoleEditValue->add_group_permission ?? "";
                $client_listing_data = explode(",",$add_group_permission);

                if (is_null($client_listing_data) || empty($client_listing_data)) {
                    return false;
                } else {
                    if (in_array($param, $client_listing_data)) {
                        return true;
                    }
                }
                break;


            // for static
            default:

                if (!is_null($static) and in_array($param, $static)) {
                    return true;
                } else {
                    return false;
                }

                break;
        }

        return false;
    }

    public static function getPermission($option, $type = JOB_ROLE_GENERAL) {
        // if contractor user
        $role = \Auth::user()->userRole;

        // check everything
        $everything = $role->getCommonEverythingViewByType($type);

        $dynamic = json_decode($role->getCommonDynamicValueByType($type));
        $static = json_decode($role->getCommonStaticValueByType($type));

        switch ($option) {

            case JR_CATEGORY_BOX_VIEW:

                if ($everything == 1) {
                    return Template::pluck('id')->toArray();
                }

                return $dynamic->category ?? [];
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


            case RESOURCES_WORKFLOW:
                if ($role->is_everything == 1) {
                    $all_type = WorkFlow::pluck('id')->toArray();
                    return $all_type;
                }

                $data = $role->work_request ?? null;
                $work_flow_type = $data[RESOURCES_WORKFLOW] ?? [];

                return $work_flow_type;
                break;

            default:
                return false;
                break;
        }
    }

    public static function getUpdatePermission($option, $type = JOB_ROLE_GENERAL) {
        // if contractor user
        $role = \Auth::user()->userRole;

        // check everything
        $everything = $role->getCommonEverythingUpdateByType($type);

        $dynamic = json_decode($role->getCommonDynamicValueUpdateByType($type));
        $static = json_decode($role->getCommonStaticValueUpdateByType($type));

        switch ($option) {

            case JR_CATEGORY_BOX_EDIT:
                if ($everything == 1) {
                    return Template::pluck('id')->toArray();
                }

                return $dynamic->category ?? [];
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
                return true;
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

    public static function getContractorPermission($contractor_id,$param, $type = 'organisation') {

        $role = \Auth::user()->userRole;

        // check everything
        $everything = $role->getCommonEverythingViewByType(JOB_ROLE_GENERAL);

        if ($everything == 1) {
            return true;
        }

        $checking_value = json_decode($role->jobRoleViewValue->organisation_listing ?? '');


        if (isset($checking_value->{$contractor_id})) {
            if (in_array($param, $checking_value->{$contractor_id})) {
               return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function getContractorIdPermission( $type = 'organisation') {
        // if contractor user
        if( \Auth::user()->clients->client_type != 0) {
            return true;
        }
        $role = \Auth::user()->userRole;

        // check everything
        $everything = $role->getCommonEverythingViewByType(JOB_ROLE_GENERAL);

        if ($everything == 1) {
            return Client::pluck('id')->toArray();;
        }
        $checking_value = json_decode($role->jobRoleViewValue->organisation_listing ?? '');

        $data = [];
        if (isset($checking_value)) {
            foreach ($checking_value as $key => $value) {
                $data[] = $key;
            }
        }
        switch ($type) {
            case 'organisation':
                $client_ids = Client::where('client_type',0)->pluck('id')->toArray();
                break;
            case 'contractor':
                $client_ids = Client::where('client_type',1)->pluck('id')->toArray();
                break;
            case 'client':
                $client_ids = Client::where('client_type',2)->pluck('id')->toArray();
                break;

            default:
                $client_ids = Client::where('client_type',0)->pluck('id')->toArray();
                break;
        }

        return array_intersect($data, $client_ids);
    }

    public static function getContractorUpdatePermission($contractor_id,$param, $type = 'organisation') {

        $role = \Auth::user()->userRole;

        // check everything
        $everything = $role->getCommonEverythingUpdateByType(JOB_ROLE_GENERAL);
        if ($everything == 1) {
            return true;
        }
        $checking_value = json_decode($role->jobRoleEditValue->organisation_listing ?? '');

        if (isset($checking_value->{$contractor_id})) {
            if (in_array($param, $checking_value->{$contractor_id})) {
               return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function setViewEMP($type, $param, $addition_param = 0){

        $roles = JobRole::all();

        if (count($roles)) {
            foreach ($roles as $role) {
                // update for all emp role
                $everything = $role->getCommonEverythingViewByType(JOB_ROLE_GENERAL);
                $static = json_decode($role->getCommonStaticValueByType(JOB_ROLE_GENERAL)) ?? [];

                switch ($type) {
                        case JR_CATEGORY_BOX_VIEW:

                            if ($everything == 1 || in_array($type, $static)) {
                                $data_dynamic = json_decode($role->jobRoleViewValue->common_dynamic_values_view ?? '') ?? (object)[];
                                $data_general = json_decode($data_dynamic->general ?? '') ?? (object)[];
                                $data_category = $data_general->category ?? [];
                                $data_category[] = $param;

                                $data_general->category = $data_category;
                                $data_dynamic->general = json_encode($data_general);

                                JobRoleViewValue::where('role_id', $role->id)->update(['common_dynamic_values_view' => json_encode($data_dynamic)]);
                            }

                            break;

                        case JR_PROPERTY_EMP:

                            if ($everything == 1 || in_array($type, $static)) {
                                $data_create = [
                                    'role_id' => $role->id ?? 0,
                                    'property_id' => $param ?? 0,
                                ];
                                CPRoleViewProperty::insert($data_create);
                            }

                            break;

                        case JR_ORGANISATION_EMP:

                            if ($everything == 1 || in_array($type, $static)) {
                                $data_contractor = json_decode($role->jobRoleViewValue->organisation_listing ?? '') ?? (object)[];

                                $data_contractor->{$param} = [3,4,5,6];

                                JobRoleViewValue::where('role_id', $role->id)->update(['organisation_listing' => json_encode($data_contractor)]);
                            }
                            break;

                        case JR_GROUP_EMP:
                            if ($everything == 1 || in_array($type, $static)) {
                                $data_client_listing = json_decode($role->jobRoleViewValue->client_listing ?? '') ?? (object)[];
                                $data_client = $data_client_listing->{$addition_param} ?? [];
                                $data_client[] = $param;
                                $data_client_listing->{$addition_param} = $data_client;
                                JobRoleViewValue::where('role_id', $role->id)->update(['client_listing' => json_encode($data_client_listing)]);
                            }

                            break;

                        default:
                            # code...
                            break;
                }
            }
        }
        return true;
    }

    public static function setUpdateEMP($type, $param, $addition_param = 0){
        $roles = JobRole::all();
        if (count($roles)) {
            foreach ($roles as $role) {
                // update for all emp role
                $everything = $role->getCommonEverythingUpdateByType(JOB_ROLE_GENERAL);
                $static = json_decode($role->getCommonStaticValueUpdateByType(JOB_ROLE_GENERAL)) ?? [];
                    switch ($type) {
                        case JR_CATEGORY_BOX_EDIT:

                            if ($everything == 1 || in_array($type, $static)) {
                                $data_dynamic = json_decode($role->jobRoleEditValue->common_dynamic_values_update ?? '') ?? (object)[];
                                $data_general = json_decode($data_dynamic->general ?? '') ?? (object)[];
                                $data_category = $data_general->category ?? [];
                                $data_category[] = $param;

                                $data_general->category = $data_category;
                                $data_dynamic->general = json_encode($data_general);

                                JobRoleEditValue::where('role_id', $role->id)->update(['common_dynamic_values_update' => json_encode($data_dynamic)]);
                            }

                            break;

                          case JR_UPDATE_PROPERTY_EMP:
                            if ($everything == 1 || in_array($type, $static)) {
                                $data_create = [
                                    'role_id' => $role->id ?? 0,
                                    'property_id' => $param ?? 0,
                                ];
                                CPRoleUpdateProperty::insert($data_create);
                            }
                            break;

                        case JR_ORGANISATION_EMP:

                            if ($everything == 1 || in_array($type, $static)) {
                                $data_contractor = json_decode($role->jobRoleEditValue->organisation_listing ?? '') ?? (object)[];

                                $data_contractor->{$param} = [3,4,5,6];

                                JobRoleEditValue::where('role_id', $role->id)->update(['organisation_listing' => json_encode($data_contractor)]);
                            }
                            break;

                        case JR_UPDATE_GROUP_EMP:
                            if ($everything == 1 || in_array($type, $static)) {
                                $data_client_listing = json_decode($role->jobRoleEditValue->client_listing ?? '') ?? (object)[];
                                $data_client = $data_client_listing->{$addition_param} ?? [];
                                $data_client[] = $param;
                                $data_client_listing->{$addition_param} = $data_client;

                                JobRoleEditValue::where('role_id', $role->id)->update(['client_listing' => json_encode($data_client_listing)]);
                            }

                            break;

                        default:
                            # code...
                            break;
                    }
                }
        }


        return true;
    }

    public static function getAvailableSummary(){
        $role = \Auth::user()->userRole;
        if (!\CommonHelpers::isSystemClient()) {
            return 'summary.sampleSummary';
        }
        // check everything

        $dynamic = json_decode($role->getCommonDynamicValueByType(JOB_ROLE_ASBESTOS));
        $everything = $role->getCommonEverythingViewByType(JOB_ROLE_ASBESTOS);
        $checking_value = $dynamic->reporting ?? [];
        if ($everything == 1) {
            return 'summary.sampleSummary';
        }
        $summaries = $checking_value[0] ?? 0;
        $summary_type = SummaryType::find($summaries);
        return (is_null($summary_type)) ? false :  'summary.'.$summary_type->value;
    }

    public static function getPropertyPermission($type = null) {
        if (\CommonHelpers::isSystemClient()) {
            $role_id = \Auth::user()->role  ?? 0;
            $role = \Auth::user()->userRole;

            // check everything
            $everything = $role->getCommonEverythingViewByType(JOB_ROLE_GENERAL);

            if ($everything == 1) {
                return $table_join = '(SELECT id as prop_id from tbl_property) as permission';
            }
            if ($type == 'property') {
                return $table_join = 'cp_job_role_view_property';
            } else {
                $table_join  = "(SELECT id as prop_id from tbl_property
                                    join cp_job_role_view_property v on v.property_id = id
                                    where v.role_id = $role_id) as permission";
            }
        } else {
            $client_id = \Auth::user()->client_id  ?? 0;
            $table_join = "(SELECT DISTINCT property_id as prop_id from tbl_project
                            where status != 5 and (client_id = $client_id or FIND_IN_SET('$client_id', REPLACE(contractors, ' ', ''))) ) as permission";
        }

        return $table_join;
    }

    public static function getDataCentreAssessmentProjectPermission($type, $tab, $data_type = null) {
        if( \Auth::user()->clients->client_type != 0) {
            if ($type == 'project') {
                $data = ProjectType::pluck('id')->toArray();
            } else {
                $data = [1,2,3,4];
            }
            if ($data_type == 'sql') {

            if (!count($data)) {
                return '(0)';
            }

            return '('.implode(',',$data).')';
            } else{
                return $data;
            }
        }

        $role = \Auth::user()->userRole;
        if (is_null($role)) {
            return true;
        }

        // check everything
        $everything_asbestos = $role->getCommonEverythingViewByType(JOB_ROLE_ASBESTOS);
        $everything_fire = $role->getCommonEverythingViewByType(JOB_ROLE_FIRE);
        $everything_water = $role->getCommonEverythingViewByType(JOB_ROLE_WATER);
        $everything_rcf = $role->getCommonEverythingViewByType(JOB_ROLE_RCF);
        $everything_hs = $role->getCommonEverythingViewByType(JOB_ROLE_H_S);

        $static_asbestos = json_decode($role->getCommonStaticValueByType(JOB_ROLE_ASBESTOS)) ?? [];
        $static_fire = json_decode($role->getCommonStaticValueByType(JOB_ROLE_FIRE)) ?? [];
        $static_water = json_decode($role->getCommonStaticValueByType(JOB_ROLE_WATER)) ?? [];
        $static_rcf = json_decode($role->getCommonStaticValueByType(JOB_ROLE_RCF)) ?? [];
        $static_hs = json_decode($role->getCommonStaticValueByType(JOB_ROLE_H_S)) ?? [];

        $assessment_type = [];
        $project_type_cats = [];
        switch ($tab) {
            case 'assessment':
                    if ($everything_fire || in_array(JR_DATA_CENTRE_ASSESSMENT_FIRE, $static_fire)) {
                        $assessment_type[] = ASSESSMENT_FIRE_TYPE;
                        $project_type_cats[] = 2;
                    }
                    if (($everything_water || in_array(JR_DATA_CENTRE_ASSESSMENT_WATER, $static_water)) and env('WATER_MODULE')) {
                        $assessment_type[] = ASSESSMENT_WATER_TYPE;
                        $project_type_cats[] = 3;
                    }

//                    if ($everything_rcf || in_array(JR_DATA_CENTRE_RCF_WATER, $static_rcf)) {
//                        $project_type_cats[] = 2;
                   // }

                   if ($everything_hs || in_array(JR_DATA_CENTRE_ASSESSMENT_HS, $static_hs)) {
                        $assessment_type[] = ASSESSMENT_HS_TYPE;
                       $project_type_cats[] = 4;
                   }

                break;

            case 'critical':

                    if ($everything_fire || in_array(JR_DATA_CENTRE_FIRE_CRITICAL, $static_fire)) {
                        $assessment_type[] = ASSESSMENT_FIRE_TYPE;
                        $project_type_cats[] = 2;
                    }
                    if (($everything_water || in_array(JR_DATA_CENTRE_WATER_CRITICAL, $static_water)) and env('WATER_MODULE')) {
                        $assessment_type[] = ASSESSMENT_WATER_TYPE;
                        $project_type_cats[] = 3;
                    }
//                    if ($everything_rcf || in_array(JR_DATA_CENTRE_RCF_CRITICAL, $static_rcf)) {
//                        $project_type_cats[] = 2;
//                    }
                    if ($everything_asbestos || in_array(JR_DATA_CENTRE_ASBESTOS_CRITICAL, $static_asbestos)) {
                        $project_type_cats[] = 1;
                    }

                    if ($everything_hs || in_array(JR_DATA_CENTRE_HS_CRITICAL, $static_hs)) {
                        $assessment_type[] = ASSESSMENT_HS_TYPE;
                        $project_type_cats[] = 4;
                    }
                break;

            case 'urgent':
                    if ($everything_fire || in_array(JR_DATA_CENTRE_FIRE_URGENT, $static_fire)) {
                        $assessment_type[] = ASSESSMENT_FIRE_TYPE;
                        $project_type_cats[] = 2;
                    }
                    if (($everything_water || in_array(JR_DATA_CENTRE_WATER_URGENT, $static_water)) and env('WATER_MODULE')) {
                        $assessment_type[] = ASSESSMENT_WATER_TYPE;
                        $project_type_cats[] = 3;
                    }
//                    if ($everything_rcf || in_array(JR_DATA_CENTRE_RCF_URGENT, $static_rcf)) {
//                        $project_type_cats[] = 2;
//                    }
                    if ($everything_asbestos || in_array(JR_DATA_CENTRE_ASBESTOS_URGENT, $static_asbestos)) {
                        $project_type_cats[] = 1;
                    }

                    if ($everything_hs || in_array(JR_DATA_CENTRE_HS_URGENT, $static_hs)) {
                        $assessment_type[] = ASSESSMENT_HS_TYPE;
                        $project_type_cats[] = 4;
                    }
                break;

            case 'important':
                    if ($everything_fire || in_array(JR_DATA_CENTRE_FIRE_IMPORTANT, $static_fire)) {
                        $assessment_type[] = ASSESSMENT_FIRE_TYPE;
                        $project_type_cats[] = 2;
                    }
                    if (($everything_water || in_array(JR_DATA_CENTRE_WATER_IMPORTANT, $static_water)) and env('WATER_MODULE')) {
                        $assessment_type[] = ASSESSMENT_WATER_TYPE;
                        $project_type_cats[] = 3;
                    }
//                    if ($everything_rcf || in_array(JR_DATA_CENTRE_RCF_IMPORTANT, $static_rcf)) {
//                        $project_type_cats[] = 2;
//                    }
                    if ($everything_asbestos || in_array(JR_DATA_CENTRE_ASBESTOS_IMPORTANT, $static_asbestos)) {
                        $project_type_cats[] = 1;
                    }

                    if ($everything_hs || in_array(JR_DATA_CENTRE_HS_IMPORTANT, $static_hs)) {
                        $assessment_type[] = ASSESSMENT_HS_TYPE;
                        $project_type_cats[] = 4;
                    }
                break;
            case 'attention':
                    if ($everything_fire || in_array(JR_DATA_CENTRE_FIRE_ATTENTION, $static_fire)) {
                        $assessment_type[] = ASSESSMENT_FIRE_TYPE;
                        $project_type_cats[] = 2;
                    }
                    if (($everything_water || in_array(JR_DATA_CENTRE_WATER_ATTENTION, $static_water)) and env('WATER_MODULE')) {
                        $assessment_type[] = ASSESSMENT_WATER_TYPE;
                        $project_type_cats[] = 3;
                    }
//                    if ($everything_rcf || in_array(JR_DATA_CENTRE_RCF_ATTENTION, $static_rcf)) {
//                        $project_type_cats[] = 2;
//                    }
                    if ($everything_asbestos || in_array(JR_DATA_CENTRE_ASBESTOS_ATTENTION, $static_asbestos)) {
                        $project_type_cats[] = 1;
                    }

                    if ($everything_hs || in_array(JR_DATA_CENTRE_HS_ATTENTION, $static_hs)) {
                        $assessment_type[] = ASSESSMENT_HS_TYPE;
                        $project_type_cats[] = 4;
                    }
                break;
            case 'deadline':
                    if ($everything_fire || in_array(JR_DATA_CENTRE_FIRE_DEADLINE, $static_fire)) {
                        $assessment_type[] = ASSESSMENT_FIRE_TYPE;
                        $project_type_cats[] = 2;
                    }
                    if (($everything_water || in_array(JR_DATA_CENTRE_WATER_DEADLINE, $static_water)) and env('WATER_MODULE')) {
                        $assessment_type[] = ASSESSMENT_WATER_TYPE;
                        $project_type_cats[] = 3;
                    }
//                    if ($everything_rcf || in_array(JR_DATA_CENTRE_RCF_DEADLINE, $static_rcf)) {
//                        $project_type_cats[] = 2;
//                    }
                    if ($everything_asbestos || in_array(JR_DATA_CENTRE_ASBESTOS_DEADLINE, $static_asbestos)) {
                        $project_type_cats[] = 1;
                    }

                    if ($everything_hs || in_array(JR_DATA_CENTRE_HS_DEADLINE, $static_hs)) {
                        $assessment_type[] = ASSESSMENT_HS_TYPE;
                        $project_type_cats[] = 4;
                    }
                break;
            case 'approval':
                    if ($everything_fire || in_array(JR_DATA_CENTRE_FIRE_APPROVAL, $static_fire)) {
                        $assessment_type[] = ASSESSMENT_FIRE_TYPE;
                        $project_type_cats[] = 2;
                    }
                    if (($everything_water || in_array(JR_DATA_CENTRE_WATER_APPROVAL, $static_water)) and env('WATER_MODULE')) {
                        $assessment_type[] = ASSESSMENT_WATER_TYPE;
                        $project_type_cats[] = 3;
                    }
//                    if ($everything_rcf || in_array(JR_DATA_CENTRE_RCF_APPROVAL, $static_rcf)) {
//
//                        $project_type_cats[] = 2;
//                    }
                    if ($everything_asbestos || in_array(JR_DATA_CENTRE_ASBESTOS_APPROVAL, $static_asbestos)) {
                        $project_type_cats[] = 1;
                    }

                    if ($everything_hs || in_array(JR_DATA_CENTRE_HS_APPROVAL, $static_hs)) {
                        $assessment_type[] = ASSESSMENT_HS_TYPE;
                        $project_type_cats[] = 4;
                    }
                break;
            case 'rejected':
                    if ($everything_fire || in_array(JR_DATA_CENTRE_FIRE_REJECTED, $static_fire)) {
                        $assessment_type[] = ASSESSMENT_FIRE_TYPE;
                        $project_type_cats[] = 2;
                    }
                    if (($everything_water || in_array(JR_DATA_CENTRE_WATER_REJECTED, $static_water)) and env('WATER_MODULE')) {
                        $assessment_type[] = ASSESSMENT_WATER_TYPE;
                        $project_type_cats[] = 3;
                    }
//                    if ($everything_rcf || in_array(JR_DATA_CENTRE_RCF_REJECTED, $static_rcf)) {
//
//                        $project_type_cats[] = 2;
//                    }
                    if ($everything_asbestos || in_array(JR_DATA_CENTRE_ASBESTOS_REJECTED, $static_asbestos)) {
                        $project_type_cats[] = 1;
                    }

                    if ($everything_hs || in_array(JR_DATA_CENTRE_HS_REJECTED, $static_hs)) {
                        $assessment_type[] = ASSESSMENT_HS_TYPE;
                        $project_type_cats[] = 4;
                    }
                break;

            default:
                # code...
                break;
        }

        if ($type == 'project') {
            $data = ProjectType::whereIn('compliance_type', $project_type_cats)->pluck('id')->toArray();
        } else {
            $data = $assessment_type;
        }

        if ($data_type == 'sql') {

            if (!count($data)) {
                return '(0)';
            }

            return '('.implode(',',$data).')';
        } else{
            return $data;
        }
    }

    public static function getDataCentrePrivilege($type) {
        if (!\CommonHelpers::isSystemClient()) {
            if ($type == 'dashboard') {
                return false;
            }
            return true;
        }
        switch ($type) {

            case 'dashboard':
                return self::checkPermission(JR_DATA_CENTRE, JR_DASHBOARD);
                break;

            case 'survey':
                return self::checkPermission(JR_DATA_CENTRE, JR_DATA_CENTRE_ASBESTOS_SURVEYS,JOB_ROLE_ASBESTOS);
                break;

            case 'project':

                return self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_PROJECT_WATER, JOB_ROLE_WATER)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_PROJECT_FIRE, JOB_ROLE_FIRE)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_PROJECT_ASBESTOS, JOB_ROLE_ASBESTOS)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_PROJECT_HS, JOB_ROLE_H_S);
                break;

            case 'assessment':
                return self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_ASSESSMENT_WATER, JOB_ROLE_WATER)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_ASSESSMENT_FIRE, JOB_ROLE_FIRE)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_ASSESSMENT_HS, JOB_ROLE_H_S);
                break;

            case 'certificate':
                return self::checkPermission(JR_DATA_CENTRE, JR_CERTIFICATES, JOB_ROLE_ASBESTOS);
                break;

            case 'critical':
                return self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_WATER_CRITICAL, JOB_ROLE_WATER)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_FIRE_CRITICAL, JOB_ROLE_FIRE)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_ASBESTOS_CRITICAL, JOB_ROLE_ASBESTOS)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_HS_CRITICAL, JOB_ROLE_H_S);
                break;

            case 'urgent':
                return self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_WATER_URGENT, JOB_ROLE_WATER)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_FIRE_URGENT, JOB_ROLE_FIRE)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_ASBESTOS_URGENT, JOB_ROLE_ASBESTOS)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_HS_URGENT, JOB_ROLE_H_S);
                break;

            case 'important':
                return self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_WATER_IMPORTANT, JOB_ROLE_WATER)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_FIRE_IMPORTANT, JOB_ROLE_FIRE)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_ASBESTOS_IMPORTANT, JOB_ROLE_ASBESTOS)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_HS_IMPORTANT, JOB_ROLE_H_S);
                break;

            case 'attention':
                return self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_WATER_ATTENTION, JOB_ROLE_WATER)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_FIRE_ATTENTION, JOB_ROLE_FIRE)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_ASBESTOS_ATTENTION, JOB_ROLE_ASBESTOS)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_HS_ATTENTION, JOB_ROLE_H_S);
                break;

            case 'deadline':
                return self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_WATER_DEADLINE, JOB_ROLE_WATER)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_FIRE_DEADLINE, JOB_ROLE_FIRE)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_ASBESTOS_DEADLINE, JOB_ROLE_ASBESTOS)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_HS_DEADLINE, JOB_ROLE_H_S);
                break;

            case 'approval':
                return self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_WATER_APPROVAL, JOB_ROLE_WATER)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_FIRE_APPROVAL, JOB_ROLE_FIRE)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_ASBESTOS_APPROVAL, JOB_ROLE_ASBESTOS)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_HS_APPROVAL, JOB_ROLE_H_S);
                break;

            case 'rejected':
                return self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_WATER_REJECTED, JOB_ROLE_WATER)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_FIRE_REJECTED, JOB_ROLE_FIRE)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_ASBESTOS_REJECTED, JOB_ROLE_ASBESTOS)
                or self::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_HS_REJECTED, JOB_ROLE_H_S);
                break;

            default:
                return false;
                break;
        }
    }

    public static function getDataCentreDisplayPrivs() {
        if( !self::getDataCentrePrivilege('dashboard')
            and !self::getDataCentrePrivilege('project')
            and !self::getDataCentrePrivilege('assessment')
            and !self::getDataCentrePrivilege('survey')
            and !self::getDataCentrePrivilege('critical')
            and !self::getDataCentrePrivilege('urgent')
            and !self::getDataCentrePrivilege('important')
            and !self::getDataCentrePrivilege('attention')
            and !self::getDataCentrePrivilege('deadline')
            and !self::getDataCentrePrivilege('approval')
            and !self::getDataCentrePrivilege('rejected')) {
            return false;
        }
        return true;
    }

    public static function getGroupListingPermission($type, $client_id = 1) {

        $role = \Auth::user()->userRole;
        if (is_null($role) || \Auth::user()->clients->client_type != 0) {
           return Zone::where('client_id', $client_id)->pluck('id')->toArray();
        }
        $everything = $role->getCommonEverythingViewByType(JOB_ROLE_GENERAL);

        // check everything
        if ($everything == 1 || \Auth::user()->clients->client_type != 0) {
            return Zone::where('client_id', $client_id)->pluck('id')->toArray();
        } else {
            $client_listing = $role->jobRoleViewValue->client_listing ?? "";
            $client_listing_data = json_decode($client_listing);

            $group_view_permission = $client_listing_data->{$client_id} ?? [];

            return $group_view_permission;
        }

    }

    public static function checkRegisterDataPermission(){
        if( !self::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_REGISTER_ASBESTOS, JOB_ROLE_ASBESTOS)
            and !self::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_REGISTER_FIRE, JOB_ROLE_FIRE)
            and !self::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_REGISTER_WATER, JOB_ROLE_WATER)
            and !self::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_REGISTER_HS, JOB_ROLE_H_S)
            and !self::checkPermission(JR_PROPERTIES_INFORMATION,JR_OVERALL)
        ){
            return false;
        }
        return true;
    }

    public static function getActiveTabRegisterProject() {
        if (!\CommonHelpers::isSystemClient() || self::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_PROJECT_ASBESTOS, JOB_ROLE_ASBESTOS)) {
            return '#asbestos';
        }

        if(self::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_PROJECT_FIRE, JOB_ROLE_FIRE)){
            return '#fire';
        }

        if(self::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_PROJECT_WATER, JOB_ROLE_WATER)){
            return '#water';

        }
        if(self::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_PROJECT_RCF, JOB_ROLE_RCF)){
            return '#rcf';
        }
        if(self::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_PROJECT_HS, JOB_ROLE_H_S)){
            return '#hs';
        }
        return '';
    }

    public static function getActiveTabDataCentreProject() {
        if (!\CommonHelpers::isSystemClient() || self::checkPermission(JR_PROPERTIES_INFORMATION,JR_DATA_CENTRE_PROJECT_ASBESTOS, JOB_ROLE_ASBESTOS)) {
            return '#asbestos';
        }

        if(self::checkPermission(JR_PROPERTIES_INFORMATION,JR_DATA_CENTRE_PROJECT_FIRE, JOB_ROLE_FIRE)){
            return '#fire';
        }
        if(self::checkPermission(JR_PROPERTIES_INFORMATION,JR_DATA_CENTRE_PROJECT_WATER, JOB_ROLE_WATER)){
            return '#water';
        }
        if(self::checkPermission(JR_PROPERTIES_INFORMATION,JR_DATA_CENTRE_PROJECT_RCF, JOB_ROLE_RCF)){
            return '#rcf';
        }
        if(self::checkPermission(JR_PROPERTIES_INFORMATION,JR_DATA_CENTRE_PROJECT_HS, JOB_ROLE_H_S)){
            return '#hs';
        }
        return '';
    }

    public static function getActiveTabRegisterAssessment() {
        if (!\CommonHelpers::isSystemClient() || self::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_ASSESSMENT_ASBESTOS, JOB_ROLE_ASBESTOS)) {
            return '#asbestos';
        }
        if(self::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_ASSESSMENT_FIRE, JOB_ROLE_FIRE)){
            return '#fire';
        }
        if(self::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_ASSESSMENTS_WATER, JOB_ROLE_WATER)){
            return '#water';
        }
        if(self::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_ASSESSMENT_RCF, JOB_ROLE_RCF)){
            return '#rcf';
        }
        if(self::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_ASSESSMENT_HS, JOB_ROLE_H_S)){
            return '#hs';
        }
        return '';
    }
}
