<?php

namespace app\Http\Controllers\Migration;

use App\Models\Zone;
use Illuminate\Routing\Controller;
use App\Http\Controllers\LoginController as BaseController;
use App\Models\AssetClass;
use Illuminate\Support\Facades\DB;

class MigrationAssetClassController extends Controller
{
    /**
     * Migration tbl_area
     * @return string
     */
    public function migrate_area(){
        $con_gsk_old = "mysql_gsk_old";
        $con_gsk_new = "mysql";
        $sql = "SELECT * FROM tblarea;";
        $data_area = [];
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));
        if(count($results) > 0){
            foreach ($results as $area){
                $data_area[] = [
                    'id' => $area->ID,
                    'reference' => $area->shineReference,
                    'record_id' => $area->recordID,
                    'version' => $area->version,
                    'property_id' => $area->assetID,
                    'survey_id' => $area->surveyID,
                    'is_locked' => $area->locked == -1 ? AREA_LOCKED : AREA_UNLOCKED,
                    'description' => $area->description,
                    'area_reference' => $area->reference,
                    'decommissioned' => $area->decommissioned == -1 ? AREA_DECOMMISSION : AREA_UNDECOMMISSION,
                    'updated_at'=> $area->lastUpdatedDate > 0 ? date("Y-m-d H:i:s", $area->lastUpdatedDate) : NULL
                ];
            }
//            dd($data_user);
            $insert_data_area = collect($data_area);
            $chunks = $insert_data_area->chunk(500);
            foreach ($chunks as $chunk)
            {
                Area::insert($chunk->toArray());
            }
            DB::beginTransaction();
            try{
                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());

            }
        }
        return 'Done';
    }
}
