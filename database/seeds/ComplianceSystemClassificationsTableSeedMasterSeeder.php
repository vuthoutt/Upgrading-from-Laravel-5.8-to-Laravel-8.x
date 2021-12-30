<?php

use Illuminate\Database\Seeder;

class ComplianceSystemClassificationsTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('compliance_system_classifications')->delete();
        
        \DB::table('compliance_system_classifications')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => 'Fire Detection, Fighting & Suppression',
            ),
            1 => 
            array (
                'id' => 2,
                'description' => 'Electrical Installations & Equipment',
            ),
            2 => 
            array (
                'id' => 4,
                'description' => 'Security & Estate Services',
            ),
            3 => 
            array (
                'id' => 5,
                'description' => 'Air Equipment & Installations',
            ),
            4 => 
            array (
                'id' => 6,
                'description' => 'Alternative Energy',
            ),
            5 => 
            array (
                'id' => 7,
                'description' => 'Emergency Equipment',
            ),
            6 => 
            array (
                'id' => 8,
                'description' => 'Lifting Equipment',
            ),
            7 => 
            array (
                'id' => 9,
                'description' => 'Man Safe',
            ),
            8 => 
            array (
                'id' => 10,
                'description' => 'Natural Hazards',
            ),
            9 => 
            array (
                'id' => 11,
                'description' => 'Specialist Equipment',
            ),
            10 => 
            array (
                'id' => 12,
                'description' => 'Utility Meter',
            ),
            11 => 
            array (
                'id' => 13,
                'description' => 'Water Management',
            ),
        ));
        
        
    }
}