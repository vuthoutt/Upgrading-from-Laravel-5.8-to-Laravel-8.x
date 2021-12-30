<?php

use Illuminate\Database\Seeder;

class CpHazardTypeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('cp_hazard_type')->delete();
        
        \DB::table('cp_hazard_type')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => 'Aerosol Risk',
                'type' => 4,
                'parent_id' => 0,
            ),
            1 => 
            array (
                'id' => 2,
                'description' => 'Air Vent',
                'type' => 4,
                'parent_id' => 0,
            ),
            2 => 
            array (
                'id' => 3,
                'description' => 'Anti-stratification Pump Fitted',
                'type' => 4,
                'parent_id' => 0,
            ),
            3 => 
            array (
                'id' => 4,
                'description' => 'Arrangements & Policies',
                'type' => 4,
                'parent_id' => 0,
            ),
            4 => 
            array (
                'id' => 5,
                'description' => 'Arson',
                'type' => 4,
                'parent_id' => 0,
            ),
            5 => 
            array (
                'id' => 6,
                'description' => 'Botton Temperature',
                'type' => 4,
                'parent_id' => 0,
            ),
            6 => 
            array (
                'id' => 7,
                'description' => 'Can it be Isolated',
                'type' => 4,
                'parent_id' => 0,
            ),
            7 => 
            array (
                'id' => 8,
                'description' => 'Cooking',
                'type' => 4,
                'parent_id' => 0,
            ),
            8 => 
            array (
                'id' => 9,
                'description' => 'Dangerous Substances',
                'type' => 4,
                'parent_id' => 0,
            ),
            9 => 
            array (
                'id' => 10,
                'description' => 'Degree of Biological Slime',
                'type' => 4,
                'parent_id' => 0,
            ),
            10 => 
            array (
                'id' => 11,
                'description' => 'Degree of Fouling',
                'type' => 4,
                'parent_id' => 0,
            ),
            11 => 
            array (
                'id' => 12,
                'description' => 'Direct/Indirect Fired',
                'type' => 4,
                'parent_id' => 0,
            ),
            12 => 
            array (
                'id' => 13,
                'description' => 'Drain Valve',
                'type' => 4,
                'parent_id' => 0,
            ),
            13 => 
            array (
                'id' => 14,
                'description' => 'Electrical Hazard',
                'type' => 4,
                'parent_id' => 0,
            ),
            14 => 
            array (
                'id' => 15,
                'description' => 'Equipment Appropriateness',
                'type' => 4,
                'parent_id' => 0,
            ),
            15 => 
            array (
                'id' => 16,
                'description' => 'Equipment Cleanliness',
                'type' => 4,
                'parent_id' => 0,
            ),
            16 => 
            array (
                'id' => 17,
                'description' => 'Equipment Design',
                'type' => 4,
                'parent_id' => 0,
            ),
            17 => 
            array (
                'id' => 18,
                'description' => 'Equipment Missing',
                'type' => 4,
                'parent_id' => 0,
            ),
            18 => 
            array (
                'id' => 19,
                'description' => 'Equipment Usage',
                'type' => 4,
                'parent_id' => 0,
            ),
            19 => 
            array (
                'id' => 20,
                'description' => 'Evidence of Stagnation',
                'type' => 4,
                'parent_id' => 0,
            ),
            20 => 
            array (
                'id' => 21,
                'description' => 'Extent of Corrosion',
                'type' => 4,
                'parent_id' => 0,
            ),
            21 => 
            array (
                'id' => 22,
                'description' => 'Flow Temperature',
                'type' => 4,
                'parent_id' => 0,
            ),
            22 => 
            array (
                'id' => 23,
                'description' => 'Flow Temperature Gauge',
                'type' => 4,
                'parent_id' => 0,
            ),
            23 => 
            array (
                'id' => 24,
                'description' => 'Frequency of Use',
                'type' => 4,
                'parent_id' => 0,
            ),
            24 => 
            array (
                'id' => 25,
                'description' => 'Heating Installations',
                'type' => 4,
                'parent_id' => 0,
            ),
            25 => 
            array (
                'id' => 26,
                'description' => 'Housekeeping',
                'type' => 4,
                'parent_id' => 0,
            ),
            26 => 
            array (
                'id' => 27,
                'description' => 'Inaccessible Area/floor',
                'type' => 4,
                'parent_id' => 0,
            ),
            27 => 
            array (
                'id' => 28,
                'description' => 'Inaccessible Equipment',
                'type' => 4,
                'parent_id' => 0,
            ),
            28 => 
            array (
                'id' => 29,
                'description' => 'Inaccessible Room/location',
                'type' => 4,
                'parent_id' => 0,
            ),
            29 => 
            array (
                'id' => 30,
                'description' => 'Inaccessible Void',
                'type' => 4,
                'parent_id' => 0,
            ),
            30 => 
            array (
                'id' => 31,
                'description' => 'Inlet Temperature',
                'type' => 4,
                'parent_id' => 0,
            ),
            31 => 
            array (
                'id' => 32,
                'description' => 'Insulation Condition',
                'type' => 4,
                'parent_id' => 0,
            ),
            32 => 
            array (
                'id' => 33,
                'description' => 'Insulation Thickness',
                'type' => 4,
                'parent_id' => 0,
            ),
            33 => 
            array (
                'id' => 34,
                'description' => 'Insulation Type',
                'type' => 4,
                'parent_id' => 0,
            ),
            34 => 
            array (
                'id' => 35,
                'description' => 'Introduced by Outside Contractor',
                'type' => 4,
                'parent_id' => 0,
            ),
            35 => 
            array (
                'id' => 36,
                'description' => 'Lighting',
                'type' => 4,
                'parent_id' => 0,
            ),
            36 => 
            array (
                'id' => 37,
                'description' => 'Main Access Hatch',
                'type' => 4,
                'parent_id' => 0,
            ),
            37 => 
            array (
                'id' => 38,
                'description' => 'Miscellaneous',
                'type' => 4,
                'parent_id' => 0,
            ),
            38 => 
            array (
                'id' => 39,
                'description' => 'Nominated Personnel',
                'type' => 4,
                'parent_id' => 0,
            ),
            39 => 
            array (
                'id' => 40,
                'description' => 'Operational Use',
                'type' => 4,
                'parent_id' => 0,
            ),
            40 => 
            array (
                'id' => 41,
                'description' => 'Other Significant Hazard',
                'type' => 4,
                'parent_id' => 0,
            ),
            41 => 
            array (
                'id' => 42,
                'description' => 'Outstanding Remedial Actions',
                'type' => 4,
                'parent_id' => 0,
            ),
            42 => 
            array (
                'id' => 43,
                'description' => 'PH',
                'type' => 4,
                'parent_id' => 0,
            ),
            43 => 
            array (
                'id' => 44,
                'description' => 'Pipe Insulation',
                'type' => 4,
                'parent_id' => 0,
            ),
            44 => 
            array (
                'id' => 45,
                'description' => 'Pipe Insulation Condition',
                'type' => 4,
                'parent_id' => 0,
            ),
            45 => 
            array (
                'id' => 46,
                'description' => 'Return Temperature',
                'type' => 4,
                'parent_id' => 0,
            ),
            46 => 
            array (
                'id' => 47,
                'description' => 'Return Temperature Gauge',
                'type' => 4,
                'parent_id' => 0,
            ),
            47 => 
            array (
                'id' => 48,
                'description' => 'Rodent Protection',
                'type' => 4,
                'parent_id' => 0,
            ),
            48 => 
            array (
                'id' => 49,
                'description' => 'Separate Ball Valve Hatch',
                'type' => 4,
                'parent_id' => 0,
            ),
            49 => 
            array (
                'id' => 50,
                'description' => 'Signs and Notices',
                'type' => 4,
                'parent_id' => 0,
            ),
            50 => 
            array (
                'id' => 51,
                'description' => 'Smoking',
                'type' => 4,
                'parent_id' => 0,
            ),
            51 => 
            array (
                'id' => 52,
                'description' => 'Source Accessibility',
                'type' => 4,
                'parent_id' => 0,
            ),
            52 => 
            array (
                'id' => 53,
                'description' => 'Source Condition',
                'type' => 4,
                'parent_id' => 0,
            ),
            53 => 
            array (
                'id' => 54,
                'description' => 'Storage Temperature',
                'type' => 4,
                'parent_id' => 0,
            ),
            54 => 
            array (
                'id' => 55,
                'description' => 'Stored Temperature',
                'type' => 4,
                'parent_id' => 0,
            ),
            55 => 
            array (
                'id' => 56,
                'description' => 'Screened Lid Vent',
                'type' => 4,
                'parent_id' => 0,
            ),
            56 => 
            array (
                'id' => 57,
                'description' => 'Testing and Periodic Servicing',
                'type' => 4,
                'parent_id' => 0,
            ),
            57 => 
            array (
                'id' => 58,
                'description' => 'Top Temperature',
                'type' => 4,
                'parent_id' => 0,
            ),
            58 => 
            array (
                'id' => 59,
                'description' => 'Training',
                'type' => 4,
                'parent_id' => 0,
            ),
            59 => 
            array (
                'id' => 60,
            'description' => 'Training (Refresher)',
                'type' => 4,
                'parent_id' => 0,
            ),
            60 => 
            array (
                'id' => 61,
                'description' => 'Warning Overflow',
                'type' => 4,
                'parent_id' => 0,
            ),
            61 => 
            array (
                'id' => 62,
                'description' => 'Pipework Insulation Condition',
                'type' => 4,
                'parent_id' => 0,
            ),
            62 => 
            array (
                'id' => 63,
                'description' => 'Bottom Temperature',
                'type' => 4,
                'parent_id' => 0,
            ),
            63 => 
            array (
                'id' => 64,
                'description' => 'Overflow Pipe',
                'type' => 4,
                'parent_id' => 0,
            ),
            64 => 
            array (
                'id' => 65,
                'description' => 'Backflow Protection',
                'type' => 4,
                'parent_id' => 0,
            ),
            65 => 
            array (
                'id' => 66,
                'description' => 'Inaccessible Assembly Points',
                'type' => 4,
                'parent_id' => 0,
            ),
            66 => 
            array (
                'id' => 67,
                'description' => 'Inaccessible Fire Exits',
                'type' => 4,
                'parent_id' => 0,
            ),
            67 => 
            array (
                'id' => 68,
                'description' => 'Inaccessible Vehicle Parking',
                'type' => 4,
                'parent_id' => 0,
            ),
            68 => 
            array (
                'id' => 69,
            'description' => 'Dead End (<6 Pipe Diameter)',
                'type' => 4,
                'parent_id' => 0,
            ),
            69 => 
            array (
                'id' => 70,
            'description' => 'Dead End (6+ Pipe Diameter)',
                'type' => 4,
                'parent_id' => 0,
            ),
            70 => 
            array (
                'id' => 71,
                'description' => 'Dead Leg',
                'type' => 4,
                'parent_id' => 0,
            ),
            71 => 
            array (
                'id' => 72,
                'description' => 'Pipework Condition',
                'type' => 4,
                'parent_id' => 0,
            ),
            72 => 
            array (
                'id' => 73,
                'description' => 'Incoming Mains Pipework Temperature',
                'type' => 4,
                'parent_id' => 0,
            ),
            73 => 
            array (
                'id' => 74,
                'description' => 'Hot Flow Temperature',
                'type' => 4,
                'parent_id' => 0,
            ),
            74 => 
            array (
                'id' => 75,
                'description' => 'Cold Flow Temperature',
                'type' => 4,
                'parent_id' => 0,
            ),
            75 => 
            array (
                'id' => 76,
                'description' => 'Pre-TMV Cold Flow Temperature',
                'type' => 4,
                'parent_id' => 0,
            ),
            76 => 
            array (
                'id' => 77,
                'description' => 'Pre-TMV Hot Flow Temperature',
                'type' => 4,
                'parent_id' => 0,
            ),
            77 => 
            array (
                'id' => 78,
                'description' => 'Post-TMV Temperature',
                'type' => 4,
                'parent_id' => 0,
            ),
            78 => 
            array (
                'id' => 79,
                'description' => 'Additional Survey Required',
                'type' => 2,
                'parent_id' => 0,
            ),
            79 => 
            array (
                'id' => 80,
                'description' => 'Building Management',
                'type' => 2,
                'parent_id' => 0,
            ),
            80 => 
            array (
                'id' => 81,
                'description' => 'Component',
                'type' => 2,
                'parent_id' => 0,
            ),
            81 => 
            array (
                'id' => 82,
                'description' => 'Fire Door',
                'type' => 2,
                'parent_id' => 0,
            ),
            82 => 
            array (
                'id' => 83,
                'description' => 'Fire Safety Management',
                'type' => 2,
                'parent_id' => 0,
            ),
            83 => 
            array (
                'id' => 84,
                'description' => 'Fire Stopping',
                'type' => 2,
                'parent_id' => 0,
            ),
            84 => 
            array (
                'id' => 153,
                'description' => 'Aerosol Risk',
                'type' => 5,
                'parent_id' => 0,
            ),
            85 => 
            array (
                'id' => 154,
                'description' => 'Air Vent',
                'type' => 5,
                'parent_id' => 0,
            ),
            86 => 
            array (
                'id' => 155,
                'description' => 'Anti-stratification Pump Fitted',
                'type' => 5,
                'parent_id' => 0,
            ),
            87 => 
            array (
                'id' => 156,
                'description' => 'Arrangements & Policies',
                'type' => 5,
                'parent_id' => 0,
            ),
            88 => 
            array (
                'id' => 157,
                'description' => 'Arson',
                'type' => 5,
                'parent_id' => 0,
            ),
            89 => 
            array (
                'id' => 158,
                'description' => 'Botton Temperature',
                'type' => 5,
                'parent_id' => 0,
            ),
            90 => 
            array (
                'id' => 159,
                'description' => 'Can it be Isolated',
                'type' => 5,
                'parent_id' => 0,
            ),
            91 => 
            array (
                'id' => 160,
                'description' => 'Cooking',
                'type' => 5,
                'parent_id' => 0,
            ),
            92 => 
            array (
                'id' => 161,
                'description' => 'Dangerous Substances',
                'type' => 5,
                'parent_id' => 0,
            ),
            93 => 
            array (
                'id' => 162,
                'description' => 'Degree of Biological Slime',
                'type' => 5,
                'parent_id' => 0,
            ),
            94 => 
            array (
                'id' => 163,
                'description' => 'Degree of Fouling',
                'type' => 5,
                'parent_id' => 0,
            ),
            95 => 
            array (
                'id' => 164,
                'description' => 'Direct/Indirect Fired',
                'type' => 5,
                'parent_id' => 0,
            ),
            96 => 
            array (
                'id' => 165,
                'description' => 'Drain Valve',
                'type' => 5,
                'parent_id' => 0,
            ),
            97 => 
            array (
                'id' => 166,
                'description' => 'Electrical Hazard',
                'type' => 5,
                'parent_id' => 0,
            ),
            98 => 
            array (
                'id' => 167,
                'description' => 'Equipment Appropriateness',
                'type' => 5,
                'parent_id' => 0,
            ),
            99 => 
            array (
                'id' => 168,
                'description' => 'Equipment Cleanliness',
                'type' => 5,
                'parent_id' => 0,
            ),
            100 => 
            array (
                'id' => 169,
                'description' => 'Equipment Design',
                'type' => 5,
                'parent_id' => 0,
            ),
            101 => 
            array (
                'id' => 170,
                'description' => 'Equipment Missing',
                'type' => 5,
                'parent_id' => 0,
            ),
            102 => 
            array (
                'id' => 171,
                'description' => 'Equipment Usage',
                'type' => 5,
                'parent_id' => 0,
            ),
            103 => 
            array (
                'id' => 172,
                'description' => 'Evidence of Stagnation',
                'type' => 5,
                'parent_id' => 0,
            ),
            104 => 
            array (
                'id' => 173,
                'description' => 'Extent of Corrosion',
                'type' => 5,
                'parent_id' => 0,
            ),
            105 => 
            array (
                'id' => 174,
                'description' => 'Flow Temperature',
                'type' => 5,
                'parent_id' => 0,
            ),
            106 => 
            array (
                'id' => 175,
                'description' => 'Flow Temperature Gauge',
                'type' => 5,
                'parent_id' => 0,
            ),
            107 => 
            array (
                'id' => 176,
                'description' => 'Frequency of Use',
                'type' => 5,
                'parent_id' => 0,
            ),
            108 => 
            array (
                'id' => 177,
                'description' => 'Heating Installations',
                'type' => 5,
                'parent_id' => 0,
            ),
            109 => 
            array (
                'id' => 178,
                'description' => 'Housekeeping',
                'type' => 5,
                'parent_id' => 0,
            ),
            110 => 
            array (
                'id' => 179,
                'description' => 'Inaccessible Area/floor',
                'type' => 5,
                'parent_id' => 0,
            ),
            111 => 
            array (
                'id' => 180,
                'description' => 'Inaccessible Equipment',
                'type' => 5,
                'parent_id' => 0,
            ),
            112 => 
            array (
                'id' => 181,
                'description' => 'Inaccessible Room/location',
                'type' => 5,
                'parent_id' => 0,
            ),
            113 => 
            array (
                'id' => 182,
                'description' => 'Inaccessible Void',
                'type' => 5,
                'parent_id' => 0,
            ),
            114 => 
            array (
                'id' => 183,
                'description' => 'Inlet Temperature',
                'type' => 5,
                'parent_id' => 0,
            ),
            115 => 
            array (
                'id' => 184,
                'description' => 'Insulation Condition',
                'type' => 5,
                'parent_id' => 0,
            ),
            116 => 
            array (
                'id' => 185,
                'description' => 'Insulation Thickness',
                'type' => 5,
                'parent_id' => 0,
            ),
            117 => 
            array (
                'id' => 186,
                'description' => 'Insulation Type',
                'type' => 5,
                'parent_id' => 0,
            ),
            118 => 
            array (
                'id' => 187,
                'description' => 'Introduced by Outside Contractor',
                'type' => 5,
                'parent_id' => 0,
            ),
            119 => 
            array (
                'id' => 188,
                'description' => 'Lighting',
                'type' => 5,
                'parent_id' => 0,
            ),
            120 => 
            array (
                'id' => 189,
                'description' => 'Main Access Hatch',
                'type' => 5,
                'parent_id' => 0,
            ),
            121 => 
            array (
                'id' => 190,
                'description' => 'Miscellaneous',
                'type' => 5,
                'parent_id' => 0,
            ),
            122 => 
            array (
                'id' => 191,
                'description' => 'Nominated Personnel',
                'type' => 5,
                'parent_id' => 0,
            ),
            123 => 
            array (
                'id' => 192,
                'description' => 'Operational Use',
                'type' => 5,
                'parent_id' => 0,
            ),
            124 => 
            array (
                'id' => 193,
                'description' => 'Other Significant Hazard',
                'type' => 5,
                'parent_id' => 0,
            ),
            125 => 
            array (
                'id' => 194,
                'description' => 'Outstanding Remedial Actions',
                'type' => 5,
                'parent_id' => 0,
            ),
            126 => 
            array (
                'id' => 195,
                'description' => 'PH',
                'type' => 5,
                'parent_id' => 0,
            ),
            127 => 
            array (
                'id' => 196,
                'description' => 'Pipe Insulation',
                'type' => 5,
                'parent_id' => 0,
            ),
            128 => 
            array (
                'id' => 197,
                'description' => 'Pipe Insulation Condition',
                'type' => 5,
                'parent_id' => 0,
            ),
            129 => 
            array (
                'id' => 198,
                'description' => 'Return Temperature',
                'type' => 5,
                'parent_id' => 0,
            ),
            130 => 
            array (
                'id' => 199,
                'description' => 'Return Temperature Gauge',
                'type' => 5,
                'parent_id' => 0,
            ),
            131 => 
            array (
                'id' => 200,
                'description' => 'Rodent Protection',
                'type' => 5,
                'parent_id' => 0,
            ),
            132 => 
            array (
                'id' => 201,
                'description' => 'Separate Ball Valve Hatch',
                'type' => 5,
                'parent_id' => 0,
            ),
            133 => 
            array (
                'id' => 202,
                'description' => 'Signs and Notices',
                'type' => 5,
                'parent_id' => 0,
            ),
            134 => 
            array (
                'id' => 203,
                'description' => 'Smoking',
                'type' => 5,
                'parent_id' => 0,
            ),
            135 => 
            array (
                'id' => 204,
                'description' => 'Source Accessibility',
                'type' => 5,
                'parent_id' => 0,
            ),
            136 => 
            array (
                'id' => 205,
                'description' => 'Source Condition',
                'type' => 5,
                'parent_id' => 0,
            ),
            137 => 
            array (
                'id' => 206,
                'description' => 'Storage Temperature',
                'type' => 5,
                'parent_id' => 0,
            ),
            138 => 
            array (
                'id' => 207,
                'description' => 'Stored Temperature',
                'type' => 5,
                'parent_id' => 0,
            ),
            139 => 
            array (
                'id' => 208,
                'description' => 'Screened Lid Vent',
                'type' => 5,
                'parent_id' => 0,
            ),
            140 => 
            array (
                'id' => 209,
                'description' => 'Testing and Periodic Servicing',
                'type' => 5,
                'parent_id' => 0,
            ),
            141 => 
            array (
                'id' => 210,
                'description' => 'Top Temperature',
                'type' => 5,
                'parent_id' => 0,
            ),
            142 => 
            array (
                'id' => 211,
                'description' => 'Training',
                'type' => 5,
                'parent_id' => 0,
            ),
            143 => 
            array (
                'id' => 212,
            'description' => 'Training (Refresher)',
                'type' => 5,
                'parent_id' => 0,
            ),
            144 => 
            array (
                'id' => 213,
                'description' => 'Warning Overflow',
                'type' => 5,
                'parent_id' => 0,
            ),
            145 => 
            array (
                'id' => 214,
                'description' => 'Pipework Insulation Condition',
                'type' => 5,
                'parent_id' => 0,
            ),
            146 => 
            array (
                'id' => 215,
                'description' => 'Bottom Temperature',
                'type' => 5,
                'parent_id' => 0,
            ),
            147 => 
            array (
                'id' => 216,
                'description' => 'Overflow Pipe',
                'type' => 5,
                'parent_id' => 0,
            ),
            148 => 
            array (
                'id' => 217,
                'description' => 'Backflow Protection',
                'type' => 5,
                'parent_id' => 0,
            ),
            149 => 
            array (
                'id' => 218,
                'description' => 'Inaccessible Assembly Points',
                'type' => 5,
                'parent_id' => 0,
            ),
            150 => 
            array (
                'id' => 219,
                'description' => 'Inaccessible Fire Exits',
                'type' => 5,
                'parent_id' => 0,
            ),
            151 => 
            array (
                'id' => 220,
                'description' => 'Inaccessible Vehicle Parking',
                'type' => 5,
                'parent_id' => 0,
            ),
            152 => 
            array (
                'id' => 221,
            'description' => 'Dead End (<6 Pipe Diameter)',
                'type' => 5,
                'parent_id' => 0,
            ),
            153 => 
            array (
                'id' => 222,
            'description' => 'Dead End (6+ Pipe Diameter)',
                'type' => 5,
                'parent_id' => 0,
            ),
            154 => 
            array (
                'id' => 223,
                'description' => 'Dead Leg',
                'type' => 5,
                'parent_id' => 0,
            ),
            155 => 
            array (
                'id' => 224,
                'description' => 'Pipework Condition',
                'type' => 5,
                'parent_id' => 0,
            ),
            156 => 
            array (
                'id' => 225,
                'description' => 'Incoming Mains Pipework Temperature',
                'type' => 5,
                'parent_id' => 0,
            ),
            157 => 
            array (
                'id' => 226,
                'description' => 'Hot Flow Temperature',
                'type' => 5,
                'parent_id' => 0,
            ),
            158 => 
            array (
                'id' => 227,
                'description' => 'Cold Flow Temperature',
                'type' => 5,
                'parent_id' => 0,
            ),
            159 => 
            array (
                'id' => 228,
                'description' => 'Pre-TMV Cold Flow Temperature',
                'type' => 5,
                'parent_id' => 0,
            ),
            160 => 
            array (
                'id' => 229,
                'description' => 'Pre-TMV Hot Flow Temperature',
                'type' => 5,
                'parent_id' => 0,
            ),
            161 => 
            array (
                'id' => 230,
                'description' => 'Post-TMV Temperature',
                'type' => 5,
                'parent_id' => 0,
            ),
        ));
        
        
    }
}