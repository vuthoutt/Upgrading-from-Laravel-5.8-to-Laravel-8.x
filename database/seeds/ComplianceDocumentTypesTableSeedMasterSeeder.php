<?php

use Illuminate\Database\Seeder;

class ComplianceDocumentTypesTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('compliance_document_types')->delete();

        \DB::table('compliance_document_types')->insert(array (
            0 =>
            array (
                'id' => 1,
                'description' => 'Asbestos Management Survey',
                'other' => '0',
                'type' => 1,
                'is_external_ms' => 1,
                'is_reinspected' => 0,
            ),
            1 =>
            array (
                'id' => 2,
                'description' => 'Asbestos Refurbishment Survey',
                'other' => '0',
                'type' => 1,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            2 =>
            array (
                'id' => 3,
                'description' => 'Asbestos Demolition Survey',
                'other' => '0',
                'type' => 1,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            3 =>
            array (
                'id' => 4,
                'description' => 'Asbestos Re-Inspection Survey',
                'other' => '0',
                'type' => 1,
                'is_external_ms' => 2,
                'is_reinspected' => 0,
            ),
            4 =>
            array (
                'id' => 5,
                'description' => 'Asbestos Management and Refurbishment Survey',
                'other' => '0',
                'type' => 1,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            5 =>
            array (
                'id' => 11,
                'description' => 'Asbestos Other',
                'other' => '0',
                'type' => 1,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            6 =>
            array (
                'id' => 12,
                'description' => 'Fire Enforcement Notice',
                'other' => '0',
                'type' => 2,
                'is_external_ms' => 0,
                'is_reinspected' => 1,
            ),
            7 =>
            array (
                'id' => 13,
                'description' => 'Notice of Deficiency',
                'other' => '0',
                'type' => 2,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            8 =>
            array (
                'id' => 14,
                'description' => 'Enforcement Notice Extension Letter',
                'other' => '0',
                'type' => 2,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            9 =>
            array (
                'id' => 15,
                'description' => 'Prohibition Notice',
                'other' => '0',
                'type' => 2,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            12 =>
            array (
                'id' => 18,
                'description' => 'Gas Service Record',
                'other' => '0',
                'type' => 3,
                'is_external_ms' => 0,
                'is_reinspected' => 1,
            ),
            13 =>
            array (
                'id' => 19,
                'description' => 'Gas Call-out',
                'other' => '0',
                'type' => 3,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            14 =>
            array (
                'id' => 20,
                'description' => 'Gas Certificate of Re-occupation',
                'other' => '0',
                'type' => 3,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            15 =>
            array (
                'id' => 21,
                'description' => 'Gas Remedial',
                'other' => '0',
                'type' => 3,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            16 =>
            array (
                'id' => 22,
                'description' => 'Gas Work Order',
                'other' => '0',
                'type' => 3,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            17 =>
            array (
                'id' => 23,
                'description' => 'Gas Other',
                'other' => '0',
                'type' => 3,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            18 =>
            array (
                'id' => 24,
                'description' => 'M&E Service Record',
                'other' => '0',
                'type' => 4,
                'is_external_ms' => 0,
                'is_reinspected' => 1,
            ),
            19 =>
            array (
                'id' => 25,
                'description' => 'M&E Call-out',
                'other' => '0',
                'type' => 4,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            20 =>
            array (
                'id' => 26,
                'description' => 'M&E Certificate of Re-occupation',
                'other' => '0',
                'type' => 4,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            21 =>
            array (
                'id' => 27,
                'description' => 'M&E Remedial',
                'other' => '0',
                'type' => 4,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            22 =>
            array (
                'id' => 28,
                'description' => 'M&E Work Order',
                'other' => '0',
                'type' => 4,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            23 =>
            array (
                'id' => 29,
                'description' => 'M&E Other',
                'other' => '0',
                'type' => 4,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            24 =>
            array (
                'id' => 30,
                'description' => 'Water Service Record',
                'other' => '0',
                'type' => 5,
                'is_external_ms' => 0,
                'is_reinspected' => 1,
            ),
            25 =>
            array (
                'id' => 31,
                'description' => 'Water Call-out',
                'other' => '0',
                'type' => 5,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            26 =>
            array (
                'id' => 32,
                'description' => 'Water Certificate of Re-occupation',
                'other' => '0',
                'type' => 5,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            27 =>
            array (
                'id' => 33,
                'description' => 'Water Remedial',
                'other' => '0',
                'type' => 5,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            28 =>
            array (
                'id' => 34,
                'description' => 'Water Work Order',
                'other' => '0',
                'type' => 5,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            29 =>
            array (
                'id' => 35,
                'description' => 'Water Other',
                'other' => '0',
                'type' => 5,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            30 =>
            array (
                'id' => 36,
                'description' => 'Fire Call-out',
                'other' => '0',
                'type' => 2,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            31 =>
            array (
                'id' => 37,
                'description' => 'Fire Certificate of Re-occupation',
                'other' => '0',
                'type' => 2,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            32 =>
            array (
                'id' => 38,
                'description' => 'Fire Remedial',
                'other' => '0',
                'type' => 2,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            33 =>
            array (
                'id' => 39,
                'description' => 'Fire Risk Assessment',
                'other' => '0',
                'type' => 2,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            34 =>
            array (
                'id' => 40,
                'description' => 'Fire Service Record',
                'other' => '0',
                'type' => 2,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            35 =>
            array (
                'id' => 41,
                'description' => 'Fire Work Order',
                'other' => '0',
                'type' => 2,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            36 =>
            array (
                'id' => 42,
                'description' => 'Fire Other',
                'other' => '0',
                'type' => 2,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            37 =>
            array (
                'id' => 43,
                'description' => 'Water Risk Assessment',
                'other' => '0',
                'type' => 5,
                'is_external_ms' => 0,
                'is_reinspected' => 0,
            ),
            38 =>
                array (
                    'id' => 6,
                    'description' => 'Asbestos Sample Survey',
                    'other' => '0',
                    'type' => 1,
                    'is_external_ms' => 0,
                    'is_reinspected' => 0,
                ),
        ));


    }
}
