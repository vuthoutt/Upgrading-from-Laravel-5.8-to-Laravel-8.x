<?php
namespace App\Repositories\ShineCompliance;

use App\Models\ShineCompliance\ComplianceDocumentType;
use Prettus\Repository\Eloquent\BaseRepository;

class ComplianceDocumentTypeRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return ComplianceDocumentType::class;
    }
}
