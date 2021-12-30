<?php

use Illuminate\Database\Seeder;

class CpIncidentReportDropdownDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('incident_report_dropdown_data')->delete();

        \DB::table('incident_report_dropdown_data')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'dropdown_id' => 1,
                    'description' => 'Equipment Nonconformity',
                    'order' => 1,
                    'other' => 0,
                ),
            1 =>
                array (
                    'id' => 2,
                    'dropdown_id' => 1,
                    'description' => 'Identified Hazard & Near Miss',
                    'order' => 2,
                    'other' => 0,
                ),
            2 =>
                array (
                    'id' => 3,
                    'dropdown_id' => 1,
                    'description' => 'Incident',
                    'order' => 3,
                    'other' => 0,
                ),
            3 =>
                array (
                    'id' => 4,
                    'dropdown_id' => 2,
                    'description' => 'Arson',
                    'order' => 1,
                    'other' => 0,
                ),
            4 =>
                array (
                    'id' => 5,
                    'dropdown_id' => 2,
                    'description' => 'Asbestos Exposure',
                    'order' => 2,
                    'other' => 0,
                ),
            5 =>
                array (
                    'id' => 6,
                    'dropdown_id' => 2,
                    'description' => 'Assualt Physical',
                    'order' => 3,
                    'other' => 0,
                ),
            6 =>
                array (
                    'id' => 7,
                    'dropdown_id' => 2,
                    'description' => 'Assualt Verbals',
                    'order' => 4,
                    'other' => 0,
                ),
            7 =>
                array (
                    'id' => 8,
                    'dropdown_id' => 2,
                    'description' => 'Defective Equipment',
                    'order' => 5,
                    'other' => 0,
                ),
            8 =>
                array (
                    'id' => 9,
                    'dropdown_id' => 2,
                    'description' => 'Defective Machinery',
                    'order' => 6,
                    'other' => 0,
                ),
            9 =>
                array (
                    'id' => 10,
                    'dropdown_id' => 2,
                    'description' => 'Defective Plany',
                    'order' => 7,
                    'other' => 0,
                ),
            10 =>
                array (
                    'id' => 11,
                    'dropdown_id' => 2,
                    'description' => 'Drowning',
                    'order' => 8,
                    'other' => 0,
                ),
            11 =>
                array (
                    'id' => 12,
                    'dropdown_id' => 2,
                    'description' => 'Exposure to Asbestos',
                    'order' => 9,
                    'other' => 0,
                ),
            12 =>
                array (
                    'id' => 13,
                    'dropdown_id' => 2,
                    'description' => 'Electrocution',
                    'order' => 10,
                    'other' => 0,
                ),
            13 =>
                array (
                    'id' => 14,
                    'dropdown_id' => 2,
                    'description' => 'Fall From Height',
                    'order' => 11,
                    'other' => 0,
                ),
            14 =>
                array (
                    'id' => 15,
                    'dropdown_id' => 2,
                    'description' => 'Fall from Ladder or Scaffold',
                    'order' => 12,
                    'other' => 0,
                ),
            15 =>
                array (
                    'id' => 16,
                    'dropdown_id' => 2,
                    'description' => 'Fire/Explosion',
                    'order' => 13,
                    'other' => 0,
                ),
            16 =>
                array (
                    'id' => 17,
                    'dropdown_id' => 2,
                    'description' => 'Gas Leak',
                    'order' => 14,
                    'other' => 0,
                ),
            17 =>
                array (
                    'id' => 18,
                    'dropdown_id' => 2,
                    'description' => 'Injury Whilst Lifting',
                    'order' => 15,
                    'other' => 0,
                ),
            18 =>
                array (
                    'id' => 19,
                    'dropdown_id' => 2,
                    'description' => 'Lack of Personal Care',
                    'order' => 16,
                    'other' => 0,
                ),
            19 =>
                array (
                    'id' => 20,
                    'dropdown_id' => 2,
                    'description' => 'Manual Handling',
                    'order' => 17,
                    'other' => 0,
                ),
            20 =>
                array (
                    'id' => 21,
                    'dropdown_id' => 2,
                    'description' => 'Manual Handling - Lifting',
                    'order' => 18,
                    'other' => 0,
                ),
            21 =>
                array (
                    'id' => 22,
                    'dropdown_id' => 2,
                    'description' => 'Non-Passive Failure',
                    'order' => 19,
                    'other' => 0,
                ),
            22 =>
                array (
                    'id' => 23,
                    'dropdown_id' => 2,
                    'description' => 'Operator Error',
                    'order' => 20,
                    'other' => 0,
                ),
            23 =>
                array (
                    'id' => 24,
                    'dropdown_id' => 2,
                    'description' => 'Other',
                    'order' => 21,
                    'other' => 1,
                ),
            24 =>
                array (
                    'id' => 25,
                    'dropdown_id' => 2,
                    'description' => 'Poor Housekeeping',
                    'order' => 22,
                    'other' => 0,
                ),
            25 =>
                array (
                    'id' => 26,
                    'dropdown_id' => 2,
                    'description' => 'Slips Trips and Falls',
                    'order' => 23,
                    'other' => 0,
                ),
            26 =>
                array (
                    'id' => 27,
                    'dropdown_id' => 2,
                    'description' => 'Struck Against Something Fixed',
                    'order' => 24,
                    'other' => 0,
                ),
            27 =>
                array (
                    'id' => 28,
                    'dropdown_id' => 2,
                    'description' => 'Struck by Falling Moving Object',
                    'order' => 25,
                    'other' => 0,
                ),
            28 =>
                array (
                    'id' => 29,
                    'dropdown_id' => 2,
                    'description' => 'Struck by Moving Object',
                    'order' => 26,
                    'other' => 0,
                ),
            29 =>
                array (
                    'id' => 30,
                    'dropdown_id' => 2,
                    'description' => 'Struck by Moving Vehicle',
                    'order' => 27,
                    'other' => 0,
                ),
            30 =>
                array (
                    'id' => 31,
                    'dropdown_id' => 2,
                    'description' => 'Weather conditions',
                    'order' => 28,
                    'other' => 0,
                ),
            31 =>
                array (
                    'id' => 32,
                    'dropdown_id' => 3,
                    'description' => 'Amputation',
                    'order' => 1,
                    'other' => 0,
                ),
            32 =>
                array (
                    'id' => 33,
                    'dropdown_id' => 3,
                    'description' => 'Break',
                    'order' => 2,
                    'other' => 0,
                ),
            33 =>
                array (
                    'id' => 34,
                    'dropdown_id' => 3,
                    'description' => 'Bruise',
                    'order' => 3,
                    'other' => 0,
                ),
            34 =>
                array (
                    'id' => 35,
                    'dropdown_id' => 3,
                    'description' => 'Burn/Scald',
                    'order' => 4,
                    'other' => 0,
                ),
            35 =>
                array (
                    'id' => 36,
                    'dropdown_id' => 3,
                    'description' => 'Cut',
                    'order' => 5,
                    'other' => 0,
                ),
            36 =>
                array (
                    'id' => 37,
                    'dropdown_id' => 3,
                    'description' => 'Death',
                    'order' => 6,
                    'other' => 0,
                ),
            37 =>
                array (
                    'id' => 38,
                    'dropdown_id' => 3,
                    'description' => 'Dislocation',
                    'order' => 7,
                    'other' => 0,
                ),
            38 =>
                array (
                    'id' => 39,
                    'dropdown_id' => 3,
                    'description' => 'Fracture',
                    'order' => 8,
                    'other' => 0,
                ),
            39 =>
                array (
                    'id' => 40,
                    'dropdown_id' => 3,
                    'description' => 'Graze(s)',
                    'order' => 9,
                    'other' => 0,
                ),
            40 =>
                array (
                    'id' => 41,
                    'dropdown_id' => 3,
                    'description' => 'Infalmmation',
                    'order' => 10,
                    'other' => 0,
                ),
            41 =>
                array (
                    'id' => 42,
                    'dropdown_id' => 3,
                    'description' => 'Irritation',
                    'order' => 11,
                    'other' => 0,
                ),
            42 =>
                array (
                    'id' => 43,
                    'dropdown_id' => 3,
                    'description' => 'Laceration',
                    'order' => 12,
                    'other' => 0,
                ),
            43 =>
                array (
                    'id' => 44,
                    'dropdown_id' => 3,
                    'description' => 'Psychological',
                    'order' => 13,
                    'other' => 0,
                ),
            44 =>
                array (
                    'id' => 45,
                    'dropdown_id' => 3,
                    'description' => 'Muscular/Pulled Ligaments',
                    'order' => 14,
                    'other' => 0,
                ),
            45 =>
                array (
                    'id' => 46,
                    'dropdown_id' => 3,
                    'description' => 'Respiratory',
                    'order' => 15,
                    'other' => 0,
                ),
//            46 =>
//                array (
//                    'id' => 47,
//                    'dropdown_id' => 3,
//                    'description' => 'Other',
//                    'order' => 16,
//                    'other' => 1,
//                ),
            47 =>
                array (
                    'id' => 48,
                    'dropdown_id' => 3,
                    'description' => 'Slipped Disc',
                    'order' => 17,
                    'other' => 0,
                ),
            48 =>
                array (
                    'id' => 49,
                    'dropdown_id' => 3,
                    'description' => 'Sprain',
                    'order' => 18,
                    'other' => 0,
                ),
            49 =>
                array (
                    'id' => 50,
                    'dropdown_id' => 3,
                    'description' => 'Whiplash',
                    'order' => 19,
                    'other' => 0,
                ),
            50 =>
                array (
                    'id' => 51,
                    'dropdown_id' => 4,
                    'description' => 'Ankle',
                    'order' => 1,
                    'other' => 0,
                ),
            51 =>
                array (
                    'id' => 52,
                    'dropdown_id' => 4,
                    'description' => 'Back',
                    'order' => 2,
                    'other' => 0,
                ),
            52 =>
                array (
                    'id' => 53,
                    'dropdown_id' => 4,
                    'description' => 'Ear',
                    'order' => 3,
                    'other' => 0,
                ),
            53 =>
                array (
                    'id' => 54,
                    'dropdown_id' => 4,
                    'description' => 'Eye',
                    'order' => 4,
                    'other' => 0,
                ),
            54 =>
                array (
                    'id' => 55,
                    'dropdown_id' => 4,
                    'description' => 'Face',
                    'order' => 5,
                    'other' => 0,
                ),
            55 =>
                array (
                    'id' => 56,
                    'dropdown_id' => 4,
                    'description' => 'Finger(s)',
                    'order' => 6,
                    'other' => 0,
                ),
            56 =>
                array (
                    'id' => 57,
                    'dropdown_id' => 4,
                    'description' => 'Hand',
                    'order' => 7,
                    'other' => 0,
                ),
            57 =>
                array (
                    'id' => 58,
                    'dropdown_id' => 4,
                    'description' => 'Internal Organ(s)',
                    'order' => 8,
                    'other' => 0,
                ),
            58 =>
                array (
                    'id' => 59,
                    'dropdown_id' => 4,
                    'description' => 'Head (Excluding Face)',
                    'order' => 9,
                    'other' => 0,
                ),
            59 =>
                array (
                    'id' => 60,
                    'dropdown_id' => 4,
                    'description' => 'Psychological',
                    'order' => 10,
                    'other' => 0,
                ),
            60 =>
                array (
                    'id' => 61,
                    'dropdown_id' => 4,
                    'description' => 'Neck',
                    'order' => 11,
                    'other' => 0,
                ),
            61 =>
                array (
                    'id' => 62,
                    'dropdown_id' => 4,
                    'description' => 'Left Leg',
                    'order' => 12,
                    'other' => 0,
                ),
            62 =>
                array (
                    'id' => 63,
                    'dropdown_id' => 4,
                    'description' => 'Right Leg',
                    'order' => 13,
                    'other' => 0,
                ),
            63 =>
                array (
                    'id' => 64,
                    'dropdown_id' => 4,
                    'description' => 'Left Arm',
                    'order' => 14,
                    'other' => 0,
                ),
            64 =>
                array (
                    'id' => 65,
                    'dropdown_id' => 4,
                    'description' => 'Right Arm',
                    'order' => 15,
                    'other' => 0,
                ),
            65 =>
                array (
                    'id' => 66,
                    'dropdown_id' => 4,
                    'description' => 'Torso',
                    'order' => 16,
                    'other' => 0,
                ),
            66 =>
                array (
                    'id' => 67,
                    'dropdown_id' => 4,
                    'description' => 'Several Locations',
                    'order' => 17,
                    'other' => 0,
                ),
            67 =>
                array (
                    'id' => 68,
                    'dropdown_id' => 4,
                    'description' => 'Unspecified Locations',
                    'order' => 18,
                    'other' => 0,
                ),
            68 =>
                array (
                    'id' => 69,
                    'dropdown_id' => 4,
                    'description' => 'Wrist',
                    'order' => 19,
                    'other' => 0,
                ),
            69 =>
                array (
                    'id' => 70,
                    'dropdown_id' => 5,
                    'description' => 'Capital Works',
                    'order' => 1,
                    'other' => 0,
                ),
            70 =>
                array (
                    'id' => 71,
                    'dropdown_id' => 5,
                    'description' => 'Minor Works',
                    'order' => 2,
                    'other' => 0,
                ),
            71 =>
                array (
                    'id' => 72,
                    'dropdown_id' => 5,
                    'description' => 'PPM (Planned Preventative Maintenance)',
                    'order' => 3,
                    'other' => 0,
                ),
            72 =>
                array (
                    'id' => 73,
                    'dropdown_id' => 5,
                    'description' => 'Responsive Repairs/Call out',
                    'order' => 4,
                    'other' => 0,
                ),
            73 =>
                array (
                    'id' => 74,
                    'dropdown_id' => 1,
                    'description' => 'Social Care',
                    'order' => 4,
                    'other' => 0,
                ),
            74 =>
                array (
                    'id' => 75,
                    'dropdown_id' => 3,
                    'description' => 'Witness Only',
                    'order' => 20,
                    'other' => 0,
                ),
        ));
    }
}
