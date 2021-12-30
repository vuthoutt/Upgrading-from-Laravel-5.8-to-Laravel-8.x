<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\JobRoleViewValue;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;

class JobRoleViewValueRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return JobRoleViewValue::class;
    }

}
