<?php


namespace App\Services\ShineCompliance;

use App\Helpers\ComplianceHelpers;
use App\Models\ShineCompliance\HistoricalDocumentType;
use App\Repositories\ShineCompliance\ComplianceCategoryDocumentRepository;
use App\Repositories\ShineCompliance\ComplianceDocumentRepository;
use App\Repositories\ShineCompliance\ComplianceDocumentTypeRepository;
use App\Repositories\ShineCompliance\ComplianceSystemRepository;
use App\Repositories\ShineCompliance\ComplianceProgrammeRepository;
use App\Repositories\ShineCompliance\ComplianceEquipmentRepository;
use App\Repositories\ShineCompliance\EquipmentRepository;
use App\Repositories\ShineCompliance\HistoricalDocumentCategoryRepository;
use Carbon\Carbon;

class SystemService
{
    private $systemRepository;
    private $programmeRepository;
    private $equipmentRepository;
    private $complianceDocumentRepository;
    private $historicalDocumentCategoryRepository;
    private $categoryDocumentRepository;
    private $complianceDocumentTypeRepository;

    public function __construct(ComplianceSystemRepository $systemRepository, EquipmentRepository $equipmentRepository ,
                                ComplianceProgrammeRepository $programmeRepository, ComplianceDocumentRepository $complianceDocumentRepository,
                                HistoricalDocumentCategoryRepository $historicalDocumentCategoryRepository,
                                ComplianceCategoryDocumentRepository $categoryDocumentRepository,
                                ComplianceDocumentTypeRepository $complianceDocumentTypeRepository)
    {
        $this->systemRepository = $systemRepository;
        $this->programmeRepository = $programmeRepository;
        $this->equipmentRepository = $equipmentRepository;
        $this->complianceDocumentRepository = $complianceDocumentRepository;
        $this->historicalDocumentCategoryRepository = $historicalDocumentCategoryRepository;
        $this->categoryDocumentRepository = $categoryDocumentRepository;
        $this->complianceDocumentTypeRepository = $complianceDocumentTypeRepository;
    }

    public function getAllSystemGroupType($property_id, $relations = []) {
        return $this->systemRepository->getAllSystemGroupType($property_id, $relations);
    }

    public function getAllSystem($property_id, $limit = 10000,$request = null) {
        return $this->systemRepository->getAllSystem($property_id, $limit,$request);
    }

    public function getAllSystemClassification() {
        return $this->systemRepository->getAllSystemClassification();
    }

    public function getAllSystemType() {
        return $this->systemRepository->getAllSystemType();
    }

    public function getSearchSystem() {
        return $this->systemRepository->searchSystem();
    }

    public function getSystem($id, $relation = []) {
        return $this->systemRepository->with($relation)->find($id);
    }

    public function searchSystem($query_string, $property_id = 0, $assess_id = 0) {

        return $this->systemRepository->searchSystemInAssessment($query_string, $property_id, $assess_id);
    }

    public function getProgramme($id) {
        return $this->programmeRepository->find($id);
    }

    public function getEquipment($id) {
        return $this->equipmentRepository->find($id);
    }

    public function getAllProgrammes($system_id, $limit, $request ) {
        return $this->programmeRepository->getAllProgrammes($system_id, $limit,$request);
    }

    public function getAllEquipmentType() {
        return $this->equipmentRepository->getAllTypes();
    }
    public function getSystemDetail($id, $relation = []) {
        return $this->systemRepository->with($relation)->where('id', $id)->first();
    }

    public function getAllEquipments($system_id, $limit, $property_id = null,$request = null) {
        if (!is_null($system_id)) {
            return $this->equipmentRepository->getAllEquipmentsInSystem($system_id, $limit,$request);
        }
        if (!is_null($property_id)) {
            return $this->equipmentRepository->getAllEquipments($property_id, $limit,$request);
        }
        return null;
    }


    public function updateOrCreateSystem($data, $id = null) {
        try {
            \DB::beginTransaction();
            //create
            if (is_null($id)) {
                $system = $this->systemRepository->create($data);
                $id = $system->id;
                $data_update['reference'] = 'PS' . $id;
                $data_update['record_id'] = $id;
                $data_update['created_by'] = \Auth::user()->id;
                $data_update['decommissioned'] = 0;
                $system = $this->systemRepository->update($data_update,$id);
                $message = 'Created New System Successfully!';
                //audit
                \ComplianceHelpers::logAudit(SYSTEM_TYPE, $system->id, AUDIT_ACTION_ADD, $system->reference, 0, null , 0 , $system->property_id ?? 0);
            } else {
                $data_update['updated_by'] = \Auth::user()->id;
                $this->systemRepository->update($data,$id);
                $system =  $this->systemRepository->find($id);
                $message = 'Updated System Successfully!';
                //audit
                \ComplianceHelpers::logAudit(SYSTEM_TYPE, $system->id, AUDIT_ACTION_EDIT, $system->reference, 0, null , 0 , $system->property_id ?? 0);
            }
            if (isset($data['photo'])) {
                $saveLocationImage = \ComplianceHelpers::saveFileComplianceDocumentStorage($data['photo'], $system->id, COMPLIANCE_SYSTEM_PHOTO);
            }

            \DB::commit();

            return \ComplianceHelpers::successResponse($message, $id);
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::debug($e);
            return \ComplianceHelpers::failResponse(STATUS_FAIL,'Error has occurred. Please try again later!');
        }
    }

    public function createOrUpdateDocument($data, $property, $document_id = null) {
        $compliance_type = $data['type'][0] ?? 0;
        $type = last($data['type']) ?? 0;
        $doc_type = $this->complianceDocumentTypeRepository->where('id', $type)->first();
        try {
            \DB::beginTransaction();
            //todo add switches, asbestos type, doctype(historical doc) is type now, added is date now
            //create
            if (is_null($document_id)) {
                $create_data = [
//                    'reference' => $data['reference'],
                    'property_id' => $property->id,
                    'equipment_id' => $data['property_equipment'] ?? NULL,
                    'programme_id' => $data['property_programme'] ?? NULL,
                    'parent_type' => $data['parent_type'] ?? NULL,
                    'compliance_type' => $compliance_type,
                    'type' => $type,
                    'type_other' => $data['type_other'] ?? NULL,
                    'is_identified_acm' => \CommonHelpers::checkArrayKey($data,'is_identified_acm') ?? 0,
                    'is_inaccess_room' => \CommonHelpers::checkArrayKey($data,'is_inaccess_room') ?? 0,
                    'is_external_ms' => $doc_type->is_external_ms ?? 0,
                    'is_reinspected' => $doc_type->is_reinspected ?? 0,
                    'category_id' => \CommonHelpers::checkArrayKey($data,'category_id'),
                    'status' => DOCUMENT_STATUS_NEW,
                    'system_id' => $data['property_system'] ?? NULL,
                    'date' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data,'date')),
                    'name' => $data['name'] ?? NULL,
                    'created_by' => \Auth::user()->id,
                    'enforcement_deadline' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data,'enforcement_deadline')),
                    'document_status' => $data['document_status'] ?? NULL,
                ];
                $document = $this->complianceDocumentRepository->create($create_data);
                $document->reference = 'HD'.$document->id;
                $document->save();
                $message = 'Created New Document Successfully!';
                //audit
                \ComplianceHelpers::logAudit(DOCUMENT_TYPE, $document->id, AUDIT_ACTION_ADD, $document->reference);
            } else {
                $update_data = [
                    'equipment_id' => $data['property_equipment'] ?? NULL,
                    'programme_id' => $data['property_programme'] ?? NULL,
                    'parent_type' => $data['parent_type'] ?? NULL,
                    'compliance_type' => $compliance_type,
                    'type' => $type,
                    'type_other' => $data['type_other'] ?? NULL,
                    'system_id' => $data['property_system'] ?? NULL,
                    'date' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data,'date')),
                    'name' => $data['name'] ?? NULL,
                    'is_identified_acm' => \CommonHelpers::checkArrayKey($data,'is_identified_acm') ?? 0,
                    'is_inaccess_room' => \CommonHelpers::checkArrayKey($data,'is_inaccess_room') ?? 0,
                    'is_external_ms' => $doc_type->is_external_ms ?? 0,
                    'is_reinspected' => $doc_type->is_reinspected ?? 0,
                    'category_id' => \CommonHelpers::checkArrayKey($data,'category_id'),
                    'enforcement_deadline' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data,'enforcement_deadline')),
                    'document_status' => $data['document_status'] ?? NULL,
                ];
                $message = 'Updated Document Successfully!';
                //audit
                $document = $this->complianceDocumentRepository->update($update_data, $document_id);
                \ComplianceHelpers::logAudit(DOCUMENT_TYPE, $document->id, AUDIT_ACTION_EDIT, $document->reference);
            }
            //check inspection of programme
            // currently only DOCUMENT_SERVICE_RECORD_TYPE will effect the inspection
            //todo get type inspect from DB
//            if(isset($doc_type->is_reinspected) && $doc_type->is_reinspected == DOCUMENT_REINSPECTED){
//                $programme = $this->programmeRepository->with('documentInspection')->where('id', $data['property_programme'])->first();
//                if($programme){
//                    $previous_linked_document = $programme->documentInspection;
//                    if($previous_linked_document && $document->getOriginal('date') > 0){
//                        $previous_inspection_date = $previous_linked_document->getOriginal('date') ?? 0;
//                        if($document->getOriginal('date') > $previous_inspection_date){
//                            $programme->linked_document_id = $document->id;
//                            $programme->save();
//                        }
//                    } else {
//                        $programme->linked_document_id = $document->id;
//                        $programme->save();
//                    }
//                }
//            }
            if (isset($data['document'])) {
                $saveLocationImage = \ComplianceHelpers::saveFileComplianceDocumentStorage($data['document'], $document->id, COMPLIANCE_DOCUMENT_PHOTO);
            }
            \DB::commit();

            return \ComplianceHelpers::successResponse($message, $document);
        } catch (\Exception $e) {
            \DB::rollback();
            return \ComplianceHelpers::failResponse(STATUS_FAIL,'Error has occurred. Please try again later!');
        }
    }

    public function decommissionSystem($id) {
        try {
            \DB::beginTransaction();
            $system = $this->systemRepository->find($id);
            if ($system->decommissioned == 0) {
               $this->systemRepository->update(['decommissioned' => 1], $id);
               $message = 'Decommissioned System Successfully!';
               \ComplianceHelpers::logAudit(SYSTEM_TYPE, $system->id, AUDIT_ACTION_DECOMMISSION, $system->reference, 0, null , 0 , $system->property_id ?? 0);
            } else {
                $this->systemRepository->update(['decommissioned' => 0], $id);
                $message = 'Recommissioned System Successfully!';
                \ComplianceHelpers::logAudit(SYSTEM_TYPE, $system->id, AUDIT_ACTION_RECOMMISSION, $system->reference, 0, null , 0 , $system->property_id ?? 0);
            }
            \DB::commit();
            return \ComplianceHelpers::successResponse($message);
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::debug($e);
            return \ComplianceHelpers::failResponse(STATUS_FAIL,'Error has occurred. Please try again later!');
        }
    }

    public function decommissionProgramme($id) {
        try {
            \DB::beginTransaction();
            $programme = $this->programmeRepository->find($id);
            if ($programme->decommissioned == 0) {
               $this->programmeRepository->update(['decommissioned' => 1], $id);
               $message = 'Decommissioned Programme Successfully!';
               \ComplianceHelpers::logAudit(PROGRAMME_TYPE, $programme->id, AUDIT_ACTION_DECOMMISSION, $programme->reference, 0, null , 0 , $programme->property_id ?? 0);
            } else {
                $this->programmeRepository->update(['decommissioned' => 0], $id);
                $message = 'Recommissioned Programme Successfully!';
                \ComplianceHelpers::logAudit(PROGRAMME_TYPE, $programme->id, AUDIT_ACTION_RECOMMISSION, $programme->reference, 0, null , 0 , $programme->property_id ?? 0);
            }
            \DB::commit();
            return \ComplianceHelpers::successResponse($message);
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::debug($e);
            return \ComplianceHelpers::failResponse(STATUS_FAIL,'Error has occurred. Please try again later!');
        }
    }

    public function decommissionEquipment($id) {
        try {
            \DB::beginTransaction();
            $equipment = $this->equipmentRepository->where('id', $id)->first();
            if ($equipment->decommissioned == 0) {
               $this->equipmentRepository->update(['decommissioned' => 1], $id);
               $message = 'Decommissioned Equipment Successfully!';
               \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $equipment->id, AUDIT_ACTION_DECOMMISSION, $equipment->reference, 0, null , 0 , $equipment->property_id ?? 0);
            } else {
                $this->equipmentRepository->update(['decommissioned' => 0], $id);
                $message = 'Recommissioned Equipment Successfully!';
                \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $equipment->id, AUDIT_ACTION_RECOMMISSION, $equipment->reference, 0, null , 0 , $equipment->property_id ?? 0);
            }
            \DB::commit();
            return \ComplianceHelpers::successResponse($message);
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::debug($e);
            return \ComplianceHelpers::failResponse(STATUS_FAIL,'Error has occurred. Please try again later!');
        }
    }

    public function updateOrCreateProgramme($system_id, $data, $id = null) {
        try {
            \DB::beginTransaction();
            //create

//            $date_inspected = \ComplianceHelpers::toTimeStamp(\ComplianceHelpers::checkArrayKey($data, 'date_inspected'));
//            $data['date_inspected'] = $date_inspected;
//            if ($date_inspected == 0) {
//                $data['next_inspection'] = 0;
//            } else {
//                // add 1 year
//                $data['next_inspection'] = $date_inspected + ($data['inspection_period'] * 86400);
//            }

            if (is_null($id)) {
                $system = $this->systemRepository->find($system_id);
                $data['system_id'] = $system->id;
                $data['property_id'] = $system->property_id;
                $programme = $this->programmeRepository->create($data);
                $id = $programme->id;
                $data_update['reference'] = 'PT' . $id;
                $data_update['created_by'] = \Auth::user()->id;
                $data_update['decommissioned'] = 0;
                $this->programmeRepository->update($data_update,$id);
                $message = 'Created New Programme Successfully!';
                //audit
                \ComplianceHelpers::logAudit(PROGRAMME_TYPE, $programme->id, AUDIT_ACTION_ADD, $programme->reference, 0, null , 0 , $programme->property_id ?? 0);
            } else {
                $data_update['updated_by'] = \Auth::user()->id;
                $this->programmeRepository->update($data,$id);
                $programme =  $this->programmeRepository->find($id);
                $message = 'Updated Programme Successfully!';
                //audit
                \ComplianceHelpers::logAudit(PROGRAMME_TYPE, $programme->id, AUDIT_ACTION_EDIT, $programme->reference, 0, null , 0 , $programme->property_id ?? 0);
            }
            if (isset($data['photo'])) {
                $saveImage = \ComplianceHelpers::saveFileComplianceDocumentStorage($data['photo'], $programme->id, COMPLIANCE_PROGRAMME_PHOTO);
            }

            \DB::commit();

            return \ComplianceHelpers::successResponse($message, $id);
        } catch (\Exception $e) {
            \DB::rollback();
            return \ComplianceHelpers::failResponse(STATUS_FAIL,'Error has occurred. Please try again later!');
        }
    }

    public function updateOrCreateEquipment($system_id, $data, $id = null) {
        try {
            \DB::beginTransaction();
            //create

            if (is_null($id)) {
                $system = $this->systemRepository->find($system_id);
                $data['system_id'] = $system->id;
                $data['property_id'] = $system->property_id;
                $equipment = $this->equipmentRepository->create($data);
                $id = $equipment->id;
                $data_update['reference'] = 'EQ' . $id;
                $data_update['created_by'] = \Auth::user()->id;
                $data_update['decommissioned'] = 0;
                $this->equipmentRepository->update($data_update,$id);
                $message = 'Created New Equipment Successfully!';
                //audit
                \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $equipment->id, AUDIT_ACTION_ADD, $equipment->reference, 0, null , 0 , $equipment->property_id ?? 0);
            } else {
                $data_update['updated_by'] = \Auth::user()->id;
                $this->equipmentRepository->update($data,$id);
                $equipment =  $this->equipmentRepository->where('id', $id)->first();
                $message = 'Updated Equipment Successfully!';
                //audit
                \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $equipment->id, AUDIT_ACTION_EDIT, $equipment->reference, 0, null , 0 , $equipment->property_id ?? 0);
            }
            if (isset($data['photo'])) {
                $saveImage = \ComplianceHelpers::saveFileComplianceDocumentStorage($data['photo'], $equipment->id, EQUIPMENT_PHOTO);
            }

            \DB::commit();

            return \ComplianceHelpers::successResponse($message, $id);
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::debug($e);
            return \ComplianceHelpers::failResponse(STATUS_FAIL,'Error has occurred. Please try again later!');
        }
    }

    public function getAllDocumentsType(){
        return $this->complianceDocumentRepository->getAllDocumentType();
    }

    public function getAllDocumentStatuses()
    {
        return $this->complianceDocumentRepository->getAllDocumentStatuses();
    }

    public function getAllDocumentsNoParent($property_id){
        return $this->complianceDocumentRepository->getAllDocumentsNoParent($property_id);
    }

    public function getAllDocumentsTypeByParent($main_document_id){
        return $this->complianceDocumentRepository->getAllDocumentsTypeByParent($main_document_id);
    }

    public function getAllCategoryDocument($property_id){
        return $this->categoryDocumentRepository->getAllCategoryDocument($property_id, ['documents']);
    }

    public function getAllParentDocumentsType(){
        return $this->complianceDocumentRepository->getAllParentDocumentsType();
    }

    public function getAllDocumentFromSystems($system_types, $systems){
        $data = [];
        foreach ($system_types as $type){
            foreach ($systems as $k => $system){
                if($type->id == $k){
                    foreach ($system as $s){
                        if(count($s->documents)){
                            $data[$type->id][] = $s->documents;
                        }
                    }
                }
            }
        }
        return $data;
    }

    public function getAllHistoricalCategory($property_id, $relations = ['historicalDoc']) {
        return $this->historicalDocumentCategoryRepository->getAllHistoricalCategory($property_id, $relations);
    }

    public function getAllHistoricalType(){
        return HistoricalDocumentType::all();
    }

    public function listSystemProperty($property_id){
        return $this->systemRepository->listSystemProperty($property_id);
    }

    public function listRegisterSystemProperty($property_id, $relations = []){
        return $this->systemRepository->listRegisterSystemProperty($property_id, $relations);
    }

    public function listProgrammeProperty($property_id, $system_id){
        return $this->programmeRepository->listProgrammeProperty($property_id, $system_id);
    }

    public function listEquimentProperty($property_id, $system_id){
        return $this->equipmentRepository->listEquimentProperty($property_id, $system_id);
    }

    public function listDocumentProperty($property_id, $system_types, $relations){
        $documents = $this->complianceDocumentRepository->listDocumentProperty($property_id, $relations);
//        dd($documents);
        $data = [];
        if(count($documents) > 0){
            foreach ($documents as $document){
                if(isset($document->system->type)){
                    foreach ($system_types as $type){
                        if($document->system->type == $type->id){
                            $data[$type->id][] = $document;
                            break;
                        }
                    }
                } else {
                    $data[-1][] = $document;
                }
            }
        }
        return $data;
    }

    public function listDocumentByType($id, $type, $relations = []){
        $data = [];
        $view = '';
        $object = NULL;
        switch ($type){
            case DOCUMENT_SYSTEM_TYPE:
                $data = $this->complianceDocumentRepository->listDocumentByType(['system_id'=>$id], $relations);
                $view = 'shineCompliance.properties.systems.system_document_list';
                $object = $this->systemRepository->where('id', $id)->first();
                break;
            case DOCUMENT_EQUIPMENT_TYPE:
                $data = $this->complianceDocumentRepository->listDocumentByType(['equipment_id'=>$id], $relations);
                $view = 'shineCompliance.properties.systems.equipment_document_list';
                $object = $this->equipmentRepository->where('id', $id)->first();
                break;
            case DOCUMENT_PROGRAMME_TYPE:
                $data = $this->complianceDocumentRepository->listDocumentByType(['programme_id'=>$id], $relations);
                $object = $this->programmeRepository->where('id', $id)->first();
                $view = 'shineCompliance.properties.systems.programme_document_list';
                break;
        }
        return [$data, $view, $object];
    }

}
