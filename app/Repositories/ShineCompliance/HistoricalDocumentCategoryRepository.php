<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\HistoricDocCategory;
use Prettus\Repository\Eloquent\BaseRepository;

class HistoricalDocumentCategoryRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return HistoricDocCategory::class;
    }

    public function getAllHistoricalCategory($property_id, $relations){
        return $this->model->with($relations)->where('property_id',$property_id)->get();
    }
}
