<?php

namespace App\Console\Commands;

use App\Models\WorkRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateDataReferenceMWR extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:UpdateDataReferenceMWR';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command UpdateDataReferenceMWR';

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
            $id = DB::table('tbl_work_request')->where('is_major',1)->pluck('id');
            foreach ($id as $value) {
                DB::table('tbl_work_request')
                ->where('id', $value)
                ->update(['reference' => 'PWR'.$value]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
        }
    }
}
