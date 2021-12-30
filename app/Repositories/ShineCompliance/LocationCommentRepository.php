<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\LocationComment;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;

class LocationCommentRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return LocationComment::class;
    }

    public function getFindLocationComment($id){
        return $this->model->find($id);
    }

}
