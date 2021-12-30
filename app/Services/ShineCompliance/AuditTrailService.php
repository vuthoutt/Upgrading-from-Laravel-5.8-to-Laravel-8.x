<?php

namespace App\Services\ShineCompliance;
use App\Repositories\ShineCompliance\ComplianceSystemRepository;
use App\Repositories\ShineCompliance\AuditTrailRepository;
use App\Repositories\ShineCompliance\SurveyRepository;
use App\Repositories\ShineCompliance\PropertyRepository;
use App\Repositories\ShineCompliance\ItemRepository;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;

class AuditTrailService
{

    private $auditTrailRepository;
    private $propertyRepository;
    private $surveyRepository;
    private $itemRepository;

    public function __construct(
        AuditTrailRepository $auditTrailRepository,
        PropertyRepository $propertyRepository,
        SurveyRepository $surveyRepository,
        ItemRepository $itemRepository
    ){

        $this->auditTrailRepository = $auditTrailRepository;
        $this->propertyRepository = $propertyRepository;
        $this->surveyRepository = $surveyRepository;
        $this->itemRepository = $itemRepository;
    }

    public function getAuditTrail($request) {
        $order_by = "tbl_audit_trail.id DESC";
        $search = "";
        if(isset($request->order[0]['column']) && isset($request->order[0]['dir'])){
            $index = $request->order[0]['column'];
            $column = $request->columns[$index]['data'] ?? '';
            $dir = $request->order[0]['dir'];
            if($column && $dir){
                $order_by = " tbl_audit_trail.".$column. " $dir";
            }
        }
        if(isset($request->search['value']) && !empty($request->search['value'])){
            $text_search = $request->search['value'];
            $timestamp = DateTime::createFromFormat('d/m/Y', $text_search);
            if($timestamp){
                $date = !empty($text_search) ? \Carbon\Carbon::createFromFormat('d/m/Y', $text_search)->toDateString() : $text_search;
                $search = "( tbl_audit_trail.`shine_reference` LIKE '%$text_search%' OR tbl_audit_trail.`object_reference` LIKE '%$text_search%' OR tbl_audit_trail.`action_type` LIKE '%$text_search%' OR tbl_audit_trail.`created_at` LIKE '%$date%' OR tbl_audit_trail.`comments` LIKE '%$text_search%') ";
            } else {
                $search = "( tbl_audit_trail.`shine_reference` LIKE '%$text_search%' OR tbl_audit_trail.`object_reference` LIKE '%$text_search%' OR tbl_audit_trail.`action_type` LIKE '%$text_search%' OR tbl_audit_trail.`comments` LIKE '%$text_search%') ";
            }
        }
        return $this->auditTrailRepository->getAllAuditTrail($order_by, $search);
    }

    public function logAjaxAudit($id, $type, $tab) {

        switch ($type) {
            case 'property':
                $property = $this->propertyRepository->find($id);

                $comment = \Auth::user()->full_name . " viewed Property " . $tab .' tab on ' .$property->name;
                \CommonHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_VIEW, $property->property_reference, $property->client_id, $comment, 0 , $property->id);

                break;

            case 'survey':
                $survey =  $this->surveyRepository->find($id);
                $comment = \Auth::user()->full_name . " viewed Survey " . $tab .' tab on ' .$survey->reference . ' on ' . $survey->property->name;
                \CommonHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_VIEW, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);

                break;

            case 'item':
                $item =  $this->itemRepository->find($id);
                $comment = \Auth::user()->full_name  . " viewed Item " . $tab . ' tab on ' . $item->reference .(isset($item->survey->reference) ? ' on '.$item->survey->reference : ''). ' on ' . ($item->property->name ?? '');
                \CommonHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_VIEW, $item->reference, $item->survey_id ,$comment, 0 ,$item->property_id);
                break;

            default:
                # code...
                break;
        }
        return true;
    }
}
