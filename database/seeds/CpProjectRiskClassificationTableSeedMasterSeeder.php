<?php

use Illuminate\Database\Seeder;

class CpProjectRiskClassificationTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('cp_project_risk_classification')->delete();
        
        \DB::table('cp_project_risk_classification')->insert(array (
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
            2 => 
            array (
                'id' => 3,
                'description' => 'Water',
            ),
        ));
        
        
    }
}