<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\Project;
use Prettus\Repository\Eloquent\BaseRepository;

class ProjectRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Project::class;
    }
    public function isWinnerSurveyContractor($property_id, $contractor_id, $type) {
        switch ($type) {
            case ASSESSMENT_ASBESTOS_TYPE:
                $projectList = \DB::select("SELECT `id` from `tbl_project`
                        WHERE `project_type` = 1
                            AND property_id = $property_id
                            AND status != 5
                            AND FIND_IN_SET('$contractor_id', `checked_contractors`) ");
                break;

            case ASSESSMENT_FIRE_TYPE:
                $projectList = \DB::select("SELECT `id` from `tbl_project`
                        WHERE `project_type` = 5
                            AND property_id = $property_id
                            AND status != 5
                            AND FIND_IN_SET('$contractor_id', `checked_contractors`) ");
                break;

            case ASSESSMENT_WATER_TYPE:
                $projectList = \DB::select("SELECT `id` from `tbl_project`
                        WHERE `project_type` = 9
                            AND property_id = $property_id
                            AND status != 5
                            AND FIND_IN_SET('$contractor_id', `checked_contractors`) ");
                break;

            case ASSESSMENT_HS_TYPE:
                $projectList = \DB::select("SELECT `id` from `tbl_project`
                        WHERE `project_type` = 13
                            AND property_id = $property_id
                            AND status != 5
                            AND FIND_IN_SET('$contractor_id', `checked_contractors`) ");
                break;

            default:
                $projectList = [];
                break;
        }

        if (count($projectList)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getFirstProjectInProgress($property_id){
        return $this->model->whereIn('status', [1, 2, 3, 4])->where(['property_id' => $property_id])->first();;
    }

    public function getPropertyProject($id,$client_id,$client_type){
        if ($client_type == 1) {
            return $this->model->where('property_id', $id)->whereRaw("FIND_IN_SET('$client_id', REPLACE(contractors, ' ', ''))")->orderBy('id','desc')->get();
        } else {
            return $this->model->where('property_id', $id)->whereIn('project_type', \CompliancePrivilege::getPermission(PROJECT_TYPE_PERMISSION))->orderBy('id','desc')->get();
        }
    }
    public function getLinkedPropertyProject($id, $project_id = 0) {
        $client_type = \Auth::user()->clients->client_type;
        $client_id = \Auth::user()->client_id;
        // missing role
        if ($client_type == 1) {
            $projects =  $this->model->where('property_id', $id)->whereRaw("FIND_IN_SET('$client_id', REPLACE(contractors, ' ', ''))")->whereRaw("id != $project_id")->orderBy('id','desc')->get();
        } else {
            $projects = $this->model->where('property_id', $id)->whereIn('project_type', \CompliancePrivilege::getPermission(PROJECT_TYPE_PERMISSION))->whereRaw("id != $project_id")->orderBy('id','desc')->get();
        }
        return is_null($projects) ? [] : $projects;
    }

    public function getSelectContractors($typestring){
        $contractors_lists =  \DB::select("
                SELECT c.id, c.name, c.reference
                FROM tbl_clients c
                LEFT JOIN tbl_client_info ci ON c.id = ci.client_id
                WHERE c.client_type = 1
                $typestring
                ORDER BY name ASC
            ");
        return is_null($contractors_lists) ? [] : $contractors_lists;
    }

    public function getProject($id){
        return $this->model->where('id', $id)->first();
    }

    public function getProjectLink($remove_link_project_ids){
        return $this->model->whereIn('id', $remove_link_project_ids)->get();
    }

    public function updateStatusTender($project_id){
        return $this->model->where('id',$project_id)->update(['status' => PROJECT_TENDERING_IN_PROGRESS_STATUS]);
    }

    public function createProject($data){
        return $this->model->create($data);
    }

    public function updateProject($id,$data){
        return $this->model->where('id', $id)->update($data);
    }

    public function getProjectWhereIn($project_ids){
        return $this->model->whereIn('id', $project_ids)->get();
    }

    public function complete_project($project_id){
        return $this->model->where('id', $project_id)->where('status', PROJECT_READY_FOR_ARCHIVE_STATUS)->update(['status' => PROJECT_COMPLETE_STATUS]);
    }

    public function getPropertyProjectType($id,$client_id,$client_type,$risk_classification_id){
        if ($client_type == 1) {
            return $this->model->where('property_id', $id)
            ->whereRaw("FIND_IN_SET('$client_id', REPLACE(contractors, ' ', ''))")
            ->where('decommissioned', PROJECT_UNDECOMMISSION)
            ->where('risk_classification_id',$risk_classification_id)
            ->orderBy('id','desc')
            ->get();
        } else {
            return $this->model->where('property_id', $id)->where('risk_classification_id',$risk_classification_id)->orderBy('id','desc')->get();
        }
    }

    public function getProjectInprogressByTpe($type, $property_id) {
        $data = \DB::select("Select count(id) count from tbl_project p
                            where property_id = $property_id
                            and status in (2,3)
                            and risk_classification_id =  $type");
        return $data[0]->count ?? false;
    }
}
