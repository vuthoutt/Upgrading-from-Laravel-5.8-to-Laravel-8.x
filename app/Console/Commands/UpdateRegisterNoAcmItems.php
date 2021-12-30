<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;
use App\Models\Location;
use App\Models\Area;
use App\Repositories\SurveyRepository;
use Illuminate\Support\Facades\DB;

class UpdateRegisterNoAcmItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_register_no_acm_items';

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
    public function handle(SurveyRepository $surveyRepository)
    {
        $all_noacm_items = Item::whereHas('survey', function($query) {
                                     return $query->where('status', COMPLETED_SURVEY_STATUS)->where('decommissioned', 0);
                                })->where('decommissioned', ITEM_UNDECOMMISSION)
                                ->where('survey_id','!=', 0)
                                ->where('state',ITEM_NOACM_STATE)->get();
        try {
            DB::beginTransaction();
            foreach ($all_noacm_items as $key => $item) {
                // clone to register
                $this->info("==========> Starting clone item {$item->id}!");
                $locationRegister =  Location::where('record_id', $item->location->record_id ?? 0)->where('survey_id', 0)->first();

                if (!is_null($locationRegister) and !is_null($locationRegister->area)) {
                    $decommissioned = false;
                    if ($locationRegister->decommissioned == 1 || $locationRegister->area->decommissioned == 1) {
                        $decommissioned = true;
                    }
                    $this->info("==========> Get Register Location {$locationRegister->location_reference}!");
                    $surveyRepository->cloneItem($item->id, $locationRegister->id, $locationRegister->area_id, 0, true, $decommissioned);
                    $this->info("==========> Clone item {$item->reference} successfully! <============");
                } else {
                    $this->info("==========> Do not have register location, area of item {$item->id} to clone!");
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
        }
        // dd($all_noacm_items->count());
    }
}
