<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\JobRoleEditValue;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;

class JobRoleEditValueRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return JobRoleEditValue::class;
    }

}
