<?php

use Illuminate\Database\Seeder;

class CpPrivilegeEditTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('cp_privilege_edit')->delete();

        \DB::table('cp_privilege_edit')->insert(array (
            0 =>
            array (
                'id' => 1,
                'note' => '',
                'name' => 'Property Listing',
                'type' => 1,
                'parent_id' => 0,
                'order' => 0,
                'is_deleted' => 0,
            ),
            1 =>
            array (
                'id' => 2,
                'note' => '',
                'name' => 'All Properties',
                'type' => 1,
                'parent_id' => 1,
                'order' => 0,
                'is_deleted' => 1,
            ),
            2 =>
            array (
                'id' => 3,
                'note' => '',
                'name' => 'Property EMP',
                'type' => 1,
                'parent_id' => 1,
                'order' => 0,
                'is_deleted' => 0,
            ),
            3 =>
            array (
                'id' => 4,
            'note' => '(dynamic)',
                'name' => 'Clients',
                'type' => 1,
                'parent_id' => 1,
                'order' => 0,
                'is_deleted' => 1,
            ),
            4 =>
            array (
                'id' => 5,
                'note' => '',
                'name' => 'Properties Infomation',
                'type' => 1,
                'parent_id' => 0,
                'order' => 0,
                'is_deleted' => 0,
            ),
            5 =>
            array (
                'id' => 6,
                'note' => '',
                'name' => 'Details',
                'type' => 1,
                'parent_id' => 5,
                'order' => 0,
                'is_deleted' => 0,
            ),
            6 =>
            array (
                'id' => 7,
                'note' => '',
                'name' => 'Register',
                'type' => 1,
                'parent_id' => 5,
                'order' => 0,
                'is_deleted' => 0,
            ),
            7 =>
            array (
                'id' => 8,
                'note' => '',
                'name' => 'Area/floor',
                'type' => 1,
                'parent_id' => 7,
                'order' => 0,
                'is_deleted' => 0,
            ),
            8 =>
            array (
                'id' => 9,
                'note' => '',
                'name' => 'Room/location',
                'type' => 1,
                'parent_id' => 7,
                'order' => 0,
                'is_deleted' => 0,
            ),
            9 =>
            array (
                'id' => 10,
                'note' => '',
                'name' => 'Systems',
                'type' => 1,
                'parent_id' => 5,
                'order' => 0,
                'is_deleted' => 0,
            ),
            10 =>
            array (
                'id' => 11,
                'note' => '',
                'name' => 'Equipment',
                'type' => 1,
                'parent_id' => 5,
                'order' => 0,
                'is_deleted' => 0,
            ),
            11 =>
            array (
                'id' => 12,
                'note' => '',
                'name' => 'Fire Exits & Assembly Points',
                'type' => 1,
                'parent_id' => 5,
                'order' => 0,
                'is_deleted' => 0,
            ),
            12 =>
            array (
                'id' => 13,
                'note' => '',
                'name' => 'Vehicle Parking',
                'type' => 1,
                'parent_id' => 5,
                'order' => 0,
                'is_deleted' => 0,
            ),
            13 =>
            array (
                'id' => 14,
                'note' => '',
                'name' => 'Documents',
                'type' => 1,
                'parent_id' => 5,
                'order' => 0,
                'is_deleted' => 0,
            ),
            14 =>
            array (
                'id' => 15,
                'note' => '',
            'name' => 'Data Centre (Document Approval)',
                'type' => 1,
                'parent_id' => 0,
                'order' => 0,
                'is_deleted' => 0,
            ),
            15 =>
            array (
                'id' => 16,
                'note' => '',
                'name' => 'Dashboard',
                'type' => 1,
                'parent_id' => 15,
                'order' => 0,
                'is_deleted' => 1,
            ),
            16 =>
            array (
                'id' => 17,
                'note' => '',
                'name' => 'Contractor Documents',
                'type' => 1,
                'parent_id' => 15,
                'order' => 0,
                'is_deleted' => 0,
            ),
            17 =>
            array (
                'id' => 18,
                'note' => '',
                'name' => 'Certificates',
                'type' => 2,
                'parent_id' => 44,
                'order' => 0,
                'is_deleted' => 0,
            ),
            18 =>
            array (
                'id' => 19,
                'note' => '',
                'name' => 'Organisational',
                'type' => 1,
                'parent_id' => 0,
                'order' => 0,
                'is_deleted' => 0,
            ),
            19 =>
            array (
                'id' => 20,
                'note' => '',
                'name' => 'Organisation EMP',
                'type' => 1,
                'parent_id' => 19,
                'order' => 0,
                'is_deleted' => 0,
            ),
            20 =>
            array (
                'id' => 21,
                'note' => 'privilege child',
                'name' => 'My Organisation',
                'type' => 1,
                'parent_id' => 19,
                'order' => 0,
                'is_deleted' => 1,
            ),
            21 =>
            array (
                'id' => 22,
                'note' => 'privilege child',
                'name' => 'Clients',
                'type' => 1,
                'parent_id' => 19,
                'order' => 0,
                'is_deleted' => 1,
            ),
            22 =>
            array (
                'id' => 23,
                'note' => 'privilege child',
                'name' => 'Contractors',
                'type' => 1,
                'parent_id' => 19,
                'order' => 0,
                'is_deleted' => 1,
            ),
            23 =>
            array (
                'id' => 24,
                'note' => '',
                'name' => 'Resources',
                'type' => 1,
                'parent_id' => 0,
                'order' => 0,
                'is_deleted' => 0,
            ),
            24 =>
            array (
                'id' => 25,
                'note' => '',
                'name' => 'Administrator Functions',
                'type' => 1,
                'parent_id' => 24,
                'order' => 0,
                'is_deleted' => 1,
            ),
            25 =>
            array (
                'id' => 26,
                'note' => '',
                'name' => 'Toolbox',
                'type' => 1,
                'parent_id' => 25,
                'order' => 0,
                'is_deleted' => 1,
            ),
            26 =>
            array (
                'id' => 27,
                'note' => '',
                'name' => 'System Settings',
                'type' => 1,
                'parent_id' => 25,
                'order' => 0,
                'is_deleted' => 1,
            ),
            27 =>
            array (
                'id' => 28,
                'note' => '',
                'name' => 'E-Learning',
                'type' => 1,
                'parent_id' => 24,
                'order' => 0,
                'is_deleted' => 1,
            ),
            28 =>
            array (
                'id' => 29,
                'note' => '',
                'name' => 'Asbestos Awareness Training',
                'type' => 1,
                'parent_id' => 28,
                'order' => 0,
                'is_deleted' => 1,
            ),
            29 =>
            array (
                'id' => 30,
                'note' => '',
                'name' => 'Site Operative View Training',
                'type' => 1,
                'parent_id' => 28,
                'order' => 0,
                'is_deleted' => 1,
            ),
            30 =>
            array (
                'id' => 31,
                'note' => '',
                'name' => 'Project Manager Training',
                'type' => 1,
                'parent_id' => 28,
                'order' => 0,
                'is_deleted' => 1,
            ),
            31 =>
            array (
                'id' => 32,
                'note' => '',
                'name' => 'Job Roles',
                'type' => 1,
                'parent_id' => 24,
                'order' => 0,
                'is_deleted' => 1,
            ),
            32 =>
            array (
                'id' => 33,
                'note' => '',
                'name' => 'Resource Documents',
                'type' => 1,
                'parent_id' => 24,
                'order' => 0,
                'is_deleted' => 0,
            ),
            33 =>
            array (
                'id' => 34,
                'note' => '',
                'name' => 'Work Requests',
                'type' => 1,
                'parent_id' => 24,
                'order' => 0,
                'is_deleted' => 1,
            ),
            34 =>
            array (
                'id' => 35,
                'note' => '',
                'name' => 'Department List',
                'type' => 1,
                'parent_id' => 24,
                'order' => 0,
                'is_deleted' => 0,
            ),
            35 =>
            array (
                'id' => 36,
                'note' => '',
                'name' => 'Properties Information',
                'type' => 2,
                'parent_id' => 0,
                'order' => 0,
                'is_deleted' => 0,
            ),
            36 =>
            array (
                'id' => 37,
                'note' => '',
                'name' => 'Details',
                'type' => 2,
                'parent_id' => 36,
                'order' => 0,
                'is_deleted' => 0,
            ),
            37 =>
            array (
                'id' => 38,
                'note' => '',
                'name' => 'Register',
                'type' => 2,
                'parent_id' => 36,
                'order' => 0,
                'is_deleted' => 0,
            ),
            38 =>
            array (
                'id' => 39,
                'note' => '',
                'name' => 'Asbestos Items',
                'type' => 2,
                'parent_id' => 38,
                'order' => 0,
                'is_deleted' => 0,
            ),
            39 =>
            array (
                'id' => 40,
                'note' => '',
                'name' => 'Projects',
                'type' => 2,
                'parent_id' => 36,
                'order' => 0,
                'is_deleted' => 0,
            ),
            40 =>
            array (
                'id' => 41,
                'note' => '',
                'name' => 'Asbestos',
                'type' => 2,
                'parent_id' => 40,
                'order' => 0,
                'is_deleted' => 0,
            ),
            41 =>
            array (
                'id' => 42,
                'note' => '',
                'name' => 'Assessments/Surveys',
                'type' => 2,
                'parent_id' => 36,
                'order' => 0,
                'is_deleted' => 0,
            ),
            42 =>
            array (
                'id' => 43,
                'note' => '',
                'name' => 'Asbestos',
                'type' => 2,
                'parent_id' => 42,
                'order' => 0,
                'is_deleted' => 0,
            ),
            43 =>
            array (
                'id' => 44,
                'note' => '',
            'name' => 'Data Centre (Document Approval)',
                'type' => 2,
                'parent_id' => 0,
                'order' => 0,
                'is_deleted' => 0,
            ),
            44 =>
            array (
                'id' => 45,
                'note' => '',
                'name' => 'Surveys',
                'type' => 2,
                'parent_id' => 44,
                'order' => 0,
                'is_deleted' => 0,
            ),
            45 =>
            array (
                'id' => 46,
                'note' => '',
                'name' => 'Properties Information',
                'type' => 3,
                'parent_id' => 0,
                'order' => 0,
                'is_deleted' => 0,
            ),
            46 =>
            array (
                'id' => 47,
                'note' => '',
                'name' => 'Details',
                'type' => 3,
                'parent_id' => 46,
                'order' => 0,
                'is_deleted' => 0,
            ),
            47 =>
            array (
                'id' => 48,
                'note' => '',
                'name' => 'Register',
                'type' => 3,
                'parent_id' => 46,
                'order' => 0,
                'is_deleted' => 0,
            ),
            48 =>
            array (
                'id' => 49,
                'note' => '',
                'name' => 'Fire Hazards',
                'type' => 3,
                'parent_id' => 48,
                'order' => 0,
                'is_deleted' => 0,
            ),
            49 =>
            array (
                'id' => 50,
                'note' => '',
                'name' => 'Project',
                'type' => 3,
                'parent_id' => 46,
                'order' => 0,
                'is_deleted' => 0,
            ),
            50 =>
            array (
                'id' => 51,
                'note' => '',
                'name' => 'Fire',
                'type' => 3,
                'parent_id' => 50,
                'order' => 0,
                'is_deleted' => 0,
            ),
            51 =>
            array (
                'id' => 52,
                'note' => '',
                'name' => 'Assessments/Surveys',
                'type' => 3,
                'parent_id' => 46,
                'order' => 0,
                'is_deleted' => 0,
            ),
            52 =>
            array (
                'id' => 53,
                'note' => '',
                'name' => 'Fire',
                'type' => 3,
                'parent_id' => 52,
                'order' => 0,
                'is_deleted' => 0,
            ),
            53 =>
            array (
                'id' => 54,
                'note' => '',
            'name' => 'Data Centre (Document Approval)',
                'type' => 3,
                'parent_id' => 0,
                'order' => 0,
                'is_deleted' => 0,
            ),
            54 =>
            array (
                'id' => 55,
                'note' => '',
                'name' => 'Assessments',
                'type' => 3,
                'parent_id' => 54,
                'order' => 0,
                'is_deleted' => 0,
            ),
            55 =>
            array (
                'id' => 56,
                'note' => '',
                'name' => 'Fire',
                'type' => 3,
                'parent_id' => 55,
                'order' => 0,
                'is_deleted' => 0,
            ),
            56 =>
            array (
                'id' => 57,
                'note' => '',
                'name' => 'Properties Information',
                'type' => 6,
                'parent_id' => 0,
                'order' => 0,
                'is_deleted' => 0,
            ),
            57 =>
            array (
                'id' => 58,
                'note' => '',
                'name' => 'Details',
                'type' => 6,
                'parent_id' => 57,
                'order' => 0,
                'is_deleted' => 0,
            ),
            58 =>
            array (
                'id' => 59,
                'note' => '',
                'name' => 'Register',
                'type' => 6,
                'parent_id' => 57,
                'order' => 0,
                'is_deleted' => 0,
            ),
            59 =>
            array (
                'id' => 60,
                'note' => '',
                'name' => 'RCF Items',
                'type' => 6,
                'parent_id' => 59,
                'order' => 0,
                'is_deleted' => 0,
            ),
            60 =>
            array (
                'id' => 61,
                'note' => '',
                'name' => 'Project',
                'type' => 6,
                'parent_id' => 57,
                'order' => 0,
                'is_deleted' => 0,
            ),
            61 =>
            array (
                'id' => 62,
                'note' => '',
                'name' => 'RCF',
                'type' => 6,
                'parent_id' => 61,
                'order' => 0,
                'is_deleted' => 0,
            ),
            62 =>
            array (
                'id' => 63,
                'note' => '',
                'name' => 'Assessments/Surveys',
                'type' => 6,
                'parent_id' => 57,
                'order' => 0,
                'is_deleted' => 0,
            ),
            63 =>
            array (
                'id' => 64,
                'note' => '',
                'name' => 'RCF',
                'type' => 6,
                'parent_id' => 63,
                'order' => 0,
                'is_deleted' => 0,
            ),
            64 =>
            array (
                'id' => 65,
                'note' => '',
            'name' => 'Data Centre (Document Approval)',
                'type' => 6,
                'parent_id' => 0,
                'order' => 0,
                'is_deleted' => 0,
            ),
            65 =>
            array (
                'id' => 66,
                'note' => '',
                'name' => 'Assessments',
                'type' => 6,
                'parent_id' => 65,
                'order' => 0,
                'is_deleted' => 0,
            ),
            66 =>
            array (
                'id' => 67,
                'note' => '',
                'name' => 'RCF',
                'type' => 6,
                'parent_id' => 66,
                'order' => 0,
                'is_deleted' => 0,
            ),
            67 =>
            array (
                'id' => 68,
                'note' => '',
                'name' => 'Properties Information',
                'type' => 7,
                'parent_id' => 0,
                'order' => 0,
                'is_deleted' => 0,
            ),
            68 =>
            array (
                'id' => 69,
                'note' => '',
                'name' => 'Details',
                'type' => 7,
                'parent_id' => 68,
                'order' => 0,
                'is_deleted' => 0,
            ),
            69 =>
            array (
                'id' => 70,
                'note' => '',
                'name' => 'Register',
                'type' => 7,
                'parent_id' => 68,
                'order' => 0,
                'is_deleted' => 0,
            ),
            70 =>
            array (
                'id' => 71,
                'note' => '',
                'name' => 'Water Hazards',
                'type' => 7,
                'parent_id' => 70,
                'order' => 0,
                'is_deleted' => 0,
            ),
            71 =>
            array (
                'id' => 72,
                'note' => '',
                'name' => 'Project',
                'type' => 7,
                'parent_id' => 68,
                'order' => 0,
                'is_deleted' => 0,
            ),
            72 =>
            array (
                'id' => 73,
                'note' => '',
                'name' => 'Water',
                'type' => 7,
                'parent_id' => 72,
                'order' => 0,
                'is_deleted' => 0,
            ),
            73 =>
            array (
                'id' => 74,
                'note' => '',
                'name' => 'Assessments/Surveys',
                'type' => 7,
                'parent_id' => 68,
                'order' => 0,
                'is_deleted' => 0,
            ),
            74 =>
            array (
                'id' => 75,
                'note' => '',
                'name' => 'Water',
                'type' => 7,
                'parent_id' => 74,
                'order' => 0,
                'is_deleted' => 0,
            ),
            75 =>
            array (
                'id' => 76,
                'note' => '',
            'name' => 'Data Centre (Document Approval)',
                'type' => 7,
                'parent_id' => 0,
                'order' => 0,
                'is_deleted' => 0,
            ),
            76 =>
            array (
                'id' => 77,
                'note' => '',
                'name' => 'Assessments',
                'type' => 7,
                'parent_id' => 76,
                'order' => 0,
                'is_deleted' => 0,
            ),
            77 =>
            array (
                'id' => 78,
                'note' => '',
                'name' => 'Water',
                'type' => 7,
                'parent_id' => 77,
                'order' => 0,
                'is_deleted' => 0,
            ),
            78 =>
            array (
                'id' => 79,
                'note' => '',
                'name' => 'Certificates',
                'type' => 2,
                'parent_id' => 36,
                'order' => 0,
                'is_deleted' => 0,
            ),
            79 =>
            array (
                'id' => 80,
                'note' => '',
                'name' => 'Project Informations',
                'type' => 1,
                'parent_id' => 5,
                'order' => 0,
                'is_deleted' => 0,
            ),
            80 =>
            array (
                'id' => 81,
                'note' => '',
                'name' => 'Tender Documents',
                'type' => 1,
                'parent_id' => 80,
                'order' => 1,
                'is_deleted' => 1,
            ),
            81 =>
            array (
                'id' => 82,
                'note' => '',
                'name' => 'Contractor Documents',
                'type' => 1,
                'parent_id' => 80,
                'order' => 2,
                'is_deleted' => 1,
            ),
            82 =>
            array (
                'id' => 83,
                'note' => '',
                'name' => 'OHS Documents',
                'type' => 1,
                'parent_id' => 80,
                'order' => 3,
                'is_deleted' => 1,
            ),
            83 =>
            array (
                'id' => 84,
                'note' => '',
                'name' => 'Group EMP',
                'type' => 1,
                'parent_id' => 1,
                'order' => 0,
                'is_deleted' => 0,
            ),
            84 =>
            array (
                'id' => 85,
                'note' => 'dynamic',
                'name' => 'Category Box',
                'type' => 1,
                'parent_id' => 24,
                'order' => 0,
                'is_deleted' => 0,
            ),
            85 =>
            array (
                'id' => 86,
                'note' => '',
                'name' => 'Work Request',
                'type' => 1,
                'parent_id' => 24,
                'order' => 0,
                'is_deleted' => 0,
            ),
            86 =>
            array (
                'id' => 87,
                'note' => '',
                'name' => 'Incident Reporting',
                'type' => 1,
                'parent_id' => 24,
                'order' => 0,
                'is_deleted' => 0,
            ),
            87 =>
            array (
                'id' => 88,
                'note' => '',
                'name' => 'Contractor Documents',
                'type' => 1,
                'parent_id' => 80,
                'order' => 3,
                'is_deleted' => 1,
            ),
            88 =>
            array (
                'id' => 89,
                'note' => '',
                'name' => 'Planning Documents',
                'type' => 1,
                'parent_id' => 80,
                'order' => 3,
                'is_deleted' => 0,
            ),
            89 =>
            array (
                'id' => 90,
                'note' => '',
                'name' => 'Pre-Start Documents',
                'type' => 1,
                'parent_id' => 80,
                'order' => 3,
                'is_deleted' => 0,
            ),
            90 =>
            array (
                'id' => 91,
                'note' => '',
                'name' => 'Site Records Documents',
                'type' => 1,
                'parent_id' => 80,
                'order' => 3,
                'is_deleted' => 0,
            ),
            91 =>
            array (
                'id' => 92,
                'note' => '',
                'name' => 'Completion Documents',
                'type' => 1,
                'parent_id' => 80,
                'order' => 3,
                'is_deleted' => 0,
            ),
            92 =>
            array (
                'id' => 93,
                'note' => '',
                'name' => 'Pre-Start Documents',
                'type' => 1,
                'parent_id' => 15,
                'order' => 0,
                'is_deleted' => 0,
            ),
            93 =>
            array (
                'id' => 94,
                'note' => '',
                'name' => 'Site Records Documents',
                'type' => 1,
                'parent_id' => 15,
                'order' => 0,
                'is_deleted' => 0,
            ),
            94 =>
            array (
                'id' => 95,
                'note' => '',
                'name' => 'Completion Documents',
                'type' => 1,
                'parent_id' => 15,
                'order' => 0,
                'is_deleted' => 0,
            ),
            95 =>
                array (
                    'id' => 96,
                    'note' => '',
                    'name' => 'Properties Information',
                    'type' => 8,
                    'parent_id' => 0,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            96 =>
                array (
                    'id' => 97,
                    'note' => '',
                    'name' => 'Details',
                    'type' => 8,
                    'parent_id' => 96,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            97 =>
                array (
                    'id' => 98,
                    'note' => '',
                    'name' => 'Register',
                    'type' => 8,
                    'parent_id' => 96,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            98 =>
                array (
                    'id' => 99,
                    'note' => '',
                    'name' => 'H&S Hazards',
                    'type' => 8,
                    'parent_id' => 98,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            99 =>
                array (
                    'id' => 100,
                    'note' => '',
                    'name' => 'Project',
                    'type' => 8,
                    'parent_id' => 96,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            100 =>
                array (
                    'id' => 101,
                    'note' => '',
                    'name' => 'H&S',
                    'type' => 8,
                    'parent_id' => 100,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            101 =>
                array (
                    'id' => 102,
                    'note' => '',
                    'name' => 'Assessments/Surveys',
                    'type' => 8,
                    'parent_id' => 96,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            102 =>
                array (
                    'id' => 103,
                    'note' => '',
                    'name' => 'H&S',
                    'type' => 8,
                    'parent_id' => 102,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            103 =>
                array (
                    'id' => 104,
                    'note' => '',
                    'name' => 'Data Centre (Document Approval)',
                    'type' => 8,
                    'parent_id' => 0,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            104 =>
                array (
                    'id' => 105,
                    'note' => '',
                    'name' => 'Assessments',
                    'type' => 8,
                    'parent_id' => 104,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            105 =>
                array (
                    'id' => 106,
                    'note' => '',
                    'name' => 'H&S',
                    'type' => 8,
                    'parent_id' => 105,
                    'order' => 0,
                    'is_deleted' => 0,
                ),
            106 =>
            array (
                'id' => 107,
                'note' => '',
                'name' => 'Pre-construction Documents',
                'type' => 1,
                'parent_id' => 80,
                'order' => 0,
                'is_deleted' => 0,
            ),
            107 =>
            array (
                'id' => 108,
                'note' => '',
                'name' => 'Design Documents',
                'type' => 1,
                'parent_id' => 80,
                'order' => 0,
                'is_deleted' => 0,
            ),
            108 =>
            array (
                'id' => 109,
                'note' => '',
                'name' => 'Commercial Documents',
                'type' => 1,
                'parent_id' => 80,
                'order' => 0,
                'is_deleted' => 0,
            ),
        ));


    }
}
