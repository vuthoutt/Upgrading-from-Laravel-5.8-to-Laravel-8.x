<?php

namespace app\Http\Controllers\Migration;

use App\Models\Zone;
use Illuminate\Routing\Controller;
use App\Http\Controllers\LoginController as BaseController;
use App\Models\Location;
use App\Models\LocationVoid;
use App\Models\LocationConstruction;
use App\Models\LocationInfo;
use Illuminate\Support\Facades\DB;

class MigrationLocationController extends Controller
{
    /**
     * Migration tblzone
     * @return string
     */
    public function migrate_location(){
        $con_gsk_old = "mysql_gsk_old";
        $con_gsk_new = "mysql";
        $sql = "SELECT * FROM tbllocation;";
        $data_location = $data_void = $data_construction = $data_location_info = [];
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));
//        dd($results);
        if(count($results) > 0){
            foreach ($results as $location){
//                    $data = "INSERT INTO tbl_users us WHERE ";

                $data_location[] = [
                    'id' => $location->ID,
                    'record_id' => $location->recordID,
                    'reference' => $location->shineReference,
                    'description' => $location->description,
                    'location_reference' => $location->reference,
                    'version' => $location->version,
                    'area_id' => $location->areaID,
                    'property_id' => $location->siteID,
                    'survey_id' => $location->surveyID,
                    'is_locked' => $location->locked == -1 ? LOCATION_LOCKED : LOCATION_UNLOCKED,
                    'state' => $location->state == 'accessible' ? LOCATION_STATE_ACCESSIBLE : LOCATION_STATE_INACCESSIBLE,
                    'decommissioned' => $location->decommissioned == -1 ? LOCATION_DECOMMISSION : LOCATION_UNDECOMMISSION,
                    'updated_at'=> $location->lastUpdatedDate > 0 ? date("Y-m-d H:i:s", $location->lastUpdatedDate) : NULL
                ];

                $data_location_info[] = [
                    'location_id' => $location->ID,
                    'reason_inaccess_key' => $location->reasonNAKey,
                    'reason_inaccess_other' => $location->reasonNAOther,
                    'comments'  => $location->comments,
                    'photo_reference' => $location->photoReference,
                    'updated_at'=> $location->lastUpdatedDate > 0 ? date("Y-m-d H:i:s", $location->lastUpdatedDate) : NULL
                ];

                $data_void[] = [
                    'location_id' => $location->ID,
                    'ceiling_other' => $location->constructionCeilingVoidOther,
                    'ceiling' => $location->constructionCeilingVoid,
                    'cavities_other' => $location->constructionCavitiesOther,
                    'cavities' => $location->constructionCavities,
                    'risers_other' => $location->constructionRisersOther,
                    'risers' => $location->constructionRisers,
                    'ducting_other' => $location->constructionDuctingOther,
                    'ducting' => $location->constructionDucting,
                    'boxing_other' => $location->constructionBoxingOther,
                    'boxing' => $location->constructionBoxing,
                    'pipework_other' => $location->constructionPipeworkOther,
                    'pipework' => $location->constructionPipework,
                    'floor_other' => $location->constructionFloorVoidOther,
                    'floor' => $location->constructionFloorVoid
                ];
                $data_construction[] = [
                    'location_id'=> $location->ID,
                    'ceiling'=> $location->constructionCeiling,
                    'ceiling_other'=> $location->constructionCeilingOther,
                    'walls'=> $location->constructionWalls,
                    'walls_other'=> $location->constructionWallsOther,
                    'floor'=> $location->constructionFloor,
                    'floor_other'=> $location->constructionFloorOther,
                    'doors'=> $location->constructionDoors,
                    'doors_other'=> $location->constructionDoorsOther,
                    'windows'=> $location->constructionWindows,
                    'windows_other' => $location->constructionWindowsOther
                ];
            }
//            dd($data_user);
            DB::beginTransaction();
            try{
//                Location::insert($data_location);
//                LocationVoid::insert($data_void);
//                LocationConstruction::insert($data_construction);
                //insert location
                $insert_data_location = collect($data_location);
                $chunks = $insert_data_location->chunk(500);
                foreach ($chunks as $chunk)
                {
                    Location::insert($chunk->toArray());
                }
                //insert location void
                $insert_data_void = collect($data_void);
                $chunks_void = $insert_data_void->chunk(500);

                foreach ($chunks_void as $chunk_v)
                {
                    LocationVoid::insert($chunk_v->toArray());
                }
                //insert location construction
                $insert_data_construction = collect($data_construction);
                $chunks_construction = $insert_data_construction->chunk(500);

                foreach ($chunks_construction as $chunk_c)
                {
                    LocationConstruction::insert($chunk_c->toArray());
                }
                //insert location infor
                $insert_data_info = collect($data_location_info);
                $chunks_info = $insert_data_info->chunk(500);

                foreach ($chunks_info as $chunk_i)
                {
                    LocationInfo::insert($chunk_i->toArray());
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
