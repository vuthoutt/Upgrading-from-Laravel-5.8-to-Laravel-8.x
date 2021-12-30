<?php

namespace app\Http\Controllers\Migration;

use App\Models\Zone;
use Illuminate\Routing\Controller;
use App\Http\Controllers\LoginController as BaseController;
use App\Models\Item;
use App\Models\ItemInfo;
use Illuminate\Support\Facades\DB;

class MigrationItemController extends Controller
{
    /**
     * Migration tblzone
     * @return string
     */
    public function migrate_item(){
        $con_gsk_old = "mysql_gsk_old";
        $con_gsk_new = "mysql";
        $sql = "SELECT i.*,
                IF(i.state = 'inaccessible',
                        IF(i.assessment = 'limitedAssessment', 12, IFNULL(dmas1.score, 0) + IFNULL(dmas2.score, 0) + IFNULL(dmas3.score, 0) + IFNULL(dmas4.score, 0)),
                        IFNULL(dmas1.score, 0) + IFNULL(dmas2.score, 0) + IFNULL(dmas3.score, 0) + IFNULL(dmas4.score, 0)) AS mas_score
                FROM tblitems i
                LEFT JOIN tbldropdownvalue vmas1 on i.keyID = vmas1.itemID and vmas1.dropdownDataParent = 600
                LEFT JOIN tbldropdowndata dmas1 on vmas1.dropdownDataID = dmas1.ID
                LEFT JOIN tbldropdownvalue vmas2 on i.keyID = vmas2.itemID and vmas2.dropdownDataParent = 604
                LEFT JOIN tbldropdowndata dmas2 on vmas2.dropdownDataID = dmas2.ID
                LEFT JOIN tbldropdownvalue vmas3 on i.keyID = vmas3.itemID and vmas3.dropdownDataParent = 609
                LEFT JOIN tbldropdowndata dmas3 on vmas3.dropdownDataID = dmas3.ID
                LEFT JOIN tbldropdownvalue vmas4 on i.keyID = vmas4.itemID and vmas4.dropdownDataParent = 614
                LEFT JOIN tbldropdowndata dmas4 on vmas4.dropdownDataID = dmas4.ID
                GROUP BY i.keyID
                ORDER BY i.keyID ASC
                ;";
        $data_item = $data_item_info = [];
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));

        if(count($results) > 0){

//           state accessible
//            inaccessible
//            noacm
            foreach ($results as $item){
                $total_score = isset($item->totalAssessmentRisk) ? $item->totalAssessmentRisk : 0;
                $total_mas_score = ($item->state == 'inaccessible') and ($item->assessment == 'limitedAssessment' )? 12 : (isset($item->mas_score) ? $item->mas_score : 0);
                $total_pas_score = $total_score - $total_mas_score > 0 ? $total_score - $total_mas_score : 0;
                $data_item[] = [
                    'id' => $item->keyID,
                    'record_id' => $item->recordID,
                    'reference' => $item->shineReference,
                    'area_id' => $item->areaID,
                    'location_id' => $item->locationID,
                    'name' => $item->itemID,
                    'property_id' => $item->siteID,
                    'survey_id' => $item->surveyID,
                    'state' => $item->state == 'noacm' ? ITEM_NOACM_STATE : ($item->state == 'inaccessible' ? ITEM_INACCESSIBLE_STATE : ITEM_ACCESSIBLE_STATE),
                    'version' => $item->version,
                    'is_locked' => $item->locked == '-1' ? ITEM_LOCKED : ITEM_UNLOCKED,
                    'total_risk' => $total_score,
                    'total_mas_risk' => $total_mas_score,
                    'total_pas_risk' => $total_pas_score,
                    'decommissioned' => $item->decommissioned == '-1' ? ITEM_DECOMMISSION : ITEM_UNDECOMMISSION,
                    'created_at' => $item->itCreated > 0 ? date("Y-m-d H:i:s", $item->itCreated) : NULL
                ];
                $data_item_info[] = [
                    'item_id' => $item->keyID,
                    'extent' => $item->asbestosQuantityValue,
                    'is_r_and_d_element' => $item->rAndD == '1' || $item->rAndD == '-1' ? ITEM_REQUIRE_RAND_ELEMENT : ITEM_NOT_REQUIRE_RAND_ELEMENT ,
                    'inspection_date' =>$item->inspectionDate,
                    'comment' => $item->generalComments,
                    'assessment' => $item->assessment == 'limitedAssessment' ? ITEM_LIMIT_ASSESSMENT : ($item->assessment == 'fullAssessment' ? ITEM_FULL_ASSESSMENT : $item->assessment),
                    'created_at' => $item->itCreated > 0 ? date("Y-m-d H:i:s", $item->itCreated) : NULL
                ];
            }

            DB::beginTransaction();
            try{
                //insert item
                $insert_item = collect($data_item);
                $chunks = $insert_item->chunk(500);
                foreach ($chunks as $chunk)
                {
                    Item::insert($chunk->toArray());
                }
                //insert item info
                $insert_info = collect($data_item_info);
                $chunks_info = $insert_info->chunk(500);

                foreach ($chunks_info as $chunk_i)
                {
                    ItemInfo::insert($chunk_i->toArray());
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
