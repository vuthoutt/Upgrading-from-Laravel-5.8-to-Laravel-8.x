<?php
namespace App\Repositories;
use App\Models\HistoricDoc;
use Prettus\Repository\Eloquent\BaseRepository;

class HistoricDocRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return HistoricDoc::class;
    }


    public function searchHistoric($q){
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

        return $this->model->whereRaw("(reference LIKE '%$q%' OR name LIKE '%$q%')")
                    ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
                    ->orderBy('name','asc')
                    ->limit(LIMIT_SEARCH)->get();
    }
}
