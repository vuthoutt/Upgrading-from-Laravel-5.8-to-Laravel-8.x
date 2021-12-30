<?php

use Illuminate\Database\Seeder;

class CpAssessmentFireSafetyAnswersTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('cp_assessment_fire_safety_answers')->delete();
        
        \DB::table('cp_assessment_fire_safety_answers')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => 'AOV',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'description' => 'Automatic Refuse Chute Shutter',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'description' => 'Dry Rising Main',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'description' => 'Emergency Lighting',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'description' => 'Communal Fire Alarm',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'description' => 'Fire Extinguishers',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'description' => 'Lightning Protection',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'description' => 'Lifts with Facilities Firefighters',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'description' => 'Sprinklers',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'description' => 'Fire Evacuation Alert System',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'description' => 'PIB',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'description' => 'Smoke Extract System',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'description' => 'Other',
                'order' => 1,
                'other' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}