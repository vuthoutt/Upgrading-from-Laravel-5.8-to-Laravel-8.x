<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;
use App\Models\DropdownItemValue\MaterialAssessmentRiskValue;
use App\Models\DropdownItemValue\PriorityAssessmentRiskValue;
class FixerCorrectItemScore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fixer_correct_item';

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
            \DB::beginTransaction();
            $item_noACMs = Item::where('state',1)->pluck('id')->toArray();
            // dd($item_noACMs);
            MaterialAssessmentRiskValue::whereIn('item_id', $item_noACMs)->update(['dropdown_data_item_id' => 0]);
            PriorityAssessmentRiskValue::whereIn('item_id', $item_noACMs)->update(['dropdown_data_item_id' => 0]);
             $this->info("==========> update Item NOACM ");
            $item_acms = Item::where('state',ITEM_INACCESSIBLE_STATE)->whereHas('itemInfo', function($query){
                                                                            return $query->where('assessment', ITEM_LIMIT_ASSESSMENT);
                                                                        })->pluck('id')->toArray();

            MaterialAssessmentRiskValue::whereIn('item_id', $item_acms)->where('dropdown_data_item_parent_id',600)->update(['dropdown_data_item_id' => 603]);
            MaterialAssessmentRiskValue::whereIn('item_id', $item_acms)->where('dropdown_data_item_parent_id',604)->update(['dropdown_data_item_id' => 608]);
            MaterialAssessmentRiskValue::whereIn('item_id', $item_acms)->where('dropdown_data_item_parent_id',609)->update(['dropdown_data_item_id' => 613]);
            MaterialAssessmentRiskValue::whereIn('item_id', $item_acms)->where('dropdown_data_item_parent_id',614)->update(['dropdown_data_item_id' => 617]);
            $this->info("==========> update Item ACM ");
            $items = Item::with('pasPrimary.getData','pasSecondary.getData',
                    'pasLocation.getData','pasAccessibility.getData',
                    'pasExtent.getData','pasNumber.getData',
                    'pasHumanFrequency.getData','pasAverageTime.getData',
                    'pasType.getData','pasMaintenanceFrequency.getData',
                    'masProductDebris.getData','masDamage.getData',
                    'masTreatment.getData','masAsbestos.getData')->get();
            foreach ($items as $item) {
                //mas
                $item_product_type = $item->masProductDebris->getData->score ?? 0;
                $item_extend_damage = $item->masDamage->getData->score ?? 0;
                $item_surface_treatment = $item->masTreatment->getData->score ?? 0;
                $item_asbestos_fibre = $item->masAsbestos->getData->score ?? 0;
                //pas
                $pasPrimary = $item->pasPrimary->getData->score ?? 0;
                $pasSecondary = $item->pasSecondary->getData->score ?? 0;
                $pasLocation = $item->pasLocation->getData->score ?? 0;
                $pasAccessibility = $item->pasAccessibility->getData->score ?? 0;
                $pasExtent = $item->pasExtent->getData->score ?? 0;
                $pasNumber = $item->pasNumber->getData->score ?? 0;
                $pasHumanFrequency = $item->pasHumanFrequency->getData->score ?? 0;
                $pasAverageTime = $item->pasAverageTime->getData->score ?? 0;
                $pasType = $item->pasType->getData->score ?? 0;
                $pasMaintenanceFrequency = $item->pasMaintenanceFrequency->getData->score ?? 0;

                $total_mas = $item_product_type + $item_extend_damage + $item_surface_treatment + $item_asbestos_fibre;
                $total_pas = round(($pasPrimary + $pasSecondary) / 2);
                $total_pas += round(($pasLocation + $pasAccessibility + $pasExtent) / 3);
                $total_pas += round(($pasNumber + $pasHumanFrequency + $pasHumanFrequency) / 3);
                $total_pas += round(($pasType + $pasMaintenanceFrequency) / 2);

                $total_risk = $total_mas + $total_pas;
                Item::where('id', $item->id)->update(['total_risk' => $total_risk, 'total_mas_risk' => $total_mas, 'total_pas_risk' => $total_pas]);
                $this->info("==========> update Item : $item->id , total_risk : $total_risk , total_mas : $total_mas, total_pas : $total_pas");
                \DB::commit();
            }
        } catch (\Exception $e) {
            \DB::rollback();
            dd($e);
        }

        // correct total

    }
}
