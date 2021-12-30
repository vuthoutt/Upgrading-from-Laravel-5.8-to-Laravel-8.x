<?php
namespace App\Http\Controllers\API\V2;

use App\Http\Controllers\API\AppBaseController;
use App\Repositories\ApiBackupRepository;
use App\Http\Request\API\ApiUploadBackupDataRequestV2;
use App\Http\Request\API\ApiUploadBackupImageRequestV2;
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

    public function backupData(ApiUploadBackupDataRequestV2 $request) {
        $data = $request->validated();

        $backupData = $this->apiBackupRepository->backupDataV2($data);

        if (isset($backupData) and !is_null($backupData)) {
            if ($backupData['status_code'] == 200) {
                return response()->json(['success'=> true, 'backupID' => $backupData['data'],'message' => $backupData['msg'] ]);
            } else {
                return response()->json(['success'=> false, 'message' => $backupData['msg'] ]);
            }
        }

    }

    public function backupImage(ApiUploadBackupImageRequestV2 $request) {
        $data = $request->validated();
        $backupData = $this->apiBackupRepository->backupImageV2($data);

        if (isset($backupData) and !is_null($backupData)) {
            if ($backupData['status_code'] == 200) {
                return response()->json(['success'=> true, 'message' => $backupData['msg'] ]);
            } else {
                return response()->json(['success'=> false, 'message' => $backupData['msg'] ]);
            }
        }
        return $this->sendError('Not found zip file', 404);
    }


    public function checkComplete(Request $request) {
        $validator = \Validator::make($request->all(), [
            'ID' => 'required|exists:tbl_upload_backup,id',
        ]);

        if ($validator->fails())
        {
            return response()->json(['success'=> false, 'message'=> $validator->errors()]);
        }

        $backup = ApiBackup::with('uploadImages')->find($request->ID);
        if (!is_null($backup)) {
            $images = count($backup->uploadImages);
            if ($backup->image_count == $images) {
                ApiBackup::where('id', $request->ID)->update(['upload_success' => 1]);
                return response()->json(['success'=> true, 'message' => 'Uploaded backup data successfully!' ]);
            }
            return $this->sendError("The uploaded images is not match with backup {$backup->image_count} - {$images}", 401);
        }
        return $this->sendError('Not found zip file', 404);
    }


    public function restoreData(Request $request) {
        $validator = \Validator::make($request->all(), [
            'ID' => 'required|exists:tbl_upload_backup,id',
        ]);

        if ($validator->fails())
        {
            return response()->json(['success'=> false, 'message'=> $validator->errors()]);
        }
        $id = $request->ID;
        $storage = ApiBackup::find($id);

        // check complete uploaded file
        if ($storage->upload_success == 1) {

            if($storage && file_exists($storage->path)){
                return response()->download($storage->path, $storage->file_name);
            }
            return $this->sendError('Not found backup file', 404);
        }

        return $this->sendError('This backup file is not completed. Please check other file', 401);

    }

    public function restoreImage(Request $request) {
        $validator = \Validator::make($request->all(), [
            'ID' => 'required|exists:tbl_upload_backup,id',
        ]);

        if ($validator->fails())
        {
            return response()->json(['success'=> false, 'message'=> $validator->errors()]);
        }

        $backup = ApiBackup::with('uploadImages')->find($request->ID);
        if (!is_null($backup)) {
            $images = $backup->uploadImages;

            $data = [];
            if (count($images)){
                foreach($images as $image) {
                    $data[] =
                    [
                        'app_id' => $image->app_id,
                        'type' => $image->type,
                        'url' => asset($image->path)
                    ];
                }
            }

            return response()->json(['success'=> true, 'data'=> $data]);
        }

        return $this->sendError('Not found backup file', 404);


    }

}
