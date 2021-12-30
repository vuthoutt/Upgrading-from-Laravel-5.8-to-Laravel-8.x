<?php

use Illuminate\Database\Seeder;

class ComplianceDocumentParentTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('compliance_document_parent_types')->delete();
        
        \DB::table('compliance_document_parent_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => 'No Parent Required',
            ),
            1 => 
            array (
                'id' => 2,
                'description' => 'System',
            ),
            2 => 
            array (
                'id' => 3,
                'description' => 'Equipment',
            ),
        ));
        
        
    }
}