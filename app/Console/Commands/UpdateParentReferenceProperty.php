<?php

namespace App\Console\Commands;

use App\Models\Property;
use Illuminate\Console\Command;
use App\Models\Item;
use App\Models\Location;
use App\Models\Area;
use App\Repositories\SurveyRepository;
use Illuminate\Support\Facades\DB;

class UpdateParentReferenceProperty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_parent_reference_property';

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
            $parent_ids = Property::whereRaw('property_reference IS NOT NULL')->get()->pluck('id','property_reference')->toArray();
            $parent_ids = array_unique(array_filter($parent_ids));
            $child_ids = Property::whereRaw('parent_reference IS NOT NULL')->get()->pluck('parent_reference','id')->toArray();
            foreach ($child_ids as $id => $reference){
                if(isset($parent_ids[$reference])){
                    DB::table('tbl_property')
                        ->where('id', $id)
                        ->update(['parent_id' => $parent_ids[$reference]]);
//                Property::where('id', $id)->update(['property_reference'=> $parent_ids[$reference]]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
        }
        dd('Done');
        // dd($all_noacm_items->count());
    }
}
