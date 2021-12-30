<?php

use Illuminate\Database\Seeder;

class TblSummaryTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_summary_types')->delete();
        
        \DB::table('tbl_summary_types')->insert(array (
            0 => 
            array (
                'id' => 3,
                'description' => 'Risk Assessment Summary',
                'value' => 'riskassessment',
                'order' => 3,
            ),
            1 => 
            array (
                'id' => 7,
                'description' => 'Technical Manager Survey Summary',
                'value' => 'survey',
                'order' => 7,
            ),
            2 => 
            array (
                'id' => 8,
                'description' => 'User Summary',
                'value' => 'user',
                'order' => 8,
            ),
            3 => 
            array (
                'id' => 9,
                'description' => 'Area/floor Check',
                'value' => 'areaCheck',
                'order' => 9,
            ),
            4 => 
            array (
                'id' => 10,
                'description' => 'Room/location Check',
                'value' => 'roomCheck',
                'order' => 10,
            ),
            5 => 
            array (
                'id' => 11,
                'description' => 'Re-Inspection Programme',
                'value' => 'reinspectionProgramme',
                'order' => 11,
            ),
            6 => 
            array (
                'id' => 12,
                'description' => 'Directors Overview - Asbestos',
                'value' => 'directorOverview',
                'order' => 12,
            ),
            7 => 
            array (
                'id' => 13,
                'description' => 'Managers Overview - Asbestos',
                'value' => 'managerOverview',
                'order' => 13,
            ),
            8 => 
            array (
                'id' => 14,
                'description' => 'Contractor KPI Summary',
                'value' => 'KPIsummary',
                'order' => 14,
            ),
            9 => 
            array (
                'id' => 15,
                'description' => 'Inaccessible Summary',
                'value' => 'inaccessible',
                'order' => 4,
            ),
            10 => 
            array (
                'id' => 16,
                'description' => 'Action/recommendation Summary',
                'value' => 'actionRecommendation',
                'order' => 5,
            ),
            11 => 
            array (
                'id' => 17,
                'description' => 'Property Information',
                'value' => 'genesisCommunalCsv',
                'order' => 15,
            ),
            12 => 
            array (
                'id' => 18,
                'description' => 'Asbestos Remedial Action Summary',
                'value' => 'asbestosRemedialAction',
                'order' => 16,
            ),
            13 => 
            array (
                'id' => 19,
                'description' => 'Project Summary',
                'value' => 'projectSummary',
                'order' => 7,
            ),
            14 => 
            array (
                'id' => 20,
                'description' => 'Register Item Change',
                'value' => 'registerItemChange',
                'order' => 7,
            ),
            15 => 
            array (
                'id' => 21,
                'description' => 'HD Document Summary',
                'value' => 'hdDocuments',
                'order' => 7,
            ),
            16 => 
            array (
                'id' => 24,
                'description' => 'Survey Summary',
                'value' => 'surveySummary',
                'order' => 7,
            ),
            17 => 
            array (
                'id' => 25,
                'description' => 'Project Document Summary',
                'value' => 'projectDocumentSummary',
                'order' => 7,
            ),
            18 => 
            array (
                'id' => 26,
                'description' => 'Priority for Action',
                'value' => 'priorityforaction',
                'order' => 26,
            ),
            19 => 
            array (
                'id' => 27,
                'description' => 'Decommissioned Item Summary',
                'value' => 'decommissionedItem',
                'order' => 27,
            ),
            20 => 
            array (
                'id' => 30,
                'description' => 'User Community Summary',
                'value' => 'userCS',
                'order' => 30,
            ),
            21 => 
            array (
                'id' => 31,
                'description' => 'Sample Summary',
                'value' => 'sampleSummary',
                'order' => 31,
            ),
            22 => 
            array (
                'id' => 32,
                'description' => 'Duplication Checker',
                'value' => 'duplicationChecker',
                'order' => 32,
            ),
            23 => 
            array (
                'id' => 33,
                'description' => 'Photography Size Summary',
                'value' => 'photography_size',
                'order' => 33,
            ),
            24 => 
            array (
                'id' => 34,
                'description' => 'Survey Document Summary',
                'value' => 'project_document',
                'order' => 34,
            ),
            25 => 
            array (
                'id' => 35,
                'description' => 'Santia Sample Summary',
                'value' => 'santiaSampleSummary',
                'order' => 35,
            ),
            26 => 
            array (
                'id' => 37,
                'description' => 'Gap Analysis Summary',
                'value' => 'orchardSummary',
                'order' => 37,
            ),
            27 => 
            array (
                'id' => 38,
                'description' => 'App User Summary',
                'value' => 'appUser',
                'order' => 40,
            ),
            28 => 
            array (
                'id' => 39,
                'description' => 'Rejection Type Summary',
                'value' => 'rejectionTypeSummary',
                'order' => 39,
            ),
        ));
        
        
    }
}