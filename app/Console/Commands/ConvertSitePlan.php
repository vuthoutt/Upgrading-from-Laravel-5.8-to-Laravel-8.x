<?php

namespace App\Console\Commands;

use App\Models\ShineCompliance\AssessmentNoteDocument;
use App\Models\ShineCompliance\AssessmentPlanDocument;
use Illuminate\Console\Command;
use App\Models\Item;
use App\Models\DropdownItemValue\MaterialAssessmentRiskValue;
use App\Models\DropdownItemValue\PriorityAssessmentRiskValue;
use Illuminate\Support\Facades\DB;

class ConvertSitePlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:convert_site_plane';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        try {
            DB::beginTransaction();
            $sql = "SELECT * FROM cp_assessment_note_documents_copy1;";
            $data_note = DB::select($sql);
            foreach ($data_note as $note){
                $this->info("==========> Starting note item {$note->id}!");
                $note_data[] = [
                    "id" => $note->id,
                    "reference" => $note->reference,
                    "property_id" => $note->property_id,
                    "plan_reference" => $note->name,
                    "description" => $note->plan_reference,
//                    "assess_id" => $note->assess_id,
                    "document_present" => $note->document_present,
                    "assess_id" => $note->category,
                    "plan_date" => $note->added,
                    "added_by" => $note->added_by,
                    "created_at" => $note->created_at,
                    "updated_at" => $note->updated_at,
                ];
            }
            AssessmentNoteDocument::insert($note_data);

            $sql = "SELECT * FROM cp_assessment_plans_documents_copy1;";
            $data_plan = DB::select($sql);
            foreach ($data_plan as $plan){
                $this->info("==========> Starting plan item {$plan->id}!");
                $plan_data[] = [
                    "id" => $plan->id,
                    "reference" => $plan->reference,
                    "property_id" => $plan->property_id,
                    "plan_reference" => $plan->name,
                    "description" => $plan->plan_reference,
                    "assess_id" => $plan->assess_id,
                    "document_present" => $plan->document_present,
//                    "category" => $note->assess_id,
                    "plan_date" => $plan->added,
                    "added_by" => $plan->added_by,
                    "created_at" => $note->created_at,
                    "updated_at" => $note->updated_at,
                ];
            }
            AssessmentPlanDocument::insert($plan_data);
            DB::commit();
            dd('Done');
        }catch (\Exception $e){
            DB::rollback();
            dd($e->getMessage());
        }

        // correct total

    }
}
