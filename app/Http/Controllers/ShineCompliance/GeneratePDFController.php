<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use App\Models\ShineCompliance\IncidentReportDocument;
use App\Models\ShineDocumentStorage;
use App\Repositories\ShineCompliance\ComplianceDocumentRepository;
use App\Models\ShineCompliance\ComplianceDocumentStorage;
use App\Services\ShineCompliance\GenerateAssessmentPDFService;
use App\Services\ShineCompliance\PropertyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GeneratePDFController extends Controller
{
    private $propertyService;
    private $generateAssessmentPDFService;
    public function __construct(PropertyService $propertyService, GenerateAssessmentPDFService $generateAssessmentPDFService){
        $this->propertyService = $propertyService;
        $this->generateAssessmentPDFService = $generateAssessmentPDFService;
    }

    public function viewDocument(Request $request){
        $type = $request->type;
        $id = $request->id;
        if($type == VIEW_COMPLIANCE_DOCUMENT){
            return $this->propertyService->viewDocument($id);
        } else if($type == VIEW_COMPLIANCE_HISTORICAL_DOCUMENT){
            return $this->propertyService->viewHistoricalDocument($id);
        }
        return false;
    }

    public function downDownload(Request $request){
        $type = $request->type;
        $id = $request->id;
        if($type == DOWNLOAD_COMPLIANCE_DOCUMENT){
            return $this->propertyService->downloadDocument($id);
        } else if($type == DOWNLOAD_COMPLIANCE_HISTORICAL_DOCUMENT){
            return $this->propertyService->downloadHistoricalDocument($id);
        }
        return false;
    }


    public function downloadImage($type, $id){

        $file = ShineDocumentStorage::where('object_id', $id)->where('type', $type)->first();

        $headers = [
            'Content-Type' => 'image/jpeg',
        ];

        if (isset($file->path)) {
            if (is_file($file->path)) {
                return response()->download(storage_path().'/app/'.$file->path, $file->file_name, $headers);
            } else {
                dd('aaaaaaaa');
                return redirect()->back()->with('err', 'No previewed image available !');
            }
        } else {
            dd($file->path);
            dd('dsfsdfsdfsdf');
                return redirect()->back()->with('err', 'No previewed image available !');
        }
    }

    public function downloadIncidentDocumentFile($id)
    {
        $file = IncidentReportDocument::where('id', $id)->first();
        //audit log
        $comment =  \Auth::user()->full_name . ' downloaded incident reporting document ' . ($file->filename ?? '');
        \CommonHelpers::logAudit(INCIDENT_REPORTING_DOC_TYPE, $id , AUDIT_ACTION_DOWNLOAD, ($file->filename ?? ''), 0 , $comment , $id, $file->incidentReport->property->id ?? 0);

        if (isset($file->path)) {
            if (is_file(storage_path().'/app/'.$file->path)) {
                $headers = [
                    'Content-Type' => 'application/pdf',
                ];
                return response()->download(storage_path().'/app/'.$file->path, $file->filename, $headers);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function createFireRegisterPDF($property_id){
        if($property = $this->propertyService->getProperty($property_id, ['project','propertyInfo','propertyType'])){
            $warning_message = $this->propertyService->getWarningMessagev3($property);
            $data_sucess = $this->generateAssessmentPDFService->createFireRegisterPDF($property, $warning_message);
            if (isset($data_sucess) and count($data_sucess) and isset($data_sucess['path']) and !empty($data_sucess['path'])) {
//                    return $this->zipFile($data_sucess['path'], $data_sucess['file_name']);
//                return redirect()->route('shineCompliance.property.property_detail', ['id' => $data_sucess['data']->id])->with('msg', $data_sucess['msg']);
                $headers = [
                    'Content-Type' => 'application/pdf',
                ];
                return response()->download($data_sucess['path'], $data_sucess['file_name'], $headers);
            }
        }
        return redirect()->back()->with('err', 'Failed to create fire register pdf. Please try again!');
    }

    public function createWaterRegisterPDF($property_id){
        if($property = $this->propertyService->getProperty($property_id, ['project','propertyInfo','propertyType'])){
            $warning_message = $this->propertyService->getWarningMessagev3($property);
            $data_sucess = $this->generateAssessmentPDFService->createWaterRegisterPDF($property, $warning_message);
            if (isset($data_sucess) and count($data_sucess) and isset($data_sucess['path']) and !empty($data_sucess['path'])) {
//                    return $this->zipFile($data_sucess['path'], $data_sucess['file_name']);
//                return redirect()->route('shineCompliance.property.property_detail', ['id' => $data_sucess['data']->id])->with('msg', $data_sucess['msg']);
                $headers = [
                    'Content-Type' => 'application/pdf',
                ];
                return response()->download($data_sucess['path'], $data_sucess['file_name'], $headers);
            }
        }
        return redirect()->back()->with('err', 'Have something wrong when create water register pdf. Please try again!');
    }

    public function viewFireRegisterPDF($property_id){
        if($property = $this->propertyService->getProperty($property_id, ['project','propertyInfo','propertyType'])){
            $warning_message = $this->propertyService->getWarningMessagev3($property);
            return $this->generateAssessmentPDFService->viewFireRegisterPDF($property, $warning_message);
        }
        return redirect()->back()->with('err', 'Failed to view fire register pdf. Please try again!');
    }

    public function viewWaterRegisterPDF($property_id){
        if($property = $this->propertyService->getProperty($property_id, ['project','propertyInfo','propertyType'])){
            $warning_message = $this->propertyService->getWarningMessagev3($property);
            return $this->generateAssessmentPDFService->viewWaterRegisterPDF($property, $warning_message);
        }
        return redirect()->back()->with('err', 'Have something wrong when view water register pdf. Please try again!');
    }


    public function viewHSRegisterPDF($property_id){
        if($property = $this->propertyService->getProperty($property_id, ['project','propertyInfo','propertyType'])){
            $warning_message = $this->propertyService->getWarningMessagev3($property);
            return $this->generateAssessmentPDFService->viewHsRegisterPDF($property, $warning_message);
        }
        return redirect()->back()->with('err', 'Have something wrong when view hs register pdf. Please try again!');
    }

    public function createHSRegisterPDF($property_id){
        if($property = $this->propertyService->getProperty($property_id, ['project','propertyInfo','propertyType'])){
            $warning_message = $this->propertyService->getWarningMessagev3($property);
            $data_sucess = $this->generateAssessmentPDFService->createHsRegisterPDF($property, $warning_message);
            if (isset($data_sucess) and count($data_sucess) and isset($data_sucess['path']) and !empty($data_sucess['path'])) {
//                    return $this->zipFile($data_sucess['path'], $data_sucess['file_name']);
//                return redirect()->route('shineCompliance.property.property_detail', ['id' => $data_sucess['data']->id])->with('msg', $data_sucess['msg']);
                $headers = [
                    'Content-Type' => 'application/pdf',
                ];
                return response()->download($data_sucess['path'], $data_sucess['file_name'], $headers);
            }
        }
        return redirect()->back()->with('err', 'Have something wrong when create hs register pdf. Please try again!');
    }
}
