<?php

use Illuminate\Database\Seeder;

class CpHazardActionResponsibilitiesTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('cp_hazard_action_responsibilities')->delete();
        
        \DB::table('cp_hazard_action_responsibilities')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => 'Fire Safety Team',
                'parent_id' => 0,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'description' => 'Housing',
                'parent_id' => 0,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'description' => 'M&E Team',
                'parent_id' => 0,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'description' => 'Major Works',
                'parent_id' => 0,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'description' => 'Minor Works',
                'parent_id' => 0,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'description' => 'TMO',
                'parent_id' => 0,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'description' => 'Morgan Sindall',
                'parent_id' => 1,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'description' => 'Internal team',
                'parent_id' => 1,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'description' => 'Internal Team',
                'parent_id' => 2,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'description' => 'GEM',
                'parent_id' => 3,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'description' => 'Oakray',
                'parent_id' => 3,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'description' => 'Precision Lifts',
                'parent_id' => 3,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'description' => 'Morgan Sindall',
                'parent_id' => 3,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'description' => 'Internal Team',
                'parent_id' => 3,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'description' => 'Morgan Sindall',
                'parent_id' => 4,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'description' => 'United Living',
                'parent_id' => 4,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'description' => 'GEM',
                'parent_id' => 5,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'description' => 'Oakray',
                'parent_id' => 5,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'description' => 'Precision Lifts',
                'parent_id' => 5,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'description' => 'Morgan Sindall',
                'parent_id' => 5,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'description' => 'External Contracors',
                'parent_id' => 6,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'description' => 'Axis Group',
                'parent_id' => 4,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'description' => 'IPS',
                'parent_id' => 4,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'description' => 'Oakray',
                'parent_id' => 4,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'description' => 'United Living',
                'parent_id' => 1,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'description' => 'IPS',
                'parent_id' => 1,
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}