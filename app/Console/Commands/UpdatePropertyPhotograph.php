<?php

namespace App\Console\Commands;

use App\Models\ShineDocumentStorage;
use Illuminate\Console\Command;
//use App\Helpers\CommonHelpers;
use App\Models\LogLogin;
use App\User;
use App\Models\AuditTrail;

class UpdatePropertyPhotograph extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_property_photograph';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Missing Property Photograph';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sql = "SELECT p.id as property_id, s3.object_id as psm_id, s3.updated_at as psm_updated_at, s3.path as psm_path,
                s.object_id as im_id, s.updated_at as im_updated_at
                FROM tbl_property p
                LEFT JOIN tbl_shine_document_storage s ON s.object_id = p.id and s.type = 'im'
                JOIN (
                        SELECT max_date.property_id, sur.id as survey_id  from (
                                SELECT MAX(sd.completed_date) as max_completed_date, property_id from tbl_survey ss
                                JOIN tbl_survey_date sd ON sd.survey_id = ss.id
                                JOIN tbl_shine_document_storage s2 ON s2.object_id = ss.id AND s2.type ='psm'
                                WHERE ss.`status` = 5
                                GROUP BY property_id
                        ) max_date
                        JOIN tbl_survey_date sd2 ON sd2.completed_date = max_completed_date
                        JOIN tbl_survey sur ON sur.id = sd2.survey_id AND sur.property_id = max_date.property_id
                ) abc ON abc.property_id = p.id
                LEFT JOIN tbl_shine_document_storage s3 ON s3.object_id = abc.survey_id and s3.type = 'psm'
                WHERE s3.created_at > IFNULL(s.updated_at, IFNULL(s.created_at, 0));";
        $result = \DB::select($sql);
        if(count($result) > 0){
            try{
                foreach ($result as $r){
                    //pull survey property image
                    $file = ShineDocumentStorage::where('object_id', $r->psm_id)->where('type', PROPERTY_SURVEY_IMAGE)->first();
                    if (is_file(public_path() . '/'.$file->path)) {
                        ShineDocumentStorage::updateOrCreate(
                            ['object_id' => $r->property_id, 'type' => PROPERTY_IMAGE],
                            [
                                'path' => $file->path,
                                'file_name' => $file->file_name,
                                'mime' => $file->mime,
                                'size' => $file->size,
                                "addedBy" => $file->addedBy ,
                                'addedDate' => $file->addedDate
                            ]
                        );
                    }
                }
            } catch (\Exception $e){
                dd($e);
            }
        }
        dd('Done');
    }
}
