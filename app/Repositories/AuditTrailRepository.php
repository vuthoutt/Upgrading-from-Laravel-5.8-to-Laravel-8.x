<?php
namespace App\Repositories;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\AuditTrail;
use App\Models\Property;
use App\Models\Project;
use App\Models\Survey;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class AuditTrailRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return AuditTrail::class;
    }

    public function getAuditTrail($limit) {
        return AuditTrail::whereNotIn('user_id', [2,1,717])->where('action_type','!=','lock')->orderBy('id','desc')->limit($limit)->get();
    }

    public function getHomeAuditTrail($user_id) {
        $audits = AuditTrail::where('user_id', $user_id)
                            ->where('action_type', 'like', '%view%')
                            ->where('action_type', 'not like', '%user%')
                            ->select(['type', 'object_type', 'object_id', 'object_parent_id', 'object_reference', 'action_type', 'comments', 'date'])->distinct()
                            ->orderBy('id')
                            ->limit(200)
                            ->get();
        return $audits;
    }

    public function getRecentSites($user_id) {

        $recentSites = DB::select("SELECT DISTINCT property_id FROM
                    (SELECT object_id, user_name, id, property_id FROM tbl_audit_trail where object_type = 'property' and property_id != 0 and user_id = " . $user_id . " order by id desc LIMIT 1, 500)
                    AS SITES LIMIT 0, 13");
        $list_site = [];
        foreach( $recentSites as $site) {
            $list_site[] =  $site->property_id;
        }
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();
        $list_site_common = $list_site;
        return Property::with('zone','propertyInfo')
                    ->whereIn('id', $list_site_common)
                    ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'id')
                    ->get();

    }

    public function getRecentView($user_id) {
        $sql = "SELECT DISTINCT object_type, object_id, object_reference
                    FROM tbl_audit_trail where user_id = $user_id AND action_type like '%view%'
                    AND object_type IN ('project', 'survey', 'property')
                    AND comments NOT LIKE '%survey PDF%'
                    order by id desc limit 5";

        $results = \DB::select($sql);

        foreach ($results as $key => $data) {
            switch ($data->object_type) {
                case 'survey':
                    $data->link = route('property.surveys',['survey_id' => $data->object_id, 'section' => SECTION_DEFAULT]);
                    $data->title = $data->object_reference;
                    break;

                case 'project':
                    $data->link = route('project.index',['project_id' => $data->object_id]);
                    $project = Project::find($data->object_id);
                    $data->title = ($project->reference ?? '') . ' - ' . ($project->title ?? 'no title');
                    break;

                case 'property':
                    if ($data->object_id) {
                        $data->link = route('property_detail',['property_id' => $data->object_id, 'section' => SECTION_DEFAULT]);
                        $property =  Property::find($data->object_id);
                        $data->title = ($property->reference ?? '') . ' - ' . ($property->name ?? 'no name');
                    } else {
                        $data->link = route('zone');
                        $data->title = 'View All Sites';
                    }
                    break;

                default:
                    # code...
                    break;
            }
        }

        return $results;
    }

    public function logAjaxAudit($id, $type, $tab) {

        switch ($type) {
            case 'property':
                $property = Property::find($id);

                $comment = \Auth::user()->full_name . " viewed Property " . $tab .' tab on ' .$property->name;
                \CommonHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_VIEW, $property->property_reference, $property->client_id, $comment, 0 , $property->id);

                break;

            case 'survey':
                $survey =  Survey::find($id);
                $comment = \Auth::user()->full_name . " viewed Survey " . $tab .' tab on ' .$survey->reference . ' on ' . $survey->property->name;
                \CommonHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_VIEW, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);

                break;

            case 'item':
                $item =  Item::find($id);
                $comment = \Auth::user()->full_name  . " viewed Item " . $tab . ' tab on ' . $item->reference .(isset($item->survey->reference) ? ' on '.$item->survey->reference : ''). ' on ' . ($item->property->name ?? '');
                \CommonHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_VIEW, $item->reference, $item->survey_id ,$comment, 0 ,$item->property_id);
                break;

            default:
                # code...
                break;
        }
        return true;
    }
}
