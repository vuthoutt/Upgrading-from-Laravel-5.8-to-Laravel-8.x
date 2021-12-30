<?php
namespace App\Repositories\ShineCompliance;

use App\Models\ShineCompliance\ComplianceDocument;
use App\Models\ShineCompliance\ComplianceDocumentStatus;
use App\Models\ShineCompliance\ComplianceDocumentType;
use App\Models\ShineCompliance\ComplianceMainDocumentType;
use App\Models\ShineCompliance\ComplianceParentDocumentType;
use Prettus\Repository\Eloquent\BaseRepository;

class ComplianceDocumentRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return ComplianceDocument::class;
    }

    public function getComplianceDocuments($property_id, $relation, $pagination){
        return  $this->model->with($relation)->where('property_id', $property_id)->paginate($pagination);
    }

    public function getAllDocumentType(){
        return ComplianceMainDocumentType::all();
    }

    public function getAllDocumentStatuses(){
        return ComplianceDocumentStatus::all();
    }

    public function getAllDocumentsNoParent($property_id){
        return  $this->model->where('property_id', $property_id)->where('parent_type',1)->get();
    }

    public function getAllDocumentsTypeByParent($main_document_id){
        return ComplianceDocumentType::where(['type' => $main_document_id])->orderByRaw('CASE WHEN description LIKE \'%Other%\' THEN 999999
                    ELSE 2 END, description')->get();
    }

    public function getAllParentDocumentsType(){
        return ComplianceParentDocumentType::all();
    }

    public function listDocumentProperty($property_id, $relations){
        return  $this->model->with($relations)->where('property_id', $property_id)->get();
    }

    public function getDocument($id, $relations){
        return  $this->model->with($relations)->where('id', $id)->first();
    }

    public function listDocumentByType($arr_condition, $relations){
        return  $this->model->with($relations)->where($arr_condition)->get();
    }

    public function searchDocument($q){
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

        return $this->model->whereRaw("(reference LIKE '%$q%' OR name LIKE '%$q%')")
            ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
            ->orderBy('name','asc')
            ->limit(LIMIT_SEARCH)->get();
    }

}
