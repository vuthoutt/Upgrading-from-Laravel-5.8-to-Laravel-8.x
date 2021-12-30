<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        $this->call(ComplianceDocumentTypesTableSeedMasterSeeder::class);
        $this->call(ComplianceDocumentParentTypesTableSeedMasterSeeder::class);
        // $this->call(CpAssessmentQuestionsTableSeedMasterSeeder::class);
        // $this->call(CpAssessmentSectionsTableSeedMasterSeeder::class);
        $this->call(CpEquipmentDropdownTableSeedMasterSeeder::class);
        $this->call(CpEquipmentDropdownDataTableSeedMasterSeeder::class);
        $this->call(CpEquipmentSectionsTableSeedMasterSeeder::class);
        $this->call(CpEquipmentSpecificLocationTableSeedMasterSeeder::class);
        // $this->call(CpEquipmentTemplateSectionsTableSeedMasterSeeder::class);
        // $this->call(CpEquipmentTemplatesTableSeedMasterSeeder::class);
        $this->call(CpEquipmentTypesTableSeedMasterSeeder::class);
        $this->call(CpHazardActionRecommendationTableSeedMasterSeeder::class);
        $this->call(CpHazardMeasurementTableSeedMasterSeeder::class);
        $this->call(CpHazardSpecificLocationTableSeedMasterSeeder::class);
        $this->call(CpAssessmentAnswersTableSeedMasterSeeder::class);
        $this->call(TblPropertyInfoDropdownsTableSeedMasterSeeder::class);
        $this->call(TblPropertyInfoDropdownDataTableSeedMasterSeeder::class);
        $this->call(CpHazardLikelihoodHarmTableSeedMasterSeeder::class);
        $this->call(CpHazardPotentialTableSeedMasterSeeder::class);
        $this->call(TblVulnerableOccupantTypesTableSeedMasterSeeder::class);
        // $this->call(CpHazardActionRecommendationVerbTableSeedMasterSeeder::class);
        // $this->call(CpHazardTypeTableSeedMasterSeeder::class);
        $this->call(ComplianceDocumentParentTypesTableSeeder::class);
        $this->call(CpTempValidationTableSeedMasterSeeder::class);
        // $this->call(CpValidateNonconformitiesTableSeedMasterSeeder::class);
        $this->call(TblConstructionMaterialsTableSeedMasterSeeder::class);
        $this->call(ComplianceMainDocumentTypesTableSeedMasterSeeder::class);
        $this->call(ComplianceSystemTypesTableSeedMasterSeeder::class);
        $this->call(CpHazardInaccessibleReasonTableSeedMasterSeeder::class);
        $this->call(TblDropdownDataAreaTableSeedMasterSeeder::class);
        $this->call(ComplianceSystemClassificationsTableSeedMasterSeeder::class);
        $this->call(TblProjectTypesTableSeedMasterSeeder::class);
        $this->call(CpProjectRiskClassificationTableSeedMasterSeeder::class);
        $this->call(TblDecommissionReasonsTableSeedMasterSeeder::class);
        $this->call(TblPropertyProgrammeTypeTableSeedMasterSeeder::class);
        $this->call(CpAssessmentManagementInfoAnswersTableSeedMasterSeeder::class);
        $this->call(CpAssessmentManagementInfoQuestionsTableSeedMasterSeeder::class);
        $this->call(TblDropdownDataPropertyTableSeedMasterSeeder::class);
        $this->call(CpAssessmentOtherInfoQuestionsTableSeedMasterSeeder::class);
        $this->call(CpAssessmentFireSafetyAnswersTableSeedMasterSeeder::class);
        $this->call(CpHazardActionResponsibilitiesTableSeedMasterSeeder::class);
        $this->call(CpDocumentStatusDropdownSeeder::class);
        $this->call(CpPrivilegeViewTableSeedMasterSeeder::class);
        $this->call(CpPrivilegeEditTableSeedMasterSeeder::class);
        $this->call(CpPrivilegeChildTableSeedMasterSeeder::class);
        $this->call(CpPrivilegeCommonTableSeedMasterSeeder::class);
        $this->call(CpIncidentReportDropdownDataSeeder::class);
        $this->call(CpAssessmentStatementAnswersTableSeedMasterSeeder::class);
        $this->call(CpHazardActionRecommendationNounTableSeeder::class);
        $this->call(TblWorkFlowTableSeedMasterSeeder::class);
        $this->call(TblAppAuditActionsTableSeedMasterSeeder::class);
        $this->call(TblPropertyTypeTableSeedMasterSeeder::class);
        $this->call(TblWorkRequestTypeTableSeedMasterSeeder::class);
        $this->call(TblWorkDataTableSeedMasterSeeder::class);
        $this->call(CpTempValidationByTemplateTableSeeder::class);

        $this->call(TblSummaryTypesTableSeeder::class);
        $this->call(TblRejectionTypeTableSeeder::class);

        $this->call(CpEquipmentDropdownTableSeeder::class);
        $this->call(CpEquipmentDropdownDataTableSeeder::class);
        $this->call(CpEquipmentSectionsTableSeeder::class);
        $this->call(CpEquipmentTemplateSectionsTableSeeder::class);
        $this->call(CpEquipmentTemplatesTableSeeder::class);
        $this->call(CpEquipmentTypesTableSeeder::class);
//        $this->call(CpHazardTypeTableSeeder::class);
        $this->call(CpHazardTypeTableSeedMasterSeeder::class);
        $this->call(CpValidateNonconformitiesTableSeeder::class);

        $this->call(CpAssessmentSectionsTableSeeder::class);
        $this->call(CpAssessmentQuestionsTableSeeder::class);
        $this->call(TblProjectTypesTableSeeder::class);
        $this->call(CpProjectRiskClassificationTableSeeder::class);
        $this->call(CpHazardActionRecommendationVerbTableSeeder::class);
        $this->call(TblSurveyTypeTableSeeder::class);
        $this->call(TblRefurbDocTypesTableSeeder::class);
        $this->call(CpAssessmentAbortedReasonTableSeeder::class);
    }
}
