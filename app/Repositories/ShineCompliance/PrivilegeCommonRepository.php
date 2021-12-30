<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\PrivilegeCommon;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;

class PrivilegeCommonRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return PrivilegeCommon::class;
    }

    public function getPrivilegeCommon()
    {
        return $this->model->all();
    }
    public function getArrayValuesPrivilegeCommon($type)
    {
        return $this->model->where('type', $type)->pluck('id')->toArray();
    }
}
