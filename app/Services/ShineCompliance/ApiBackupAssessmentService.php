<?php

namespace App\Services\ShineCompliance;

use App\Repositories\ShineCompliance\ApiAssessmentBackupImageRepository;
use App\Repositories\ShineCompliance\ApiAssessmentBackupManifestRepository;
use App\Repositories\ShineCompliance\ApiAssessmentBackupRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiBackupAssessmentService
{
    protected const STATUS_UPLOADED = 1;
    protected const STATUS_PROCESSED = 2;
    protected const STATUS_ERROR = 9;

    private $apiBackupRepository;
    private $apiBackupImageRepository;
    private $apiBackupManifestRepository;


    public function __construct(ApiAssessmentBackupRepository $apiBackupRepository,
                                ApiAssessmentBackupImageRepository $apiBackupImageRepository,
                                ApiAssessmentBackupManifestRepository $apiBackupManifestRepository)
    {
        $this->apiBackupRepository = $apiBackupRepository;
        $this->apiBackupImageRepository = $apiBackupImageRepository;
        $this->apiBackupManifestRepository = $apiBackupManifestRepository;
    }

    public function backupList($assess_id)
    {
        try {
            $backupList = $this->apiBackupManifestRepository->getBackupsByAssessId($assess_id);

            return \CommonHelpers::successResponse('Get list backup successfully', $backupList);
        } catch (\Exception $exception) {
            Log::error($exception);
            return \CommonHelpers::failResponse(STATUS_FAIL, $exception->getMessage());
        }

    }

    public function backupData($data)
    {
        $file = $data['file'];
        if (!is_null($file) and $file->isValid()) {
            // save file
            try {
                DB::beginTransaction();
                $backupManifest = $this->apiBackupManifestRepository->where('id', $data['backup_id']);
                if ($backupManifest) {
                    $date = Carbon::now();
                    $path = 'data/api/backup_v2/data/'. $date->format('Y/m/d').'/'.$data['backup_id'].'/'. \Auth::user()->id . '/';
                    \Storage::disk('local')->put($path, $file);

                    $dataCreate = [
                        'backup_id' => $data['backup_id'],
                        'user_id' => \Auth::user()->id,
                        'path' => $path. $file->hashName(),
                        'file_name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                    ];

                    $dataUpload = $this->apiBackupRepository->create($dataCreate);
                    $this->apiBackupManifestRepository->update(['status' => self::STATUS_PROCESSED], $data['backup_id']);
                    DB::commit();

                    return \CommonHelpers::successResponse('Upload backup data successfully', $dataUpload->id);
                } else {
                    DB::rollBack();
                    $this->apiBackupManifestRepository->update(['status' => self::STATUS_ERROR], $data['backup_id']);
                    return \CommonHelpers::failResponse(STATUS_FAIL,'Not exist backup manifest.');
                }
            } catch (\Exception $exception) {
                DB::rollBack();

                $this->apiBackupManifestRepository->update(['status' => self::STATUS_ERROR], $data['backup_id']);
                Log::error($exception);
                return \CommonHelpers::failResponse(STATUS_FAIL, $exception->getMessage());
            }
        } else {
            $this->apiBackupManifestRepository->update(['status' => self::STATUS_ERROR], $data['backup_id']);
            return \CommonHelpers::failResponse(STATUS_FAIL,'File not exist or invalid !');
        }
    }

    public function backupImage($data)
    {
        $file = $data['file'];
        if (!is_null($file) and $file->isValid()) {
            // save file
            try {
                $backupManifest = $this->apiBackupManifestRepository->where('id', $data['backup_id']);
                if ($backupManifest) {
                    $date = Carbon::now();
                    $path = 'data/api/backup_v2/image/'. $date->format('Y/m/d').'/'.$data['backup_id'].'/'.$data['app_id']. '/';
                    \Storage::disk('local')->put($path, $file);

                    $dataCreate = [
                        'backup_id' => $data['backup_id'],
                        'app_id' => $data['app_id'],
                        'type' => $data['image_type'],
                        'path' => $path. $file->hashName(),
                        'file_name' => $file->getClientOriginalName(),
                        'size' => $file->getSize()
                    ];

                    $dataUpload = $this->apiBackupImageRepository->create($dataCreate);
                    return \CommonHelpers::successResponse('Upload backup image successfully', $dataUpload->id);
                } else {
                    $this->apiBackupManifestRepository->update(['status' => self::STATUS_ERROR], $data['backup_id']);
                    return \CommonHelpers::failResponse(STATUS_FAIL,'Not exist backup manifest.');
                }
            } catch (\Exception $exception) {
                $this->apiBackupManifestRepository->update(['status' => self::STATUS_ERROR], $data['backup_id']);
                Log::error($exception);
                return \CommonHelpers::failResponse(STATUS_FAIL, $exception->getMessage());
            }
        } else {
            $this->apiBackupManifestRepository->update(['status' => self::STATUS_ERROR], $data['backup_id']);
            return \CommonHelpers::failResponse(STATUS_FAIL,'File not exist or invalid !');
        }
    }

    public function createBackupManifest($assess_id)
    {
        try {
            // Validate
//            if ($this->manifestRepository->where('assess_id', $assess_id)->where('status', self::STATUS_UPLOADED)->count() > 0) {
//                return \CommonHelpers::failResponse(STATUS_FAIL, 'Upload manifest failed!');
//            }
            $uploadManifest = $this->apiBackupManifestRepository->create([
                'assess_id' => $assess_id,
                'assessor_id' => \Auth::user()->id,
                'status'=> self::STATUS_UPLOADED, // 1: Uploaded, 2: Processed, 9: Error
            ]);

            return \CommonHelpers::successResponse('Upload Manifest Api successfully', ['backup_id' => $uploadManifest->id]);
        } catch (\Exception $exception) {
            Log::error($exception);
            return \CommonHelpers::failResponse(STATUS_FAIL, $exception->getMessage());
        }
    }

    public function restoreData($backup_id)
    {
        $backupManifest = $this->apiBackupManifestRepository->find($backup_id);

        // check complete uploaded file
        if ($backupManifest->status == self::STATUS_PROCESSED) {
            $data = $backupManifest->backupData;

            if ($data && \Storage::exists($data->path)) {
                return \CommonHelpers::successResponse('Restore data successfully', $data);
            }
            return \CommonHelpers::failResponse(STATUS_FAIL, 'Not found backup file');
        }

        return \CommonHelpers::failResponse(401, 'This backup file is not completed. Please check other file');
    }

    public function restoreImage($backup_id)
    {
        $backupManifest = $this->apiBackupManifestRepository->find($backup_id);

        if ($backupManifest->status == self::STATUS_PROCESSED) {
            $backupImages = $backupManifest->uploadImages;
            $data = [];

            foreach($backupImages as $image) {
                $data[] =
                    [
                        'app_id' => $image->app_id,
                        'image_type' => $image->type,
                        'path' => asset($image->path)
                    ];
            }

            return \CommonHelpers::successResponse('Restore images successfully', $data);
        }

        return \CommonHelpers::failResponse(STATUS_FAIL, 'Not found backup file');
    }

}
