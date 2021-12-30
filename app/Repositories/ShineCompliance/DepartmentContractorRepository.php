<?php


namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\DepartmentContractor;
use Prettus\Repository\Eloquent\BaseRepository;

class DepartmentContractorRepository extends BaseRepository
{
    function model()
    {
        return DepartmentContractor::class;
    }

    public function getDepartmentContractorId($id,$relation = []) {
        return $this->model->with($relation)->find($id);
    }

    public function getDepartmentsContractorType() {
        return $this->model->with('allChildrens')->where('parent_id', 0)->orWhere('client_id', 0)->get();
    }

    public function getDepartmentContractorParents($parent_id) {
        return $this->model->with('allParents')->where('parent_id', $parent_id)->get();
    }

    public function getAllDepartmentContractorClient($relation){
        return $this->model->with($relation)->where('parent_id', 0)->get();
    }

    public function getAllDepartmentClient($type = 'client', $client_id = 0) {
        if ($type == 'contractor') {
            return $this->model->with('childrens', 'allChildrens')->where('parent_id',0)->get();
        }
    }
}
