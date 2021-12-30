<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ProjectRepository;
use App\Repositories\DocumentRepository;
use App\Http\Request\Document\DocumentCreateRequest;

class DocumentController extends Controller
{
    public function __construct( ProjectRepository $projectRepository, DocumentRepository $documentRepository)
    {
        $this->documentRepository = $documentRepository;
        $this->projectRepository = $projectRepository;
    }

    public function insertDocument(Request $request) {
        if ($request->has('id')) {
            $messages = array(
                'document_file.required_if' => 'The Document is required.'
            );
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
                'document_file' => 'required_if:document_present,0|file|max:150000',
                'deadline' => 'nullable',
                'contractors' => 'nullable',
            ], $messages);
        } else {
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
                'document_file' => 'file|max:150000',
                'deadline' => 'nullable',
                'contractors' => 'nullable',
            ]);
        }

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        if ($request->has('id')) {
            $document = $this->documentRepository->updateOrCreateDocument($request->all(), $request->id);
        } else {
            $document = $this->documentRepository->updateOrCreateDocument($request->all());
        }

        if (isset($document) and !is_null($document)) {
            \Session::flash('msg', $document['msg']);
            return response()->json(['status_code' => $document['status_code'], 'success'=> $document['msg'], 'id' => $document['data']]);
        }

    }

    public function insertSampleCertificate(Request $request) {
        $validator = \Validator::make($request->all(), [
            'id' => 'sometimes',
            'survey_id' => 'required',
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
            $sampleCertificate = $this->documentRepository->updateOrCreateSampleCertificate($request->all(), $request->id);
        } else {
            $sampleCertificate = $this->documentRepository->updateOrCreateSampleCertificate($request->all());
        }

        if (isset($sampleCertificate) and !is_null($sampleCertificate)) {
            \Session::flash('msg', $sampleCertificate['msg']);
            return response()->json(['status_code' => $sampleCertificate['status_code'], 'success'=> $sampleCertificate['msg'], 'id' => $sampleCertificate['data']]);
        }
    }

    public function insertAirTestCertificate(Request $request) {
        $validator = \Validator::make($request->all(), [
            'id' => 'sometimes',
            'survey_id' => 'required',
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
            $airTestCertificate = $this->documentRepository->updateOrCreateAirTestCertificate($request->all(), $request->id);
        } else {
            $airTestCertificate = $this->documentRepository->updateOrCreateAirTestCertificate($request->all());
        }

        if (isset($airTestCertificate) and !is_null($airTestCertificate)) {
            \Session::flash('msg', $airTestCertificate['msg']);
            return response()->json(['status_code' => $airTestCertificate['status_code'], 'success'=> $airTestCertificate['msg'], 'id' => $airTestCertificate['data']]);
        }
    }

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
            $trainingRecord = $this->documentRepository->updateOrCreateTrainingRecord($request->all(), $request->doc_type, $request->id);
        } else {
            $trainingRecord = $this->documentRepository->updateOrCreateTrainingRecord($request->all(), $request->doc_type);
        }

        if (isset($trainingRecord) and !is_null($trainingRecord)) {
            \Session::flash('msg', $trainingRecord['msg']);
            return response()->json(['status_code' => $trainingRecord['status_code'], 'success'=> $trainingRecord['msg'], 'id' => $trainingRecord['data']]);
        }
    }


    public function approvalDocument($document_id){
        $approvalDocument = $this->documentRepository->approvalDocument($document_id);

        if (isset($approvalDocument) and !is_null($approvalDocument)) {
            if ($approvalDocument['status_code'] == 200) {
                return redirect()->back()->with('msg', $approvalDocument['msg']);
            } else {
                return redirect()->back()->with('err', $approvalDocument['msg']);
            }
        }
    }

    public function cancelDocument($document_id){
        $approvalDocument = $this->documentRepository->cancelDocument($document_id);

        if (isset($approvalDocument) and !is_null($approvalDocument)) {
            if ($approvalDocument['status_code'] == 200) {
                return redirect()->back()->with('msg', $approvalDocument['msg']);
            } else {
                return redirect()->back()->with('err', $approvalDocument['msg']);
            }
        }
    }

    public function searchDocument(Request $request){
        $query_string = '';
        if ($request->has('query_string')) {
            $query_string = $request->query_string;
        }
        $data = $this->documentRepository->searchDocumentForm($query_string);
        return response()->json($data);
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

        $rejectDocument = $this->documentRepository->rejectDocument($request->all());

        if (isset($rejectDocument) and !is_null($rejectDocument)) {
            \Session::flash('msg', $rejectDocument['msg']);
            return response()->json(['status_code' => $rejectDocument['status_code'], 'success'=> $rejectDocument['msg']]);
        }
    }
}
