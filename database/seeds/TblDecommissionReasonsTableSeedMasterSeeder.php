<?php

use Illuminate\Database\Seeder;

class TblDecommissionReasonsTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('tbl_decommission_reasons')->delete();

        \DB::table('tbl_decommission_reasons')->insert(array (
            0 =>
            array (
                'id' => 1,
                'type' => 'area',
                'parent_id' => 1,
                'description' => 'Surveyor Error',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'type' => 'area',
                'parent_id' => 1,
                'description' => 'Removed during Refurbishment Works',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'type' => 'location',
                'parent_id' => 1,
                'description' => 'Surveyor Error',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            3 =>
            array (
                'id' => 4,
                'type' => 'location',
                'parent_id' => 1,
                'description' => 'Removed during Refurbishment Works',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            4 =>
            array (
                'id' => 5,
                'type' => 'item',
                'parent_id' => 1,
                'description' => 'Surveyor Error',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            5 =>
            array (
                'id' => 6,
                'type' => 'item',
                'parent_id' => 1,
                'description' => 'Removed during Refurbishment Works',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            6 =>
            array (
                'id' => 7,
                'type' => 'area',
                'parent_id' => 2,
                'description' => 'Unsafe Access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            7 =>
            array (
                'id' => 8,
                'type' => 'area',
                'parent_id' => 2,
                'description' => 'No access as Area/floor is a restricted location – no access approved by area owner',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            8 =>
            array (
                'id' => 9,
                'type' => 'area',
                'parent_id' => 2,
                'description' => 'No access as area owner was unavailable to approve access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            9 =>
            array (
                'id' => 10,
                'type' => 'area',
                'parent_id' => 2,
                'description' => 'No access as area requires specifically trained engineer/operative',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            10 =>
            array (
                'id' => 11,
                'type' => 'area',
                'parent_id' => 2,
                'description' => 'No access as the area was unsafe at the time of the survey',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            11 =>
            array (
                'id' => 12,
                'type' => 'area',
                'parent_id' => 2,
                'description' => 'Limited access due to the presence of fixed services',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            12 =>
            array (
                'id' => 13,
                'type' => 'area',
                'parent_id' => 2,
                'description' => 'Limited access due to stored items/equipment',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            13 =>
            array (
                'id' => 14,
                'type' => 'area',
                'parent_id' => 2,
                'description' => 'Electrical Hazard/high voltage',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            14 =>
            array (
                'id' => 15,
                'type' => 'location',
                'parent_id' => 2,
                'description' => 'Unsafe Access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            15 =>
            array (
                'id' => 16,
                'type' => 'location',
                'parent_id' => 2,
                'description' => 'No access as Room/location is a restricted location – no access approved by area owner',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            16 =>
            array (
                'id' => 17,
                'type' => 'location',
                'parent_id' => 2,
                'description' => 'No access as area owner was unavailable to approve access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            17 =>
            array (
                'id' => 18,
                'type' => 'location',
                'parent_id' => 2,
                'description' => 'No access as area requires specifically trained engineer/operative',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            18 =>
            array (
                'id' => 19,
                'type' => 'location',
                'parent_id' => 2,
                'description' => 'No access as the area was unsafe at the time of the survey',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            19 =>
            array (
                'id' => 20,
                'type' => 'location',
                'parent_id' => 2,
                'description' => 'Limited access due to the presence of fixed services',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            20 =>
            array (
                'id' => 21,
                'type' => 'location',
                'parent_id' => 2,
                'description' => 'Limited access due to stored items/equipment',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            21 =>
            array (
                'id' => 22,
                'type' => 'location',
                'parent_id' => 2,
                'description' => 'Electrical Hazard/high voltage',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            22 =>
            array (
                'id' => 23,
                'type' => 'item',
                'parent_id' => 2,
                'description' => 'Unsafe Access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            23 =>
            array (
                'id' => 24,
                'type' => 'item',
                'parent_id' => 2,
                'description' => 'No access as Room/location is a restricted location – no access approved by area owner',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            24 =>
            array (
                'id' => 25,
                'type' => 'item',
                'parent_id' => 2,
                'description' => 'No access as area owner was unavailable to approve access',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            25 =>
            array (
                'id' => 26,
                'type' => 'item',
                'parent_id' => 2,
                'description' => 'No access as area requires specifically trained engineer/operative',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            26 =>
            array (
                'id' => 27,
                'type' => 'item',
                'parent_id' => 2,
                'description' => 'No access as the area was unsafe at the time of the survey',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            27 =>
            array (
                'id' => 28,
                'type' => 'item',
                'parent_id' => 2,
                'description' => 'Limited access due to the presence of fixed services',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            28 =>
            array (
                'id' => 29,
                'type' => 'item',
                'parent_id' => 2,
                'description' => 'Limited access due to stored items/equipment',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            29 =>
            array (
                'id' => 30,
                'type' => 'item',
                'parent_id' => 2,
                'description' => 'Electrical Hazard/high voltage',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            30 =>
            array (
                'id' => 31,
                'type' => 'area',
                'parent_id' => 3,
                'description' => 'Back Office Error',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            31 =>
            array (
                'id' => 32,
                'type' => 'location',
                'parent_id' => 3,
                'description' => 'Back Office Error',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            32 =>
            array (
                'id' => 33,
                'type' => 'item',
                'parent_id' => 3,
                'description' => 'Back Office Error',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            33 =>
            array (
                'id' => 34,
                'type' => 'area',
                'parent_id' => 4,
                'description' => 'User Error',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            34 =>
            array (
                'id' => 35,
                'type' => 'location',
                'parent_id' => 4,
                'description' => 'User Error',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            35 =>
            array (
                'id' => 36,
                'type' => 'item',
                'parent_id' => 4,
                'description' => 'User Error',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            36 =>
            array (
                'id' => 37,
                'type' => 'area',
                'parent_id' => 1,
                'description' => 'Removed as part of a Removal Project',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            37 =>
            array (
                'id' => 38,
                'type' => 'location',
                'parent_id' => 1,
                'description' => 'Removed as part of a Removal Project',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            38 =>
            array (
                'id' => 39,
                'type' => 'item',
                'parent_id' => 1,
                'description' => 'Removed as part of a Removal Project',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            39 =>
            array (
                'id' => 40,
                'type' => 'property',
                'parent_id' => 1,
                'description' => 'Property No Longer under Management',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            40 =>
            array (
                'id' => 41,
                'type' => 'property',
                'parent_id' => 1,
                'description' => 'Property Demolished',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            41 =>
            array (
                'id' => 42,
                'type' => 'work_request',
                'parent_id' => 1,
                'description' => 'Cancelled by the Asbestos Team',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            42 =>
            array (
                'id' => 43,
                'type' => 'work_request',
                'parent_id' => 1,
                'description' => 'Created in Error',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            43 =>
            array (
                'id' => 44,
                'type' => 'work_request',
                'parent_id' => 1,
                'description' => 'No Longer Required',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            44 =>
            array (
                'id' => 45,
                'type' => 'work_request',
                'parent_id' => 1,
                'description' => 'Duplicate Request',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            45 =>
            array (
                'id' => 46,
                'type' => 'audit',
                'parent_id' => 1,
                'description' => 'Cancelled by the Asbestos Team',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            46 =>
            array (
                'id' => 47,
                'type' => 'audit',
                'parent_id' => 1,
                'description' => 'Created in Error',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            47 =>
            array (
                'id' => 48,
                'type' => 'audit',
                'parent_id' => 1,
                'description' => 'No Longer Required',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            48 =>
            array (
                'id' => 49,
                'type' => 'audit',
                'parent_id' => 1,
                'description' => 'Duplicate Audit',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            49 =>
            array (
                'id' => 50,
                'type' => 'assessment_fire',
                'parent_id' => 1,
                'description' => 'Cancelled by the Fire Safey Team',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            50 =>
            array (
                'id' => 51,
                'type' => 'assessment_fire',
                'parent_id' => 1,
                'description' => 'Created in Error',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            51 =>
            array (
                'id' => 52,
                'type' => 'assessment_fire',
                'parent_id' => 1,
                'description' => 'No Longer Required',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            52=>
            array (
                'id' => 53,
                'type' => 'assessment_fire',
                'parent_id' => 1,
                'description' => 'Duplicate Request',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            53 =>
            array (
                'id' => 54,
                'type' => 'assessment_water',
                'parent_id' => 1,
                'description' => 'Cancelled by the Water Safey Team',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            54 =>
            array (
                'id' => 55,
                'type' => 'assessment_water',
                'parent_id' => 1,
                'description' => 'Created in Error',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            55 =>
            array (
                'id' => 56,
                'type' => 'assessment_water',
                'parent_id' => 1,
                'description' => 'No Longer Required',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            56=>
            array (
                'id' => 57,
                'type' => 'assessment_water',
                'parent_id' => 1,
                'description' => 'Duplicate Request',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            57 =>
                array (
                    'id' => 58,
                    'type' => 'asbestos',
                    'parent_id' => 1,
                    'description' => 'Cancelled by the Asbestos Safey Team',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            58 =>
                array (
                    'id' => 59,
                    'type' => 'asbestos',
                    'parent_id' => 1,
                    'description' => 'Created in Error',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            59 =>
                array (
                    'id' => 60,
                    'type' => 'asbestos',
                    'parent_id' => 1,
                    'description' => 'No Longer Required',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            60=>
                array (
                    'id' => 61,
                    'type' => 'asbestos',
                    'parent_id' => 1,
                    'description' => 'Duplicate Request',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            61=>
                array (
                    'id' => 62,
                    'type' => 'incident_report',
                    'parent_id' => 1,
                    'description' => 'Cancelled by the H&S Team',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            62=>
                array (
                    'id' => 63,
                    'type' => 'incident_report',
                    'parent_id' => 1,
                    'description' => 'Created in Error',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            63=>
                array (
                    'id' => 64,
                    'type' => 'incident_report',
                    'parent_id' => 1,
                    'description' => 'No Longer Required',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            64=>
                array (
                    'id' => 65,
                    'type' => 'incident_report',
                    'parent_id' => 1,
                    'description' => 'Duplicate Request',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            65 =>
            array (
                'id' => 66,
                'type' => 'assessment_hs',
                'parent_id' => 1,
                'description' => 'Cancelled by the Health and Safey Team',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            66 =>
            array (
                'id' => 67,
                'type' => 'assessment_hs',
                'parent_id' => 1,
                'description' => 'Created in Error',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            67 =>
            array (
                'id' => 68,
                'type' => 'assessment_hs',
                'parent_id' => 1,
                'description' => 'No Longer Required',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            68 =>
                array (
                    'id' => 69,
                    'type' => 'project',
                    'parent_id' => 1,
                    'description' => 'Cancelled by the Asbestos Safey Team',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            69 =>
                array (
                    'id' => 70,
                    'type' => 'project',
                    'parent_id' => 1,
                    'description' => 'Created in Error',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            70 =>
                array (
                    'id' => 71,
                    'type' => 'project',
                    'parent_id' => 1,
                    'description' => 'No Longer Required',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            71=>
                array (
                    'id' => 72,
                    'type' => 'project',
                    'parent_id' => 1,
                    'description' => 'Duplicate Request',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            72 =>
                array (
                    'id' => 73,
                    'type' => 'hazard',
                    'parent_id' => 1,
                    'description' => 'Surveyor Error',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            73 =>
                array (
                    'id' => 74,
                    'type' => 'hazard',
                    'parent_id' => 1,
                    'description' => 'Removed during Refurbishment Works',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            74=>
                array (
                    'id' => 75,
                    'type' => 'hazard',
                    'parent_id' => 1,
                    'description' => 'Removed as part of a Removal Project',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
        ));

    }
}
