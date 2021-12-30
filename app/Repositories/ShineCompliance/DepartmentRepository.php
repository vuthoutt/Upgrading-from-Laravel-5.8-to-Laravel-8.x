<?php


namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\Department;
use Prettus\Repository\Eloquent\BaseRepository;

class DepartmentRepository extends BaseRepository
{
    function model()
    {
        return Department::class;
    }

    public function getDepartmentId($id) {
        return $this->model->find($id);
    }

    public function getDepartmentType($type, $parent_id) {
        return $this->model->with($type)->where('parent_id', $parent_id)->get();
    }

    public function getAllDepartmentClient($relation){
        return $this->model->with($relation)->where('parent_id', 0)->get();
    }

    public function getDepartment($relation, $id){
        return $this->model->with($relation)->where('id', $id)->first();
    }
}
