<?php

use Illuminate\Database\Seeder;

class CpPrivilegeViewTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('cp_privilege_view')->delete();

        \DB::table('cp_privilege_view')->insert(array (
            0 =>
            array (
                'id' => 1,
                'note' => '',
                'name' => 'Data Centre',
                'type' => 1,
                'parent_id' => 0,
                'order' => 1,
                'is_deleted' => 0,
            ),
            1 =>
            array (
                'id' => 2,
                'note' => '',
                'name' => 'Dashboard',
                'type' => 1,
                'parent_id' => 1,
                'order' => 2,
                'is_deleted' => 0,
            ),
            2 =>
            array (
                'id' => 3,
                'note' => '',
                'name' => 'Certificates',
                'type' => 2,
                'parent_id' => 39,
                'order' => 42,
                'is_deleted' => 0,
            ),
            3 =>
            array (
                'id' => 4,
                'note' => '',
                'name' => 'Property Listing',
                'type' => 1,
                'parent_id' => 0,
                'order' => 4,
                'is_deleted' => 0,
            ),
            4 =>
            array (
                'id' => 5,
                'note' => '',
                'name' => 'All Properties',
                'type' => 1,
                'parent_id' => 4,
                'order' => 5,
                'is_deleted' => 1,
            ),
            5 =>
            array (
                'id' => 6,
                'note' => '',
                'name' => 'Property EMP',
                'type' => 1,
                'parent_id' => 4,
                'order' => 6,
                'is_deleted' => 0,
            ),
            6 =>
            array (
                'id' => 7,
            'note' => '(dynamic)',
                'name' => 'Clients',
                'type' => 1,
                'parent_id' => 4,
                'order' => 7,
                'is_deleted' => 1,
            ),
            7 =>
            array (
                'id' => 8,
                'note' => '',
                'name' => 'Properties Information',
                'type' => 1,
                'parent_id' => 0,
                'order' => 8,
                'is_deleted' => 0,
            ),
            8 =>
            array (
                'id' => 9,
                'note' => '',
                'name' => 'Details',
                'type' => 1,
                'parent_id' => 8,
                'order' => 9,
                'is_deleted' => 0,
            ),
            9 =>
            array (
                'id' => 10,
                'note' => '',
                'name' => 'Register',
                'type' => 1,
                'parent_id' => 8,
                'order' => 10,
                'is_deleted' => 0,
            ),
            10 =>
            array (
                'id' => 11,
                'note' => '',
                'name' => 'Overall',
                'type' => 1,
                'parent_id' => 10,
                'order' => 11,
                'is_deleted' => 0,
            ),
            11 =>
            array (
                'id' => 12,
                'note' => '',
                'name' => 'Sub Property',
                'type' => 1,
                'parent_id' => 8,
                'order' => 12,
                'is_deleted' => 0,
            ),
            12 =>
            array (
                'id' => 13,
                'note' => '',
                'name' => 'Systems',
                'type' => 1,
                'parent_id' => 8,
                'order' => 13,
                'is_deleted' => 0,
            ),
            13 =>
            array (
                'id' => 14,
                'note' => '',
                'name' => 'Equipment',
                'type' => 1,
                'parent_id' => 8,
                'order' => 14,
                'is_deleted' => 0,
            ),
            14 =>
            array (
                'id' => 15,
                'note' => '',
                'name' => 'Area/floors',
                'type' => 1,
                'parent_id' => 8,
                'order' => 15,
                'is_deleted' => 0,
            ),
            15 =>
            array (
                'id' => 16,
                'note' => '',
                'name' => 'Fire Exits & Assembly Points',
                'type' => 1,
                'parent_id' => 8,
                'order' => 16,
                'is_deleted' => 0,
            ),
            16 =>
            array (
                'id' => 17,
                'note' => '',
                'name' => 'Vehicle Parking',
                'type' => 1,
                'parent_id' => 8,
                'order' => 17,
                'is_deleted' => 0,
            ),
            17 =>
            array (
                'id' => 18,
                'note' => '',
                'name' => 'Documents',
                'type' => 1,
                'parent_id' => 8,
                'order' => 18,
                'is_deleted' => 0,
            ),
            18 =>
            array (
                'id' => 19,
            'note' => '(privilege child)',
                'name' => 'Email Notifications',
                'type' => 1,
                'parent_id' => 0,
                'order' => 19,
                'is_deleted' => 0,
            ),
            19 =>
            array (
                'id' => 20,
                'note' => '',
                'name' => 'Organisational',
                'type' => 1,
                'parent_id' => 0,
                'order' => 20,
                'is_deleted' => 0,
            ),
            20 =>
            array (
                'id' => 21,
                'note' => '',
                'name' => 'Organisation EMP',
                'type' => 1,
                'parent_id' => 20,
                'order' => 21,
                'is_deleted' => 0,
            ),
            21 =>
            array (
                'id' => 22,
            'note' => '(dynamic)',
                'name' => 'System Owner',
                'type' => 1,
                'parent_id' => 20,
                'order' => 22,
                'is_deleted' => 1,
            ),
            22 =>
            array (
                'id' => 23,
            'note' => '(dynamic)',
                'name' => 'Clients
',
                'type' => 1,
                'parent_id' => 20,
                'order' => 23,
                'is_deleted' => 1,
            ),
            23 =>
            array (
                'id' => 24,
            'note' => '(dynamic)',
                'name' => 'Contractors',
                'type' => 1,
                'parent_id' => 20,
                'order' => 24,
                'is_deleted' => 1,
            ),
            24 =>
            array (
                'id' => 25,
            'note' => '(privilege child)',
                'name' => 'Reporting',
                'type' => 1,
                'parent_id' => 0,
                'order' => 25,
                'is_deleted' => 0,
            ),
            25 =>
            array (
                'id' => 26,
                'note' => '',
                'name' => 'Resources',
                'type' => 1,
                'parent_id' => 0,
                'order' => 26,
                'is_deleted' => 0,
            ),
            26 =>
            array (
                'id' => 27,
                'note' => '',
                'name' => 'Resources EMP',
                'type' => 1,
                'parent_id' => 26,
                'order' => 27,
                'is_deleted' => 0,
            ),
            27 =>
            array (
                'id' => 28,
                'note' => '',
                'name' => 'Administrator Functions',
                'type' => 1,
                'parent_id' => 26,
                'order' => 28,
                'is_deleted' => 0,
            ),
            28 =>
            array (
                'id' => 29,
                'note' => '',
                'name' => 'E-Learning',
                'type' => 1,
                'parent_id' => 26,
                'order' => 29,
                'is_deleted' => 0,
            ),
            29 =>
            array (
                'id' => 30,
                'note' => '',
                'name' => 'Job Roles',
                'type' => 1,
                'parent_id' => 26,
                'order' => 30,
                'is_deleted' => 1,
            ),
            30 =>
            array (
                'id' => 31,
                'note' => '',
                'name' => 'Work Request',
                'type' => 1,
                'parent_id' => 26,
                'order' => 31,
                'is_deleted' => 1,
            ),
            31 =>
            array (
                'id' => 32,
            'note' => '(dynamic)',
                'name' => 'Workflow Request',
                'type' => 1,
                'parent_id' => 31,
                'order' => 32,
                'is_deleted' => 1,
            ),
            32 =>
            array (
                'id' => 33,
                'note' => '(dynamic)',
                'name' => 'Work Request Type',
                'type' => 1,
                'parent_id' => 31,
                'order' => 33,
                'is_deleted' => 1,
            ),
            33 =>
            array (
                'id' => 34,
                'note' => '',
                'name' => 'Department List',
                'type' => 1,
                'parent_id' => 26,
                'order' => 34,
                'is_deleted' => 0,
            ),
            34 =>
            array (
                'id' => 35,
                'note' => '',
                'name' => 'Audit Trail',
                'type' => 1,
                'parent_id' => 0,
                'order' => 35,
                'is_deleted' => 0,
            ),
            35 =>
            array (
                'id' => 36,
                'note' => '',
                'name' => 'Audit Trail',
                'type' => 1,
                'parent_id' => 35,
                'order' => 36,
                'is_deleted' => 0,
            ),
            36 =>
            array (
                'id' => 37,
                'note' => '',
                'name' => 'Site Operative View',
                'type' => 1,
                'parent_id' => 0,
                'order' => 37,
                'is_deleted' => 0,
            ),
            37 =>
            array (
                'id' => 38,
                'note' => '',
                'name' => 'View Troubleticket Icon',
                'type' => 1,
                'parent_id' => 0,
                'order' => 38,
                'is_deleted' => 0,
            ),
            38 =>
            array (
                'id' => 39,
                'note' => '',
                'name' => 'Data Centre',
                'type' => 2,
                'parent_id' => 0,
                'order' => 39,
                'is_deleted' => 0,
            ),
            39 =>
            array (
                'id' => 40,
                'note' => '',
                'name' => 'Projects',
                'type' => 2,
                'parent_id' => 39,
                'order' => 40,
                'is_deleted' => 0,
            ),
            40 =>
            array (
                'id' => 41,
                'note' => '',
                'name' => 'Asbestos',
                'type' => 2,
                'parent_id' => 40,
                'order' => 41,
                'is_deleted' => 0,
            ),
            41 =>
            array (
                'id' => 42,
                'note' => '',
                'name' => 'Surveys',
                'type' => 2,
                'parent_id' => 39,
                'order' => 42,
                'is_deleted' => 0,
            ),
            42 =>
            array (
                'id' => 43,
                'note' => '',
                'name' => 'Critical',
                'type' => 2,
                'parent_id' => 39,
                'order' => 43,
                'is_deleted' => 0,
            ),
            43 =>
            array (
                'id' => 44,
                'note' => '',
                'name' => 'Urgent',
                'type' => 2,
                'parent_id' => 39,
                'order' => 44,
                'is_deleted' => 0,
            ),
            44 =>
            array (
                'id' => 45,
                'note' => '',
                'name' => 'Important',
                'type' => 2,
                'parent_id' => 39,
                'order' => 45,
                'is_deleted' => 0,
            ),
            45 =>
            array (
                'id' => 46,
                'note' => '',
                'name' => 'Attention',
                'type' => 2,
                'parent_id' => 39,
                'order' => 46,
                'is_deleted' => 0,
            ),
            46 =>
            array (
                'id' => 47,
                'note' => '',
                'name' => 'Deadline',
                'type' => 2,
                'parent_id' => 39,
                'order' => 47,
                'is_deleted' => 0,
            ),
            47 =>
            array (
                'id' => 48,
                'note' => '',
                'name' => 'Approval',
                'type' => 2,
                'parent_id' => 39,
                'order' => 48,
                'is_deleted' => 0,
            ),
            48 =>
            array (
                'id' => 49,
                'note' => '',
                'name' => 'Rejected',
                'type' => 2,
                'parent_id' => 39,
                'order' => 49,
                'is_deleted' => 0,
            ),
            49 =>
            array (
                'id' => 50,
                'note' => '',
                'name' => 'Properties Information',
                'type' => 2,
                'parent_id' => 0,
                'order' => 50,
                'is_deleted' => 0,
            ),
            50 =>
            array (
                'id' => 51,
                'note' => '',
                'name' => 'Register',
                'type' => 2,
                'parent_id' => 50,
                'order' => 51,
                'is_deleted' => 0,
            ),
            51 =>
            array (
                'id' => 52,
                'note' => '',
                'name' => 'Asbestos',
                'type' => 2,
                'parent_id' => 51,
                'order' => 52,
                'is_deleted' => 0,
            ),
            52 =>
            array (
                'id' => 53,
                'note' => '',
                'name' => 'Projects',
                'type' => 2,
                'parent_id' => 50,
                'order' => 53,
                'is_deleted' => 0,
            ),
            53 =>
            array (
                'id' => 54,
                'note' => '',
                'name' => 'Asbestos',
                'type' => 2,
                'parent_id' => 53,
                'order' => 54,
                'is_deleted' => 0,
            ),
            54 =>
            array (
                'id' => 55,
                'note' => '',
                'name' => 'Assessments/Surveys',
                'type' => 2,
                'parent_id' => 50,
                'order' => 55,
                'is_deleted' => 0,
            ),
            55 =>
            array (
                'id' => 56,
                'note' => '',
                'name' => 'Asbestos',
                'type' => 2,
                'parent_id' => 55,
                'order' => 56,
                'is_deleted' => 0,
            ),
            56 =>
            array (
                'id' => 57,
            'note' => '(dynamic)',
                'name' => 'Reporting',
                'type' => 2,
                'parent_id' => 0,
                'order' => 57,
                'is_deleted' => 0,
            ),
            57 =>
            array (
                'id' => 58,
                'note' => '',
                'name' => 'Data Centre',
                'type' => 3,
                'parent_id' => 0,
                'order' => 58,
                'is_deleted' => 0,
            ),
            58 =>
            array (
                'id' => 59,
                'note' => '',
                'name' => 'Projects',
                'type' => 3,
                'parent_id' => 58,
                'order' => 59,
                'is_deleted' => 0,
            ),
            59 =>
            array (
                'id' => 60,
                'note' => '',
                'name' => 'Fire',
                'type' => 3,
                'parent_id' => 59,
                'order' => 60,
                'is_deleted' => 0,
            ),
            60 =>
            array (
                'id' => 61,
                'note' => '',
                'name' => 'Assessments',
                'type' => 3,
                'parent_id' => 58,
                'order' => 61,
                'is_deleted' => 0,
            ),
            61 =>
            array (
                'id' => 62,
                'note' => '',
                'name' => 'Fire',
                'type' => 3,
                'parent_id' => 61,
                'order' => 62,
                'is_deleted' => 0,
            ),
            62 =>
            array (
                'id' => 63,
                'note' => '',
                'name' => 'Critical',
                'type' => 3,
                'parent_id' => 58,
                'order' => 63,
                'is_deleted' => 0,
            ),
            63 =>
            array (
                'id' => 64,
                'note' => '',
                'name' => 'Urgent',
                'type' => 3,
                'parent_id' => 58,
                'order' => 64,
                'is_deleted' => 0,
            ),
            64 =>
            array (
                'id' => 65,
                'note' => '',
                'name' => 'Important',
                'type' => 3,
                'parent_id' => 58,
                'order' => 65,
                'is_deleted' => 0,
            ),
            65 =>
            array (
                'id' => 66,
                'note' => '',
                'name' => 'Attention',
                'type' => 3,
                'parent_id' => 58,
                'order' => 66,
                'is_deleted' => 0,
            ),
            66 =>
            array (
                'id' => 67,
                'note' => '',
                'name' => 'Deadline',
                'type' => 3,
                'parent_id' => 58,
                'order' => 67,
                'is_deleted' => 0,
            ),
            67 =>
            array (
                'id' => 68,
                'note' => '',
                'name' => 'Approval',
                'type' => 3,
                'parent_id' => 58,
                'order' => 68,
                'is_deleted' => 0,
            ),
            68 =>
            array (
                'id' => 69,
                'note' => '',
                'name' => 'Rejected',
                'type' => 3,
                'parent_id' => 58,
                'order' => 69,
                'is_deleted' => 0,
            ),
            69 =>
            array (
                'id' => 70,
                'note' => '',
                'name' => 'Properties Information',
                'type' => 3,
                'parent_id' => 0,
                'order' => 70,
                'is_deleted' => 0,
            ),
            70 =>
            array (
                'id' => 71,
                'note' => '',
                'name' => 'Register',
                'type' => 3,
                'parent_id' => 70,
                'order' => 71,
                'is_deleted' => 0,
            ),
            71 =>
            array (
                'id' => 72,
                'note' => '',
                'name' => 'Fire',
                'type' => 3,
                'parent_id' => 71,
                'order' => 72,
                'is_deleted' => 0,
            ),
            72 =>
            array (
                'id' => 73,
                'note' => '',
                'name' => 'Projects',
                'type' => 3,
                'parent_id' => 70,
                'order' => 73,
                'is_deleted' => 0,
            ),
            73 =>
            array (
                'id' => 74,
                'note' => '',
                'name' => 'Fire',
                'type' => 3,
                'parent_id' => 73,
                'order' => 74,
                'is_deleted' => 0,
            ),
            74 =>
            array (
                'id' => 75,
                'note' => '',
                'name' => 'Assessments/Surveys',
                'type' => 3,
                'parent_id' => 70,
                'order' => 75,
                'is_deleted' => 0,
            ),
            75 =>
            array (
                'id' => 76,
                'note' => '',
                'name' => 'Fire',
                'type' => 3,
                'parent_id' => 75,
                'order' => 76,
                'is_deleted' => 0,
            ),
            76 =>
            array (
                'id' => 77,
            'note' => '(privilege child)',
                'name' => 'Reporting',
                'type' => 3,
                'parent_id' => 0,
                'order' => 77,
                'is_deleted' => 0,
            ),
            77 =>
            array (
                'id' => 78,
                'note' => '',
                'name' => 'Data Centre',
                'type' => 6,
                'parent_id' => 0,
                'order' => 0,
                'is_deleted' => 0,
            ),
            78 =>
            array (
                'id' => 79,
                'note' => '',
                'name' => 'Projects',
                'type' => 6,
                'parent_id' => 78,
                'order' => 0,
                'is_deleted' => 0,
            ),
            79 =>
            array (
                'id' => 80,
                'note' => '',
                'name' => 'RCF',
                'type' => 6,
                'parent_id' => 79,
                'order' => 0,
                'is_deleted' => 0,
            ),
            80 =>
            array (
                'id' => 81,
                'note' => '',
                'name' => 'Assessments',
                'type' => 6,
                'parent_id' => 78,
                'order' => 0,
                'is_deleted' => 0,
            ),
            81 =>
            array (
                'id' => 82,
                'note' => '',
                'name' => 'RCF',
                'type' => 6,
                'parent_id' => 81,
                'order' => 0,
                'is_deleted' => 0,
            ),
            82 =>
            array (
                'id' => 83,
                'note' => '',
                'name' => 'Surveys',
                'type' => 6,
                'parent_id' => 78,
                'order' => 0,
                'is_deleted' => 0,
            ),
            83 =>
            array (
                'id' => 84,
                'note' => '',
                'name' => 'Critical',
                'type' => 6,
                'parent_id' => 78,
                'order' => 0,
                'is_deleted' => 0,
            ),
            84 =>
            array (
                'id' => 85,
                'note' => '',
                'name' => 'Urgent',
                'type' => 6,
                'parent_id' => 78,
                'order' => 0,
                'is_deleted' => 0,
            ),
            85 =>
            array (
                'id' => 86,
                'note' => '',
                'name' => 'Important',
                'type' => 6,
                'parent_id' => 78,
                'order' => 0,
                'is_deleted' => 0,
            ),
            86 =>
            array (
                'id' => 87,
                'note' => '',
                'name' => 'Attention',
                'type' => 6,
                'parent_id' => 78,
                'order' => 0,
                'is_deleted' => 0,
            ),
            87 =>
            array (
                'id' => 88,
                'note' => '',
                'name' => 'Deadline',
                'type' => 6,
                'parent_id' => 78,
                'order' => 0,
                'is_deleted' => 0,
            ),
            88 =>
            array (
                'id' => 89,
                'note' => '',
                'name' => 'Approval',
                'type' => 6,
                'parent_id' => 78,
                'order' => 0,
                'is_deleted' => 0,
            ),
            89 =>
            array (
                'id' => 90,
                'note' => '',
                'name' => 'Rejected',
                'type' => 6,
                'parent_id' => 78,
                'order' => 0,
                'is_deleted' => 0,
            ),
            90 =>
            array (
                'id' => 91,
                'note' => '',
                'name' => 'Properties Information',
                'type' => 6,
                'parent_id' => 0,
                'order' => 0,
                'is_deleted' => 0,
            ),
            91 =>
            array (
                'id' => 92,
                'note' => '',
                'name' => 'Register',
                'type' => 6,
                'parent_id' => 91,
                'order' => 0,
                'is_deleted' => 0,
            ),
            92 =>
            array (
                'id' => 93,
                'note' => '',
                'name' => 'RCF',
                'type' => 6,
                'parent_id' => 92,
                'order' => 0,
                'is_deleted' => 0,
            ),
            93 =>
            array (
                'id' => 94,
                'note' => '',
                'name' => 'Projects',
                'type' => 6,
                'parent_id' => 91,
                'order' => 0,
                'is_deleted' => 0,
            ),
            94 =>
            array (
                'id' => 95,
                'note' => '',
                'name' => 'RCF',
                'type' => 6,
                'parent_id' => 94,
                'order' => 0,
                'is_deleted' => 0,
            ),
            95 =>
            array (
                'id' => 96,
                'note' => '',
                'name' => 'Assessments/Surveys',
                'type' => 6,
                'parent_id' => 91,
                'order' => 0,
                'is_deleted' => 0,
            ),
            96 =>
            array (
                'id' => 97,
                'note' => '',
                'name' => 'RCF',
                'type' => 6,
                'parent_id' => 96,
                'order' => 0,
                'is_deleted' => 0,
            ),
            97 =>
            array (
                'id' => 98,
                'note' => '',
                'name' => 'Data Centre',
                'type' => 7,
                'parent_id' => 0,
                'order' => 0,
                'is_deleted' => 0,
            ),
            98 =>
            array (
                'id' => 99,
                'note' => '',
                'name' => 'Projects',
                'type' => 7,
                'parent_id' => 98,
                'order' => 0,
                'is_deleted' => 0,
            ),
            99 =>
            array (
                'id' => 100,
                'note' => '',
                'name' => 'Water',
                'type' => 7,
                'parent_id' => 99,
                'order' => 0,
                'is_deleted' => 0,
            ),
            100 =>
            array (
                'id' => 101,
                'note' => '',
                'name' => 'Assessments',
                'type' => 7,
                'parent_id' => 98,
                'order' => 0,
                'is_deleted' => 0,
            ),
            101 =>
            array (
                'id' => 102,
                'note' => '',
                'name' => 'Water',
                'type' => 7,
                'parent_id' => 101,
                'order' => 0,
                'is_deleted' => 0,
            ),
            102 =>
            array (
                'id' => 103,
                'note' => '',
                'name' => 'Critical',
                'type' => 7,
                'parent_id' => 98,
                'order' => 0,
                'is_deleted' => 0,
            ),
            103 =>
            array (
                'id' => 104,
                'note' => '',
                'name' => 'Urgent',
                'type' => 7,
                'parent_id' => 98,
                'order' => 0,
                'is_deleted' => 0,
            ),
            104 =>
            array (
                'id' => 105,
                'note' => '',
                'name' => 'Important',
                'type' => 7,
                'parent_id' => 98,
                'order' => 0,
                'is_deleted' => 0,
            ),
            105 =>
            array (
                'id' => 106,
                'note' => '',
                'name' => 'Attention',
                'type' => 7,
                'parent_id' => 98,
                'order' => 0,
                'is_deleted' => 0,
            ),
            106 =>
            array (
                'id' => 107,
                'note' => '',
                'name' => 'Deadline',
                'type' => 7,
                'parent_id' => 98,
                'order' => 0,
                'is_deleted' => 0,
            ),
            107 =>
            array (
                'id' => 108,
                'note' => '',
                'name' => 'Approval',
                'type' => 7,
                'parent_id' => 98,
                'order' => 0,
                'is_deleted' => 0,
            ),
            108 =>
            array (
                'id' => 109,
                'note' => '',
                'name' => 'Rejected',
                'type' => 7,
                'parent_id' => 98,
                'order' => 0,
                'is_deleted' => 0,
            ),
            109 =>
            array (
                'id' => 111,
                'note' => '',
                'name' => 'Properties Information',
                'type' => 7,
                'parent_id' => 0,
                'order' => 0,
                'is_deleted' => 0,
            ),
            110 =>
            array (
                'id' => 112,
                'note' => '',
                'name' => 'Register',
                'type' => 7,
                'parent_id' => 111,
                'order' => 0,
                'is_deleted' => 0,
            ),
            111 =>
            array (
                'id' => 113,
                'note' => '',
                'name' => 'Water',
                'type' => 7,
                'parent_id' => 112,
                'order' => 0,
                'is_deleted' => 0,
            ),
            112 =>
            array (
                'id' => 114,
                'note' => '',
                'name' => 'Projects',
                'type' => 7,
                'parent_id' => 111,
                'order' => 0,
                'is_deleted' => 0,
            ),
            113 =>
            array (
                'id' => 115,
                'note' => '',
                'name' => 'Water',
                'type' => 7,
                'parent_id' => 114,
                'order' => 0,
                'is_deleted' => 0,
            ),
            114 =>
            array (
                'id' => 116,
                'note' => '',
                'name' => 'Assessments/Surveys',
                'type' => 7,
                'parent_id' => 111,
                'order' => 0,
                'is_deleted' => 0,
            ),
            115 =>
            array (
                'id' => 117,
                'note' => '',
                'name' => 'Water',
                'type' => 7,
                'parent_id' => 116,
                'order' => 0,
                'is_deleted' => 0,
            ),
            116 =>
            array (
                'id' => 118,
            'note' => '(privilege child)',
                'name' => 'Reporting',
                'type' => 7,
                'parent_id' => 0,
                'order' => 0,
                'is_deleted' => 0,
            ),
            117 =>
            array (
                'id' => 119,
                'note' => '',
                'name' => 'Audit App',
                'type' => 1,
                'parent_id' => 35,
                'order' => 37,
                'is_deleted' => 0,
            ),
            118 =>
            array (
                'id' => 120,
                'note' => '',
                'name' => 'Pre-Survey Plan',
                'type' => 1,
                'parent_id' => 26,
                'order' => 35,
                'is_deleted' => 1,
            ),
            119 =>
            array (
                'id' => 121,
                'note' => '',
                'name' => 'Certificates',
                'type' => 2,
                'parent_id' => 50,
                'order' => 56,
                'is_deleted' => 0,
            ),
            120 =>
            array (
                'id' => 122,
                'note' => '',
                'name' => 'Asbestos Awareness Training',
                'type' => 1,
                'parent_id' => 29,
                'order' => 30,
                'is_deleted' => 0,
            ),
            121 =>
            array (
                'id' => 123,
                'note' => '',
                'name' => 'Site Operative View Training',
                'type' => 1,
                'parent_id' => 29,
                'order' => 31,
                'is_deleted' => 0,
            ),
            122 =>
            array (
                'id' => 124,
                'note' => '',
                'name' => 'Project Manager Training',
                'type' => 1,
                'parent_id' => 29,
                'order' => 32,
                'is_deleted' => 0,
            ),
            123 =>
            array (
                'id' => 125,
                'note' => '',
                'name' => 'Audit',
                'type' => 1,
                'parent_id' => 26,
                'order' => 36,
                'is_deleted' => 1,
            ),
            124 =>
            array (
                'id' => 126,
                'note' => '',
                'name' => 'Project Informations',
                'type' => 1,
                'parent_id' => 8,
                'order' => 19,
                'is_deleted' => 0,
            ),
            125 =>
            array (
                'id' => 127,
                'note' => '',
                'name' => 'Tender Documents',
                'type' => 1,
                'parent_id' => 126,
                'order' => 1,
                'is_deleted' => 1,
            ),
            126 =>
            array (
                'id' => 128,
                'note' => '',
                'name' => 'Contractor Documents',
                'type' => 1,
                'parent_id' => 126,
                'order' => 2,
                'is_deleted' => 1,
            ),
            127 =>
            array (
                'id' => 129,
                'note' => '',
                'name' => 'OHS Documents',
                'type' => 1,
                'parent_id' => 126,
                'order' => 3,
                'is_deleted' => 1,
            ),
            128 =>
            array (
                'id' => 130,
                'note' => '',
                'name' => 'Lab Work Log',
                'type' => 1,
                'parent_id' => 26,
                'order' => 37,
                'is_deleted' => 0,
            ),
            129 =>
            array (
                'id' => 131,
                'note' => '',
                'name' => 'Group EMP',
                'type' => 1,
                'parent_id' => 4,
                'order' => 6,
                'is_deleted' => 0,
            ),
            130 =>
            array (
                'id' => 133,
                'note' => '',
                'name' => 'Dynamic Warning Banners',
                'type' => 1,
                'parent_id' => 8,
                'order' => 9,
                'is_deleted' => 0,
            ),
            131 =>
            array (
                'id' => 134,
                'note' => '',
                'name' => 'Work Request',
                'type' => 1,
                'parent_id' => 26,
                'order' => 34,
                'is_deleted' => 0,
            ),
            132 =>
            array (
                'id' => 135,
                'note' => '',
                'name' => 'Incident Reporting',
                'type' => 1,
                'parent_id' => 26,
                'order' => 35,
                'is_deleted' => 0,
            ),
            133 =>
            array (
                'id' => 136,
                'note' => '',
                'name' => 'Resource Documents',
                'type' => 1,
                'parent_id' => 26,
                'order' => 36,
                'is_deleted' => 0,
            ),
            134 =>
            array (
                'id' => 137,
                'note' => 'dynamic',
                'name' => 'Category Box',
                'type' => 1,
                'parent_id' => 26,
                'order' => 29,
                'is_deleted' => 0,
            ),
            135 =>
            array (
                'id' => 138,
                'note' => '',
                'name' => 'Contractor Documents',
                'type' => 1,
                'parent_id' => 126,
                'order' => 3,
                'is_deleted' => 1,
            ),
            136 =>
            array (
                'id' => 139,
                'note' => '',
                'name' => 'Planning Documents',
                'type' => 1,
                'parent_id' => 126,
                'order' => 3,
                'is_deleted' => 0,
            ),
            137 =>
            array (
                'id' => 140,
                'note' => '',
                'name' => 'Pre-Start Documents',
                'type' => 1,
                'parent_id' => 126,
                'order' => 3,
                'is_deleted' => 0,
            ),
            138 =>
            array (
                'id' => 141,
                'note' => '',
                'name' => 'Site Records Documents',
                'type' => 1,
                'parent_id' => 126,
                'order' => 3,
                'is_deleted' => 0,
            ),
            139 =>
                array (
                    'id' => 142,
                    'note' => '',
                    'name' => 'Completion Documents',
                    'type' => 1,
                    'parent_id' => 126,
                    'order' => 3,
                    'is_deleted' => 0,
                ),

            140 =>
                array (
                    'id' => 143,
                    'note' => '',
                    'name' => 'Data Centre',
                    'type' => 8,
                    'parent_id' => 0,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            141 =>
                array (
                    'id' => 144,
                    'note' => '',
                    'name' => 'Projects',
                    'type' => 8,
                    'parent_id' => 143,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            142 =>
                array (
                    'id' => 145,
                    'note' => '',
                    'name' => 'H&S',
                    'type' => 8,
                    'parent_id' => 144,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            143 =>
                array (
                    'id' => 146,
                    'note' => '',
                    'name' => 'Assessments',
                    'type' => 8,
                    'parent_id' => 143,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            144 =>
                array (
                    'id' => 147,
                    'note' => '',
                    'name' => 'H&S',
                    'type' => 8,
                    'parent_id' => 146,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            145 =>
                array (
                    'id' => 148,
                    'note' => '',
                    'name' => 'Critical',
                    'type' => 8,
                    'parent_id' => 143,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            146 =>
                array (
                    'id' => 149,
                    'note' => '',
                    'name' => 'Urgent',
                    'type' => 8,
                    'parent_id' => 143,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            147 =>
                array (
                    'id' => 150,
                    'note' => '',
                    'name' => 'Important',
                    'type' => 8,
                    'parent_id' => 143,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            148 =>
                array (
                    'id' => 151,
                    'note' => '',
                    'name' => 'Attention',
                    'type' => 8,
                    'parent_id' => 143,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            149 =>
                array (
                    'id' => 152,
                    'note' => '',
                    'name' => 'Deadline',
                    'type' => 8,
                    'parent_id' => 143,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            150 =>
                array (
                    'id' => 153,
                    'note' => '',
                    'name' => 'Approval',
                    'type' => 8,
                    'parent_id' => 143,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            151 =>
                array (
                    'id' => 154,
                    'note' => '',
                    'name' => 'Rejected',
                    'type' => 8,
                    'parent_id' => 143,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            152 =>
                array (
                    'id' => 155,
                    'note' => '',
                    'name' => 'Properties Information',
                    'type' => 8,
                    'parent_id' => 0,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            153 =>
                array (
                    'id' => 156,
                    'note' => '',
                    'name' => 'Register',
                    'type' => 8,
                    'parent_id' => 155,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            154 =>
                array (
                    'id' => 157,
                    'note' => '',
                    'name' => 'H&S',
                    'type' => 8,
                    'parent_id' => 156,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            155 =>
                array (
                    'id' => 158,
                    'note' => '',
                    'name' => 'Projects',
                    'type' => 8,
                    'parent_id' => 155,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            156 =>
                array (
                    'id' => 159,
                    'note' => '',
                    'name' => 'H&S',
                    'type' => 8,
                    'parent_id' => 158,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            157 =>
                array (
                    'id' => 160,
                    'note' => '',
                    'name' => 'Assessments/Surveys',
                    'type' => 8,
                    'parent_id' => 155,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            158 =>
                array (
                    'id' => 161,
                    'note' => '',
                    'name' => 'H&S',
                    'type' => 8,
                    'parent_id' => 160,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            159 =>
                array (
                    'id' => 162,
                    'note' => '(privilege child)',
                    'name' => 'Reporting',
                    'type' => 8,
                    'parent_id' => 0,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            160 =>
            array (
                'id' => 164,
                'note' => '',
                'name' => 'Pre-construction Documents',
                'type' => 1,
                'parent_id' => 126,
                'order' => 2,
                'is_deleted' => 0,
            ),
            161 =>
            array (
                'id' => 165,
                'note' => '',
                'name' => 'Design Documents',
                'type' => 1,
                'parent_id' => 126,
                'order' => 2,
                'is_deleted' => 0,
            ),
            162 =>
            array (
                'id' => 166,
                'note' => '',
                'name' => 'Commercial',
                'type' => 1,
                'parent_id' => 126,
                'order' => 2,
                'is_deleted' => 0,
            ),
            163 =>
            array (
                'id' => 163,
                'note' => '',
                'name' => 'Critical',
                'type' => 8,
                'parent_id' => 143,
                'order' => 0,
                'is_deleted' => 0,
            ),
        ));


    }
}
