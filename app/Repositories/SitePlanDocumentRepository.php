<?php
namespace App\Repositories;
use App\Models\SitePlanDocument;
use Prettus\Repository\Eloquent\BaseRepository;

class SitePlanDocumentRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return SitePlanDocument::class;
    }


    public function searchPlan($q, $survey_id = 0){
        $condition = '='; // search Property Plan
        if($survey_id){
            $condition = '>'; // search Survey Plan
        }
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

        return $this->model->whereRaw("(reference LIKE '%$q%' OR plan_reference LIKE '%$q%' OR name LIKE '%$q%')")
                    ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
                    ->where('survey_id',$condition,0)
                    ->orderBy('plan_reference','asc')->limit(LIMIT_SEARCH)->get();
    }
}
