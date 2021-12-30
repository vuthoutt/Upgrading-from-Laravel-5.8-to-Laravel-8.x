<?php


namespace App\Services\ShineCompliance;
use App\Repositories\ShineCompliance\DepartmentRepository;
use App\Repositories\ShineCompliance\DepartmentContractorRepository;

class DepartmentService
{
    private $departmentRepository;
    private $departmentContractorRepository;

    public function __construct(DepartmentRepository $departmentRepository, DepartmentContractorRepository $departmentContractorRepository){
        $this->departmentRepository = $departmentRepository;
        $this->departmentContractorRepository = $departmentContractorRepository;
    }

    public function checkDataUser($user){
        $departments_id = $user->department_id ?? '';
        if($user->clients->client_type == 0){
            $departments = $this->departmentRepository->getDepartmentId($departments_id);
        } else {
            $departments = $this->departmentContractorRepository->getDepartmentContractorId($departments_id);
        }
        return $departments;
    }

    public function getAllDepartmentClient($type = 'client', $client_id = 0)
    {
        if ($type == 'contractor') {
            $relation = ['childrens', 'allChildrens'];
            return $this->departmentContractorRepository->getAllDepartmentContractorClient($relation);
        } else {
            $relation = ['childrens', 'allChildrens'];
            return $this->departmentRepository->getAllDepartmentClient($relation);
        }
    }

    public function getDepartment($type, $id) {
        $relation = ['childrens', 'allChildrens'];
        if ($type == 'contractor') {
            return $this->departmentContractorRepository->getDepartmentContractorId($id,$relation);
        } else {
            return $this->departmentRepository->getDepartment($relation,$id);
        }
        $relation = ['client'];
        $condtion = 'id';
        return !is_null($department) ? $department: '';
    }
}
