<?php

namespace App\Services\ShineCompliance;

use App\Repositories\ShineCompliance\RoleRepository;

class RoleService{

    private $roleRepository;

    public function __construct(RoleRepository $roleRepository){
        $this->roleRepository = $roleRepository;
    }

    public function getAllRole(){
        return $this->roleRepository->getAllRole();
    }
    public function decodeData($role_data){
        return json_decode(json_encode($role_data));;
    }
}
