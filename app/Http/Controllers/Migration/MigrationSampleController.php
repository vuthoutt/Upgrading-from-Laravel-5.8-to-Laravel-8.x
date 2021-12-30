<?php

namespace app\Http\Controllers\Migration;

use App\Models\Sample;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class MigrationSampleController extends Controller
{
    /**
     * Migration upload_manifest and download_manifest
     * @return string
     */
    public function migrate_sample_data(){
        $con_gsk_old = "mysql_gsk_old";
        $sql = "SELECT * FROM tblsample;";
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));
        if(count($results) > 0 ){
            foreach ($results as $sample){
                $data_sample[] = [
                    'id' => $sample->ID,
                    'reference' => $sample->shineReference,
                    'is_real' => $sample->isReal == -1 ? 1 : 0,
                    'description' => $sample->description,
                    'comment_key' => $sample->commentKey,
                    'comment_other' => $sample->commentOther,
                    'original_item_id' => $sample->originalItemId
                ];
            }

            DB::beginTransaction();
            try{
                $insert_data = collect($data_sample);
                $chunks = $insert_data->chunk(500);
                foreach ($chunks as $chunk)
                {
                    Sample::insert($chunk->toArray());
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
