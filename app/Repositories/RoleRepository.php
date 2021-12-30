<?php
namespace App\Repositories;
use App\Models\PrivilegeView;
use App\Models\Role;
use Prettus\Repository\Eloquent\BaseRepository;

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


}
