<?php
namespace App\Repositories;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Department;
use App\Models\Client;
use App\Models\DepartmentContractor;
use App\Helpers\CommonHelpers;

class DepartmentRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Department::class;
    }

    /**
     * get client department from id
    * @return
     */
    public function getClientDepartments($clientID) {
        $client = Client::find($clientID);
        if ($client->client_type == 0) {
            $organisations = Department::all();
        } else {
            $organisations = DepartmentContractor::all();
        }
        return $organisations;
    }

    public function getDepartment($id) {
        $department = Department::with('client')->where('id', $id)->first();
        return !is_null($department) ? $department: '';
    }
}