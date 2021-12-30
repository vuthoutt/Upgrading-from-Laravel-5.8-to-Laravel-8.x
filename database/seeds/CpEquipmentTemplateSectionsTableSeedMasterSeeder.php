<?php

use Illuminate\Database\Seeder;

class CpEquipmentTemplateSectionsTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('cp_equipment_template_sections')->delete();
        
        \DB::table('cp_equipment_template_sections')->insert(array (
            0 => 
            array (
                'id' => 1,
                'template_id' => 1,
                'section_id' => 1,
                'field' => 'manufacturer',
                'required' => 0,
            ),
            1 => 
            array (
                'id' => 2,
                'template_id' => 1,
                'section_id' => 2,
                'field' => 'model',
                'required' => 0,
            ),
            2 => 
            array (
                'id' => 3,
                'template_id' => 1,
                'section_id' => 3,
                'field' => 'dimensions',
                'required' => 0,
            ),
            3 => 
            array (
                'id' => 8,
                'template_id' => 1,
                'section_id' => 10,
                'field' => 'labelling',
                'required' => 0,
            ),
            4 => 
            array (
                'id' => 9,
                'template_id' => 1,
                'section_id' => 41,
                'field' => 'cleanliness',
                'required' => 1,
            ),
            5 => 
            array (
                'id' => 10,
                'template_id' => 1,
                'section_id' => 42,
                'field' => 'ease_cleaning',
                'required' => 0,
            ),
            6 => 
            array (
                'id' => 11,
                'template_id' => 1,
                'section_id' => 43,
                'field' => 'comments',
                'required' => 0,
            ),
            7 => 
            array (
                'id' => 12,
                'template_id' => 2,
                'section_id' => 1,
                'field' => 'manufacturer',
                'required' => 0,
            ),
            8 => 
            array (
                'id' => 13,
                'template_id' => 2,
                'section_id' => 2,
                'field' => 'model',
                'required' => 0,
            ),
            9 => 
            array (
                'id' => 14,
                'template_id' => 2,
                'section_id' => 3,
                'field' => 'dimensions',
                'required' => 0,
            ),
            10 => 
            array (
                'id' => 15,
                'template_id' => 2,
                'section_id' => 4,
                'field' => 'capacity',
                'required' => 0,
            ),
            11 => 
            array (
                'id' => 16,
                'template_id' => 2,
                'section_id' => 14,
                'field' => 'construction_material',
                'required' => 0,
            ),
            12 => 
            array (
                'id' => 17,
                'template_id' => 2,
                'section_id' => 15,
                'field' => 'insulation_type',
                'required' => 1,
            ),
            13 => 
            array (
                'id' => 18,
                'template_id' => 2,
                'section_id' => 16,
                'field' => 'insulation_thickness',
                'required' => 1,
            ),
            14 => 
            array (
                'id' => 19,
                'template_id' => 2,
                'section_id' => 17,
                'field' => 'insulation_condition',
                'required' => 1,
            ),
            15 => 
            array (
                'id' => 20,
                'template_id' => 2,
                'section_id' => 11,
                'field' => 'pipe_insulation',
                'required' => 1,
            ),
            16 => 
            array (
                'id' => 21,
                'template_id' => 2,
                'section_id' => 12,
                'field' => 'pipe_insulation_condition',
                'required' => 1,
            ),
            17 => 
            array (
                'id' => 22,
                'template_id' => 2,
                'section_id' => 19,
                'field' => 'anti_stratification',
                'required' => 0,
            ),
            18 => 
            array (
                'id' => 23,
                'template_id' => 2,
                'section_id' => 20,
                'field' => 'direct_fired',
                'required' => 0,
            ),
            19 => 
            array (
                'id' => 24,
                'template_id' => 2,
                'section_id' => 13,
                'field' => 'horizontal_vertical',
                'required' => 0,
            ),
            20 => 
            array (
                'id' => 25,
                'template_id' => 2,
                'section_id' => 21,
                'field' => 'water_softener',
                'required' => 0,
            ),
            21 => 
            array (
                'id' => 26,
                'template_id' => 2,
                'section_id' => 22,
                'field' => 'system_recirculated',
                'required' => 0,
            ),
            22 => 
            array (
                'id' => 27,
                'template_id' => 2,
                'section_id' => 23,
                'field' => 'drain_valve',
                'required' => 0,
            ),
            23 => 
            array (
                'id' => 28,
                'template_id' => 2,
                'section_id' => 24,
                'field' => 'flow_temp_gauge',
                'required' => 0,
            ),
            24 => 
            array (
                'id' => 29,
                'template_id' => 2,
                'section_id' => 25,
                'field' => 'return_temp_gauge',
                'required' => 0,
            ),
            25 => 
            array (
                'id' => 30,
                'template_id' => 2,
                'section_id' => 10,
                'field' => 'labelling',
                'required' => 0,
            ),
            26 => 
            array (
                'id' => 31,
                'template_id' => 2,
                'section_id' => 41,
                'field' => 'cleanliness',
                'required' => 1,
            ),
            27 => 
            array (
                'id' => 32,
                'template_id' => 2,
                'section_id' => 42,
                'field' => 'ease_cleaning',
                'required' => 0,
            ),
            28 => 
            array (
                'id' => 33,
                'template_id' => 2,
                'section_id' => 43,
                'field' => 'comments',
                'required' => 0,
            ),
            29 => 
            array (
                'id' => 34,
                'template_id' => 2,
                'section_id' => 45,
                'field' => 'stored_temp',
                'required' => 1,
            ),
            30 => 
            array (
                'id' => 35,
                'template_id' => 2,
                'section_id' => 47,
                'field' => 'flow_temp',
                'required' => 1,
            ),
            31 => 
            array (
                'id' => 36,
                'template_id' => 2,
                'section_id' => 48,
                'field' => 'return_temp',
                'required' => 1,
            ),
            32 => 
            array (
                'id' => 37,
                'template_id' => 2,
                'section_id' => 51,
                'field' => 'ph',
                'required' => 0,
            ),
            33 => 
            array (
                'id' => 38,
                'template_id' => 3,
                'section_id' => 1,
                'field' => 'manufacturer',
                'required' => 0,
            ),
            34 => 
            array (
                'id' => 39,
                'template_id' => 3,
                'section_id' => 2,
                'field' => 'model',
                'required' => 0,
            ),
            35 => 
            array (
                'id' => 40,
                'template_id' => 3,
                'section_id' => 3,
                'field' => 'dimensions',
                'required' => 0,
            ),
            36 => 
            array (
                'id' => 41,
                'template_id' => 3,
                'section_id' => 4,
                'field' => 'capacity',
                'required' => 0,
            ),
            37 => 
            array (
                'id' => 42,
                'template_id' => 3,
                'section_id' => 5,
                'field' => 'stored_water',
                'required' => 0,
            ),
            38 => 
            array (
                'id' => 43,
                'template_id' => 3,
                'section_id' => 26,
                'field' => 'source',
                'required' => 1,
            ),
            39 => 
            array (
                'id' => 44,
                'template_id' => 3,
                'section_id' => 27,
                'field' => 'source_accessibility',
                'required' => 1,
            ),
            40 => 
            array (
                'id' => 45,
                'template_id' => 3,
                'section_id' => 28,
                'field' => 'source_condition',
                'required' => 1,
            ),
            41 => 
            array (
                'id' => 46,
                'template_id' => 3,
                'section_id' => 14,
                'field' => 'construction_material',
                'required' => 0,
            ),
            42 => 
            array (
                'id' => 47,
                'template_id' => 3,
                'section_id' => 15,
                'field' => 'insulation_type',
                'required' => 1,
            ),
            43 => 
            array (
                'id' => 48,
                'template_id' => 3,
                'section_id' => 16,
                'field' => 'insulation_thickness',
                'required' => 1,
            ),
            44 => 
            array (
                'id' => 49,
                'template_id' => 3,
                'section_id' => 17,
                'field' => 'insulation_condition',
                'required' => 1,
            ),
            45 => 
            array (
                'id' => 50,
                'template_id' => 3,
                'section_id' => 29,
                'field' => 'rodent_protection',
                'required' => 0,
            ),
            46 => 
            array (
                'id' => 51,
                'template_id' => 3,
                'section_id' => 30,
                'field' => 'screened_lid_vent',
                'required' => 0,
            ),
            47 => 
            array (
                'id' => 52,
                'template_id' => 3,
                'section_id' => 31,
                'field' => 'air_vent',
                'required' => 0,
            ),
            48 => 
            array (
                'id' => 53,
                'template_id' => 3,
                'section_id' => 32,
                'field' => 'warning_pipe',
                'required' => 0,
            ),
            49 => 
            array (
                'id' => 54,
                'template_id' => 3,
                'section_id' => 33,
                'field' => 'can_isolated',
                'required' => 0,
            ),
            50 => 
            array (
                'id' => 55,
                'template_id' => 3,
                'section_id' => 34,
                'field' => 'main_access_hatch',
                'required' => 0,
            ),
            51 => 
            array (
                'id' => 56,
                'template_id' => 3,
                'section_id' => 35,
                'field' => 'ball_valve_hatch',
                'required' => 0,
            ),
            52 => 
            array (
                'id' => 57,
                'template_id' => 3,
                'section_id' => 10,
                'field' => 'labelling',
                'required' => 0,
            ),
            53 => 
            array (
                'id' => 59,
                'template_id' => 3,
                'section_id' => 37,
                'field' => 'envidence_stagnation',
                'required' => 1,
            ),
            54 => 
            array (
                'id' => 60,
                'template_id' => 3,
                'section_id' => 38,
                'field' => 'degree_fouling',
                'required' => 1,
            ),
            55 => 
            array (
                'id' => 61,
                'template_id' => 3,
                'section_id' => 39,
                'field' => 'degree_biological',
                'required' => 1,
            ),
            56 => 
            array (
                'id' => 62,
                'template_id' => 3,
                'section_id' => 40,
                'field' => 'extent_corrosion',
                'required' => 1,
            ),
            57 => 
            array (
                'id' => 63,
                'template_id' => 3,
                'section_id' => 41,
                'field' => 'cleanliness',
                'required' => 1,
            ),
            58 => 
            array (
                'id' => 64,
                'template_id' => 3,
                'section_id' => 42,
                'field' => 'ease_cleaning',
                'required' => 0,
            ),
            59 => 
            array (
                'id' => 65,
                'template_id' => 3,
                'section_id' => 43,
                'field' => 'comments',
                'required' => 0,
            ),
            60 => 
            array (
                'id' => 66,
                'template_id' => 3,
                'section_id' => 44,
                'field' => 'inlet_temp',
                'required' => 1,
            ),
            61 => 
            array (
                'id' => 67,
                'template_id' => 3,
                'section_id' => 45,
                'field' => 'stored_temp',
                'required' => 1,
            ),
            62 => 
            array (
                'id' => 68,
                'template_id' => 3,
                'section_id' => 51,
                'field' => 'ph',
                'required' => 0,
            ),
            63 => 
            array (
                'id' => 69,
                'template_id' => 4,
                'section_id' => 1,
                'field' => 'manufacturer',
                'required' => 0,
            ),
            64 => 
            array (
                'id' => 70,
                'template_id' => 4,
                'section_id' => 2,
                'field' => 'model',
                'required' => 0,
            ),
            65 => 
            array (
                'id' => 71,
                'template_id' => 4,
                'section_id' => 3,
                'field' => 'dimensions',
                'required' => 0,
            ),
            66 => 
            array (
                'id' => 72,
                'template_id' => 4,
                'section_id' => 10,
                'field' => 'labelling',
                'required' => 0,
            ),
            67 => 
            array (
                'id' => 73,
                'template_id' => 4,
                'section_id' => 41,
                'field' => 'cleanliness',
                'required' => 1,
            ),
            68 => 
            array (
                'id' => 74,
                'template_id' => 4,
                'section_id' => 42,
                'field' => 'ease_cleaning',
                'required' => 0,
            ),
            69 => 
            array (
                'id' => 75,
                'template_id' => 4,
                'section_id' => 43,
                'field' => 'comments',
                'required' => 0,
            ),
            70 => 
            array (
                'id' => 76,
                'template_id' => 5,
                'section_id' => 1,
                'field' => 'manufacturer',
                'required' => 0,
            ),
            71 => 
            array (
                'id' => 77,
                'template_id' => 5,
                'section_id' => 2,
                'field' => 'model',
                'required' => 0,
            ),
            72 => 
            array (
                'id' => 78,
                'template_id' => 5,
                'section_id' => 3,
                'field' => 'dimensions',
                'required' => 0,
            ),
            73 => 
            array (
                'id' => 79,
                'template_id' => 5,
                'section_id' => 6,
                'field' => 'aerosol_risk',
                'required' => 1,
            ),
            74 => 
            array (
                'id' => 80,
                'template_id' => 5,
                'section_id' => 7,
                'field' => 'flexible_hose',
                'required' => 0,
            ),
            75 => 
            array (
                'id' => 81,
                'template_id' => 5,
                'section_id' => 8,
                'field' => 'sentinel',
                'required' => 0,
            ),
            76 => 
            array (
                'id' => 82,
                'template_id' => 5,
                'section_id' => 9,
                'field' => 'tmv_fitted',
                'required' => 0,
            ),
            77 => 
            array (
                'id' => 83,
                'template_id' => 5,
                'section_id' => 10,
                'field' => 'labelling',
                'required' => 0,
            ),
            78 => 
            array (
                'id' => 84,
                'template_id' => 5,
                'section_id' => 41,
                'field' => 'cleanliness',
                'required' => 1,
            ),
            79 => 
            array (
                'id' => 85,
                'template_id' => 5,
                'section_id' => 42,
                'field' => 'ease_cleaning',
                'required' => 0,
            ),
            80 => 
            array (
                'id' => 86,
                'template_id' => 5,
                'section_id' => 43,
                'field' => 'comments',
                'required' => 0,
            ),
            81 => 
            array (
                'id' => 87,
                'template_id' => 5,
                'section_id' => 49,
                'field' => 'top_temp',
                'required' => 1,
            ),
            82 => 
            array (
                'id' => 88,
                'template_id' => 5,
                'section_id' => 50,
                'field' => 'bottom_temp',
                'required' => 1,
            ),
            83 => 
            array (
                'id' => 89,
                'template_id' => 5,
                'section_id' => 51,
                'field' => 'ph',
                'required' => 0,
            ),
            84 => 
            array (
                'id' => 90,
                'template_id' => 6,
                'section_id' => 1,
                'field' => 'manufacturer',
                'required' => 0,
            ),
            85 => 
            array (
                'id' => 91,
                'template_id' => 6,
                'section_id' => 2,
                'field' => 'model',
                'required' => 0,
            ),
            86 => 
            array (
                'id' => 92,
                'template_id' => 6,
                'section_id' => 3,
                'field' => 'dimensions',
                'required' => 0,
            ),
            87 => 
            array (
                'id' => 93,
                'template_id' => 6,
                'section_id' => 6,
                'field' => 'aerosol_risk',
                'required' => 1,
            ),
            88 => 
            array (
                'id' => 94,
                'template_id' => 6,
                'section_id' => 7,
                'field' => 'flexible_hose',
                'required' => 0,
            ),
            89 => 
            array (
                'id' => 95,
                'template_id' => 6,
                'section_id' => 8,
                'field' => 'sentinel',
                'required' => 0,
            ),
            90 => 
            array (
                'id' => 96,
                'template_id' => 6,
                'section_id' => 9,
                'field' => 'tmv_fitted',
                'required' => 0,
            ),
            91 => 
            array (
                'id' => 97,
                'template_id' => 6,
                'section_id' => 10,
                'field' => 'labelling',
                'required' => 0,
            ),
            92 => 
            array (
                'id' => 98,
                'template_id' => 6,
                'section_id' => 41,
                'field' => 'cleanliness',
                'required' => 1,
            ),
            93 => 
            array (
                'id' => 99,
                'template_id' => 6,
                'section_id' => 42,
                'field' => 'ease_cleaning',
                'required' => 0,
            ),
            94 => 
            array (
                'id' => 100,
                'template_id' => 6,
                'section_id' => 43,
                'field' => 'comments',
                'required' => 0,
            ),
            95 => 
            array (
                'id' => 101,
                'template_id' => 6,
                'section_id' => 47,
                'field' => 'flow_temp',
                'required' => 1,
            ),
            96 => 
            array (
                'id' => 102,
                'template_id' => 6,
                'section_id' => 51,
                'field' => 'ph',
                'required' => 0,
            ),
            97 => 
            array (
                'id' => 103,
                'template_id' => 7,
                'section_id' => 1,
                'field' => 'manufacturer',
                'required' => 0,
            ),
            98 => 
            array (
                'id' => 104,
                'template_id' => 7,
                'section_id' => 2,
                'field' => 'model',
                'required' => 0,
            ),
            99 => 
            array (
                'id' => 105,
                'template_id' => 7,
                'section_id' => 3,
                'field' => 'dimensions',
                'required' => 0,
            ),
            100 => 
            array (
                'id' => 106,
                'template_id' => 7,
                'section_id' => 4,
                'field' => 'capacity',
                'required' => 0,
            ),
            101 => 
            array (
                'id' => 107,
                'template_id' => 7,
                'section_id' => 6,
                'field' => 'aerosol_risk',
                'required' => 1,
            ),
            102 => 
            array (
                'id' => 108,
                'template_id' => 7,
                'section_id' => 11,
                'field' => 'pipe_insulation',
                'required' => 1,
            ),
            103 => 
            array (
                'id' => 109,
                'template_id' => 7,
                'section_id' => 12,
                'field' => 'pipe_insulation_condition',
                'required' => 1,
            ),
            104 => 
            array (
                'id' => 110,
                'template_id' => 7,
                'section_id' => 13,
                'field' => 'horizontal_vertical',
                'required' => 0,
            ),
            105 => 
            array (
                'id' => 111,
                'template_id' => 7,
                'section_id' => 10,
                'field' => 'labelling',
                'required' => 0,
            ),
            106 => 
            array (
                'id' => 112,
                'template_id' => 7,
                'section_id' => 41,
                'field' => 'cleanliness',
                'required' => 1,
            ),
            107 => 
            array (
                'id' => 113,
                'template_id' => 7,
                'section_id' => 42,
                'field' => 'ease_cleaning',
                'required' => 0,
            ),
            108 => 
            array (
                'id' => 114,
                'template_id' => 7,
                'section_id' => 43,
                'field' => 'comments',
                'required' => 0,
            ),
            109 => 
            array (
                'id' => 116,
                'template_id' => 7,
                'section_id' => 47,
                'field' => 'flow_temp',
                'required' => 1,
            ),
            110 => 
            array (
                'id' => 117,
                'template_id' => 7,
                'section_id' => 51,
                'field' => 'ph',
                'required' => 0,
            ),
            111 => 
            array (
                'id' => 118,
                'template_id' => 6,
                'section_id' => 11,
                'field' => 'pipe_insulation',
                'required' => 1,
            ),
            112 => 
            array (
                'id' => 119,
                'template_id' => 6,
                'section_id' => 12,
                'field' => 'pipe_insulation_condition',
                'required' => 1,
            ),
            113 => 
            array (
                'id' => 120,
                'template_id' => 4,
                'section_id' => 6,
                'field' => 'aerosol_risk',
                'required' => 1,
            ),
            114 => 
            array (
                'id' => 121,
                'template_id' => 4,
                'section_id' => 7,
                'field' => 'flexible_hose',
                'required' => 0,
            ),
            115 => 
            array (
                'id' => 122,
                'template_id' => 4,
                'section_id' => 8,
                'field' => 'sentinel',
                'required' => 0,
            ),
            116 => 
            array (
                'id' => 123,
                'template_id' => 4,
                'section_id' => 47,
                'field' => 'flow_temp',
                'required' => 1,
            ),
            117 => 
            array (
                'id' => 124,
                'template_id' => 4,
                'section_id' => 51,
                'field' => 'ph',
                'required' => 0,
            ),
            118 => 
            array (
                'id' => 125,
                'template_id' => 5,
                'section_id' => 11,
                'field' => 'pipe_insulation',
                'required' => 1,
            ),
            119 => 
            array (
                'id' => 126,
                'template_id' => 5,
                'section_id' => 12,
                'field' => 'pipe_insulation_condition',
                'required' => 1,
            ),
            120 => 
            array (
                'id' => 127,
                'template_id' => 3,
                'section_id' => 54,
                'field' => 'overflow_pipe',
                'required' => 0,
            ),
            121 => 
            array (
                'id' => 128,
                'template_id' => 3,
                'section_id' => 55,
                'field' => 'backflow_protection',
                'required' => 0,
            ),
            122 => 
            array (
                'id' => 129,
                'template_id' => 3,
                'section_id' => 56,
                'field' => 'drain_size',
                'required' => 1,
            ),
            123 => 
            array (
                'id' => 130,
                'template_id' => 3,
                'section_id' => 57,
                'field' => 'drain_location',
                'required' => 1,
            ),
            124 => 
            array (
                'id' => 131,
                'template_id' => 3,
                'section_id' => 58,
                'field' => 'cold_feed_size',
                'required' => 1,
            ),
            125 => 
            array (
                'id' => 132,
                'template_id' => 3,
                'section_id' => 59,
                'field' => 'cold_feed_location',
                'required' => 1,
            ),
            126 => 
            array (
                'id' => 133,
                'template_id' => 3,
                'section_id' => 60,
                'field' => 'outlet_size',
                'required' => 1,
            ),
            127 => 
            array (
                'id' => 134,
                'template_id' => 3,
                'section_id' => 61,
                'field' => 'outlet_location',
                'required' => 1,
            ),
            128 => 
            array (
                'id' => 135,
                'template_id' => 2,
                'section_id' => 55,
                'field' => 'backflow_protection',
                'required' => 0,
            ),
            129 => 
            array (
                'id' => 136,
                'template_id' => 2,
                'section_id' => 56,
                'field' => 'drain_size',
                'required' => 1,
            ),
            130 => 
            array (
                'id' => 137,
                'template_id' => 2,
                'section_id' => 57,
                'field' => 'drain_location',
                'required' => 1,
            ),
            131 => 
            array (
                'id' => 138,
                'template_id' => 2,
                'section_id' => 58,
                'field' => 'cold_feed_size',
                'required' => 1,
            ),
            132 => 
            array (
                'id' => 139,
                'template_id' => 2,
                'section_id' => 59,
                'field' => 'cold_feed_location',
                'required' => 1,
            ),
            133 => 
            array (
                'id' => 140,
                'template_id' => 2,
                'section_id' => 60,
                'field' => 'outlet_size',
                'required' => 1,
            ),
            134 => 
            array (
                'id' => 141,
                'template_id' => 2,
                'section_id' => 61,
                'field' => 'outlet_location',
                'required' => 1,
            ),
            135 => 
            array (
                'id' => 142,
                'template_id' => 1,
                'section_id' => 62,
                'field' => 'extent',
                'required' => 0,
            ),
            136 => 
            array (
                'id' => 143,
                'template_id' => 4,
                'section_id' => 62,
                'field' => 'extent',
                'required' => 0,
            ),
            137 => 
            array (
                'id' => 144,
                'template_id' => 5,
                'section_id' => 62,
                'field' => 'extent',
                'required' => 0,
            ),
            138 => 
            array (
                'id' => 145,
                'template_id' => 6,
                'section_id' => 62,
                'field' => 'extent',
                'required' => 0,
            ),
            139 => 
            array (
                'id' => 146,
                'template_id' => 2,
                'section_id' => 5,
                'field' => 'stored_water',
                'required' => 0,
            ),
            140 => 
            array (
                'id' => 147,
                'template_id' => 7,
                'section_id' => 5,
                'field' => 'stored_water',
                'required' => 0,
            ),
            141 => 
            array (
                'id' => 148,
                'template_id' => 5,
                'section_id' => 63,
                'field' => 'nearest_furthest',
                'required' => 0,
            ),
            142 => 
            array (
                'id' => 149,
                'template_id' => 4,
                'section_id' => 63,
                'field' => 'nearest_furthest',
                'required' => 0,
            ),
            143 => 
            array (
                'id' => 150,
                'template_id' => 6,
                'section_id' => 63,
                'field' => 'nearest_furthest',
                'required' => 0,
            ),
            144 => 
            array (
                'id' => 151,
                'template_id' => 2,
                'section_id' => 52,
                'field' => 'flow_temp_gauge_value',
                'required' => 1,
            ),
            145 => 
            array (
                'id' => 152,
                'template_id' => 2,
                'section_id' => 53,
                'field' => 'return_temp_gauge_value',
                'required' => 1,
            ),
            146 => 
            array (
                'id' => 153,
                'template_id' => 4,
                'section_id' => 64,
                'field' => 'has_sample',
                'required' => 0,
            ),
            147 => 
            array (
                'id' => 154,
                'template_id' => 4,
                'section_id' => 65,
                'field' => 'sample_reference',
                'required' => 0,
            ),
            148 => 
            array (
                'id' => 155,
                'template_id' => 5,
                'section_id' => 64,
                'field' => 'has_sample',
                'required' => 0,
            ),
            149 => 
            array (
                'id' => 156,
                'template_id' => 5,
                'section_id' => 65,
                'field' => 'sample_reference',
                'required' => 0,
            ),
            150 => 
            array (
                'id' => 157,
                'template_id' => 6,
                'section_id' => 64,
                'field' => 'has_sample',
                'required' => 0,
            ),
            151 => 
            array (
                'id' => 158,
                'template_id' => 6,
                'section_id' => 65,
                'field' => 'sample_reference',
                'required' => 0,
            ),
            152 => 
            array (
                'id' => 159,
                'template_id' => 8,
                'section_id' => 66,
                'field' => 'access',
                'required' => 0,
            ),
            153 => 
            array (
                'id' => 160,
                'template_id' => 8,
                'section_id' => 67,
                'field' => 'water_meter_fitted',
                'required' => 0,
            ),
            154 => 
            array (
                'id' => 161,
                'template_id' => 8,
                'section_id' => 68,
                'field' => 'water_meter_reading',
                'required' => 0,
            ),
            155 => 
            array (
                'id' => 162,
                'template_id' => 8,
                'section_id' => 69,
                'field' => 'material_of_pipework',
                'required' => 0,
            ),
            156 => 
            array (
                'id' => 163,
                'template_id' => 8,
                'section_id' => 70,
                'field' => 'size_of_pipework',
                'required' => 0,
            ),
            157 => 
            array (
                'id' => 164,
                'template_id' => 8,
                'section_id' => 71,
                'field' => 'condition_of_pipework',
                'required' => 0,
            ),
            158 => 
            array (
                'id' => 165,
                'template_id' => 8,
                'section_id' => 17,
                'field' => 'insulation_condition',
                'required' => 0,
            ),
            159 => 
            array (
                'id' => 166,
                'template_id' => 8,
                'section_id' => 72,
                'field' => 'stop_tap_fitted',
                'required' => 0,
            ),
            160 => 
            array (
                'id' => 167,
                'template_id' => 8,
                'section_id' => 23,
                'field' => 'drain_valve',
                'required' => 0,
            ),
            161 => 
            array (
                'id' => 168,
                'template_id' => 8,
                'section_id' => 73,
                'field' => 'ambient_area_temp',
                'required' => 0,
            ),
            162 => 
            array (
                'id' => 169,
                'template_id' => 8,
                'section_id' => 74,
                'field' => 'incoming_main_pipe_work_temp',
                'required' => 0,
            ),
            163 => 
            array (
                'id' => 170,
                'template_id' => 8,
                'section_id' => 62,
                'field' => 'extent',
                'required' => 0,
            ),
            164 => 
            array (
                'id' => 171,
                'template_id' => 5,
                'section_id' => 75,
                'field' => 'hot_parent_id',
                'required' => 0,
            ),
            165 => 
            array (
                'id' => 172,
                'template_id' => 5,
                'section_id' => 76,
                'field' => 'cold_parent_id',
                'required' => 0,
            ),
            166 => 
            array (
                'id' => 173,
                'template_id' => 1,
                'section_id' => 77,
                'field' => 'parent_id',
                'required' => 0,
            ),
            167 => 
            array (
                'id' => 174,
                'template_id' => 2,
                'section_id' => 77,
                'field' => 'parent_id',
                'required' => 0,
            ),
            168 => 
            array (
                'id' => 175,
                'template_id' => 3,
                'section_id' => 77,
                'field' => 'parent_id',
                'required' => 0,
            ),
            169 => 
            array (
                'id' => 176,
                'template_id' => 4,
                'section_id' => 77,
                'field' => 'parent_id',
                'required' => 0,
            ),
            170 => 
            array (
                'id' => 177,
                'template_id' => 6,
                'section_id' => 77,
                'field' => 'parent_id',
                'required' => 0,
            ),
            171 => 
            array (
                'id' => 178,
                'template_id' => 7,
                'section_id' => 77,
                'field' => 'parent_id',
                'required' => 0,
            ),
            172 => 
            array (
                'id' => 179,
                'template_id' => 8,
                'section_id' => 77,
                'field' => 'parent_id',
                'required' => 0,
            ),
        ));
        
        
    }
}