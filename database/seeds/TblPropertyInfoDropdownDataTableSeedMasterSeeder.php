<?php

use Illuminate\Database\Seeder;

class TblPropertyInfoDropdownDataTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('tbl_property_info_dropdown_data')->delete();

        \DB::table('tbl_property_info_dropdown_data')->insert(array (
            0 =>
            array (
                'id' => 1,
                'property_info_dropdown_id' => 1,
                'description' => 'Operational',
                'order' => 1,
                'other' => 0,
            ),
            1 =>
            array (
                'id' => 2,
                'property_info_dropdown_id' => 1,
                'description' => 'Not Operational',
                'order' => 2,
                'other' => 0,
            ),
            2 =>
            array (
                'id' => 3,
                'property_info_dropdown_id' => 2,
                'description' => 'Occupied',
                'order' => 1,
                'other' => 0,
            ),
            3 =>
            array (
                'id' => 4,
                'property_info_dropdown_id' => 2,
                'description' => 'Not Occupied',
                'order' => 2,
                'other' => 0,
            ),
            4 =>
            array (
                'id' => 5,
                'property_info_dropdown_id' => 3,
                'description' => 'No',
                'order' => 1,
                'other' => 0,
            ),
            5 =>
            array (
                'id' => 6,
                'property_info_dropdown_id' => 3,
                'description' => 'Grade 1',
                'order' => 2,
                'other' => 0,
            ),
            6 =>
            array (
                'id' => 7,
                'property_info_dropdown_id' => 3,
                'description' => 'Grade 2',
                'order' => 3,
                'other' => 0,
            ),
            7 =>
            array (
                'id' => 8,
                'property_info_dropdown_id' => 3,
                'description' => 'Grade 2*',
                'order' => 4,
                'other' => 0,
            ),
            8 =>
            array (
                'id' => 9,
                'property_info_dropdown_id' => 3,
                'description' => 'Other',
                'order' => 5,
                'other' => 1,
            ),
            9 =>
            array (
                'id' => 10,
                'property_info_dropdown_id' => 4,
                'description' => 'Property Parking Available',
                'order' => 1,
                'other' => 0,
            ),
            10 =>
            array (
                'id' => 11,
                'property_info_dropdown_id' => 4,
                'description' => 'Private Car Park',
                'order' => 2,
                'other' => 0,
            ),
            11 =>
            array (
                'id' => 12,
                'property_info_dropdown_id' => 4,
                'description' => 'Street Parking Only',
                'order' => 3,
                'other' => 0,
            ),
            12 =>
            array (
                'id' => 13,
                'property_info_dropdown_id' => 4,
                'description' => 'Other',
                'order' => 4,
                'other' => 1,
            ),
            13 =>
            array (
                'id' => 14,
                'property_info_dropdown_id' => 5,
                'description' => 'Substantial',
                'order' => 4,
                'other' => 0,
            ),
            14 =>
            array (
                'id' => 15,
                'property_info_dropdown_id' => 5,
                'description' => 'Moderate',
                'order' => 3,
                'other' => 0,
            ),
            15 =>
            array (
                'id' => 16,
                'property_info_dropdown_id' => 5,
                'description' => 'Tolerable',
                'order' => 2,
                'other' => 0,
            ),
            16 =>
            array (
                'id' => 17,
                'property_info_dropdown_id' => 6,
                'description' => 'Defend in Place',
                'order' => 1,
                'other' => 0,
            ),
            17 =>
            array (
                'id' => 18,
                'property_info_dropdown_id' => 6,
                'description' => 'Simultaneous Evacuation',
                'order' => 2,
                'other' => 0,
            ),
            18 =>
            array (
                'id' => 19,
                'property_info_dropdown_id' => 7,
                'description' => 'Concrete',
                'order' => 1,
                'other' => 0,
            ),
            19 =>
            array (
                'id' => 20,
                'property_info_dropdown_id' => 7,
                'description' => 'Timber',
                'order' => 2,
                'other' => 0,
            ),
            20 =>
            array (
                'id' => 21,
                'property_info_dropdown_id' => 7,
                'description' => 'Other',
                'order' => 3,
                'other' => 1,
            ),
            21 =>
            array (
                'id' => 22,
                'property_info_dropdown_id' => 8,
                'description' => 'Concrete',
                'order' => 1,
                'other' => 0,
            ),
            22 =>
            array (
                'id' => 23,
                'property_info_dropdown_id' => 8,
                'description' => 'Timber',
                'order' => 2,
                'other' => 0,
            ),
            23 =>
            array (
                'id' => 24,
                'property_info_dropdown_id' => 8,
                'description' => 'Other',
                'order' => 3,
                'other' => 1,
            ),
            24 =>
            array (
                'id' => 25,
                'property_info_dropdown_id' => 9,
                'description' => 'Solid brick/block',
                'order' => 1,
                'other' => 0,
            ),
            25 =>
            array (
                'id' => 26,
                'property_info_dropdown_id' => 9,
                'description' => 'Cavity brick/block',
                'order' => 2,
                'other' => 0,
            ),
            26 =>
            array (
                'id' => 27,
                'property_info_dropdown_id' => 9,
                'description' => 'Composite panels',
                'order' => 3,
                'other' => 0,
            ),
            27 =>
            array (
                'id' => 28,
                'property_info_dropdown_id' => 9,
                'description' => 'Other',
                'order' => 4,
                'other' => 1,
            ),
            28 =>
            array (
                'id' => 29,
                'property_info_dropdown_id' => 10,
                'description' => 'Plain brick/block',
                'order' => 1,
                'other' => 0,
            ),
            29 =>
            array (
                'id' => 30,
                'property_info_dropdown_id' => 10,
                'description' => 'Concrete/Stucco render',
                'order' => 2,
                'other' => 0,
            ),
            30 =>
            array (
                'id' => 31,
                'property_info_dropdown_id' => 10,
                'description' => 'Timber',
                'order' => 3,
                'other' => 0,
            ),
            31 =>
            array (
                'id' => 32,
                'property_info_dropdown_id' => 10,
                'description' => 'Concrete panels',
                'order' => 4,
                'other' => 0,
            ),
            32 =>
            array (
                'id' => 33,
                'property_info_dropdown_id' => 10,
                'description' => 'Metal panels',
                'order' => 5,
                'other' => 0,
            ),
            33 =>
            array (
                'id' => 34,
                'property_info_dropdown_id' => 10,
                'description' => 'Concrete tiles',
                'order' => 6,
                'other' => 0,
            ),
            34 =>
            array (
                'id' => 35,
                'property_info_dropdown_id' => 10,
                'description' => 'Composite panels',
                'order' => 7,
                'other' => 0,
            ),
            35 =>
            array (
                'id' => 36,
                'property_info_dropdown_id' => 10,
                'description' => 'Other',
                'order' => 8,
                'other' => 1,
            ),
            36 =>
            array (
                'id' => 37,
                'property_info_dropdown_id' => 11,
                'description' => 'Converted Property up to 3 storeys',
                'order' => 1,
                'other' => 0,
            ),
            37 =>
            array (
                'id' => 38,
                'property_info_dropdown_id' => 11,
                'description' => 'Converted Property 4-7 storeys',
                'order' => 2,
                'other' => 0,
            ),
            38 =>
            array (
                'id' => 39,
                'property_info_dropdown_id' => 11,
                'description' => 'Small Purpose Built Block',
                'order' => 3,
                'other' => 0,
            ),
            39 =>
            array (
                'id' => 40,
                'property_info_dropdown_id' => 11,
                'description' => 'Medium Purpose Built Block',
                'order' => 4,
                'other' => 0,
            ),
            40 =>
            array (
                'id' => 41,
                'property_info_dropdown_id' => 11,
                'description' => 'Large Purpose Built Block',
                'order' => 5,
                'other' => 0,
            ),
            41 =>
            array (
                'id' => 42,
                'property_info_dropdown_id' => 11,
                'description' => 'Medium High Rise Building',
                'order' => 6,
                'other' => 0,
            ),
            42 =>
            array (
                'id' => 43,
                'property_info_dropdown_id' => 7,
                'description' => 'Unknown',
                'order' => 2,
                'other' => 0,
            ),
            43 =>
            array (
                'id' => 44,
                'property_info_dropdown_id' => 8,
                'description' => 'Unknown',
                'order' => 2,
                'other' => 0,
            ),
            44 =>
            array (
                'id' => 45,
                'property_info_dropdown_id' => 9,
                'description' => 'Unknown',
                'order' => 3,
                'other' => 0,
            ),
            45 =>
            array (
                'id' => 46,
                'property_info_dropdown_id' => 10,
                'description' => 'Unknown',
                'order' => 7,
                'other' => 0,
            ),
            46 =>
                array (
                    'id' => 47,
                    'property_info_dropdown_id' => 6,
                    'description' => 'Mixed Evacuation Strategy',
                    'order' => 3,
                    'other' => 0,
                ),
            47 =>
                array (
                    'id' => 48,
                    'property_info_dropdown_id' => 5,
                    'description' => 'Trivial',
                    'order' => 1,
                    'other' => 0,
                ),
            48 =>
                array (
                    'id' => 49,
                    'property_info_dropdown_id' => 5,
                    'description' => 'Intolerable',
                    'order' => 5,
                    'other' => 0,
                ),
        ));


    }
}
