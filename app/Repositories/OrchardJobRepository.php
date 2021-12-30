<?php
namespace App\Repositories;
use App\Models\LogOrchardJob;
use App\Models\OrchardJob;
use Carbon\Carbon;
use Prettus\Repository\Eloquent\BaseRepository;

class OrchardJobRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return OrchardJob::class;
    }

    /**
     * create or update Orchard Job + Orchard Job Log
     * @return
     */
    public function updateOrCreateOrchardJob($data, $orchard_job_id, $is_sucess = 0) {
        // pass step 6 and status = 1
        try {
            $orchard_job = NULL;
            if (is_null($orchard_job_id)) {
                $data_insert = [
                    'work_id' => $data['work_id'] ?? 0,
                    'step' => $data['step'] ?? 0,
                    'job_number' => $data['job_number'] ?? 0,
                    'description1' => $data['description1'] ?? '',
                    'expense_code' => $data['expense_code'] ?? '',
                    'trade_code' => $data['trade_code'] ?? '',
                    'priority_code' => $data['priority_code'] ?? '',
                    'sor_type_code' => $data['sor_type_code'] ?? '',
                    'volume_cde' => $data['volume_cde'] ?? '',
                    'sor_num' => $data['sor_num'] ?? '',
                    'department_code' => $data['department_code'] ?? '',
                    'contract_number' => $data['contract_number'] ?? '',
                    'status' => $is_sucess,
                    'date' => time(),
                    'timestamp' => $data['timestamp'] ?? '',
                ];
                $orchard_job = OrchardJob::create($data_insert);

            } else {
                $orchard_job = OrchardJob::where('id',$orchard_job_id)->first();
                $data_update = [
//                    'work_id' => $data['work_id'] ?? $orchard_job->work_id,
                    'step' => $data['step'] ?? $orchard_job->step,
                    'job_number' => $data['job_number'] ?? $orchard_job->job_number,
                    'description1' => $data['description1'] ?? $orchard_job->description1,
                    'expense_code' => $data['expense_code'] ?? $orchard_job->expense_code,
                    'trade_code' => $data['trade_code'] ?? $orchard_job->trade_code,
                    'priority_code' => $data['priority_code'] ?? $orchard_job->priority_code,
                    'sor_type_code' => $data['sor_type_code'] ?? $orchard_job->sor_type_code,
                    'volume_cde' => $data['volume_cde'] ?? $orchard_job->volume_cde,
                    'sor_num' => $data['sor_num'] ?? $orchard_job->sor_num,
                    'department_code' => $data['department_code'] ?? $orchard_job->department_code,
                    'contract_number' => $data['contract_number'] ?? $orchard_job->contract_number,
                    'date' => time(),
                    'timestamp' => $data['timestamp'] ?? $orchard_job->timestamp,
                    'status' => $is_sucess
                ];
                $orchard_job->update($data_update);
            }
            //log data api
            if(isset($orchard_job->id)){
                $this->createOrchardJob($orchard_job->id, $data);
            }
            return ['id' => $orchard_job->id ?? 0, 'message' => ''];
        } catch (\Exception $e) {
            return ['id' => NULL, 'message' => $e->getMessage()];
        }
    }

    public function createOrchardJob($job_id, $data){
        if(isset($job_id)){
            $data_log = [
                'job_id' => $job_id,
                'status_code' => $data['status_code'] ?? 0,
                'sent_request' => $data['sent_request'] ?? '',
                'response_request' => $data['response_request'] ?? '',
                'step' => $data['step'] ?? 0,
                'created_date' => time(),
            ];
            LogOrchardJob::create($data_log);
        }
    }

}
