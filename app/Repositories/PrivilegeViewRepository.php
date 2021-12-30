<?php
namespace App\Repositories;
use App\Models\PrivilegeView;
use Prettus\Repository\Eloquent\BaseRepository;

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


}
