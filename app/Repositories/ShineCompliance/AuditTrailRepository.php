<?php
namespace App\Repositories\ShineCompliance;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\ShineCompliance\AuditTrail;
use Illuminate\Support\Facades\DB;

class AuditTrailRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return AuditTrail::class;
    }

    public function getAllAuditTrail($order_by = "", $search = "") {
        $builder = $this->model->query();
        $builder->with('auditUser')->where('action_type','!=','lock');
//        $builder = AuditTrail::where('action_type','!=','lock');
        if(!empty($search)){
            $builder->whereRaw($search);
        }
        if($order_by){
            $builder->orderByRaw($order_by);
        }
        $builder->select("shine_reference", "object_reference", "action_type", "user_name", "date", "comments");
        return $builder;
    }
}
