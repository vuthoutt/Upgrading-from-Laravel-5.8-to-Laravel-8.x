<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\HistoricDoc;
use Prettus\Repository\Eloquent\BaseRepository;

class HistoricalDocumentRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return HistoricDoc::class;
    }

    public function getFirstMsHistoricalDocument($property_id){
        return $this->model->where('property_id',$property_id)->whereIn('is_external_ms', [1,2])->first();
    }

    public function getDocument($id, $relations){
        return $this->model->with($relations)->where('id',$id)->first();
    }
}
