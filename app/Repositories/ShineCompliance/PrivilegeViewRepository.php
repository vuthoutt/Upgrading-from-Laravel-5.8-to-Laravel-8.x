<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\PrivilegeView;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;

class PrivilegeViewRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return PrivilegeView::class;
    }

    public function getPrivilegeView($type, $relations){
        return $this->model->with($relations)->where('type', $type)->where('parent_id', 0)->where('is_deleted', 0)->get();
    }
}
