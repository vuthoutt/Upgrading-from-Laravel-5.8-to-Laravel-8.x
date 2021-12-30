<?php

namespace app\Http\Controllers\Migration;

use App\Models\Zone;
use Illuminate\Routing\Controller;
use App\Http\Controllers\LoginController as BaseController;
use Illuminate\Support\Facades\DB;
use App\Models\SummaryPdf;

class MigrationSummaryPDFController extends Controller
{
    /**
     * Migration tblzone
     * @return string
     */
    public function migrate_summary_pdf(){
        $con_gsk_old = "mysql_gsk_old";
        $sql = "SELECT * FROM tblsummarypdf;";
        $data_summary_pdf = [];
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));

        if(count($results) > 0){
            foreach ($results as $ps){
                $type = 0;
                switch ($ps->dcType){
                    case 'areaCheck':
                        $type = TYPE_AREA_CHECK;
                        break;
                    case 'asbestosRegister':
                        $type = TYPE_ASBESTOS_REGISTER;
                        break;
                    case 'directorOverview':
                        $type = TYPE_DIRECTOR_OVERVIEW;
                        break;
                    case 'managerOverview':
                        $type = TYPE_MANAGER_OVERVIEW;
                        break;
                    case 'material':
                        $type = TYPE_MATERIAL;
                        break;
                    case 'overall':
                        $type = TYPE_OVERALL;
                        break;
                    case 'priority':
                        $type = TYPE_PRIORITY;
                        break;
                    case 'roomCheck':
                        $type = TYPE_ROOMCHECK;
                        break;
                    case 'sitecheck':
                        $type = TYPE_SITECHECK;
                        break;
                    case 'survey':
                        $type = TYPE_SURVEY;
                        break;
                    case 'user':
                        $type = TYPE_USER;
                        break;

                }
                $data_summary_pdf[] = [
                    'id' => $ps->keyID,
                    'reference' => $ps->ssReference,
                    'type' => $type,
                    'object_id' => $ps->dcObjectID,
                    'file_name' => $ps->dcFileName,
                    'path' => $ps->dcPath,
                ];
            }
            DB::beginTransaction();
            try{
                //insert location
                $insert_data_survey = collect($data_summary_pdf);
                $chunks = $insert_data_survey->chunk(500);
                foreach ($chunks as $chunk)
                {
                    SummaryPdf::insert($chunk->toArray());
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
