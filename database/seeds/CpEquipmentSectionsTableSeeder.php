<?php

use Illuminate\Database\Seeder;

class CpEquipmentSectionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('cp_equipment_sections')->delete();

        \DB::table('cp_equipment_sections')->insert(array (
            0 =>
            array (
                'id' => 1,
                'description' => 'Manufacturer',
                'field_name' => 'manufacturer',
                'section' => 'model',
            ),
            1 =>
            array (
                'id' => 2,
                'description' => 'Model',
                'field_name' => 'model',
                'section' => 'model',
            ),
            2 =>
            array (
                'id' => 3,
                'description' => 'Dimensions',
                'field_name' => 'dimensions',
                'section' => 'model',
            ),
            3 =>
            array (
                'id' => 4,
                'description' => 'Capacity',
                'field_name' => 'capacity',
                'section' => 'model',
            ),
            4 =>
            array (
                'id' => 5,
                'description' => 'Stored Volume',
                'field_name' => 'stored_water',
                'section' => 'model',
            ),
            5 =>
            array (
                'id' => 6,
                'description' => 'Aerosol Risk',
                'field_name' => 'aerosol_risk',
                'section' => 'construction',
            ),
            6 =>
            array (
                'id' => 7,
                'description' => 'Flexible Hose',
                'field_name' => 'flexible_hose',
                'section' => 'construction',
            ),
            7 =>
            array (
                'id' => 8,
                'description' => 'Sentinel',
                'field_name' => 'sentinel',
                'section' => 'construction',
            ),
            8 =>
            array (
                'id' => 9,
                'description' => 'TMV Fitted',
                'field_name' => 'tmv_fitted',
                'section' => 'construction',
            ),
            9 =>
            array (
                'id' => 10,
                'description' => 'Labelling',
                'field_name' => 'labelling',
                'section' => 'construction',
            ),
            10 =>
            array (
                'id' => 11,
                'description' => 'Pipe Insulation',
                'field_name' => 'pipe_insulation',
                'section' => 'construction',
            ),
            11 =>
            array (
                'id' => 12,
                'description' => 'Pipe Insulation Condition',
                'field_name' => 'pipe_insulation_condition',
                'section' => 'construction',
            ),
            12 =>
            array (
                'id' => 13,
                'description' => 'Horizontal/Vertical',
                'field_name' => 'horizontal_vertical',
                'section' => 'construction',
            ),
            13 =>
            array (
                'id' => 14,
                'description' => 'Construction Material',
                'field_name' => 'construction_material',
                'section' => 'construction',
            ),
            14 =>
            array (
                'id' => 15,
                'description' => 'Insulation Type',
                'field_name' => 'insulation_type',
                'section' => 'construction',
            ),
            15 =>
            array (
                'id' => 16,
                'description' => 'Insulation Thickness',
                'field_name' => 'insulation_thickness',
                'section' => 'construction',
            ),
            16 =>
            array (
                'id' => 17,
                'description' => 'Insulation Condition',
                'field_name' => 'insulation_condition',
                'section' => 'construction',
            ),
            17 =>
            array (
                'id' => 19,
                'description' => 'Anti-stratification Pump Fitted ',
                'field_name' => 'anti_stratification',
                'section' => 'construction',
            ),
            18 =>
            array (
                'id' => 20,
                'description' => 'Direct/Indirect Fired',
                'field_name' => 'direct_fired',
                'section' => 'construction',
            ),
            19 =>
            array (
                'id' => 21,
                'description' => 'Includes Water Softener',
                'field_name' => 'water_softener',
                'section' => 'construction',
            ),
            20 =>
            array (
                'id' => 22,
                'description' => 'System Recirculated',
                'field_name' => 'system_recirculated',
                'section' => 'construction',
            ),
            21 =>
            array (
                'id' => 23,
                'description' => 'Drain Valve',
                'field_name' => 'drain_valve',
                'section' => 'construction',
            ),
            22 =>
            array (
                'id' => 24,
                'description' => 'Flow Temperature Gauge',
                'field_name' => 'flow_temp_gauge',
                'section' => 'construction',
            ),
            23 =>
            array (
                'id' => 25,
                'description' => 'Return Temperature Gauge',
                'field_name' => 'return_temp_gauge',
                'section' => 'construction',
            ),
            24 =>
            array (
                'id' => 26,
                'description' => 'Source',
                'field_name' => 'source',
                'section' => 'construction',
            ),
            25 =>
            array (
                'id' => 27,
                'description' => 'Source Accessibility',
                'field_name' => 'source_accessibility',
                'section' => 'construction',
            ),
            26 =>
            array (
                'id' => 28,
                'description' => 'Source Condition',
                'field_name' => 'source_condition',
                'section' => 'construction',
            ),
            27 =>
            array (
                'id' => 29,
                'description' => 'Rodent Protection',
                'field_name' => 'rodent_protection',
                'section' => 'construction',
            ),
            28 =>
            array (
                'id' => 30,
                'description' => 'Screened Lid Vent',
                'field_name' => 'screened_lid_vent',
                'section' => 'construction',
            ),
            29 =>
            array (
                'id' => 31,
                'description' => 'Air Vent',
                'field_name' => 'air_vent',
                'section' => 'construction',
            ),
            30 =>
            array (
                'id' => 32,
                'description' => 'Overflow Warning',
                'field_name' => 'warning_pipe',
                'section' => 'construction',
            ),
            31 =>
            array (
                'id' => 33,
                'description' => 'Can it be Isolated',
                'field_name' => 'can_isolated',
                'section' => 'construction',
            ),
            32 =>
            array (
                'id' => 34,
                'description' => 'Main Access Hatch',
                'field_name' => 'main_access_hatch',
                'section' => 'construction',
            ),
            33 =>
            array (
                'id' => 35,
                'description' => 'Separate Ball Valve Hatch',
                'field_name' => 'ball_valve_hatch',
                'section' => 'construction',
            ),
            34 =>
            array (
                'id' => 36,
                'description' => 'Operational Exposure',
                'field_name' => 'operational_exposure',
                'section' => 'cleaning',
            ),
            35 =>
            array (
                'id' => 37,
                'description' => 'Evidence of Stagnation',
                'field_name' => 'envidence_stagnation',
                'section' => 'cleaning',
            ),
            36 =>
            array (
                'id' => 38,
                'description' => 'Degree of Fouling',
                'field_name' => 'degree_fouling',
                'section' => 'cleaning',
            ),
            37 =>
            array (
                'id' => 39,
                'description' => 'Degree of Biological Slime',
                'field_name' => 'degree_biological',
                'section' => 'cleaning',
            ),
            38 =>
            array (
                'id' => 40,
                'description' => 'Extent of Corrosion',
                'field_name' => 'extent_corrosion',
                'section' => 'cleaning',
            ),
            39 =>
            array (
                'id' => 41,
                'description' => 'Cleanliness',
                'field_name' => 'cleanliness',
                'section' => 'cleaning',
            ),
            40 =>
            array (
                'id' => 42,
                'description' => 'Ease of Cleaning',
                'field_name' => 'ease_cleaning',
                'section' => 'cleaning',
            ),
            41 =>
            array (
                'id' => 43,
                'description' => 'Comments',
                'field_name' => 'comments',
                'section' => 'cleaning',
            ),
            42 =>
            array (
                'id' => 44,
                'description' => 'Inlet Temperature:',
                'field_name' => 'inlet_temp',
                'section' => 'temp',
            ),
            43 =>
            array (
                'id' => 45,
                'description' => 'Stored Temperature:',
                'field_name' => 'stored_temp',
                'section' => 'temp',
            ),
            44 =>
            array (
                'id' => 47,
                'description' => 'Flow Temperature:',
                'field_name' => 'flow_temp',
                'section' => 'temp',
            ),
            45 =>
            array (
                'id' => 48,
                'description' => 'Return Temperature:',
                'field_name' => 'return_temp',
                'section' => 'temp',
            ),
            46 =>
            array (
                'id' => 49,
                'description' => 'Top Temperature',
                'field_name' => 'top_temp',
                'section' => 'temp',
            ),
            47 =>
            array (
                'id' => 50,
                'description' => 'Bottom Temperature',
                'field_name' => 'bottom_temp',
                'section' => 'temp',
            ),
            48 =>
            array (
                'id' => 51,
                'description' => 'pH',
                'field_name' => 'ph',
                'section' => 'temp',
            ),
            49 =>
            array (
                'id' => 52,
                'description' => 'Flow Temperature Gauge',
                'field_name' => 'flow_temp_gauge_value',
                'section' => 'temp',
            ),
            50 =>
            array (
                'id' => 53,
                'description' => 'Return Temperature Gauge',
                'field_name' => 'return_temp_gauge_value',
                'section' => 'temp',
            ),
            51 =>
            array (
                'id' => 54,
                'description' => 'Overflow Pipe',
                'field_name' => 'overflow_pipe',
                'section' => 'construction',
            ),
            52 =>
            array (
                'id' => 55,
                'description' => 'Backflow Protection',
                'field_name' => 'backflow_protection',
                'section' => 'construction',
            ),
            53 =>
            array (
                'id' => 56,
                'description' => 'Drain Size',
                'field_name' => 'drain_size',
                'section' => 'construction',
            ),
            54 =>
            array (
                'id' => 57,
                'description' => 'Drain Location',
                'field_name' => 'drain_location',
                'section' => 'construction',
            ),
            55 =>
            array (
                'id' => 58,
                'description' => 'Cold Feed Size',
                'field_name' => 'cold_feed_size',
                'section' => 'construction',
            ),
            56 =>
            array (
                'id' => 59,
                'description' => 'Cold Feed Location',
                'field_name' => 'cold_feed_location',
                'section' => 'construction',
            ),
            57 =>
            array (
                'id' => 60,
                'description' => 'Outlet Size',
                'field_name' => 'outlet_size',
                'section' => 'construction',
            ),
            58 =>
            array (
                'id' => 61,
                'description' => 'Outlet Location',
                'field_name' => 'outlet_location',
                'section' => 'construction',
            ),
            59 =>
            array (
                'id' => 62,
                'description' => 'Extent',
                'field_name' => 'extent',
                'section' => 'detail',
            ),
            60 =>
            array (
                'id' => 63,
                'description' => 'Nearest/Furthest',
                'field_name' => 'nearest_furthest',
                'section' => 'construction',
            ),
            61 =>
            array (
                'id' => 64,
                'description' => 'Has a Water Sample been collected?',
                'field_name' => 'has_sample',
                'section' => 'sampling',
            ),
            62 =>
            array (
                'id' => 65,
                'description' => 'Sample Reference',
                'field_name' => 'sample_reference',
                'section' => 'sampling',
            ),
            63 =>
            array (
                'id' => 66,
                'description' => 'Access',
                'field_name' => 'access',
                'section' => 'construction',
            ),
            64 =>
            array (
                'id' => 67,
                'description' => 'Water Meter Fitted',
                'field_name' => 'water_meter_fitted',
                'section' => 'construction',
            ),
            65 =>
            array (
                'id' => 68,
                'description' => 'Water Meter Reading',
                'field_name' => 'water_meter_reading',
                'section' => 'construction',
            ),
            66 =>
            array (
                'id' => 69,
                'description' => 'Material of Pipework',
                'field_name' => 'material_of_pipework',
                'section' => 'construction',
            ),
            67 =>
            array (
                'id' => 70,
            'description' => 'Size of Pipework (mm)',
                'field_name' => 'size_of_pipework',
                'section' => 'construction',
            ),
            68 =>
            array (
                'id' => 71,
                'description' => 'Condition of Pipework',
                'field_name' => 'condition_of_pipework',
                'section' => 'construction',
            ),
            69 =>
            array (
                'id' => 72,
                'description' => 'Stop Tap Fitted',
                'field_name' => 'stop_tap_fitted',
                'section' => 'construction',
            ),
            70 =>
            array (
                'id' => 73,
                'description' => 'Ambient Area Temperature',
                'field_name' => 'ambient_area_temp',
                'section' => 'temp',
            ),
            71 =>
            array (
                'id' => 74,
                'description' => 'Incoming Main Pipework Surface Temperature',
                'field_name' => 'incoming_main_pipe_work_temp',
                'section' => 'temp',
            ),
            72 =>
            array (
                'id' => 75,
                'description' => 'Hot Parent',
                'field_name' => 'hot_parent_id',
                'section' => 'detail',
            ),
            73 =>
            array (
                'id' => 76,
                'description' => 'Cold Parent',
                'field_name' => 'cold_parent_id',
                'section' => 'detail',
            ),
            74 =>
            array (
                'id' => 77,
                'description' => 'Parent',
                'field_name' => 'parent_id',
                'section' => 'detail',
            ),
            75 =>
            array (
                'id' => 78,
                'description' => 'Hot Flow Temperature',
                'field_name' => 'hot_flow_temp',
                'section' => 'temp',
            ),
            76 =>
            array (
                'id' => 79,
                'description' => 'Cold Flow Temperature',
                'field_name' => 'cold_flow_temp',
                'section' => 'temp',
            ),
            77 =>
            array (
                'id' => 80,
                'description' => 'Pre-TMV Cold Flow Temperature',
                'field_name' => 'pre_tmv_cold_flow_temp',
                'section' => 'temp',
            ),
            78 =>
            array (
                'id' => 81,
                'description' => 'Pre-TMV Hot Flow Temperature',
                'field_name' => 'pre_tmv_hot_flow_temp',
                'section' => 'temp',
            ),
            79 =>
            array (
                'id' => 82,
                'description' => 'Post-TMV Temperature',
                'field_name' => 'post_tmv_temp',
                'section' => 'temp',
            ),
            80 =>
            array (
                'id' => 83,
                'description' => 'Return Temperature',
                'field_name' => 'construction_return_temp',
                'section' => 'construction',
            ),
            81 =>
            array (
                'id' => 84,
                'description' => 'Mixed Temperature',
                'field_name' => 'mixed_temp',
                'section' => 'temp',
            ),
        ));
        
        
    }
}