<?php
namespace App\Repositories;
use App\Models\SummaryPdf;
use Prettus\Repository\Eloquent\BaseRepository;

class SummaryPdfRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return SummaryPdf::class;
    }


    public function searchSummary($q){
        return $this->model->whereRaw("(file_name LIKE '%$q%' OR reference LIKE '%$q%')")->limit(10)->get();
    }
}
