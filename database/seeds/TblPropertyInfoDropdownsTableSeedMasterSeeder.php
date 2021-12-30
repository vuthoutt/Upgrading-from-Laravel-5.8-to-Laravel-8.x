<?php

use Illuminate\Database\Seeder;

class TblPropertyInfoDropdownsTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('tbl_property_info_dropdowns')->delete();

        \DB::table('tbl_property_info_dropdowns')->insert(array (
            0 =>
            array (
                'id' => 1,
                'description' => 'Property Status',
                'order' => 1,
            ),
            1 =>
            array (
                'id' => 2,
                'description' => 'Property Occupied',
                'order' => 1,
            ),
            2 =>
            array (
                'id' => 3,
                'description' => 'Listed Building',
                'order' => 1,
            ),
            3 =>
            array (
                'id' => 4,
                'description' => 'Parking Arrangements',
                'order' => 1,
            ),
            4 =>
            array (
                'id' => 5,
                'description' => 'FRA Overall Risk Rating',
                'order' => 1,
            ),
            5 =>
            array (
                'id' => 6,
                'description' => 'Evacuation Strategy',
                'order' => 1,
            ),
            6 =>
            array (
                'id' => 7,
                'description' => 'Stairs',
                'order' => 1,
            ),
            7 =>
            array (
                'id' => 8,
                'description' => 'Floors',
                'order' => 1,
            ),
            8 =>
            array (
                'id' => 9,
                'description' => 'External Wall Construction',
                'order' => 1,
            ),
            9 =>
            array (
                'id' => 10,
                'description' => 'External Wall Finish',
                'order' => 1,
            ),
            10 =>
            array (
                'id' => 11,
                'description' => 'Property Type',
                'order' => 1,
            ),
        ));


    }
}
