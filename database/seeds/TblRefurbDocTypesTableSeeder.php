<?php

use Illuminate\Database\Seeder;

class TblRefurbDocTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_refurb_doc_types')->delete();
        
        \DB::table('tbl_refurb_doc_types')->insert(array (
            0 => 
            array (
                'id' => 20,
                'doc_type' => 'Section 62 Waste Certificates',
                'refurb_type' => 'completion',
                'order' => 3,
                'is_active' => 0,
            ),
            1 => 
            array (
                'id' => 24,
                'doc_type' => 'Client Commission',
                'refurb_type' => 'tender',
                'order' => 1,
                'is_active' => 1,
            ),
            2 => 
            array (
                'id' => 25,
                'doc_type' => 'PSQ',
                'refurb_type' => 'tender',
                'order' => 2,
                'is_active' => 1,
            ),
            3 => 
            array (
                'id' => 26,
                'doc_type' => 'PPQ',
                'refurb_type' => 'tender',
                'order' => 3,
                'is_active' => 1,
            ),
            4 => 
            array (
                'id' => 27,
                'doc_type' => 'Tender',
                'refurb_type' => 'tender',
                'order' => 4,
                'is_active' => 1,
            ),
            5 => 
            array (
                'id' => 28,
                'doc_type' => 'Enquiry',
                'refurb_type' => 'tender',
                'order' => 5,
                'is_active' => 1,
            ),
            6 => 
            array (
                'id' => 29,
                'doc_type' => 'Specification of Work',
                'refurb_type' => 'tender',
                'order' => 6,
                'is_active' => 1,
            ),
            7 => 
            array (
                'id' => 30,
                'doc_type' => 'Other',
                'refurb_type' => 'tender',
                'order' => 999,
                'is_active' => 1,
            ),
            8 => 
            array (
                'id' => 31,
                'doc_type' => 'Quotation',
                'refurb_type' => 'contractor',
                'order' => 1,
                'is_active' => 1,
            ),
            9 => 
            array (
                'id' => 32,
                'doc_type' => 'T&C\'s',
                'refurb_type' => 'contractor',
                'order' => 2,
                'is_active' => 1,
            ),
            10 => 
            array (
                'id' => 33,
                'doc_type' => 'Plan of Works',
                'refurb_type' => 'contractor',
                'order' => 4,
                'is_active' => 1,
            ),
            11 => 
            array (
                'id' => 34,
                'doc_type' => 'Risk Assessment',
                'refurb_type' => 'contractor',
                'order' => 5,
                'is_active' => 1,
            ),
            12 => 
            array (
                'id' => 35,
                'doc_type' => 'Other',
                'refurb_type' => 'contractor',
                'order' => 999,
                'is_active' => 1,
            ),
            13 => 
            array (
                'id' => 36,
                'doc_type' => 'Specification',
                'refurb_type' => 'planning',
                'order' => 4,
                'is_active' => 0,
            ),
            14 => 
            array (
                'id' => 37,
                'doc_type' => 'Method Statement',
                'refurb_type' => 'planning',
                'order' => 4,
                'is_active' => 1,
            ),
            15 => 
            array (
                'id' => 38,
                'doc_type' => 'Risk Assessment',
                'refurb_type' => 'planning',
                'order' => 9,
                'is_active' => 1,
            ),
            16 => 
            array (
                'id' => 39,
                'doc_type' => 'Plan of Works',
                'refurb_type' => 'planning',
                'order' => 5,
                'is_active' => 1,
            ),
            17 => 
            array (
                'id' => 40,
                'doc_type' => 'Purchase Order',
                'refurb_type' => 'planning',
                'order' => 1,
                'is_active' => 0,
            ),
            18 => 
            array (
                'id' => 41,
                'doc_type' => 'Contractor Commission',
                'refurb_type' => 'planning',
                'order' => 2,
                'is_active' => 0,
            ),
            19 => 
            array (
                'id' => 42,
                'doc_type' => 'Work Confirmation',
                'refurb_type' => 'planning',
                'order' => 3,
                'is_active' => 0,
            ),
            20 => 
            array (
                'id' => 43,
                'doc_type' => 'Other',
                'refurb_type' => 'planning',
                'order' => 999,
                'is_active' => 1,
            ),
            21 => 
            array (
                'id' => 44,
                'doc_type' => 'Air Testing Certificate',
                'refurb_type' => 'completion',
                'order' => 1,
                'is_active' => 0,
            ),
            22 => 
            array (
                'id' => 45,
                'doc_type' => 'Asbestos Waste Consignment Notes',
                'refurb_type' => 'completion',
                'order' => 8,
                'is_active' => 1,
            ),
            23 => 
            array (
                'id' => 46,
                'doc_type' => 'Practical Completion Certificate',
                'refurb_type' => 'completion',
                'order' => 14,
                'is_active' => 1,
            ),
            24 => 
            array (
                'id' => 47,
                'doc_type' => 'Site Handover Certificate',
                'refurb_type' => 'completion',
                'order' => 15,
                'is_active' => 1,
            ),
            25 => 
            array (
                'id' => 48,
                'doc_type' => 'Other',
                'refurb_type' => 'completion',
                'order' => 999,
                'is_active' => 1,
            ),
            26 => 
            array (
                'id' => 49,
                'doc_type' => 'Specification of Work Response',
                'refurb_type' => 'contractor',
                'order' => 3,
                'is_active' => 1,
            ),
            27 => 
            array (
                'id' => 50,
                'doc_type' => 'Method Statement',
                'refurb_type' => 'contractor',
                'order' => 6,
                'is_active' => 1,
            ),
            28 => 
            array (
                'id' => 51,
                'doc_type' => 'Project Plan',
                'refurb_type' => 'planning',
                'order' => 8,
                'is_active' => 0,
            ),
            29 => 
            array (
                'id' => 52,
                'doc_type' => 'ASB5',
                'refurb_type' => 'planning',
                'order' => 2,
                'is_active' => 1,
            ),
            30 => 
            array (
                'id' => 53,
                'doc_type' => 'Permit to Work',
                'refurb_type' => 'prestart',
                'order' => 2,
                'is_active' => 1,
            ),
            31 => 
            array (
                'id' => 54,
                'doc_type' => 'Minor Works Authorisation',
                'refurb_type' => 'prestart',
                'order' => 1,
                'is_active' => 1,
            ),
            32 => 
            array (
                'id' => 55,
                'doc_type' => 'Training Notes',
                'refurb_type' => 'prestart',
                'order' => 3,
                'is_active' => 0,
            ),
            33 => 
            array (
                'id' => 56,
                'doc_type' => 'Site Instruction',
                'refurb_type' => 'prestart',
                'order' => 4,
                'is_active' => 0,
            ),
            34 => 
            array (
                'id' => 57,
                'doc_type' => 'Other',
                'refurb_type' => 'prestart',
                'order' => 999,
                'is_active' => 1,
            ),
            35 => 
            array (
                'id' => 58,
                'doc_type' => 'Risk Assessment',
                'refurb_type' => 'siterecords',
                'order' => 1,
                'is_active' => 0,
            ),
            36 => 
            array (
                'id' => 59,
                'doc_type' => 'Method Statement',
                'refurb_type' => 'siterecords',
                'order' => 2,
                'is_active' => 0,
            ),
            37 => 
            array (
                'id' => 60,
                'doc_type' => 'Daily Log Diary',
                'refurb_type' => 'siterecords',
                'order' => 3,
                'is_active' => 0,
            ),
            38 => 
            array (
                'id' => 61,
                'doc_type' => 'Timesheet',
                'refurb_type' => 'siterecords',
                'order' => 4,
                'is_active' => 1,
            ),
            39 => 
            array (
                'id' => 62,
                'doc_type' => 'Plant Inspection',
                'refurb_type' => 'siterecords',
                'order' => 5,
                'is_active' => 1,
            ),
            40 => 
            array (
                'id' => 63,
                'doc_type' => 'Vehicle Inspection',
                'refurb_type' => 'siterecords',
                'order' => 6,
                'is_active' => 1,
            ),
            41 => 
            array (
                'id' => 64,
                'doc_type' => 'Waste Consignment Notes',
                'refurb_type' => 'siterecords',
                'order' => 7,
                'is_active' => 0,
            ),
            42 => 
            array (
                'id' => 65,
                'doc_type' => 'Summary',
                'refurb_type' => 'tender',
                'order' => 7,
                'is_active' => 1,
            ),
            43 => 
            array (
                'id' => 66,
                'doc_type' => 'Smoke Test',
                'refurb_type' => 'siterecords',
                'order' => 8,
                'is_active' => 0,
            ),
            44 => 
            array (
                'id' => 67,
                'doc_type' => 'Smoke Test',
                'refurb_type' => 'completion',
                'order' => 6,
                'is_active' => 0,
            ),
            45 => 
            array (
                'id' => 68,
                'doc_type' => 'Completion Photographs',
                'refurb_type' => 'completion',
                'order' => 7,
                'is_active' => 0,
            ),
            46 => 
            array (
                'id' => 69,
                'doc_type' => 'Four Stage Clearance',
                'refurb_type' => 'completion',
                'order' => 8,
                'is_active' => 0,
            ),
            47 => 
            array (
                'id' => 70,
                'doc_type' => 'ASB1',
                'refurb_type' => 'planning',
                'order' => 1,
                'is_active' => 1,
            ),
            48 => 
            array (
                'id' => 71,
                'doc_type' => 'F10',
                'refurb_type' => 'planning',
                'order' => 3,
                'is_active' => 1,
            ),
            49 => 
            array (
                'id' => 72,
                'doc_type' => 'Planning Photo’s',
                'refurb_type' => 'planning',
                'order' => 6,
                'is_active' => 1,
            ),
            50 => 
            array (
                'id' => 73,
                'doc_type' => 'Site Set-up Plans',
                'refurb_type' => 'planning',
                'order' => 12,
                'is_active' => 1,
            ),
            51 => 
            array (
                'id' => 74,
                'doc_type' => 'Smoke Test',
                'refurb_type' => 'prestart',
                'order' => 4,
                'is_active' => 1,
            ),
            52 => 
            array (
                'id' => 75,
                'doc_type' => 'Pre-Start Photos',
                'refurb_type' => 'prestart',
                'order' => 3,
                'is_active' => 1,
            ),
            53 => 
            array (
                'id' => 76,
                'doc_type' => 'Other',
                'refurb_type' => 'siterecords',
                'order' => 999,
                'is_active' => 1,
            ),
            54 => 
            array (
                'id' => 77,
                'doc_type' => 'Site Diary',
                'refurb_type' => 'siterecords',
                'order' => 3,
                'is_active' => 1,
            ),
            55 => 
            array (
                'id' => 78,
                'doc_type' => 'Daily Log',
                'refurb_type' => 'siterecords',
                'order' => 2,
                'is_active' => 1,
            ),
            56 => 
            array (
                'id' => 79,
                'doc_type' => 'Signed Rams',
                'refurb_type' => 'siterecords',
                'order' => 1,
                'is_active' => 1,
            ),
            57 => 
            array (
                'id' => 80,
                'doc_type' => 'Asbestos Background Air Monitoring',
                'refurb_type' => 'completion',
                'order' => 2,
                'is_active' => 1,
            ),
            58 => 
            array (
                'id' => 81,
                'doc_type' => 'Asbestos Reassurance Air Monitoring',
                'refurb_type' => 'completion',
                'order' => 7,
                'is_active' => 1,
            ),
            59 => 
            array (
                'id' => 82,
                'doc_type' => 'Asbestos Leak Air Test',
                'refurb_type' => 'completion',
                'order' => 5,
                'is_active' => 1,
            ),
            60 => 
            array (
                'id' => 83,
                'doc_type' => 'Asbestos Personal Air Monitoring',
                'refurb_type' => 'completion',
                'order' => 6,
                'is_active' => 1,
            ),
            61 => 
            array (
                'id' => 84,
                'doc_type' => 'Asbestos DCU Clearance Test',
                'refurb_type' => 'completion',
                'order' => 3,
                'is_active' => 1,
            ),
            62 => 
            array (
                'id' => 85,
                'doc_type' => 'Asbestos Four Stage Clearance',
                'refurb_type' => 'completion',
                'order' => 4,
                'is_active' => 1,
            ),
            63 => 
            array (
                'id' => 86,
                'doc_type' => 'Completion Photo’s',
                'refurb_type' => 'completion',
                'order' => 1,
                'is_active' => 1,
            ),
            64 => 
            array (
                'id' => 87,
                'doc_type' => 'Asbestos Risk Register',
                'refurb_type' => 'preconstruction',
                'order' => 1,
                'is_active' => 1,
            ),
            65 => 
            array (
                'id' => 88,
                'doc_type' => 'Building Survey',
                'refurb_type' => 'preconstruction',
                'order' => 2,
                'is_active' => 1,
            ),
            66 => 
            array (
                'id' => 89,
                'doc_type' => 'Cladding Survey',
                'refurb_type' => 'preconstruction',
                'order' => 3,
                'is_active' => 1,
            ),
            67 => 
            array (
                'id' => 90,
                'doc_type' => 'Client Brief',
                'refurb_type' => 'preconstruction',
                'order' => 4,
                'is_active' => 1,
            ),
            68 => 
            array (
                'id' => 91,
                'doc_type' => 'Electrical Safety Documentation',
                'refurb_type' => 'preconstruction',
                'order' => 5,
                'is_active' => 1,
            ),
            69 => 
            array (
                'id' => 92,
                'doc_type' => 'Employers Requirements',
                'refurb_type' => 'preconstruction',
                'order' => 6,
                'is_active' => 1,
            ),
            70 => 
            array (
                'id' => 93,
                'doc_type' => 'Fire Risk Assessment',
                'refurb_type' => 'preconstruction',
                'order' => 7,
                'is_active' => 1,
            ),
            71 => 
            array (
                'id' => 94,
                'doc_type' => 'Fire Risk Register',
                'refurb_type' => 'preconstruction',
                'order' => 8,
                'is_active' => 1,
            ),
            72 => 
            array (
                'id' => 95,
                'doc_type' => 'Fire Safety Survey',
                'refurb_type' => 'preconstruction',
                'order' => 9,
                'is_active' => 1,
            ),
            73 => 
            array (
                'id' => 96,
                'doc_type' => 'Gas Safety Documentation',
                'refurb_type' => 'preconstruction',
                'order' => 10,
                'is_active' => 1,
            ),
            74 => 
            array (
                'id' => 97,
                'doc_type' => 'Specification of Work',
                'refurb_type' => 'preconstruction',
                'order' => 11,
                'is_active' => 1,
            ),
            75 => 
            array (
                'id' => 98,
                'doc_type' => 'Summary',
                'refurb_type' => 'preconstruction',
                'order' => 12,
                'is_active' => 1,
            ),
            76 => 
            array (
                'id' => 99,
                'doc_type' => 'Vulnerable Persons Lists',
                'refurb_type' => 'preconstruction',
                'order' => 13,
                'is_active' => 1,
            ),
            77 => 
            array (
                'id' => 100,
                'doc_type' => 'Water Risk Register',
                'refurb_type' => 'preconstruction',
                'order' => 14,
                'is_active' => 1,
            ),
            78 => 
            array (
                'id' => 101,
                'doc_type' => 'Other',
                'refurb_type' => 'preconstruction',
                'order' => 999,
                'is_active' => 1,
            ),
            79 => 
            array (
                'id' => 102,
                'doc_type' => 'Building Control Application',
                'refurb_type' => 'design',
                'order' => 1,
                'is_active' => 1,
            ),
            80 => 
            array (
                'id' => 103,
                'doc_type' => 'Building Control Approval',
                'refurb_type' => 'design',
                'order' => 2,
                'is_active' => 1,
            ),
            81 => 
            array (
                'id' => 104,
                'doc_type' => 'Electrical Component Design',
                'refurb_type' => 'design',
                'order' => 3,
                'is_active' => 1,
            ),
            82 => 
            array (
                'id' => 105,
                'doc_type' => 'Fire Door Design',
                'refurb_type' => 'design',
                'order' => 4,
                'is_active' => 1,
            ),
            83 => 
            array (
                'id' => 106,
                'doc_type' => 'Fire Door Testing Certification',
                'refurb_type' => 'design',
                'order' => 5,
                'is_active' => 1,
            ),
            84 => 
            array (
                'id' => 107,
                'doc_type' => 'Fire Stopping Design',
                'refurb_type' => 'design',
                'order' => 6,
                'is_active' => 1,
            ),
            85 => 
            array (
                'id' => 108,
                'doc_type' => 'Location Breakdown',
                'refurb_type' => 'design',
                'order' => 7,
                'is_active' => 1,
            ),
            86 => 
            array (
                'id' => 109,
                'doc_type' => 'Meeting Minutes',
                'refurb_type' => 'design',
                'order' => 8,
                'is_active' => 1,
            ),
            87 => 
            array (
                'id' => 110,
                'doc_type' => 'Planning Application',
                'refurb_type' => 'design',
                'order' => 9,
                'is_active' => 1,
            ),
            88 => 
            array (
                'id' => 111,
                'doc_type' => 'Planning Approval',
                'refurb_type' => 'design',
                'order' => 10,
                'is_active' => 1,
            ),
            89 => 
            array (
                'id' => 112,
                'doc_type' => 'Other',
                'refurb_type' => 'design',
                'order' => 999,
                'is_active' => 1,
            ),
            90 => 
            array (
                'id' => 113,
                'doc_type' => 'Purchase Order',
                'refurb_type' => 'commercial',
                'order' => 1,
                'is_active' => 1,
            ),
            91 => 
            array (
                'id' => 114,
                'doc_type' => 'Quotation',
                'refurb_type' => 'commercial',
                'order' => 2,
                'is_active' => 1,
            ),
            92 => 
            array (
                'id' => 115,
                'doc_type' => 'Schedule of Works',
                'refurb_type' => 'commercial',
                'order' => 3,
                'is_active' => 1,
            ),
            93 => 
            array (
                'id' => 116,
                'doc_type' => 'Specification of Work Response',
                'refurb_type' => 'commercial',
                'order' => 4,
                'is_active' => 1,
            ),
            94 => 
            array (
                'id' => 117,
                'doc_type' => 'T&C’s',
                'refurb_type' => 'commercial',
                'order' => 5,
                'is_active' => 1,
            ),
            95 => 
            array (
                'id' => 118,
                'doc_type' => 'Other',
                'refurb_type' => 'commercial',
                'order' => 999,
                'is_active' => 1,
            ),
            96 => 
            array (
                'id' => 120,
                'doc_type' => 'Project Programme',
                'refurb_type' => 'planning',
                'order' => 8,
                'is_active' => 1,
            ),
            97 => 
            array (
                'id' => 121,
                'doc_type' => 'RLO Communication',
                'refurb_type' => 'planning',
                'order' => 10,
                'is_active' => 1,
            ),
            98 => 
            array (
                'id' => 122,
                'doc_type' => 'S20 Documentation',
                'refurb_type' => 'planning',
                'order' => 11,
                'is_active' => 1,
            ),
            99 => 
            array (
                'id' => 123,
                'doc_type' => 'Fire 3rd Party Verification Certification',
                'refurb_type' => 'completion',
                'order' => 9,
                'is_active' => 1,
            ),
            100 => 
            array (
                'id' => 124,
                'doc_type' => 'Fire Door Certification',
                'refurb_type' => 'completion',
                'order' => 10,
                'is_active' => 1,
            ),
            101 => 
            array (
                'id' => 125,
                'doc_type' => 'Firestopping Certification',
                'refurb_type' => 'completion',
                'order' => 11,
                'is_active' => 1,
            ),
            102 => 
            array (
                'id' => 126,
                'doc_type' => 'O&M Manual',
                'refurb_type' => 'completion',
                'order' => 12,
                'is_active' => 1,
            ),
            103 => 
            array (
                'id' => 127,
                'doc_type' => 'Post Inspection Report',
                'refurb_type' => 'completion',
                'order' => 13,
                'is_active' => 1,
            ),
            104 => 
            array (
                'id' => 128,
                'doc_type' => 'Valuation',
                'refurb_type' => 'completion',
                'order' => 16,
                'is_active' => 1,
            ),
            105 => 
            array (
                'id' => 129,
                'doc_type' => 'Work Request',
                'refurb_type' => 'preconstruction',
                'order' => 15,
                'is_active' => 1,
            ),
        ));
        
        
    }
}