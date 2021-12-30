<?php

use Illuminate\Database\Seeder;

class ComplianceSystemTypesTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('compliance_system_types')->delete();
        
        \DB::table('compliance_system_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => 'AOV',
            ),
            1 => 
            array (
                'id' => 2,
                'description' => 'Emergency Lighting',
            ),
            2 => 
            array (
                'id' => 3,
                'description' => 'Fall and Arrest',
            ),
            3 => 
            array (
                'id' => 4,
                'description' => 'Fire Detection & Alarm',
            ),
            4 => 
            array (
                'id' => 5,
                'description' => 'Lightning Protection',
            ),
            5 => 
            array (
                'id' => 6,
                'description' => 'Mains Electric',
            ),
            6 => 
            array (
                'id' => 7,
                'description' => 'PAT',
            ),
            7 => 
            array (
                'id' => 8,
                'description' => 'Hot and Cold Water',
            ),
            8 => 
            array (
                'id' => 9,
                'description' => 'Dry Riser',
            ),
            9 => 
            array (
                'id' => 10,
                'description' => 'Evaporative Cooling',
            ),
            10 => 
            array (
                'id' => 11,
                'description' => 'Fire Extinguisher',
            ),
            11 => 
            array (
                'id' => 12,
                'description' => 'Spa Pools',
            ),
            12 => 
            array (
                'id' => 13,
                'description' => 'Sprinklers',
            ),
            13 => 
            array (
                'id' => 14,
                'description' => 'Water Pumps & Pressurisation Units',
            ),
            14 => 
            array (
                'id' => 15,
                'description' => 'Water Temperature Monitoring',
            ),
            15 => 
            array (
                'id' => 16,
                'description' => 'Wet Riser',
            ),
            16 => 
            array (
                'id' => 17,
                'description' => 'Wet Riser',
            ),
        ));
        
        
    }
}