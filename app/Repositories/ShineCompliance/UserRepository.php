<?php
namespace App\Repositories\ShineCompliance;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\ShineCompliance\User;
use App\Models\ShineCompliance\Contact;

class UserRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */

    function model()
    {
        return User::class;
    }

    public function getFindUser($id){
        return $this->model->find($id);
    }

    public function getUsers($user_id) {
        return $this->model->with('clients','department','contact')->where('id', $user_id)->first();
    }

    public function updateProfile($id,$data) {
        return $this->model->where('id', $id)->update($data);
    }

    public function createProfile($data) {
        return $this->model->create($data);
    }

    public function getSurveyUsers() {
        return $this->model->where('client_id', 1)->where('is_locked',0)->get();
    }

    public function getWhereInUser($id) {
        return $this->model->whereIn('id', $id)->get();
    }

    public function getContactProperty($contact, $relation = []){
        return $this->model->with($relation)->whereIn('id',$contact)->get();
    }

    public function getClientUsers($client_id){
        return $this->model->where(['client_id'=> $client_id, 'is_locked' => USER_UNLOCKED])->orderBy('first_name','ASC')->get();
    }

    public function getClientUsersAssessment($client_id){
        if($client_id != 1){
            return $this->model->where(['client_id'=> $client_id, 'is_locked' => USER_UNLOCKED])->orderBy('first_name','ASC')->get();
        }else{
            return $this->model->where(['is_locked' => USER_UNLOCKED])->orderBy('first_name','ASC')->get();
        }

    }

    public function getClientAssessment($client_id){
        return $this->model->where(['client_id'=> $client_id, 'is_locked' => USER_UNLOCKED])->orderBy('first_name','ASC')->get();
    }

    public function getClientLeadsAssessment($client_id){
            return $this->model
                ->where('client_id', $client_id)
                ->where('is_locked', 0)
                ->where('assessor_lead', true)
                ->orderBy('first_name')
                ->get();
    }

    public function searchUser($query_string) {
        if (!\CommonHelpers::isSystemClient()) {
            $condition = 'AND client_id = '. \Auth::user()->client_id;
        } else {
            $condition = '';
        }

        $sql = "SELECT id,username,shine_reference,first_name, last_name,CONCAT(`first_name`, ' ',`last_name`) as full_name
                FROM `tbl_users`
                WHERE (`shine_reference` LIKE '%" . $query_string . "%'
                    OR `username` LIKE '%" . $query_string . "%'
                    OR `first_name` LIKE '%" . $query_string . "%'
                    OR `last_name` LIKE '%" . $query_string . "%'
                     OR CONCAT(`first_name`, ' ',`last_name`) LIKE '%" . $query_string . "%' )

                    $condition
                ORDER BY `username` ASC
                LIMIT 0,30";

        $list = \DB::select($sql);
        return $list;
    }

    public function getUserDepartment($relation,$client_id,$department_id){
        return User::with($relation)->where('client_id', $client_id)->where('department_id', $department_id)->get();
    }

    public function getAsbestosLeads() {
        $users = $this->model->where('joblead', 1)
            ->where('is_locked', 0)
            ->get();
        return $users;
    }

    public function getListHSLeadIncidentReporting(){
        return User::where('is_lead_workrequest', LEAD_WORKREQUEST)->get();
    }

    public function getAllUsers(){
        return User::where('is_locked', USER_UNLOCKED)->get();
    }

    public function getAllIncidentReportingUsers(){
        return User::where('is_locked', USER_UNLOCKED)->orderBy('first_name')->get();
    }
}
