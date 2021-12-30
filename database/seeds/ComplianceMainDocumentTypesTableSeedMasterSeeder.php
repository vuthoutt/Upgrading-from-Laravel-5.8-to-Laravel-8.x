<?php

use Illuminate\Database\Seeder;

class ComplianceMainDocumentTypesTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('compliance_main_document_types')->delete();
        
        \DB::table('compliance_main_document_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => 'Asbestos',
            ),
            1 => 
            array (
                'id' => 2,
                'description' => 'Fire',
            ),
            2 => 
            array (
                'id' => 3,
                'description' => 'Gas',
            ),
            3 => 
            array (
                'id' => 4,
                'description' => 'M&E',
            ),
            4 => 
            array (
                'id' => 5,
                'description' => 'Water',
            ),
            5 => 
            array (
                'id' => 6,
                'description' => 'Other',
            ),
        ));
        
        
    }
}