<?php

use Illuminate\Database\Seeder;

class TblWorkRequestTypeTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_work_request_type')->delete();
        
        \DB::table('tbl_work_request_type')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => 'Asbestos',
            ),
            1 => 
            array (
                'id' => 2,
                'description' => 'Fire',
            ),
        ));
        
        
    }
}