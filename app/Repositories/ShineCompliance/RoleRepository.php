<?php

namespace App\Repositories\ShineCompliance;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\ShineCompliance\Role;

class RoleRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */

    function model()
    {
        return Role::class;
    }

    public function getAllRole() {
        return $this->model->all();
    }
    public function getRoleFind($id) {
        return $this->model->find($id);
    }
}
