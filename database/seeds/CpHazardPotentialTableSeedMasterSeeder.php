<?php

use Illuminate\Database\Seeder;

class CpHazardPotentialTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('cp_hazard_potential')->delete();
        
        \DB::table('cp_hazard_potential')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => 'Rare',
                'order' => 1,
                'score' => 1,
                'other' => 0,
            ),
            1 => 
            array (
                'id' => 2,
                'description' => 'Unlikely',
                'order' => 2,
                'score' => 2,
                'other' => 0,
            ),
            2 => 
            array (
                'id' => 3,
                'description' => 'Possible',
                'order' => 3,
                'score' => 3,
                'other' => 0,
            ),
            3 => 
            array (
                'id' => 4,
                'description' => 'Likely',
                'order' => 4,
                'score' => 4,
                'other' => 0,
            ),
            4 => 
            array (
                'id' => 5,
                'description' => 'Almost Certain',
                'order' => 5,
                'score' => 5,
                'other' => 0,
            ),
        ));
        
        
    }
}