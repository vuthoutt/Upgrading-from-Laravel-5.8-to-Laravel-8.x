<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\PropertyComment;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;

class PropertyCommentRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return PropertyComment::class;
    }

    function getFindPropertyComment($id){
        return $this->model->find($id);
    }

}
