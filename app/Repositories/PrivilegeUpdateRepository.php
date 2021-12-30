<?php
namespace App\Repositories;
use App\Models\PrivilegeUpdate;
use Prettus\Repository\Eloquent\BaseRepository;

class PrivilegeUpdateRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return PrivilegeUpdate::class;
    }


}
