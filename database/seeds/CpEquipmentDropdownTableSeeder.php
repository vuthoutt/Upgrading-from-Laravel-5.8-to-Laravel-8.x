<?php

use Illuminate\Database\Seeder;

class CpEquipmentDropdownTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('cp_equipment_dropdown')->delete();

        \DB::table('cp_equipment_dropdown')->insert(array (
            0 =>
            array (
                'id' => 1,
                'description' => 'Reason for Inaccessibility',
            ),
            1 =>
            array (
                'id' => 2,
                'description' => 'Frequency of Use',
            ),
            2 =>
            array (
                'id' => 3,
                'description' => 'Insulation Type',
            ),
            3 =>
            array (
                'id' => 4,
                'description' => 'Labelling',
            ),
            4 =>
            array (
                'id' => 5,
                'description' => 'Drain Location',
            ),
            5 =>
            array (
                'id' => 6,
                'description' => 'Cold Field Location',
            ),
            6 =>
            array (
                'id' => 7,
                'description' => 'Outlet Field Location',
            ),
            7 =>
            array (
                'id' => 8,
                'description' => 'Cleanliness',
            ),
            8 =>
            array (
                'id' => 9,
                'description' => 'Ease of Cleaning',
            ),
            9 =>
            array (
                'id' => 10,
                'description' => 'Direct/Indirect Fired',
            ),
            10 =>
            array (
                'id' => 11,
                'description' => 'Operational Use',
            ),
            11 =>
            array (
                'id' => 12,
                'description' => 'Source',
            ),
            12 =>
            array (
                'id' => 13,
                'description' => 'Source Accessibility',
            ),
            13 =>
            array (
                'id' => 14,
                'description' => 'Source Condition',
            ),
            14 =>
            array (
                'id' => 15,
                'description' => 'Insulation Condition',
            ),
            15 =>
            array (
                'id' => 16,
                'description' => 'Operation Exposure',
            ),
            16 =>
            array (
                'id' => 17,
                'description' => 'Degree of Fouling',
            ),
            17 =>
            array (
                'id' => 18,
                'description' => 'Degree of Biological Slime',
            ),
            18 =>
            array (
                'id' => 19,
                'description' => 'Extent of Corrosion',
            ),
            19 =>
            array (
                'id' => 20,
                'description' => 'Construction Materials',
            ),
            20 =>
            array (
                'id' => 21,
                'description' => 'Aerosol Risk',
            ),
            21 =>
            array (
                'id' => 22,
                'description' => 'Pipe Insulation Condition',
            ),
            22 =>
            array (
                'id' => 23,
                'description' => 'Evidence of Stagnation',
            ),
            23 =>
            array (
                'id' => 24,
                'description' => 'Pipe Insulation',
            ),
            24 =>
            array (
                'id' => 25,
                'description' => 'Nearest/Furthest',
            ),
            25 =>
            array (
                'id' => 26,
                'description' => 'Horizontal/Vertical',
            ),
            26 =>
            array (
                'id' => 27,
                'description' => 'Material of Pipework',
            ),
        ));
        
        
    }
}