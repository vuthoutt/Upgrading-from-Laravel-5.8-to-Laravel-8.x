<?php

use Illuminate\Database\Seeder;

class CpEquipmentDropdownDataTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('cp_equipment_dropdown_data')->delete();

        \DB::table('cp_equipment_dropdown_data')->insert(array (
            0 =>
            array (
                'id' => 1,
                'dropdown_id' => 2,
                'description' => 'Redundant',
                'other' => 0,
                'parent_id' => 0,
                'order' => 5,
            ),
            1 =>
            array (
                'id' => 2,
                'dropdown_id' => 2,
                'description' => 'Infrequent',
                'other' => 0,
                'parent_id' => 0,
                'order' => 4,
            ),
            2 =>
            array (
                'id' => 3,
                'dropdown_id' => 2,
                'description' => 'Monthly',
                'other' => 0,
                'parent_id' => 0,
                'order' => 3,
            ),
            3 =>
            array (
                'id' => 4,
                'dropdown_id' => 2,
                'description' => 'Weekly',
                'other' => 0,
                'parent_id' => 0,
                'order' => 2,
            ),
            4 =>
            array (
                'id' => 5,
                'dropdown_id' => 2,
                'description' => 'Daily',
                'other' => 0,
                'parent_id' => 0,
                'order' => 1,
            ),
            5 =>
            array (
                'id' => 6,
                'dropdown_id' => 3,
                'description' => 'Duct Wrap',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            6 =>
            array (
                'id' => 7,
                'dropdown_id' => 3,
                'description' => 'Fibreglass Blanket',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            7 =>
            array (
                'id' => 8,
                'dropdown_id' => 3,
                'description' => 'Foil Backed Glass Wool',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            8 =>
            array (
                'id' => 9,
                'dropdown_id' => 3,
                'description' => 'Foil Backed Slabs',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            9 =>
            array (
                'id' => 10,
                'dropdown_id' => 3,
                'description' => 'Foil Backed Rock Wool',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            10 =>
            array (
                'id' => 11,
                'dropdown_id' => 3,
                'description' => 'Kingspan',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            11 =>
            array (
                'id' => 12,
                'dropdown_id' => 3,
                'description' => 'Mineral Wool Blanket',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            12 =>
            array (
                'id' => 13,
                'dropdown_id' => 3,
                'description' => 'Neoflam',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            13 =>
            array (
                'id' => 14,
                'dropdown_id' => 3,
                'description' => 'Spray-on Ceramic',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            14 =>
            array (
                'id' => 15,
                'dropdown_id' => 3,
                'description' => 'Spray-on Foam',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            15 =>
            array (
                'id' => 16,
                'dropdown_id' => 4,
                'description' => 'Prohibition Sign',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            16 =>
            array (
                'id' => 17,
                'dropdown_id' => 4,
                'description' => 'Warning Sign',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            17 =>
            array (
                'id' => 18,
                'dropdown_id' => 4,
                'description' => 'Mandatory Sign',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            18 =>
            array (
                'id' => 19,
                'dropdown_id' => 4,
                'description' => 'Safe Condition Sign',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            19 =>
            array (
                'id' => 20,
                'dropdown_id' => 4,
                'description' => 'Fire Equipment Sign',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            20 =>
            array (
                'id' => 21,
                'dropdown_id' => 4,
                'description' => 'Supplementary Information Sign',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            21 =>
            array (
                'id' => 22,
                'dropdown_id' => 5,
                'description' => 'Base',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            22 =>
            array (
                'id' => 23,
                'dropdown_id' => 5,
                'description' => 'Side',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            23 =>
            array (
                'id' => 24,
                'dropdown_id' => 5,
                'description' => 'Top',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            24 =>
            array (
                'id' => 25,
                'dropdown_id' => 6,
                'description' => 'Base',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            25 =>
            array (
                'id' => 26,
                'dropdown_id' => 6,
                'description' => 'Side',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            26 =>
            array (
                'id' => 27,
                'dropdown_id' => 6,
                'description' => 'Top',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            27 =>
            array (
                'id' => 28,
                'dropdown_id' => 7,
                'description' => 'Base',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            28 =>
            array (
                'id' => 29,
                'dropdown_id' => 7,
                'description' => 'Side',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            29 =>
            array (
                'id' => 30,
                'dropdown_id' => 7,
                'description' => 'Top',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            30 =>
            array (
                'id' => 31,
                'dropdown_id' => 8,
                'description' => 'Good',
                'other' => 0,
                'parent_id' => 0,
                'order' => 1,
            ),
            31 =>
            array (
                'id' => 32,
                'dropdown_id' => 8,
                'description' => 'Generally Satisfactory',
                'other' => 0,
                'parent_id' => 0,
                'order' => 2,
            ),
            32 =>
            array (
                'id' => 33,
                'dropdown_id' => 8,
                'description' => 'Improvement Necessary',
                'other' => 0,
                'parent_id' => 0,
                'order' => 3,
            ),
            33 =>
            array (
                'id' => 34,
                'dropdown_id' => 8,
                'description' => 'Major Improvement Necessary',
                'other' => 0,
                'parent_id' => 0,
                'order' => 4,
            ),
            34 =>
            array (
                'id' => 35,
                'dropdown_id' => 8,
                'description' => 'Urgent Improvement Necessary',
                'other' => 0,
                'parent_id' => 0,
                'order' => 5,
            ),
            35 =>
            array (
                'id' => 36,
                'dropdown_id' => 9,
                'description' => 'Relatively Easy',
                'other' => 0,
                'parent_id' => 0,
                'order' => 1,
            ),
            36 =>
            array (
                'id' => 37,
                'dropdown_id' => 9,
                'description' => 'Possible',
                'other' => 0,
                'parent_id' => 0,
                'order' => 2,
            ),
            37 =>
            array (
                'id' => 38,
                'dropdown_id' => 9,
                'description' => 'Moderately Difficult',
                'other' => 0,
                'parent_id' => 0,
                'order' => 3,
            ),
            38 =>
            array (
                'id' => 39,
                'dropdown_id' => 9,
                'description' => 'Very Difficult',
                'other' => 0,
                'parent_id' => 0,
                'order' => 4,
            ),
            39 =>
            array (
                'id' => 40,
                'dropdown_id' => 9,
                'description' => 'Nearly Impossible',
                'other' => 0,
                'parent_id' => 0,
                'order' => 5,
            ),
            40 =>
            array (
                'id' => 41,
                'dropdown_id' => 10,
                'description' => 'Direct',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            41 =>
            array (
                'id' => 42,
                'dropdown_id' => 10,
                'description' => 'Indirect',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            42 =>
            array (
                'id' => 43,
                'dropdown_id' => 1,
                'description' => 'Confined Space',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            43 =>
            array (
                'id' => 44,
                'dropdown_id' => 1,
                'description' => 'Denied Access',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            44 =>
            array (
                'id' => 45,
                'dropdown_id' => 1,
                'description' => 'Excessive Storage',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            45 =>
            array (
                'id' => 46,
                'dropdown_id' => 1,
                'description' => 'Extensive Waste',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            46 =>
            array (
                'id' => 47,
                'dropdown_id' => 1,
                'description' => 'No Keys',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            47 =>
            array (
                'id' => 48,
                'dropdown_id' => 1,
                'description' => 'Locked',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            48 =>
            array (
                'id' => 49,
                'dropdown_id' => 1,
                'description' => 'Height Restriction',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            49 =>
            array (
                'id' => 50,
                'dropdown_id' => 1,
                'description' => 'Restricted Access',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            50 =>
            array (
                'id' => 51,
                'dropdown_id' => 1,
                'description' => 'Room/location Occupied',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            51 =>
            array (
                'id' => 52,
                'dropdown_id' => 1,
                'description' => 'Structurally Unsafe',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            52 =>
            array (
                'id' => 53,
                'dropdown_id' => 1,
                'description' => 'Unsafe Access',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            53 =>
            array (
                'id' => 54,
                'dropdown_id' => 1,
                'description' => 'Other',
                'other' => 0,
                'parent_id' => 0,
                'order' => 1,
            ),
            54 =>
            array (
                'id' => 55,
                'dropdown_id' => 1,
                'description' => 'Fixed Ceiling Tiles',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            55 =>
            array (
                'id' => 56,
                'dropdown_id' => 1,
                'description' => 'Parquet Floor',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            56 =>
            array (
                'id' => 57,
                'dropdown_id' => 1,
                'description' => 'Sealed Floor',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            57 =>
            array (
                'id' => 58,
                'dropdown_id' => 1,
                'description' => 'Listed Decoration/feature',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            58 =>
            array (
                'id' => 59,
                'dropdown_id' => 1,
                'description' => 'Damage to Decoration',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            59 =>
            array (
                'id' => 60,
                'dropdown_id' => 1,
                'description' => 'Loft - No Hatch in Communal Area',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            60 =>
            array (
                'id' => 61,
                'dropdown_id' => 1,
                'description' => 'Loft - Access to Hatch Denied by Tenant',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            61 =>
            array (
                'id' => 69,
                'dropdown_id' => 11,
                'description' => 'Active',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            62 =>
            array (
                'id' => 70,
                'dropdown_id' => 11,
                'description' => 'Inactive',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            63 =>
            array (
                'id' => 71,
                'dropdown_id' => 12,
                'description' => 'Mains',
                'other' => 0,
                'parent_id' => 0,
                'order' => 2,
            ),
            64 =>
            array (
                'id' => 72,
                'dropdown_id' => 12,
                'description' => 'Rainwater Storage',
                'other' => 0,
                'parent_id' => 0,
                'order' => 3,
            ),
            65 =>
            array (
                'id' => 73,
                'dropdown_id' => 12,
                'description' => 'Borehole',
                'other' => 0,
                'parent_id' => 0,
                'order' => 4,
            ),
            66 =>
            array (
                'id' => 74,
                'dropdown_id' => 12,
                'description' => 'Intercluster Groundwater Flow',
                'other' => 0,
                'parent_id' => 0,
                'order' => 5,
            ),
            67 =>
            array (
                'id' => 75,
                'dropdown_id' => 12,
                'description' => 'Canal & River Extraction',
                'other' => 0,
                'parent_id' => 0,
                'order' => 6,
            ),
            68 =>
            array (
                'id' => 76,
                'dropdown_id' => 13,
                'description' => 'Enclosed System',
                'other' => 0,
                'parent_id' => 0,
                'order' => 1,
            ),
            69 =>
            array (
                'id' => 77,
                'dropdown_id' => 13,
                'description' => 'Open Indoors',
                'other' => 0,
                'parent_id' => 0,
                'order' => 2,
            ),
            70 =>
            array (
                'id' => 78,
                'dropdown_id' => 13,
                'description' => 'Open Outdoors',
                'other' => 0,
                'parent_id' => 0,
                'order' => 3,
            ),
            71 =>
            array (
                'id' => 79,
                'dropdown_id' => 13,
                'description' => 'Adjacent to or in Airstream',
                'other' => 0,
                'parent_id' => 0,
                'order' => 4,
            ),
            72 =>
            array (
                'id' => 80,
                'dropdown_id' => 14,
                'description' => 'Chemically Treated',
                'other' => 0,
                'parent_id' => 0,
                'order' => 1,
            ),
            73 =>
            array (
                'id' => 81,
                'dropdown_id' => 14,
                'description' => 'Clean',
                'other' => 0,
                'parent_id' => 0,
                'order' => 2,
            ),
            74 =>
            array (
                'id' => 82,
                'dropdown_id' => 14,
                'description' => 'Moderate Contamination',
                'other' => 0,
                'parent_id' => 0,
                'order' => 3,
            ),
            75 =>
            array (
                'id' => 83,
                'dropdown_id' => 14,
                'description' => 'Heavy Contamination',
                'other' => 0,
                'parent_id' => 0,
                'order' => 4,
            ),
            76 =>
            array (
                'id' => 84,
                'dropdown_id' => 15,
                'description' => 'Good',
                'other' => 0,
                'parent_id' => 0,
                'order' => 1,
            ),
            77 =>
            array (
                'id' => 85,
                'dropdown_id' => 15,
                'description' => 'Generally Satisfactory',
                'other' => 0,
                'parent_id' => 0,
                'order' => 2,
            ),
            78 =>
            array (
                'id' => 86,
                'dropdown_id' => 15,
                'description' => 'Improvement Necessary',
                'other' => 0,
                'parent_id' => 0,
                'order' => 3,
            ),
            79 =>
            array (
                'id' => 87,
                'dropdown_id' => 15,
                'description' => 'Major Improvement Necessary',
                'other' => 0,
                'parent_id' => 0,
                'order' => 4,
            ),
            80 =>
            array (
                'id' => 88,
                'dropdown_id' => 15,
                'description' => 'Urgent Improvement Necessary',
                'other' => 0,
                'parent_id' => 0,
                'order' => 5,
            ),
            81 =>
            array (
                'id' => 89,
                'dropdown_id' => 16,
            'description' => 'Slight (<3 Hours/Week)',
                'other' => 0,
                'parent_id' => 0,
                'order' => 1,
            ),
            82 =>
            array (
                'id' => 90,
                'dropdown_id' => 16,
            'description' => 'Moderate (3 to 30 Hours/Week)',
                'other' => 0,
                'parent_id' => 0,
                'order' => 2,
            ),
            83 =>
            array (
                'id' => 91,
                'dropdown_id' => 16,
            'description' => 'High (>30 Hours/Week)',
                'other' => 0,
                'parent_id' => 0,
                'order' => 3,
            ),
            84 =>
            array (
                'id' => 92,
                'dropdown_id' => 17,
                'description' => 'None',
                'other' => 0,
                'parent_id' => 0,
                'order' => 1,
            ),
            85 =>
            array (
                'id' => 93,
                'dropdown_id' => 17,
                'description' => 'Low',
                'other' => 0,
                'parent_id' => 0,
                'order' => 2,
            ),
            86 =>
            array (
                'id' => 94,
                'dropdown_id' => 17,
                'description' => 'Medium',
                'other' => 0,
                'parent_id' => 0,
                'order' => 3,
            ),
            87 =>
            array (
                'id' => 95,
                'dropdown_id' => 17,
                'description' => 'High',
                'other' => 0,
                'parent_id' => 0,
                'order' => 4,
            ),
            88 =>
            array (
                'id' => 96,
                'dropdown_id' => 17,
                'description' => 'Very High',
                'other' => 0,
                'parent_id' => 0,
                'order' => 5,
            ),
            89 =>
            array (
                'id' => 97,
                'dropdown_id' => 18,
                'description' => 'None',
                'other' => 0,
                'parent_id' => 0,
                'order' => 1,
            ),
            90 =>
            array (
                'id' => 98,
                'dropdown_id' => 18,
                'description' => 'Low',
                'other' => 0,
                'parent_id' => 0,
                'order' => 2,
            ),
            91 =>
            array (
                'id' => 99,
                'dropdown_id' => 18,
                'description' => 'Medium',
                'other' => 0,
                'parent_id' => 0,
                'order' => 3,
            ),
            92 =>
            array (
                'id' => 100,
                'dropdown_id' => 18,
                'description' => 'High',
                'other' => 0,
                'parent_id' => 0,
                'order' => 4,
            ),
            93 =>
            array (
                'id' => 101,
                'dropdown_id' => 18,
                'description' => 'Very High',
                'other' => 0,
                'parent_id' => 0,
                'order' => 5,
            ),
            94 =>
            array (
                'id' => 102,
                'dropdown_id' => 19,
                'description' => 'None',
                'other' => 0,
                'parent_id' => 0,
                'order' => 1,
            ),
            95 =>
            array (
                'id' => 103,
                'dropdown_id' => 19,
                'description' => 'Low',
                'other' => 0,
                'parent_id' => 0,
                'order' => 2,
            ),
            96 =>
            array (
                'id' => 104,
                'dropdown_id' => 19,
                'description' => 'Medium',
                'other' => 0,
                'parent_id' => 0,
                'order' => 3,
            ),
            97 =>
            array (
                'id' => 105,
                'dropdown_id' => 19,
                'description' => 'High',
                'other' => 0,
                'parent_id' => 0,
                'order' => 4,
            ),
            98 =>
            array (
                'id' => 106,
                'dropdown_id' => 19,
                'description' => 'Very High',
                'other' => 0,
                'parent_id' => 0,
                'order' => 5,
            ),
            99 =>
            array (
                'id' => 107,
                'dropdown_id' => 20,
                'description' => 'Adhesive-Non-Asbestos',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            100 =>
            array (
                'id' => 108,
                'dropdown_id' => 20,
                'description' => 'Aggregate',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            101 =>
            array (
                'id' => 109,
                'dropdown_id' => 20,
                'description' => 'Breezeblock',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            102 =>
            array (
                'id' => 110,
                'dropdown_id' => 20,
                'description' => 'Brick',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            103 =>
            array (
                'id' => 111,
                'dropdown_id' => 20,
                'description' => 'Cardboard',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            104 =>
            array (
                'id' => 112,
                'dropdown_id' => 20,
                'description' => 'Carpet',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            105 =>
            array (
                'id' => 113,
                'dropdown_id' => 20,
                'description' => 'Cement-Non-Asbestos',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            106 =>
            array (
                'id' => 114,
                'dropdown_id' => 20,
                'description' => 'Ceramic',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            107 =>
            array (
                'id' => 115,
                'dropdown_id' => 20,
                'description' => 'Chipboard',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            108 =>
            array (
                'id' => 116,
                'dropdown_id' => 20,
                'description' => 'Clay',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            109 =>
            array (
                'id' => 117,
                'dropdown_id' => 20,
                'description' => 'Composite',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            110 =>
            array (
                'id' => 118,
                'dropdown_id' => 20,
                'description' => 'Concrete',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            111 =>
            array (
                'id' => 119,
                'dropdown_id' => 20,
                'description' => 'Cork',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            112 =>
            array (
                'id' => 120,
                'dropdown_id' => 20,
                'description' => 'Earth',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            113 =>
            array (
                'id' => 121,
                'dropdown_id' => 20,
                'description' => 'Felt-Non-Asbestos',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            114 =>
            array (
                'id' => 122,
                'dropdown_id' => 20,
                'description' => 'Fibreboard',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            115 =>
            array (
                'id' => 123,
                'dropdown_id' => 20,
                'description' => 'Fibreglass',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            116 =>
            array (
                'id' => 124,
                'dropdown_id' => 20,
                'description' => 'Glass',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            117 =>
            array (
                'id' => 125,
                'dropdown_id' => 20,
                'description' => 'Hessian',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            118 =>
            array (
                'id' => 126,
                'dropdown_id' => 20,
                'description' => 'Lath and Plaster',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            119 =>
            array (
                'id' => 127,
                'dropdown_id' => 20,
                'description' => 'Marble',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            120 =>
            array (
                'id' => 128,
                'dropdown_id' => 20,
                'description' => 'MDF',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            121 =>
            array (
                'id' => 129,
                'dropdown_id' => 20,
                'description' => 'Metal',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            122 =>
            array (
                'id' => 130,
                'dropdown_id' => 20,
                'description' => 'MMMF',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            123 =>
            array (
                'id' => 131,
                'dropdown_id' => 20,
                'description' => 'Paper',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            124 =>
            array (
                'id' => 132,
                'dropdown_id' => 20,
                'description' => 'Plaster',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            125 =>
            array (
                'id' => 133,
                'dropdown_id' => 20,
                'description' => 'Plasterboard',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            126 =>
            array (
                'id' => 134,
                'dropdown_id' => 20,
                'description' => 'Plastic',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            127 =>
            array (
                'id' => 135,
                'dropdown_id' => 20,
                'description' => 'Polystyrene',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            128 =>
            array (
                'id' => 136,
                'dropdown_id' => 20,
                'description' => 'Quarry Tile',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            129 =>
            array (
                'id' => 137,
                'dropdown_id' => 20,
                'description' => 'RCF',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            130 =>
            array (
                'id' => 138,
                'dropdown_id' => 20,
                'description' => 'Render',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            131 =>
            array (
                'id' => 139,
                'dropdown_id' => 20,
                'description' => 'Roughcast',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            132 =>
            array (
                'id' => 140,
                'dropdown_id' => 20,
                'description' => 'Rubber',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            133 =>
            array (
                'id' => 141,
                'dropdown_id' => 20,
                'description' => 'Rubble',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            134 =>
            array (
                'id' => 142,
                'dropdown_id' => 20,
                'description' => 'Screed',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            135 =>
            array (
                'id' => 143,
                'dropdown_id' => 20,
                'description' => 'Silicon',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            136 =>
            array (
                'id' => 144,
                'dropdown_id' => 20,
                'description' => 'Slate',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            137 =>
            array (
                'id' => 145,
                'dropdown_id' => 20,
                'description' => 'Stone',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            138 =>
            array (
                'id' => 146,
                'dropdown_id' => 20,
                'description' => 'Strawboard',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            139 =>
            array (
                'id' => 147,
                'dropdown_id' => 20,
                'description' => 'Terazzo',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            140 =>
            array (
                'id' => 148,
                'dropdown_id' => 20,
                'description' => 'Timber',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            141 =>
            array (
                'id' => 149,
                'dropdown_id' => 20,
                'description' => 'Vinyl-Non-Asbestos',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            142 =>
            array (
                'id' => 150,
                'dropdown_id' => 20,
                'description' => 'Woodchip Paper',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            143 =>
            array (
                'id' => 151,
                'dropdown_id' => 20,
                'description' => 'Other',
                'other' => 1,
                'parent_id' => 0,
                'order' => 0,
            ),
            144 =>
            array (
                'id' => 152,
                'dropdown_id' => 20,
                'description' => 'Floor Tiles',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            145 =>
            array (
                'id' => 153,
                'dropdown_id' => 20,
                'description' => 'Vinyl',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            146 =>
            array (
                'id' => 154,
                'dropdown_id' => 20,
                'description' => 'Flooring',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            147 =>
            array (
                'id' => 155,
                'dropdown_id' => 20,
                'description' => 'Adhesive',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            148 =>
            array (
                'id' => 156,
                'dropdown_id' => 20,
                'description' => 'Floor & Adhesive',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            149 =>
            array (
                'id' => 157,
                'dropdown_id' => 20,
                'description' => 'Floor Tiles',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            150 =>
            array (
                'id' => 158,
                'dropdown_id' => 20,
                'description' => 'Vinyl Flooring',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            151 =>
            array (
                'id' => 159,
                'dropdown_id' => 20,
                'description' => 'Carpet Tiles',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            152 =>
            array (
                'id' => 160,
                'dropdown_id' => 20,
                'description' => 'Out of Scope',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            153 =>
            array (
                'id' => 161,
                'dropdown_id' => 1,
                'description' => 'Loft - Excessive Storage Preventing Access',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            154 =>
            array (
                'id' => 162,
                'dropdown_id' => 1,
                'description' => 'Loft - Excessive Waste Preventing Access',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            155 =>
            array (
                'id' => 163,
                'dropdown_id' => 1,
                'description' => 'Loft - Unable to Erect Ladder beneath Hatch',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            156 =>
            array (
                'id' => 164,
                'dropdown_id' => 1,
                'description' => 'Loft - Issue with Integrated Loft Ladder',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            157 =>
            array (
                'id' => 165,
                'dropdown_id' => 1,
                'description' => 'Loft - Locked and Unable to Open with Keys',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            158 =>
            array (
                'id' => 166,
                'dropdown_id' => 1,
            'description' => 'Loft - Painted Over (Risk of Decoration Damage)',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            159 =>
            array (
                'id' => 167,
                'dropdown_id' => 1,
            'description' => 'Loft - Screwed Shut (Risk of Decoration Damage)',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            160 =>
            array (
                'id' => 168,
                'dropdown_id' => 1,
            'description' => 'Loft - No Crawl Boards (Unsafe Access)',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            161 =>
            array (
                'id' => 169,
                'dropdown_id' => 1,
                'description' => 'Loft - Hatch above Single Person Safe Height',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            162 =>
            array (
                'id' => 170,
                'dropdown_id' => 1,
                'description' => 'Loft - Hatch above Stairwell',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            163 =>
            array (
                'id' => 171,
                'dropdown_id' => 1,
                'description' => 'Cupboard/voids - Locked and Unable to Open with Keys',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            164 =>
            array (
                'id' => 172,
                'dropdown_id' => 1,
                'description' => 'Cupboard/voids - Excessive Storage Preventing Access',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            165 =>
            array (
                'id' => 173,
                'dropdown_id' => 1,
                'description' => 'Cupboard/voids - Excessive Waste Preventing Access',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            166 =>
            array (
                'id' => 174,
                'dropdown_id' => 1,
            'description' => 'Cupboard/voids - Painted Over (Risk of Decoration Damage)',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            167 =>
            array (
                'id' => 175,
                'dropdown_id' => 1,
            'description' => 'Cupboard/voids - Screwed Shut (Risk of Decoration Damage)',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            168 =>
            array (
                'id' => 176,
                'dropdown_id' => 1,
                'description' => 'Cupboard/voids - Contents/equipment Preventing Access',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            169 =>
            array (
                'id' => 177,
                'dropdown_id' => 1,
                'description' => 'Cupboard/voids - Door Jammed Shut',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            170 =>
            array (
                'id' => 178,
                'dropdown_id' => 1,
            'description' => 'Externals (Garden/side) - Access via Tenants Dwelling',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            171 =>
            array (
                'id' => 179,
                'dropdown_id' => 1,
            'description' => 'Externals (Garden/side) - Unable to Access Locked Door/Gate with Issued Keys',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            172 =>
            array (
                'id' => 180,
                'dropdown_id' => 1,
            'description' => 'Externals (Garden/side) - Access Denied by Tenant',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            173 =>
            array (
                'id' => 181,
                'dropdown_id' => 1,
            'description' => 'Externals (Garden/side) - Excessive Storage Preventing Access',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            174 =>
            array (
                'id' => 182,
                'dropdown_id' => 1,
            'description' => 'Externals (Garden/side) - Excessive Waste Preventing Access',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            175 =>
            array (
                'id' => 183,
                'dropdown_id' => 1,
            'description' => 'Externals (Garden/side) - Animal(s) within Garden Preventing Access',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            176 =>
            array (
                'id' => 184,
                'dropdown_id' => 1,
            'description' => 'Externals (Garden/side) - Uneven/unsafe Ground Preventing Access',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            177 =>
            array (
                'id' => 185,
                'dropdown_id' => 1,
            'description' => 'Externals (Garden/side) - Above Single Person Safe Height',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            178 =>
            array (
                'id' => 186,
                'dropdown_id' => 1,
                'description' => 'Room/location - Unable to Access Locked Door/Gate with Issued Keys',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            179 =>
            array (
                'id' => 187,
                'dropdown_id' => 1,
                'description' => 'Room/location - Access Denied by Tenant',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            180 =>
            array (
                'id' => 188,
                'dropdown_id' => 1,
                'description' => 'Room/location - Excessive Storage Preventing Access',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            181 =>
            array (
                'id' => 189,
                'dropdown_id' => 1,
                'description' => 'Room/location - Excessive Waste Preventing Access',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            182 =>
            array (
                'id' => 190,
                'dropdown_id' => 1,
                'description' => 'Rooms/location - Above Single Person Safe Height',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            183 =>
            array (
                'id' => 191,
                'dropdown_id' => 1,
            'description' => 'Room/location - Painted Over (Risk of Decoration Damage)',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            184 =>
            array (
                'id' => 192,
                'dropdown_id' => 1,
            'description' => 'Room/location - Screwed Shut (Risk of Decoration Damage)',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            185 =>
            array (
                'id' => 193,
                'dropdown_id' => 1,
                'description' => 'Room/location - Uneven/unsafe Ground Preventing Access',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            186 =>
            array (
                'id' => 194,
                'dropdown_id' => 1,
            'description' => 'Room/location - Requires Specially Trained Operative/engineer (Lift Pit)',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            187 =>
            array (
                'id' => 195,
                'dropdown_id' => 1,
                'description' => 'Room/location - Limited Access',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            188 =>
            array (
                'id' => 196,
                'dropdown_id' => 21,
                'description' => 'None',
                'other' => 0,
                'parent_id' => 0,
                'order' => 1,
            ),
            189 =>
            array (
                'id' => 197,
                'dropdown_id' => 21,
                'description' => 'Low',
                'other' => 0,
                'parent_id' => 0,
                'order' => 2,
            ),
            190 =>
            array (
                'id' => 198,
                'dropdown_id' => 21,
                'description' => 'Medium',
                'other' => 0,
                'parent_id' => 0,
                'order' => 3,
            ),
            191 =>
            array (
                'id' => 199,
                'dropdown_id' => 21,
                'description' => 'High',
                'other' => 0,
                'parent_id' => 0,
                'order' => 4,
            ),
            192 =>
            array (
                'id' => 200,
                'dropdown_id' => 21,
                'description' => 'Very High',
                'other' => 0,
                'parent_id' => 0,
                'order' => 5,
            ),
            193 =>
            array (
                'id' => 201,
                'dropdown_id' => 22,
                'description' => 'Good',
                'other' => 0,
                'parent_id' => 0,
                'order' => 1,
            ),
            194 =>
            array (
                'id' => 202,
                'dropdown_id' => 22,
                'description' => 'Generally Satisfactory',
                'other' => 0,
                'parent_id' => 0,
                'order' => 2,
            ),
            195 =>
            array (
                'id' => 203,
                'dropdown_id' => 22,
                'description' => 'Improvement Necessary',
                'other' => 0,
                'parent_id' => 0,
                'order' => 3,
            ),
            196 =>
            array (
                'id' => 204,
                'dropdown_id' => 22,
                'description' => 'Major Improvement Necessary',
                'other' => 0,
                'parent_id' => 0,
                'order' => 4,
            ),
            197 =>
            array (
                'id' => 205,
                'dropdown_id' => 22,
                'description' => 'Urgent Improvement Necessary',
                'other' => 0,
                'parent_id' => 0,
                'order' => 5,
            ),
            198 =>
            array (
                'id' => 206,
                'dropdown_id' => 23,
                'description' => 'None',
                'other' => 0,
                'parent_id' => 0,
                'order' => 1,
            ),
            199 =>
            array (
                'id' => 207,
                'dropdown_id' => 23,
                'description' => 'Low',
                'other' => 0,
                'parent_id' => 0,
                'order' => 2,
            ),
            200 =>
            array (
                'id' => 208,
                'dropdown_id' => 23,
                'description' => 'Medium',
                'other' => 0,
                'parent_id' => 0,
                'order' => 3,
            ),
            201 =>
            array (
                'id' => 209,
                'dropdown_id' => 23,
                'description' => 'High',
                'other' => 0,
                'parent_id' => 0,
                'order' => 4,
            ),
            202 =>
            array (
                'id' => 210,
                'dropdown_id' => 23,
                'description' => 'Very High',
                'other' => 0,
                'parent_id' => 0,
                'order' => 5,
            ),
            203 =>
            array (
                'id' => 211,
                'dropdown_id' => 24,
                'description' => 'Duct Wrap',
                'other' => 0,
                'parent_id' => 0,
                'order' => 1,
            ),
            204 =>
            array (
                'id' => 212,
                'dropdown_id' => 24,
                'description' => 'Fibreglass Blanket',
                'other' => 0,
                'parent_id' => 0,
                'order' => 2,
            ),
            205 =>
            array (
                'id' => 213,
                'dropdown_id' => 24,
                'description' => 'Foil Backed Glass Wool',
                'other' => 0,
                'parent_id' => 0,
                'order' => 3,
            ),
            206 =>
            array (
                'id' => 214,
                'dropdown_id' => 24,
                'description' => 'Foil Backed Slabs',
                'other' => 0,
                'parent_id' => 0,
                'order' => 4,
            ),
            207 =>
            array (
                'id' => 215,
                'dropdown_id' => 24,
                'description' => 'Foil Backed Rock Wool',
                'other' => 0,
                'parent_id' => 0,
                'order' => 5,
            ),
            208 =>
            array (
                'id' => 216,
                'dropdown_id' => 24,
                'description' => 'Kingspan',
                'other' => 0,
                'parent_id' => 0,
                'order' => 6,
            ),
            209 =>
            array (
                'id' => 217,
                'dropdown_id' => 24,
                'description' => 'Mineral Wool Blanket',
                'other' => 0,
                'parent_id' => 0,
                'order' => 7,
            ),
            210 =>
            array (
                'id' => 218,
                'dropdown_id' => 24,
                'description' => 'Neoflam',
                'other' => 0,
                'parent_id' => 0,
                'order' => 8,
            ),
            211 =>
            array (
                'id' => 219,
                'dropdown_id' => 24,
                'description' => 'Spray-on Ceramic',
                'other' => 0,
                'parent_id' => 0,
                'order' => 9,
            ),
            212 =>
            array (
                'id' => 220,
                'dropdown_id' => 24,
                'description' => 'Spray-on Foam',
                'other' => 0,
                'parent_id' => 0,
                'order' => 10,
            ),
            213 =>
            array (
                'id' => 221,
                'dropdown_id' => 3,
                'description' => 'None',
                'other' => 0,
                'parent_id' => 0,
                'order' => 0,
            ),
            214 =>
            array (
                'id' => 222,
                'dropdown_id' => 24,
                'description' => 'None',
                'other' => 0,
                'parent_id' => 0,
                'order' => 11,
            ),
            215 =>
            array (
                'id' => 223,
                'dropdown_id' => 12,
                'description' => 'Domestic Storage',
                'other' => 0,
                'parent_id' => 0,
                'order' => 1,
            ),
            216 =>
            array (
                'id' => 224,
                'dropdown_id' => 25,
                'description' => 'N/A',
                'other' => 0,
                'parent_id' => 0,
                'order' => 1,
            ),
            217 =>
            array (
                'id' => 225,
                'dropdown_id' => 25,
                'description' => 'Nearest',
                'other' => 0,
                'parent_id' => 0,
                'order' => 2,
            ),
            218 =>
            array (
                'id' => 226,
                'dropdown_id' => 25,
                'description' => 'Furthest',
                'other' => 0,
                'parent_id' => 0,
                'order' => 3,
            ),
            220 =>
                array (
                    'id' => 228,
                    'dropdown_id' => 26,
                    'description' => 'Horizontal',
                    'other' => 0,
                    'parent_id' => 0,
                    'order' => 1,
                ),
            221 =>
                array (
                    'id' => 229,
                    'dropdown_id' => 26,
                    'description' => 'Vertical',
                    'other' => 0,
                    'parent_id' => 0,
                    'order' => 1,
                ),
            222 =>
                array (
                    'id' => 240,
                    'dropdown_id' => 27,
                    'description' => 'Chlorinated Polyvinyl Chloride (CPVC)',
                    'other' => 0,
                    'parent_id' => 0,
                    'order' => 1,
                ),
            223 =>
                array (
                    'id' => 241,
                    'dropdown_id' => 27,
                    'description' => 'Copper',
                    'other' => 0,
                    'parent_id' => 0,
                    'order' => 1,
                ),
            224 =>
                array (
                    'id' => 242,
                    'dropdown_id' => 27,
                    'description' => 'Cross-linked Polyethylene (PEX)',
                    'other' => 0,
                    'parent_id' => 0,
                    'order' => 1,
                ),
            225 =>
                array (
                    'id' => 243,
                    'dropdown_id' => 27,
                    'description' => 'Galvanised Steel',
                    'other' => 0,
                    'parent_id' => 0,
                    'order' => 1,
                ),
            226 =>
                array (
                    'id' => 244,
                    'dropdown_id' => 27,
                    'description' => 'High-density Polyethylene (HDPE)',
                    'other' => 0,
                    'parent_id' => 0,
                    'order' => 1,
                ),
            227 =>
                array (
                    'id' => 245,
                    'dropdown_id' => 27,
                    'description' => 'Lead',
                    'other' => 0,
                    'parent_id' => 0,
                    'order' => 1,
                ),
            228 =>
                array (
                    'id' => 246,
                    'dropdown_id' => 27,
                    'description' => 'Medium-density Polyethylene (MPDE)',
                    'other' => 0,
                    'parent_id' => 0,
                    'order' => 1,
                ),
            229 =>
                array (
                    'id' => 247,
                    'dropdown_id' => 27,
                    'description' => 'Plastic',
                    'other' => 0,
                    'parent_id' => 0,
                    'order' => 1,
                ),
            230 =>
                array (
                    'id' => 248,
                    'dropdown_id' => 27,
                    'description' => 'Polyvinyl Chloride (PVC)',
                    'other' => 0,
                    'parent_id' => 0,
                    'order' => 1,
                ),
            231 =>
                array (
                    'id' => 249,
                    'dropdown_id' => 27,
                    'description' => 'Other',
                    'other' => 1,
                    'parent_id' => 0,
                    'order' => 2,
                ),
        ));


    }
}
