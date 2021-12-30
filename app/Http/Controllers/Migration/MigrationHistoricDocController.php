<?php

namespace app\Http\Controllers\Migration;

use App\Models\Zone;
use Illuminate\Routing\Controller;
use App\Http\Controllers\LoginController as BaseController;
use Illuminate\Support\Facades\DB;
use App\Models\HistoricDoc;
use App\Models\HistoricDocCategory;

class MigrationHistoricDocController extends Controller
{
    /**
     * Migration tblzone
     * @return string
     */
    public function migrate_historic(){
        $con_gsk_old = "mysql_gsk_old";
        $sql = "SELECT * FROM historicdocs;";
        $sql_cat = "SELECT * FROM historicdocs_categories;";
        $data_historic = [];
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));

        $results_cat = DB::connection($con_gsk_old)
                    ->select(DB::raw($sql_cat));

            $data_historic = [];
            $data_historic_cat = [];

            if(count($results) > 0){
                foreach ($results as $hs){
                    $data_historic[] = [
                        'id' => $hs->dcID,
                        'reference' => $hs->shineReference,
                        'name' => $hs->dcName,
                        'category' => $hs->dcCategory,
                        'size' => $hs->dcSize,
                        'file_name' => $hs->dcFilename,
                        'mime' => $hs->dcMime,
                        'property_id' => $hs->dcSiteID,
                        'is_external_ms' => $hs->isExternalMS == -1 ? 1 : 0,
                        'added' => $hs->dcAdded,
                        'created_by' => $hs->dcAddedBy,
                        'created_at' => $hs->dcAdded > 0 ? date("Y-m-d H:i:s", $hs->dcAdded) : NULL,
                        'historical_type' => $hs->historicalType,
                        'is_inaccess_room' => $hs->inaccessRoom == -1 ? 1 : 0,
                        'is_identified_acm' => $hs->identifiedACM  == -1 ? 1 : 0,
                        'is_presumed_acm' => $hs->presumedACM  == -1 ? 1 : 0,
                        'is_sp_acm' => $hs->spACM  == -1 ? 1 : 0,
                        'is_historical_action' => $hs->historicalAction  == -1 ? 1 : 0,
                        'is_hazards' => $hs->hazards  == -1 ? 1 : 0,
                    ];
                }
            }
            if(count($results_cat) > 0){
                foreach ($results_cat as $data){
                    $data_historic_cat[] = [
                        "id" => $data->ID,
                        "property_id" => $data->siteID,
                        "category" => $data->category,
                        "order" => $data->order,
                    ];
                }
            }

            DB::beginTransaction();
            try{
                //insert location
                $insert_data = collect($data_historic);
                $chunks = $insert_data->chunk(500);
                foreach ($chunks as $chunk)
                {
                    HistoricDoc::insert($chunk->toArray());
                }

                $insert_data_cat = collect($data_historic_cat);
                $chunks1 = $insert_data_cat->chunk(500);
                foreach ($chunks1 as $chunk1)
                {
                    HistoricDocCategory::insert($chunk1->toArray());
                }
                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());

            }


        DB::disconnect($con_gsk_old);
        dd('Done');
    }
}
