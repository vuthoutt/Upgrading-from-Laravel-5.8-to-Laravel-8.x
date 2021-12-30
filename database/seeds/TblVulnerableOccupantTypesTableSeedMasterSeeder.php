<?php

use Illuminate\Database\Seeder;

class TblVulnerableOccupantTypesTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_vulnerable_occupant_types')->delete();
        
        \DB::table('tbl_vulnerable_occupant_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => 'Disabled',
            ),
            1 => 
            array (
                'id' => 2,
                'description' => 'Elderly',
            ),
            2 => 
            array (
                'id' => 3,
                'description' => 'Immunosuppressed',
            ),
            3 => 
            array (
                'id' => 4,
                'description' => 'Remote Persons/Lone Workers',
            ),
            4 => 
            array (
                'id' => 5,
                'description' => 'Sleeping',
            ),
            5 => 
            array (
                'id' => 6,
                'description' => 'Young Persons',
            ),
            6 => 
            array (
                'id' => 7,
                'description' => 'N/A',
            ),
        ));
        
        
    }
}