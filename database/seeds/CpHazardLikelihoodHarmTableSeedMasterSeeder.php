<?php

use Illuminate\Database\Seeder;

class CpHazardLikelihoodHarmTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('cp_hazard_likelihood_harm')->delete();
        
        \DB::table('cp_hazard_likelihood_harm')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => 'Negligible',
                'order' => 1,
                'score' => 1,
                'other' => 0,
            ),
            1 => 
            array (
                'id' => 2,
                'description' => 'Slight',
                'order' => 2,
                'score' => 2,
                'other' => 0,
            ),
            2 => 
            array (
                'id' => 3,
                'description' => 'Moderate',
                'order' => 3,
                'score' => 3,
                'other' => 0,
            ),
            3 => 
            array (
                'id' => 4,
                'description' => 'Severe',
                'order' => 4,
                'score' => 4,
                'other' => 0,
            ),
            4 => 
            array (
                'id' => 5,
                'description' => 'Extreme',
                'order' => 5,
                'score' => 5,
                'other' => 0,
            ),
        ));
        
        
    }
}