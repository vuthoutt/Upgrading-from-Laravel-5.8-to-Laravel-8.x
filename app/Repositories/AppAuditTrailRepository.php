<?php

namespace App\Repositories;

use App\Models\AppAuditTrail;
use Carbon\Carbon;
use Prettus\Repository\Eloquent\BaseRepository;

class AppAuditTrailRepository extends BaseRepository
{
    public function model()
    {
        return AppAuditTrail::class;
    }

    public function logAuditTrail($data, $dataContent)
    {
        try {
            \DB::beginTransaction();
            $loggedData = [];

            foreach ($data['audit_trails'] as $log) {
                $loggedData[] = [
                    'user_id' => $log['user_id'] ?? \Auth::user()->id,
                    'type' => $log['type'] == 'survey' ? 1 : ($log['type'] == 'audit' ? '2' : 0),
                    'action_type' => $log['actionType'],
                    'object_id' => $log['objectId'] ?? NULL,
                    'comment' => $log['comment'],
                    'timestamp' => $log['timestamp'],
                    'app_version' => $log['appVersion'],
                    'ip' => $log['ip'],
                    'device_id' => $log['deviceId'],
                    'device_soft_version' => $log['deviceSoftVersion'],
                    'created_at' => Carbon::now(),
                ];
            }
            $insert_log = collect($loggedData);
            $chunks = $insert_log->chunk(500);
            foreach ($chunks as $chunk)
            {
                $this->model->insert($chunk->toArray());
            }
            \DB::commit();
            return \CommonHelpers::successResponse('Upload App Audit Trail Successfully!', []);
        } catch (\Exception $exception) {
            \Log::error($exception);
            \DB::rollBack();
            return \CommonHelpers::failResponse(STATUS_FAIL, $exception->getMessage());
        }
    }

    public function getAppAuditTrail($limit)
    {
        return $this->model->with('actionType', 'user', 'survey')->orderBy('id','desc')->limit($limit)->get();
    }
}
