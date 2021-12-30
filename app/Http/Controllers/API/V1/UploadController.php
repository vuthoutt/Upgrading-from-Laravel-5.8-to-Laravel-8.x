<?php
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\AppBaseController;
use App\Repositories\ApiTokenRepository;
use App\Repositories\UploadManifestRepository;
use App\Repositories\SurveyRepository;
use App\Models\UploadDocumentStorage;
use App\Models\ShineAppDocumentStorage;
use App\Models\UploadManifest;
use Illuminate\Http\Request;
use App\Http\Request\API\UploadManifestRequest;
use App\Http\Request\API\UploadDataRequest;
use App\Http\Request\API\UploadImageRequest;
use App\Http\Request\API\CheckCompleteRequest;
use App\Jobs\ProcessMobileDataEmail;

/**
 * Created by PhpStorm.
 * User: Hoang Tran
 * Date: 8/8/2019
 * Time: 3:55 PM
 */
class UploadController extends  AppBaseController {
    private $apiTokenRepository;
    private $uploadManifestRepository;
    private $surveyRepository;
    private $request;

    public function __construct(ApiTokenRepository $apiTokenRepository,
                                SurveyRepository $surveyRepository,
                                UploadManifestRepository $uploadManifestRepository, Request $request)
    {
        $this->apiTokenRepository = $apiTokenRepository;
        $this->uploadManifestRepository = $uploadManifestRepository;
        $this->surveyRepository = $surveyRepository;
        $this->request = $request;
    }

    public function uploadManifest(UploadManifestRequest $uploadManifestRequest) {
        $survey_id = $this->request->surveyDetailId;

        $createManifest = $this->uploadManifestRepository->createManifest($survey_id);

        if (isset($createManifest) and !is_null($createManifest)) {
            if ($createManifest['status_code'] == 200) {
                $data['manifestID'] = $createManifest['data'];
                return $this->sendResponse($data, $createManifest['msg']);
            } else {
                return $this->sendResponse($createManifest['msg'], $createManifest['status_code']);
            }
        }
    }

    public function uploadData(UploadDataRequest $uploadDataRequest) {
        $uploadData = $this->uploadManifestRepository->createUploadData($this->request->type, $this->request->manifestID, $this->request->objectData );

        if (isset($uploadData) and !is_null($uploadData)) {
            if ($uploadData['status_code'] == 200) {
                $data['id'] = $uploadData['data'];
                return $this->sendResponse($data, $uploadData['msg']);
            } else {
                return $this->sendResponse($uploadData['msg'], $uploadData['status_code']);
            }
        }
    }

    public function uploadImage(UploadImageRequest $uploadImageRequest) {
        $survey_id = $this->request->surveyID;
        $manifest_id = $this->request->manifestID;
        $type = $this->request->type;
        $record_id = $this->request->objectID; // upload data id
        $file = $this->request->objectData;

        $uploadImage = $this->uploadManifestRepository->createUploadImage($type, $record_id, $manifest_id, $survey_id, $file);

        if (isset($uploadImage) and !is_null($uploadImage)) {
            if ($uploadImage['status_code'] == 200) {

                return $this->sendResponse(null, $uploadImage['msg']);
            } else {
                return $this->sendResponse($uploadImage['msg'], $uploadImage['status_code']);
            }
        }
    }

    public function checkComplete(CheckCompleteRequest $request)
    {
        $manifest_id = $request->manifestID;


        $upload_manifest = UploadManifest::where('id', $manifest_id)->first();

        if ($upload_manifest) {
            $total_photo_manifest   = $upload_manifest->total_image ?? 0;
            $total_data_manifest    = 1 + intval($upload_manifest->total_floor) + intval($upload_manifest->total_room) + intval($upload_manifest->total_record) + intval($upload_manifest->total_plan);
            $total_data_upload      = UploadDocumentStorage::where('manifest_id', $manifest_id)->count();
            $total_photo_upload     = ShineAppDocumentStorage::where('manifest_id', $manifest_id)->count();

            if ($total_photo_manifest == $total_photo_upload && $total_data_manifest == $total_data_upload) {
                // \Queue::pushOn(API_PROCESS_DATA,new ProcessMobileDataEmail($manifest_id));
                $uploadManifest =   $this->uploadManifestRepository->insertUploadData($manifest_id);
                if ($uploadManifest['status_code'] == 200) {
                    return $this->sendResponse(null, $uploadManifest['msg']);
                } else {
                    return $this->sendError($uploadManifest['msg'], $uploadManifest['status_code']);
                }

            } else {
                return $this->sendError("The number data is not correct, total_photo_manifest: $total_photo_manifest, total_photo_upload: $total_photo_upload,total_data_manifest: $total_data_manifest, total_data_upload: $total_data_upload", 404);
            }
        }
        return $this->sendResponse('manifest not found !', STATUS_FAIL);
    }

    private function insertUploadData($manifest_id){

    }
}
