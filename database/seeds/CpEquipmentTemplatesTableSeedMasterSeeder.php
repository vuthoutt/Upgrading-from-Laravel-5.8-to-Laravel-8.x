<?php

use Illuminate\Database\Seeder;

class CpEquipmentTemplatesTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('cp_equipment_templates')->delete();
        
        \DB::table('cp_equipment_templates')->insert(array (
            0 => 
            array (
                'id' => 1,
                'decription' => 'Misc',
            ),
            1 => 
            array (
                'id' => 2,
                'decription' => 'Hot Services',
            ),
            2 => 
            array (
                'id' => 3,
                'decription' => 'Cold Storage',
            ),
            3 => 
            array (
                'id' => 4,
                'decription' => 'Cold Outlets',
            ),
            4 => 
            array (
                'id' => 5,
                'decription' => 'Mixer Outlets',
            ),
            5 => 
            array (
                'id' => 6,
                'decription' => 'Hot Outlets',
            ),
            6 => 
            array (
                'id' => 7,
                'decription' => 'Point of Use Heaters',
            ),
        ));
        
        
    }
}