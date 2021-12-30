<?php

namespace app\Http\Controllers\Migration;

use App\Models\Zone;
use Illuminate\Routing\Controller;
use App\Http\Controllers\LoginController as BaseController;
use Illuminate\Support\Facades\DB;
use App\Models\PropertyComment;
use App\Models\ProjectComment;
use App\Models\LocationComment;
use App\Models\ItemComment;

class MigrationCommentHistoryController extends Controller
{
    /**
     * Migration tblzone
     * @return string
     */
    public function migrate_comment_history(){
        $con_gsk_old = "mysql_gsk_old";
        $sql_prop_comment= "SELECT * FROM tblpropertycomment;";
        $sql_project_comment= "SELECT * FROM tblprojectcomment;";
        $sql_location_comment= "SELECT * FROM tbllocationcomment;";
        $sql_item_comment= "SELECT * FROM tblitemcomment;";

        $data_prop_comment = [];
        $data_project_comment = [];
        $data_location_comment = [];
        $data_item_comment = [];

        $prop_comment = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_prop_comment));

        $proj_comment = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_project_comment));

        $location_comment = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_location_comment));

        $item_comment = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_item_comment));

        if(count($prop_comment) > 0 &&
            count($proj_comment) > 0 &&
            count($location_comment) > 0 &&
            count($item_comment) > 0){
            foreach ($prop_comment as $comment){
                $data_prop_comment[] = [
                    'id' => $comment->ID,
                    'record_id' => $comment->recordID,
                    'comment' => $comment->comment,
                    'parent_reference' => $comment->parentReference,
                    'created_at' => date("Y-m-d H:i:s", $comment->createdDate),
                ];
            }

            foreach ($proj_comment as $comment){
                $data_proj_comment[] = [
                    'id' => $comment->ID,
                    'record_id' => $comment->recordID,
                    'comment' => $comment->comment,
                    'parent_reference' => $comment->parentReference,
                    'created_at' => date("Y-m-d H:i:s", $comment->createdDate),
                ];
            }

            foreach ($location_comment as $comment){
                $data_location_comment[] = [
                    'id' => $comment->ID,
                    'record_id' => $comment->recordID,
                    'comment' => $comment->comment,
                    'parent_reference' => $comment->parentReference,
                    'created_at' => date("Y-m-d H:i:s", $comment->createdDate),
                ];
            }

            foreach ($item_comment as $comment){
                $data_item_comment[] = [
                    'id' => $comment->ID,
                    'record_id' => $comment->recordID,
                    'comment' => $comment->comment,
                    'parent_reference' => $comment->parentReference,
                    'created_at' => date("Y-m-d H:i:s", $comment->createdDate),
                ];
            }
            DB::beginTransaction();
            try{

                //insert comment
                $data_prop_comment = collect($data_prop_comment);
                $chunks_prop = $data_prop_comment->chunk(500);
                foreach ($chunks_prop as $chunk)
                {
                    PropertyComment::insert($chunk->toArray());
                }

                //insert comment
                $data_proj_comment = collect($data_proj_comment);
                $chunks_project = $data_proj_comment->chunk(500);
                foreach ($chunks_project as $chunk)
                {
                    ProjectComment::insert($chunk->toArray());
                }

                //insert comment
                $data_location_comment = collect($data_location_comment);
                $chunks_location = $data_location_comment->chunk(500);
                foreach ($chunks_location as $chunk)
                {
                    LocationComment::insert($chunk->toArray());
                }

                //insert comment
                $data_item_comment = collect($data_item_comment);
                $chunks_item = $data_item_comment->chunk(500);
                foreach ($chunks_item as $chunk)
                {
                    ItemComment::insert($chunk->toArray());
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
