<?php
namespace App\Repositories;
use App\Models\RoleUpdate;
use Prettus\Repository\Eloquent\BaseRepository;

class RoleUpdateRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return RoleUpdate::class;
    }


}
