<?php

use Illuminate\Database\Seeder;

class TblWorkDataTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('tbl_work_data')->delete();

        \DB::table('tbl_work_data')->insert(array (
            0 =>
            array (
                'id' => 1,
                'description' => 'Asbestos Air Monitoring',
                'other' => NULL,
                'parent_id' => 0,
                'type' => 'workType',
                'compliance_type' => 1,
            ),
            1 =>
            array (
                'id' => 2,
                'description' => 'Asbestos Remediation',
                'other' => NULL,
                'parent_id' => 0,
                'type' => 'workType',
                'compliance_type' => 1,
            ),
            2 =>
            array (
                'id' => 3,
                'description' => 'Asbestos Survey',
                'other' => NULL,
                'parent_id' => 0,
                'type' => 'workType',
                'compliance_type' => 1,
            ),
            3 =>
            array (
                'id' => 4,
                'description' => 'Site Occupied',
                'other' => NULL,
                'parent_id' => 0,
                'type' => 'siteOccupied',
                'compliance_type' => 1,
            ),
            4 =>
            array (
                'id' => 5,
                'description' => 'Parking Arrangements',
                'other' => NULL,
                'parent_id' => 0,
                'type' => 'parkingArrangements',
                'compliance_type' => 1,
            ),
            5 =>
            array (
                'id' => 6,
                'description' => 'Ceiling Height',
                'other' => NULL,
                'parent_id' => 0,
                'type' => 'ceilingHeight',
                'compliance_type' => 1,
            ),
            6 =>
            array (
                'id' => 7,
                'description' => 'Personal Monitoring',
                'other' => NULL,
                'parent_id' => 1,
                'type' => 'air',
                'compliance_type' => 1,
            ),
            7 =>
            array (
                'id' => 10,
                'description' => 'Background Test',
                'other' => NULL,
                'parent_id' => 1,
                'type' => 'air',
                'compliance_type' => 1,
            ),
            8 =>
            array (
                'id' => 11,
                'description' => 'Reassurance Test',
                'other' => NULL,
                'parent_id' => 1,
                'type' => 'air',
                'compliance_type' => 1,
            ),
            9 =>
            array (
                'id' => 12,
                'description' => '4-Stage Clearance',
                'other' => NULL,
                'parent_id' => 1,
                'type' => 'air',
                'compliance_type' => 1,
            ),
            10 =>
            array (
                'id' => 15,
                'description' => 'Remediation',
                'other' => NULL,
                'parent_id' => 2,
                'type' => 'remediation',
                'compliance_type' => 1,
            ),
            11 =>
            array (
                'id' => 16,
                'description' => 'Management Survey',
                'other' => NULL,
                'parent_id' => 3,
                'type' => 'survey',
                'compliance_type' => 1,
            ),
            12 =>
            array (
                'id' => 17,
                'description' => 'Refurbishment Survey',
                'other' => NULL,
                'parent_id' => 3,
                'type' => 'survey',
                'compliance_type' => 1,
            ),
            13 =>
            array (
                'id' => 18,
                'description' => 'Management & Refurbishment Survey',
                'other' => NULL,
                'parent_id' => 3,
                'type' => 'survey',
                'compliance_type' => 1,
            ),
            14 =>
            array (
                'id' => 19,
                'description' => 'Demolition Survey',
                'other' => NULL,
                'parent_id' => 3,
                'type' => 'survey',
                'compliance_type' => 1,
            ),
            15 =>
            array (
                'id' => 20,
                'description' => 'Re-Inspection Survey',
                'other' => NULL,
                'parent_id' => 3,
                'type' => 'survey',
                'compliance_type' => 1,
            ),
            16 =>
            array (
                'id' => 21,
                'description' => 'Sample Survey',
                'other' => NULL,
                'parent_id' => 3,
                'type' => 'survey',
                'compliance_type' => 1,
            ),
            17 =>
            array (
                'id' => 22,
            'description' => 'Immediate (0)',
                'other' => 1,
                'parent_id' => 4,
                'type' => 'siteOccupied',
                'compliance_type' => 1,
            ),
            18 =>
            array (
                'id' => 23,
            'description' => 'Emergency (1)',
                'other' => 1,
                'parent_id' => 4,
                'type' => 'siteOccupied',
                'compliance_type' => 1,
            ),
            19 =>
            array (
                'id' => 24,
            'description' => 'Urgent Work (2)',
                'other' => 1,
                'parent_id' => 4,
                'type' => 'siteOccupied',
                'compliance_type' => 1,
            ),
            20 =>
            array (
                'id' => 25,
            'description' => 'Routine (5)',
                'other' => NULL,
                'parent_id' => 4,
                'type' => 'siteOccupied',
                'compliance_type' => 1,
            ),
            21 =>
            array (
                'id' => 26,
                'description' => 'Property Parking Available',
                'other' => NULL,
                'parent_id' => 5,
                'type' => 'parkingArrangements',
                'compliance_type' => 1,
            ),
            22 =>
            array (
                'id' => 27,
                'description' => 'Private Car Park',
                'other' => NULL,
                'parent_id' => 5,
                'type' => 'parkingArrangements',
                'compliance_type' => 1,
            ),
            23 =>
            array (
                'id' => 28,
                'description' => 'Street Parking Only',
                'other' => NULL,
                'parent_id' => 5,
                'type' => 'parkingArrangements',
                'compliance_type' => 1,
            ),
            24 =>
            array (
                'id' => 29,
                'description' => 'Other',
                'other' => 1,
                'parent_id' => 5,
                'type' => 'parkingArrangements',
                'compliance_type' => 1,
            ),
            25 =>
            array (
                'id' => 30,
            'description' => 'Standard (3m)',
                'other' => NULL,
                'parent_id' => 6,
                'type' => 'ceilingHeight',
                'compliance_type' => 1,
            ),
            26 =>
            array (
                'id' => 31,
            'description' => 'Long (6m)',
                'other' => NULL,
                'parent_id' => 6,
                'type' => 'ceilingHeight',
                'compliance_type' => 1,
            ),
            27 =>
            array (
                'id' => 32,
            'description' => 'Tower (4m)',
                'other' => NULL,
                'parent_id' => 6,
                'type' => 'ceilingHeight',
                'compliance_type' => 1,
            ),
            28 =>
            array (
                'id' => 33,
            'description' => 'Tower (6-10m)',
                'other' => NULL,
                'parent_id' => 6,
                'type' => 'ceilingHeight',
                'compliance_type' => 1,
            ),
            29 =>
            array (
                'id' => 34,
            'description' => 'Power (10m+)',
                'other' => NULL,
                'parent_id' => 6,
                'type' => 'ceilingHeight',
                'compliance_type' => 1,
            ),
            30 =>
            array (
                'id' => 35,
                'description' => 'Programmed Work Request',
                'other' => NULL,
                'parent_id' => 0,
                'type' => 'workType',
                'compliance_type' => 1,
            ),
            31 =>
            array (
                'id' => 36,
                'description' => 'Asbestos Air Monitoring',
                'other' => NULL,
                'parent_id' => 35,
                'type' => 'workMajor',
                'compliance_type' => 1,
            ),
            32 =>
            array (
                'id' => 37,
                'description' => 'Asbestos Remediation',
                'other' => NULL,
                'parent_id' => 35,
                'type' => 'workMajor',
                'compliance_type' => 1,
            ),
            33 =>
            array (
                'id' => 38,
                'description' => 'Asbestos Survey',
                'other' => NULL,
                'parent_id' => 35,
                'type' => 'workMajor',
                'compliance_type' => 1,
            ),
            34 =>
            array (
                'id' => 39,
                'description' => 'Personal Monitoring',
                'other' => NULL,
                'parent_id' => 36,
                'type' => 'air',
                'compliance_type' => 1,
            ),
            35 =>
            array (
                'id' => 40,
                'description' => 'Leak Test',
                'other' => NULL,
                'parent_id' => 36,
                'type' => 'air',
                'compliance_type' => 1,
            ),
            36 =>
            array (
                'id' => 41,
                'description' => 'Smoke Test',
                'other' => NULL,
                'parent_id' => 36,
                'type' => 'air',
                'compliance_type' => 1,
            ),
            37 =>
            array (
                'id' => 42,
                'description' => 'Background Test',
                'other' => NULL,
                'parent_id' => 36,
                'type' => 'air',
                'compliance_type' => 1,
            ),
            38 =>
            array (
                'id' => 43,
                'description' => 'Reassurance Test',
                'other' => NULL,
                'parent_id' => 36,
                'type' => 'air',
                'compliance_type' => 1,
            ),
            39 =>
            array (
                'id' => 44,
                'description' => '4-Stage Clearance',
                'other' => NULL,
                'parent_id' => 36,
                'type' => 'air',
                'compliance_type' => 1,
            ),
            40 =>
            array (
                'id' => 45,
                'description' => 'DCU Clearance Testing',
                'other' => NULL,
                'parent_id' => 36,
                'type' => 'air',
                'compliance_type' => 1,
            ),
            41 =>
            array (
                'id' => 46,
                'description' => 'Perimeter Monitoring',
                'other' => NULL,
                'parent_id' => 36,
                'type' => 'air',
                'compliance_type' => 1,
            ),
            42 =>
            array (
                'id' => 47,
                'description' => 'Remediation',
                'other' => NULL,
                'parent_id' => 37,
                'type' => 'remediation',
                'compliance_type' => 1,
            ),
            43 =>
            array (
                'id' => 48,
                'description' => 'Management Survey',
                'other' => NULL,
                'parent_id' => 38,
                'type' => 'survey',
                'compliance_type' => 1,
            ),
            44 =>
            array (
                'id' => 49,
                'description' => 'Refurbishment Survey',
                'other' => NULL,
                'parent_id' => 38,
                'type' => 'survey',
                'compliance_type' => 1,
            ),
            45 =>
            array (
                'id' => 50,
                'description' => 'Management & Refurbishment Survey',
                'other' => NULL,
                'parent_id' => 38,
                'type' => 'survey',
                'compliance_type' => 1,
            ),
            46 =>
            array (
                'id' => 51,
                'description' => 'Demolition Survey',
                'other' => NULL,
                'parent_id' => 38,
                'type' => 'survey',
                'compliance_type' => 1,
            ),
            47 =>
            array (
                'id' => 52,
                'description' => 'Re-Inspection Survey',
                'other' => NULL,
                'parent_id' => 38,
                'type' => 'survey',
                'compliance_type' => 1,
            ),
            48 =>
            array (
                'id' => 53,
                'description' => 'Sample Survey',
                'other' => NULL,
                'parent_id' => 38,
                'type' => 'survey',
                'compliance_type' => 1,
            ),
            49 =>
            array (
                'id' => 54,
                'description' => 'SEM Testing',
                'other' => NULL,
                'parent_id' => 1,
                'type' => 'air',
                'compliance_type' => 1,
            ),
            50 =>
            array (
                'id' => 55,
                'description' => 'Fire Assessment',
                'other' => NULL,
                'parent_id' => 0,
                'type' => 'workType',
                'compliance_type' => 2,
            ),
            51 =>
            array (
                'id' => 56,
                'description' => 'Fire Remediation',
                'other' => NULL,
                'parent_id' => 0,
                'type' => 'workType',
                'compliance_type' => 2,
            ),
            52 =>
            array (
                'id' => 57,
                'description' => 'Programmed Work Request',
                'other' => NULL,
                'parent_id' => 0,
                'type' => 'workType',
                'compliance_type' => 2,
            ),
            53 =>
            array (
                'id' => 58,
                'description' => 'Equipment Assessment',
                'other' => NULL,
                'parent_id' => 55,
                'type' => 'survey',
                'compliance_type' => 2,
            ),
            54 =>
            array (
                'id' => 59,
                'description' => 'Risk Assessment',
                'other' => NULL,
                'parent_id' => 55,
                'type' => 'survey',
                'compliance_type' => 2,
            ),
            55 =>
            array (
                'id' => 60,
                'description' => 'Remediation',
                'other' => NULL,
                'parent_id' => 56,
                'type' => 'remediation',
                'compliance_type' => 2,
            ),
            56 =>
            array (
                'id' => 61,
                'description' => 'Fire Assessment',
                'other' => NULL,
                'parent_id' => 57,
                'type' => 'workMajor',
                'compliance_type' => 0,
            ),
            57 =>
            array (
                'id' => 62,
                'description' => 'Fire Remediation',
                'other' => NULL,
                'parent_id' => 57,
                'type' => 'workMajor',
                'compliance_type' => 0,
            ),
            58 =>
            array (
                'id' => 63,
                'description' => 'Equipment Assessment',
                'other' => NULL,
                'parent_id' => 61,
                'type' => 'survey',
                'compliance_type' => 0,
            ),
            59 =>
            array (
                'id' => 64,
                'description' => 'Risk Assessment',
                'other' => NULL,
                'parent_id' => 61,
                'type' => 'survey',
                'compliance_type' => 0,
            ),
            60 =>
            array (
                'id' => 65,
                'description' => 'Remediation',
                'other' => NULL,
                'parent_id' => 62,
                'type' => 'remediation',
                'compliance_type' => 0,
            ),
        ));


    }
}
