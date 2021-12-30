<?php

namespace app\Http\Controllers\Migration;

use App\Models\Zone;
use Illuminate\Routing\Controller;
use App\Http\Controllers\LoginController as BaseController;
use Illuminate\Support\Facades\DB;
use App\Models\PublishedSurvey;

class MigrationPublishSurveyController extends Controller
{
    /**
     * Migration tblzone
     * @return string
     */
    public function migrate_publish_survey(){
        $con_gsk_old = "mysql_gsk_old";
        $sql = "SELECT * FROM published_surveys;";
        $data_publish_survey = [];
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));
//        dd($results);
        // data = created_date, addedBy = created_date
        if(count($results) > 0){
            foreach ($results as $ps){
                $data_publish_survey[] = [
                    'id' => $ps->psID,
//                    'client_id' => $ps->clientKey, // remove
//                    'contractor_id' => $ps->psContractor, // remove
                    'survey_id' => $ps->psReportID,
                    'name' => $ps->psName,
                    'revision' => $ps->psRevision,
                    'type' => $ps->psType,
                    'size' => $ps->psSize,
                    'filename' => $ps->psFilename,
                    'mime' => $ps->psMime,
                    'path' => $ps->psPath,
                    'is_large_file' => $ps->is_large_file,
                    'created_by' => $ps->psAddedBy,
                    'created_at' => $ps->psAdded > 0 ? date("Y-m-d H:i:s", $ps->psAdded) : NULL
                ];
            }
            DB::beginTransaction();
            try{
//                Location::insert($data_location);
//                LocationVoid::insert($data_void);
//                LocationConstruction::insert($data_construction);
                //insert location
                $insert_data_survey = collect($data_publish_survey);
                $chunks = $insert_data_survey->chunk(500);
                foreach ($chunks as $chunk)
                {
                    PublishedSurvey::insert($chunk->toArray());
                }
                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());

            }
        }
        DB::disconnect($con_gsk_old);
        dd('Done');
    }
}
