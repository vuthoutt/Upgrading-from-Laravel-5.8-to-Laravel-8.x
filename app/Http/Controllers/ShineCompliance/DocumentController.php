<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use App\Services\ShineCompliance\PropertyService;
use App\Services\ShineCompliance\ZoneService;
use App\Services\ShineCompliance\ClientService;
use App\Services\ShineCompliance\DepartmentService;
use App\Services\ShineCompliance\ContractorSetupService;
use App\Services\ShineCompliance\DocumentService;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Property;
use App\Models\Zone;
use App\Http\Request\ShineCompliance\Zone\ZoneRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Request\ShineCompliance\Client\UpdateOrganisationRequest;

class DocumentController extends Controller
{
    /**
     * @var DocumentService
     */
    private $documentService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        ZoneService $zoneService,
        PropertyService $propertyService,
        ClientService $clientService,
        DepartmentService $departmentService,
        ContractorSetupService $contractorSetupService,
        DocumentService $documentService
    )
    {
        $this->zoneService = $zoneService;
        $this->propertyService = $propertyService;
        $this->clientService = $clientService;
        $this->departmentService = $departmentService;
        $this->contractorSetupService = $contractorSetupService;
        $this->documentService = $documentService;
    }

    /**
     * Show my organisation by id.
     *
     */
    public function insertTrainingRecord(Request $request) {
        $validator = \Validator::make($request->all(), [
            'doc_type' => 'required',
            'client_id' => 'required',
            'id' => 'sometimes',
            'name' => 'required|max:255',
            'traning_date' => 'nullable|date_format:d/m/Y',
            'document' => 'required_without:id|file|max:100000',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        if ($request->has('id')) {
            $trainingRecord = $this->documentService->updateOrCreateTrainingRecord($request->all(), $request->doc_type, $request->id);
        } else {
            $trainingRecord = $this->documentService->updateOrCreateTrainingRecord($request->all(), $request->doc_type);
        }

        if (isset($trainingRecord) and !is_null($trainingRecord)) {
            \Session::flash('msg', $trainingRecord['msg']);
            return response()->json(['status_code' => $trainingRecord['status_code'], 'success'=> $trainingRecord['msg'], 'id' => $trainingRecord['data']]);
        }
    }

    public function insertDocument(Request $request) {

        $validator = \Validator::make($request->all(), [
            'id' => 'sometimes',
            'project_id' => 'required',
            'doc_cat' => 'nullable',
            'contractor_key' => 'nullable',
            'client_id' => 'nullable',
            'added_by' => 'nullable',
            'name' => 'required',
            'type' => 'nullable',
            'doc_value' => 'nullable',
            'document_file' => 'required_without:id|file|max:100000',
            'deadline' => 'sometimes|date_format:d/m/Y',
            'contractors' => 'nullable',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        if ($request->has('id')) {
            $document = $this->documentService->updateOrCreateDocument($request->all(), $request->id);
        } else {
            $document = $this->documentService->updateOrCreateDocument($request->all());
        }

        if (isset($document) and !is_null($document)) {
            \Session::flash('msg', $document['msg']);
            return response()->json(['status_code' => $document['status_code'], 'success'=> $document['msg']]);
        }

    }

    public function rejectDocument(Request $request){
        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            'deadline' => 'nullable|date_format:d/m/Y',
            'note' => 'nullable|string|max:255',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        $rejectDocument = $this->documentService->rejectDocument($request->all());

        if (isset($rejectDocument) and !is_null($rejectDocument)) {
            \Session::flash('msg', $rejectDocument['msg']);
            return response()->json(['status_code' => $rejectDocument['status_code'], 'success'=> $rejectDocument['msg']]);
        }
    }

    public function insertSampleCertificate(Request $request) {
        $validator = \Validator::make($request->all(), [
            'id' => 'sometimes',
            'assess_id' => 'required',
            'name' => 'required|max:255',
            'plan_date' => 'nullable|date_format:d/m/Y',
            'document' => 'required_without:id|file|max:100000',
            'description' => 'nullable|max:255'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        if ($request->has('id')) {
            $sampleCertificate = $this->documentService->updateOrCreateSampleCertificate($request->all(), $request->id);
        } else {
            $sampleCertificate = $this->documentService->updateOrCreateSampleCertificate($request->all());
        }

        if (isset($sampleCertificate) and !is_null($sampleCertificate)) {
            \Session::flash('msg', $sampleCertificate['msg']);
            return response()->json(['status_code' => $sampleCertificate['status_code'], 'success'=> $sampleCertificate['msg']]);
        }
    }

    public function insertAirTestCertificate(Request $request) {
        $validator = \Validator::make($request->all(), [
            'id' => 'sometimes',
            'assess_id' => 'required',
            'name' => 'required|max:255',
            'plan_date' => 'nullable|date_format:d/m/Y',
            'document' => 'required_without:id|file|max:100000',
            'description' => 'nullable|max:255'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        if ($request->has('id')) {
            $airTestCertificate = $this->documentService->updateOrCreateAirTestCertificate($request->all(), $request->id);
        } else {
            $airTestCertificate = $this->documentService->updateOrCreateAirTestCertificate($request->all());
        }

        if (isset($airTestCertificate) and !is_null($airTestCertificate)) {
            \Session::flash('msg', $airTestCertificate['msg']);
            return response()->json(['status_code' => $airTestCertificate['status_code'], 'success'=> $airTestCertificate['msg'], 'id' => $airTestCertificate['data']]);
        }
    }
}
