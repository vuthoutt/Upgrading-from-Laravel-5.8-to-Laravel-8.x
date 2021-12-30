<?php


namespace App\Http\Controllers\API\V2\Compliance\Assessment;


use App\Http\Controllers\API\AppBaseController;
use App\Http\Request\API\ShineCompliance\ApiUploadBackupDataRequest;
use App\Http\Request\API\ShineCompliance\ApiUploadBackupImageRequest;
use App\Services\ShineCompliance\ApiBackupAssessmentService;
use Illuminate\Http\Request;

class ApiBackupAssessmentController extends AppBaseController
{
    private $apiBackupAssessmentService;

    public function __construct( ApiBackupAssessmentService $apiBackupAssessmentService)
    {
        $this->apiBackupAssessmentService = $apiBackupAssessmentService;
    }

    public function backupManifest(Request $request)
    {
        $validatedData = $request->validate([
            'assess_id' => 'required|integer',
        ]);

        $result = $this->apiBackupAssessmentService->createBackupManifest($validatedData['assess_id']);

        if ($result['status_code'] == 200) {

            return $this->sendResponse($result['data'], $result['msg']);
        } else {
            return $this->sendError($result['msg'], $result['status_code']);
        }
    }
    public function backupData(ApiUploadBackupDataRequest $apiUploadBackupDataRequest)
    {
        $data = $apiUploadBackupDataRequest->validated();

        $backupData = $this->apiBackupAssessmentService->backupData($data);

        if ($backupData['status_code'] == 200) {
            return $this->sendResponse($backupData['data'], $backupData['msg']);
        } else {
            return $this->sendError($backupData['msg'], $backupData['status_code']);
        }
    }

    public function backupImage(ApiUploadBackupImageRequest $request)
    {
        $data = $request->validated();
        $backupData = $this->apiBackupAssessmentService->backupImage($data);

        if ($backupData['status_code'] == 200) {
            return $this->sendResponse([], $backupData['msg']);
        } else {
            return $this->sendError($backupData['msg']);
        }
    }

    public function restoreData($backup_id, Request $request)
    {
        $result = $this->apiBackupAssessmentService->restoreData($backup_id);

        if ($result['status_code'] == 200) {
            return response()->download(\Storage::path($result['data']->path), $result['data']->file_name);
        } else {
            return $this->sendError($result['msg'], $result['status_code']);
        }
    }

    public function restoreImage($backup_id, Request $request)
    {
        $images = $this->apiBackupAssessmentService->restoreImage($backup_id);

        if ($images['status_code'] == 200) {
            return $this->sendResponse($images['data'], $images['msg']);
        } else {
            return $this->sendError($images['msg'], $images['status_code']);
        }
    }

    public function listBackup($assess_id)
    {
        $result = $this->apiBackupAssessmentService->backupList($assess_id);

        if ($result['status_code'] == 200) {
            return $this->sendResponse($result['data'], $result['msg']);
        } else {
            return $this->sendError($result['msg'], $result['status_code']);
        }
    }
}
