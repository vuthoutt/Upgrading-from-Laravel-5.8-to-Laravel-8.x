<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\HistoricalDocumentType;
use Prettus\Repository\Eloquent\BaseRepository;

class HistoricalDocumentTypeRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return HistoricalDocumentType::class;
    }
}
