<?php

namespace app\Http\Controllers\Migration;

use App\Models\Document;
use App\Models\DocumentBluelightService;
use App\Models\Elearning;
use App\Models\ElearningApi;
use App\Models\ElearningLog;
use App\Models\Policy;
use App\Models\SampleCertificate;
use App\Models\SitePlanDocument;
use App\Models\Template;
use App\Models\TemplatesCategory;
use App\Models\TrainingRecord;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class MigrationElearningController extends Controller
{
    /**
     * Migration upload_manifest and download_manifest
     * @return string
     */
    public function migrate_data(){
        $con_gsk_old = "mysql_gsk_old";
        $con_gsk_new = "mysql";
        //no need convert blue light service
        $sql2 = "SELECT * FROM tbl_api_elearning;";
        $sql3 = "SELECT * FROM tbl_elearning;";
        $sql4 = "SELECT * FROM tbl_log_elearning;";
        $results2 = DB::connection($con_gsk_old)
            ->select(DB::raw($sql2));
        $results3 = DB::connection($con_gsk_old)
            ->select(DB::raw($sql3));
        $results4 = DB::connection($con_gsk_old)
            ->select(DB::raw($sql4));
        $data_2 = $data_3 = $data_4 = [];
        if(count($results2) > 0 && count($results3) > 0 && count($results4) > 0){
            foreach ($results2 as $d){
                $data_2[] = [
                    'id' => $d->id,
                    'data' => $d->data,
                    'created_date' => $d->created_date
                ];
            }

            foreach ($results3 as $d){
                $data_3[] = [
                    'id' => $d->id,
                    'user_id' => $d->user_id,
                    'token_id' => $d->token_id,
                    'login_key' => $d->login_key,
                    'created_date' => $d->created_date
                ];
            }
            //next
            foreach ($results4 as $d){
                $data_4[] = [
                    'id' => $d->id,
                    'user_id' => $d->user_id,
                    'message' => $d->message,
                    'exception_message' => $d->exception_message,
                    'status_code' => $d->status_code,
                    'ip_address' => $d->ip_address,
                    'type' => $d->type,
                    'created_date' => $d->created_date
                ];
            }
//            dd($data_user);
            DB::beginTransaction();
            try{

//                $insert_1 = collect($data_1);
//                $chunks = $insert_1->chunk(500);
//                foreach ($chunks as $chunk)
//                {
//                    DocumentBluelightService::insert($chunk->toArray());
//                }

                $insert_2 = collect($data_2);
                $chunks = $insert_2->chunk(500);
                foreach ($chunks as $chunk)
                {
                    ElearningApi::insert($chunk->toArray());
                }

                $insert_3 = collect($data_3);
                $chunks = $insert_3->chunk(500);
                foreach ($chunks as $chunk)
                {
                    Elearning::insert($chunk->toArray());
                }

                $insert_4 = collect($data_4);
                $chunks = $insert_4->chunk(500);
                foreach ($chunks as $chunk)
                {
                    ElearningLog::insert($chunk->toArray());
                }

                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());

            }
        }
        dd('Done');
    }
}
