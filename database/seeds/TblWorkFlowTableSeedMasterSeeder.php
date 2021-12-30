<?php

use Illuminate\Database\Seeder;

class TblWorkFlowTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_work_flow')->delete();
        
        \DB::table('tbl_work_flow')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => 'Hackney Surveying Department',
                'client_id' => '1,2',
                'winner_contractor' => '1,2',
                'project_status' => 3,
            ),
            1 => 
            array (
                'id' => 2,
                'description' => 'Tersus Group',
                'client_id' => '2',
                'winner_contractor' => '2',
                'project_status' => 3,
            ),
            2 => 
            array (
                'id' => 3,
                'description' => 'Wates',
                'client_id' => '6',
                'winner_contractor' => NULL,
                'project_status' => 2,
            ),
            3 => 
            array (
                'id' => 4,
                'description' => 'Keltbray',
                'client_id' => '7',
                'winner_contractor' => '7',
                'project_status' => 3,
            ),
            4 => 
            array (
                'id' => 5,
                'description' => 'Keltbray and Tersus Group',
                'client_id' => '2,7',
                'winner_contractor' => '2,7',
                'project_status' => 3,
            ),
        ));
        
        
    }
}