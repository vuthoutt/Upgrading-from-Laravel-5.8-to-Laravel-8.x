<?php
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\AppBaseController;
use App\Repositories\ApiBackupRepository;
use App\Http\Request\API\ApiListBackupRequest;
use App\Http\Request\API\ApiUploadBackupRequest;
use Illuminate\Http\Request;
use App\Models\ApiBackup;

/**
 * User: Anh Ngoc
 * Date: 19/11/2019
 * Time: 3:55 PM
 */
class BackupController extends  AppBaseController {

    private $apiBackupRepository;

    public function __construct(ApiBackupRepository $apiBackupRepository)
    {
        $this->apiBackupRepository = $apiBackupRepository;
    }

    public function listBackup(ApiListBackupRequest $request) {
        $data = $request->validated();
        $listBackup = $this->apiBackupRepository->listBackup($data);
        return $this->sendResponse($listBackup, 'successfully');
    }

    public function doBackup(ApiUploadBackupRequest $request) {
        $data = $request->validated();
        $backupData = $this->apiBackupRepository->backupData($data);

        if (isset($backupData) and !is_null($backupData)) {
            if ($backupData['status_code'] == 200) {
                return response()->json(['success'=> true, 'message' => $backupData['msg'] ]);
            } else {
                return response()->json(['success'=> false, 'message' => $backupData['msg'] ]);
            }
        }

    }

    public function restore(Request $request) {
        $validator = \Validator::make($request->all(), [
            'ID' => 'required|exists:tbl_upload_backup,id',
        ]);

        if ($validator->fails())
        {
            return response()->json(['success'=> false, 'message'=> $validator->errors()]);
        }
        $id = $request->ID;
        $storage = ApiBackup::find($id);

        if($storage && file_exists($storage->path)){
            return response()->download($storage->path, $storage->file_name);
        }

        return $this->sendError('Not found zip file', 404);
    }

}
