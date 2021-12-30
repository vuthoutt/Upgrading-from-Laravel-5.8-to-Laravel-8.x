<?php

use Illuminate\Database\Seeder;

class CpPrivilegeCommonTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('cp_privilege_common')->delete();
        
        \DB::table('cp_privilege_common')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Tender Documents',
                'type' => '1',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Contractor Documents',
                'type' => '1',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Details',
                'type' => '2',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Policy Documents',
                'type' => '2',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Departments',
                'type' => '2',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Training Records Table',
                'type' => '2',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Surveys',
                'type' => '3',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Contractor Documents',
                'type' => '3',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Planning Documents',
                'type' => '1',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Pre-Start Documents',
                'type' => '1',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Site Records Documents',
                'type' => '1',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Completion Documents',
                'type' => '1',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Financial Documents',
                'type' => '1',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Pre-Start Documents',
                'type' => '3',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Site Records Documents',
                'type' => '3',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Completion Documents',
                'type' => '3',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Financial Documents',
                'type' => '3',
            ),
        ));
        
        
    }
}