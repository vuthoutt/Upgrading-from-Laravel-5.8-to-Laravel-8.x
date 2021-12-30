<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\PrivilegeChild;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;

class PrivilegeChildRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return PrivilegeChild::class;
    }

}
