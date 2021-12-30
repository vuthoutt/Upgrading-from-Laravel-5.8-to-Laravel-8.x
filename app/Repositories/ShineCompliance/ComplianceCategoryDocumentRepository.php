<?php
namespace App\Repositories\ShineCompliance;

use App\Models\ShineCompliance\ComplianceDocument;
use App\Models\ShineCompliance\ComplianceDocumentCategory;
use Prettus\Repository\Eloquent\BaseRepository;

class ComplianceCategoryDocumentRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return ComplianceDocumentCategory::class;
    }


    public function getAllCategoryDocument($property_id, $relations){
        $data = $this->model->with($relations)->where(['property_id' => $property_id])->get();
        return $data;
    }

    public function getDocProperty($property_id){
        return ComplianceDocument::where(['property_id' => $property_id])->where('category_id',"!=", 0)->get();
    }

    public function getDocProject($ids){
        if($ids){
            $sql = "SELECT d.id,d.reference,d.name,cdc.name as category,d.date
                FROM compliance_documents as d
                LEFT JOIN compliance_document_category as cdc ON d.category_id = cdc.id
                WHERE d.id IN ($ids)";
            $data = \DB::select($sql);
        }
        return $data ?? [];
    }

}
