<?php
namespace App\Repositories\ShineCompliance;
use App\Models\Property;
use App\Models\ShineCompliance\DecommissionReason;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;

class HomeRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Zone::class;
    }

    public function getDecomissionReason($type){
        return DecommissionReason::where('type', $type)->where('parent_id', DECOMMISSION)->get();
    }

    public function getRecomissionReason($type){
        return DecommissionReason::where('type', $type)->where('parent_id', RECOMMISSION)->get();
    }

}
