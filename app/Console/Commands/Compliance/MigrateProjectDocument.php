<?php

namespace App\Console\Commands\Compliance;

use App\Models\Document;
use App\Models\ShineCompliance\Property;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

// use RecursiveIteratorIterator;

class MigrateProjectDocument extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:migrate_project_document';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
    public function handle(){
//        ini_set('memory_limit','5120M');
        //check migrate materials (new on compliance migrated from construction type) to Other + old data
//        DB::beginTransaction();
        try{
            $PRE_CONSTRUCTION_DOC_CATEGORY = PRE_CONSTRUCTION_DOC_CATEGORY;
            $PLANNING_DOC_CATEGORY = PLANNING_DOC_CATEGORY;
            $COMMERCIAL_DOC_CATEGORY = COMMERCIAL_DOC_CATEGORY;

            $PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS = PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS;
            $PROJECT_STAGE_PRE_CONSTRUCTION = PROJECT_STAGE_PRE_CONSTRUCTION;
            $PROJECT_DOC_PUBLISHED = PROJECT_DOC_PUBLISHED;

            $this->info('======> staring migrate Status of documents into new table');
            DB::update("UPDATE tbl_documents SET `status` = $PROJECT_DOC_PUBLISHED WHERE `status` = 1 AND document_present = 1;");
            $this->info('======> staring migrate Tender documents into new table');
            $this->info('======> staring migrate Work Request documents into new table');
            DB::update("UPDATE tbl_documents SET category = $PRE_CONSTRUCTION_DOC_CATEGORY, reference = CONCAT('DD', id), `type` = 129, `status` = 2 WHERE `type` = 30 AND (`name` LIKE 'MWR%' OR `name` LIKE 'WR%' );");
            DB::update("UPDATE tbl_documents SET category = $PRE_CONSTRUCTION_DOC_CATEGORY, reference = CONCAT('DD', id), `type` = 101, `status` = 2 WHERE `type` = 30;");
            DB::update("UPDATE tbl_documents SET category = $PRE_CONSTRUCTION_DOC_CATEGORY, reference = CONCAT('DD', id), `type` = 97, `status` = 2 WHERE `type` = 29;");
            DB::update("UPDATE tbl_documents SET category = $PRE_CONSTRUCTION_DOC_CATEGORY, reference = CONCAT('DD', id), `type` = 98, `status` = 2 WHERE `type` = 65;");
            DB::update("UPDATE tbl_documents SET category = $PRE_CONSTRUCTION_DOC_CATEGORY, reference = CONCAT('DD', id), `type` = 90, `status` = 2 WHERE `type` = 27;");
            $this->info('======> staring migrate Contractor documents into new table');
            DB::update("UPDATE tbl_documents SET category = $PLANNING_DOC_CATEGORY, reference = CONCAT('PD', id), `type` = 37 WHERE `type` = 50;");
            DB::update("UPDATE tbl_documents SET category = $COMMERCIAL_DOC_CATEGORY, reference = CONCAT('FD', id), `type` = 118 WHERE `type` = 35;");
            DB::update("UPDATE tbl_documents SET category = $PLANNING_DOC_CATEGORY, reference = CONCAT('PD', id), `type` = 39 WHERE `type` = 33;");
            DB::update("UPDATE tbl_documents SET category = $COMMERCIAL_DOC_CATEGORY, reference = CONCAT('FD', id), `type` = 114 WHERE `type` = 31;");
            DB::update("UPDATE tbl_documents SET category = $PLANNING_DOC_CATEGORY, reference = CONCAT('PD', id), `type` = 38 WHERE `type` = 34;");
            DB::update("UPDATE tbl_documents SET category = $COMMERCIAL_DOC_CATEGORY, reference = CONCAT('FD', id), `type` = 116 WHERE `type` = 49;");
            $this->info('======> staring migrate Project status from technical in progress to Pre-construction in progress');
            DB::update("UPDATE tbl_project SET progress_stage = $PROJECT_STAGE_PRE_CONSTRUCTION WHERE `status` = $PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS;");

            //todo change logic when approve WR
            // SELECT * FROM tbl_documents WHERE type = 30 AND  (`name` LIKE 'MWR%' OR `name` LIKE 'WR%' );
//            DB::commit();
            $this->info('======> Done');
        }catch (\Exception $e){
//            DB::rollback();
            dd($e->getMessage());
        }
    }
}
