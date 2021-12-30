<?php
namespace App\Repositories;
use App\Models\ApiBackup;
use App\Models\ApiBackupImage;
use Prettus\Repository\Eloquent\BaseRepository;
use Carbon\Carbon;

class ApiBackupRepository extends BaseRepository {

    function model()
    {
        return ApiBackup::class;
    }

    public function backupData($data) {
        $file = $data['zip'];
        if (!is_null($file) and $file->isValid()) {
            // save file
            try {
                $date = Carbon::now();
                $path = 'data/api/backup/'. $date->format('Y/m/d').'/'.$data['surveyID'].'/'.$data['userID']. '/';
                \Storage::disk('local')->put($path, $file);

                $dataCreate = [
                    'user_id' => $data['userID'],
                    'survey_id' => $data['surveyID'],
                    'path' => $path. $file->hashName(),
                    'file_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'upload_success' => 1
                ];

                ApiBackup::create($dataCreate);
                return \CommonHelpers::successResponse('Upload backup zip file successfully', $file->hashName());
            } catch (\Exception $e) {
                return \CommonHelpers::failResponse(STATUS_FAIL,$e->getMessage());
            }
        } else {
            return \CommonHelpers::failResponse(STATUS_FAIL,'File not exist or invalid !');
        }
    }

    public function backupDataV2($data) {
        $file = $data['file'];
        if (!is_null($file) and $file->isValid()) {
            // save file
            try {
                $date = Carbon::now();
                $path = 'data/api/backup_v2/data/'. $date->format('Y/m/d').'/'.$data['surveyID'].'/'.$data['userID']. '/';
                \Storage::disk('local')->put($path, $file);

                $dataCreate = [
                    'user_id' => $data['userID'],
                    'survey_id' => $data['surveyID'],
                    'image_count' => $data['imageCount'],
                    'path' => $path. $file->hashName(),
                    'file_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'version' => 2
                ];

                $dataUpload = ApiBackup::create($dataCreate);
                return \CommonHelpers::successResponse('Upload backup data successfully', $dataUpload->id);
            } catch (\Exception $e) {
                return \CommonHelpers::failResponse(STATUS_FAIL,$e->getMessage());
            }
        } else {
            return \CommonHelpers::failResponse(STATUS_FAIL,'File not exist or invalid !');
        }
    }

    public function backupImageV2($data) {
        $file = $data['file'];
        if (!is_null($file) and $file->isValid()) {
            // save file
            try {
                $date = Carbon::now();
                $path = 'data/api/backup_v2/image/'. $date->format('Y/m/d').'/'.$data['backupID'].'/'.$data['appID']. '/';
                \Storage::disk('local')->put($path, $file);

                $dataCreate = [
                    'backup_id' => $data['backupID'],
                    'app_id' => $data['appID'],
                    'type' => $data['type'],
                    'path' => $path. $file->hashName(),
                    'file_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize()
                ];

                $dataUpload = ApiBackupImage::create($dataCreate);
                return \CommonHelpers::successResponse('Upload backup image successfully', $dataUpload->id);
            } catch (\Exception $e) {
                return \CommonHelpers::failResponse(STATUS_FAIL,$e->getMessage());
            }
        } else {
            return \CommonHelpers::failResponse(STATUS_FAIL,'File not exist or invalid !');
        }
    }

    public function listBackup($data) {
        $listBackups = ApiBackup::where('user_id', $data['userID'])
                            ->where('survey_id', $data['surveyID'])
                            ->where(['upload_success'=> 1])
                            ->orderBy('created_at', 'desc')->get();
        $data = [];
        if (!is_null($listBackups)) {
            foreach ($listBackups as $listBackup) {
                $data[] = [
                    "ID" => $listBackup->id,
                    "version" => $listBackup->version,
                    "date" => $listBackup->created_at->format('d/m/Y H:i:s')
                ];
            }
        }
        return $data;
    }
}
