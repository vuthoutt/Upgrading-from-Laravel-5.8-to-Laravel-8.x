<?php

namespace app\Http\Controllers\Migration;

use App\Models\Zone;
use Illuminate\Routing\Controller;
use App\Http\Controllers\LoginController as BaseController;
use Illuminate\Support\Facades\DB;
use App\Models\DropdownShort;
use App\Models\DropdownLocation;
use App\Models\DropdownItem;
use App\Models\DropdownProperty;
use App\Models\DropdownSurvey;
use App\Models\DropdownDataLocation;
use App\Models\DropdownDataProperty;
use App\Models\DropdownDataItem;
use App\Models\DropdownDataSurvey;
use App\Models\DropdownValue;
use PHPUnit\Runner\Exception;
use App\Models\DropdownItem\ProductDebrisType;//3
use App\Models\DropdownItemValue\ProductDebrisTypeValue;//3
use App\Models\DropdownItem\Extent;//4
use App\Models\DropdownItemValue\ExtentValue;//4
use App\Models\DropdownItem\AsbestosType;//5
use App\Models\DropdownItemValue\AsbestosTypeValue;//5
use App\Models\DropdownItem\ActionRecommendation;//7
use App\Models\DropdownItemValue\ActionRecommendationValue;//7
use App\Models\DropdownItem\AdditionalInformation;//8
use App\Models\DropdownItemValue\AdditionalInformationValue;//8
use App\Models\DropdownItem\SampleComment;//9
use App\Models\DropdownItemValue\SampleCommentValue;//9
use App\Models\DropdownItem\SpecificLocation;//11
use App\Models\DropdownItemValue\SpecificLocationValue;//11
use App\Models\DropdownItem\AccessibilityVulnerability;//12
use App\Models\DropdownItemValue\AccessibilityVulnerabilityValue;//12
use App\Models\DropdownItem\LicensedNonLicensed;//13
use App\Models\DropdownItemValue\LicensedNonLicensedValue;//13
use App\Models\DropdownItem\UnableToSample;//14
use App\Models\DropdownItemValue\UnableToSampleValue;//14
use App\Models\DropdownItem\ItemNoAccess;//15
use App\Models\DropdownItemValue\ItemNoAccessValue;//15
use App\Models\DropdownItem\NoACMComments;//16
use App\Models\DropdownItemValue\NoACMCommentsValue;//16
use App\Models\DropdownItem\PriorityAssessmentRisk;//18
use App\Models\DropdownItemValue\PriorityAssessmentRiskValue;//18
use App\Models\DropdownItem\MaterialAssessmentRisk;//19
use App\Models\DropdownItemValue\MaterialAssessmentRiskValue;//19
use App\Models\DropdownItem\SampleId;//500
use App\Models\DropdownItemValue\SampleIdValue;//500
use App\Models\DropdownItem\SubSampleId;//502
use App\Models\DropdownItemValue\SubSampleIdValue;//502



class MigrationDropdownController extends Controller
{
    /**
     * Migration tblzone
     * int $type
     * 1 : migrate item
     * 2 : migrate dropdown short
     * 3 : migrate property
     * 4 : migrate location
     * 5 : migrate survey
     * @return string
     */
    public function migrate_dropdown($type){
        $con_gsk_old = "mysql_gsk_old";


        DB::beginTransaction();
        try{
            if($type == 1){
                $this->migrate_dropdown_item($con_gsk_old);
            } else if($type == 2){
                $this->migrate_dropdown_short($con_gsk_old);
            } else if($type == 3){
                $this->migrate_dropdown_property($con_gsk_old);
            } else if($type == 4){
                $this->migrate_dropdown_location($con_gsk_old);
            } else {
                $this->migrate_dropdown_survey($con_gsk_old);
            }
            DB::commit();
        } catch (\Exception $e){
            DB::rollback();
            dd($e->getMessage());
        }
        dd('Done');
    }

    private function migrate_dropdown_short($con_gsk_old){

        $sql2 = "SELECT * FROM tbldropdownshort;";
        $data_dropdown_short = [];
        $tbldropdownshort = DB::connection($con_gsk_old)->select(DB::raw($sql2));
//      get data for migrate tbl_dropdown_short
        if(count($tbldropdownshort) > 0){
            foreach ($tbldropdownshort as $d_short){
                $data_dropdown_short[] = [
                    'id' => $d_short->ID,
                    'short_text' => $d_short->shortText
                ];
            }
        }
        try{
            DropdownShort::insert($data_dropdown_short);
        }catch (Exception $e){
            Throw New Exception($e->getMessage());
        }
        return 'Done';
    }

    private function migrate_dropdown_property($con_gsk_old){
        $property_dropdown_id = '1, 17, 508, 601, 602, 603, 604';
        $sql_data = "   (
                            SELECT d1.* FROM tbldropdowndata d1
                            JOIN tbldropdown d on d1.dropdownID = d.ID
                            WHERE d.ID IN ($property_dropdown_id) AND parentID = 0
                            ORDER BY d1.ID
                        )
                        UNION
                        (
                            SELECT d2.* FROM tbldropdowndata d1
                            JOIN tbldropdown d on d1.dropdownID = d.ID
                            JOIN tbldropdowndata d2 ON d2.parentID = d1.ID
                            WHERE d.ID IN ($property_dropdown_id) AND d2.parentID != 0
                            ORDER BY d2.ID
                        )
                        UNION
                        (
                            SELECT d3.* FROM tbldropdowndata d1
                            JOIN tbldropdown d on d1.dropdownID = d.ID
                            JOIN tbldropdowndata d2 ON d2.parentID = d1.ID
                            JOIN tbldropdowndata d3 ON d3.parentID = d2.ID
                            WHERE d.ID IN ($property_dropdown_id) AND d2.parentID != 0
                            ORDER BY d3.ID
                        )";
        $sql_dropdown = "SELECT * from tbldropdown WHERE ID IN($property_dropdown_id)";
        $dropdown_data  = DB::connection($con_gsk_old)->select(DB::raw($sql_data));
        $dropdown  = DB::connection($con_gsk_old)->select(DB::raw($sql_dropdown));
        $data_d_data = [];
        $data_d = [];

        if(count($dropdown_data) > 0 && count($dropdown) ){
            foreach ($dropdown_data as $d_data){
                $data_d_data[] = [
                    'id' => $d_data->ID,
                    'description' => $d_data->description,
                    'dropdown_property_id' => $d_data->dropdownID,
                    'order' => $d_data->order,
                    'score' => $d_data->score,
                    'other' => $d_data->other,
                    'decommissioned' => $d_data->decommissioned == -1 ? 1 : 0,
                    'parent_id' => $d_data->parentID,
                    'removal_cost' => $d_data->removalCost,
                ];
            }

            foreach ($dropdown as $d_dropdown){
                $data_d[] = [
                    'id' => $d_dropdown->ID,
                    'description' => $d_dropdown->description,
                    'endpoint' => $d_dropdown->endpoint,
                    'multi_tiered' => $d_dropdown->multiTiered,
                ];
            }

            try{
                DropdownDataProperty::insert($data_d_data);
                DropdownProperty::insert($data_d);
            }catch (\Exception $e){
                Throw New Exception($e->getMessage());
            }
        }
        return 'Done';
    }

    private function migrate_dropdown_survey($con_gsk_old){
        $survey_dropdown_id = '300, 507';
        $sql_data = "   (
                            SELECT d1.* FROM tbldropdowndata d1
                            JOIN tbldropdown d on d1.dropdownID = d.ID
                            WHERE d.ID IN ($survey_dropdown_id) AND parentID = 0
                            ORDER BY d1.ID
                        )
                        UNION
                        (
                            SELECT d2.* FROM tbldropdowndata d1
                            JOIN tbldropdown d on d1.dropdownID = d.ID
                            JOIN tbldropdowndata d2 ON d2.parentID = d1.ID
                            WHERE d.ID IN ($survey_dropdown_id) AND d2.parentID != 0
                            ORDER BY d2.ID
                        )
                        UNION
                        (
                            SELECT d3.* FROM tbldropdowndata d1
                            JOIN tbldropdown d on d1.dropdownID = d.ID
                            JOIN tbldropdowndata d2 ON d2.parentID = d1.ID
                            JOIN tbldropdowndata d3 ON d3.parentID = d2.ID
                            WHERE d.ID IN ($survey_dropdown_id) AND d2.parentID != 0
                            ORDER BY d3.ID
                        )";
        $sql_dropdown = "SELECT * from tbldropdown WHERE ID IN($survey_dropdown_id)";
        $dropdown_data  = DB::connection($con_gsk_old)->select(DB::raw($sql_data));
        $dropdown  = DB::connection($con_gsk_old)->select(DB::raw($sql_dropdown));
        $data_d_data = [];
        $data_d = [];

        if(count($dropdown_data) > 0 && count($dropdown) > 0 ){
            foreach ($dropdown_data as $d_data){
                $data_d_data[] = [
                    'id' => $d_data->ID,
                    'description' => $d_data->description,
                    'dropdown_survey_id' => $d_data->dropdownID,
                    'order' => $d_data->order,
                    'score' => $d_data->score,
                    'other' => $d_data->other,
                    'decommissioned' => $d_data->decommissioned == -1 ? 1 : 0,
                    'parent_id' => $d_data->parentID,
                    'removal_cost' => $d_data->removalCost,
                ];
            }

            foreach ($dropdown as $d_dropdown){
                $data_d[] = [
                    'id' => $d_dropdown->ID,
                    'description' => $d_dropdown->description,
                    'endpoint' => $d_dropdown->endpoint,
                    'multi_tiered' => $d_dropdown->multiTiered,
                ];
            }

            try{
                DropdownDataSurvey::insert($data_d_data);
                DropdownSurvey::insert($data_d);
            }catch (\Exception $e){
                Throw New Exception($e->getMessage());
            }
        }
        return 'Done';
    }

    private function migrate_dropdown_location($con_gsk_old){
        $survey_dropdown_id = '6,10,20,21,22,23,24,25,26,27,28,29,30,31,501';
        $sql_data = "   (
                            SELECT d1.* FROM tbldropdowndata d1
                            JOIN tbldropdown d on d1.dropdownID = d.ID
                            WHERE d.ID IN ($survey_dropdown_id) AND parentID = 0
                            ORDER BY d1.ID
                        )
                        UNION
                        (
                            SELECT d2.* FROM tbldropdowndata d1
                            JOIN tbldropdown d on d1.dropdownID = d.ID
                            JOIN tbldropdowndata d2 ON d2.parentID = d1.ID
                            WHERE d.ID IN ($survey_dropdown_id) AND d2.parentID != 0
                            ORDER BY d2.ID
                        )
                        UNION
                        (
                            SELECT d3.* FROM tbldropdowndata d1
                            JOIN tbldropdown d on d1.dropdownID = d.ID
                            JOIN tbldropdowndata d2 ON d2.parentID = d1.ID
                            JOIN tbldropdowndata d3 ON d3.parentID = d2.ID
                            WHERE d.ID IN ($survey_dropdown_id) AND d2.parentID != 0
                            ORDER BY d3.ID
                        )";
        $sql_dropdown = "SELECT * from tbldropdown WHERE ID IN($survey_dropdown_id)";
        $dropdown_data  = DB::connection($con_gsk_old)->select(DB::raw($sql_data));
        $dropdown  = DB::connection($con_gsk_old)->select(DB::raw($sql_dropdown));
        $data_d_data = [];
        $data_d = [];

        if(count($dropdown_data) > 0 && count($dropdown) > 0 ){
            foreach ($dropdown_data as $d_data){
                $data_d_data[] = [
                    'id' => $d_data->ID,
                    'description' => $d_data->description,
                    'dropdown_location_id' => $d_data->dropdownID,
                    'order' => $d_data->order,
                    'score' => $d_data->score,
                    'other' => $d_data->other,
                    'decommissioned' => $d_data->decommissioned == -1 ? 1 : 0,
                    'parent_id' => $d_data->parentID,
                    'removal_cost' => $d_data->removalCost,
                ];
            }

            foreach ($dropdown as $d_dropdown){
                $data_d[] = [
                    'id' => $d_dropdown->ID,
                    'description' => $d_dropdown->description,
                    'endpoint' => $d_dropdown->endpoint,
                    'multi_tiered' => $d_dropdown->multiTiered,
                ];
            }

            try{
                DropdownDataLocation::insert($data_d_data);
                DropdownLocation::insert($data_d);
            }catch (\Exception $e){
                Throw New Exception($e->getMessage());
            }
        }
        return 'Done';
    }

    private function migrate_dropdown_item($con_gsk_old){
        ini_set('max_execution_time', 12000 );
        // dropdownID = 11 if dropdownDataID = -1 then dropdownDataID = other
        // divine into 32 table (x2 id)
        $item_dropdown_id = [3, 4, 5, 7, 8, 11, 12, 13, 15, 16, 18, 19, 9, 14, 500, 502];
        $item_dropdown_id_a = "3, 4, 5, 7, 8, 11, 12, 13, 15, 16, 18, 19, 9, 14, 500, 502";

        // dd($sql_dropdown);
        foreach ($item_dropdown_id as $id) {
            $dropdownData = "SELECT * from tbldropdowndata WHERE dropdownID = $id";
            $dropdown_value = "SELECT * from tbldropdownvalue WHERE dropdownID = $id";



            $dropdowndata_data  = DB::connection($con_gsk_old)->select(DB::raw($dropdownData));
            $dropdownvalue_data  = DB::connection($con_gsk_old)->select(DB::raw($dropdown_value));
            $data_d_data = [];

            foreach ($dropdowndata_data as $d_data){
                $data_d_data[] = [
                    'id' => $d_data->ID,
                    'description' => $d_data->description,
                    'dropdown_item_id' => $d_data->dropdownID,
                    'order' => $d_data->order,
                    'score' => $d_data->score,
                    'other' => $d_data->other,
                    'decommissioned' => $d_data->decommissioned == -1 ? 1 : 0,
                    'parent_id' => $d_data->parentID,
                    'removal_cost' => $d_data->removalCost,
                ];
            }

            $data_d_value= [];
            if ($id == 11) {
                foreach ($dropdownvalue_data as $d_value){
                    $data_d_value[] = [
                        'id' => $d_value->ID,
                        'item_id' => $d_value->itemID,
                        'dropdown_item_id' => $d_value->dropdownID,
                        'dropdown_data_item_parent_id' => $d_value->dropdownDataParent,
                        'dropdown_data_item_id' => ($d_value->dropdownDataID == -1 ) ? $d_value->dropdownOther : $d_value->dropdownDataID,
                        'dropdown_other' => $d_value->dropdownOther,
                    ];
                }
            } else {
                foreach ($dropdownvalue_data as $d_value){
                    $data_d_value[] = [
                        'id' => $d_value->ID,
                        'item_id' => $d_value->itemID,
                        'dropdown_item_id' => $d_value->dropdownID,
                        'dropdown_data_item_parent_id' => $d_value->dropdownDataParent,
                        'dropdown_data_item_id' => $d_value->dropdownDataID,
                        'dropdown_other' => $d_value->dropdownOther,
                    ];
                }
            }
            // dd($data_d_data);
            $this->updateDropdownData($id, $data_d_data,$data_d_value);

        }
        echo "ok 3";
        // $sql_data = "   (
        //                     SELECT d1.* FROM tbldropdowndata d1
        //                     JOIN tbldropdown d on d1.dropdownID = d.ID
        //                     WHERE d.ID IN ($item_dropdown_id) AND parentID = 0
        //                     ORDER BY d1.ID
        //                 )
        //                 UNION
        //                 (
        //                     SELECT d2.* FROM tbldropdowndata d1
        //                     JOIN tbldropdown d on d1.dropdownID = d.ID
        //                     JOIN tbldropdowndata d2 ON d2.parentID = d1.ID
        //                     WHERE d.ID IN ($item_dropdown_id) AND d2.parentID != 0
        //                     ORDER BY d2.ID
        //                 )
        //                 UNION
        //                 (
        //                     SELECT d3.* FROM tbldropdowndata d1
        //                     JOIN tbldropdown d on d1.dropdownID = d.ID
        //                     JOIN tbldropdowndata d2 ON d2.parentID = d1.ID
        //                     JOIN tbldropdowndata d3 ON d3.parentID = d2.ID
        //                     WHERE d.ID IN ($item_dropdown_id) AND d2.parentID != 0
        //                     ORDER BY d3.ID
        //                 )";
        $sql_dropdown = "SELECT * from tbldropdown WHERE ID IN($item_dropdown_id_a)";

        $dropdown  = DB::connection($con_gsk_old)->select(DB::raw($sql_dropdown));

        $data_d_data = [];
        $data_d = [];
        $data_d_value = [];
            foreach ($dropdown as $d_dropdown){
                $data_d[] = [
                    'id' => $d_dropdown->ID,
                    'description' => $d_dropdown->description,
                    'endpoint' => $d_dropdown->endpoint,
                    'multi_tiered' => $d_dropdown->multiTiered,
                ];
            }


            try{

                DropdownItem::insert($data_d);
                // $insert_data = collect($data_d_value); // Make a collection to use the chunk method

            }catch (\Exception $e){
                Throw New Exception($e->getMessage());
            }
        echo "ok alll";
    }

    public function updateDropdownData($id, $data, $dataValue) {
        try{
            switch ($id) {
                case 3:
                    ProductDebrisType::insert($data);
                    $insert_data = collect($dataValue);
                    // dd($dataValue);
                    $chunks = $insert_data->chunk(500);
                    foreach ($chunks as $chunk)
                    {
                        // dd(1);
                        ProductDebrisTypeValue::insert($chunk->toArray());
                    }
                    echo 'ok2';
                    break;
                case 4:
                    Extent::insert($data);
                    $insert_data = collect($dataValue);
                    $chunks = $insert_data->chunk(500);
                    foreach ($chunks as $chunk)
                    {
                        ExtentValue::insert($chunk->toArray());
                    }
                    echo 'ok2';
                    break;
                case 5:
                    AsbestosType::insert($data);
                    $insert_data = collect($dataValue);
                    $chunks = $insert_data->chunk(500);
                    foreach ($chunks as $chunk)
                    {
                        AsbestosTypeValue::insert($chunk->toArray());
                    }
                    echo 'ok2';
                    break;
                case 7:
                    ActionRecommendation::insert($data);
                    $insert_data = collect($dataValue);
                    $chunks = $insert_data->chunk(500);
                    foreach ($chunks as $chunk)
                    {
                        ActionRecommendationValue::insert($chunk->toArray());
                    }
                    echo 'ok2';
                    break;
                case 8:
                    AdditionalInformation::insert($data);
                    $insert_data = collect($dataValue);
                    $chunks = $insert_data->chunk(500);
                    foreach ($chunks as $chunk)
                    {
                        AdditionalInformationValue::insert($chunk->toArray());
                    }
                    echo 'ok2';
                    break;
                case 9:
                    SampleComment::insert($data);
                    $insert_data = collect($dataValue);
                    $chunks = $insert_data->chunk(500);
                    foreach ($chunks as $chunk)
                    {
                        SampleCommentValue::insert($chunk->toArray());
                    }
                    echo 'ok2';
                    break;
                case 11:
                    SpecificLocation::insert($data);
                    $insert_data = collect($dataValue);
                    $chunks = $insert_data->chunk(500);
                    foreach ($chunks as $chunk)
                    {
                        SpecificLocationValue::insert($chunk->toArray());
                    }
                    echo 'ok2';
                    break;
                case 12:
                    AccessibilityVulnerability::insert($data);
                    $insert_data = collect($dataValue);
                    $chunks = $insert_data->chunk(500);
                    foreach ($chunks as $chunk)
                    {
                        AccessibilityVulnerabilityValue::insert($chunk->toArray());
                    }
                    echo 'ok2';
                    break;
                case 13:
                    LicensedNonLicensed::insert($data);
                    $insert_data = collect($dataValue);
                    $chunks = $insert_data->chunk(500);
                    foreach ($chunks as $chunk)
                    {
                        LicensedNonLicensedValue::insert($chunk->toArray());
                    }
                    echo 'ok2';
                    break;
                case 14:
                    UnableToSample::insert($data);
                    $insert_data = collect($dataValue);
                    $chunks = $insert_data->chunk(500);
                    foreach ($chunks as $chunk)
                    {
                        UnableToSampleValue::insert($chunk->toArray());
                    }
                    echo 'ok2';
                    break;
                case 15:
                    ItemNoAccess::insert($data);
                    $insert_data = collect($dataValue);
                    $chunks = $insert_data->chunk(500);
                    foreach ($chunks as $chunk)
                    {
                        ItemNoAccessValue::insert($chunk->toArray());
                    }
                    echo 'ok2';
                    break;
                case 18:
                    PriorityAssessmentRisk::insert($data);
                    $insert_data = collect($dataValue);
                    $chunks = $insert_data->chunk(500);
                    foreach ($chunks as $chunk)
                    {
                        PriorityAssessmentRiskValue::insert($chunk->toArray());
                    }
                    echo 'ok2';
                    break;
                case 16:
                    NoACMComments::insert($data);
                    $insert_data = collect($dataValue);
                    $chunks = $insert_data->chunk(500);
                    foreach ($chunks as $chunk)
                    {
                        NoACMCommentsValue::insert($chunk->toArray());
                    }
                    echo 'ok2';
                    break;

                case 19:
                    MaterialAssessmentRisk::insert($data);
                    $insert_data = collect($dataValue);
                    $chunks = $insert_data->chunk(500);
                    foreach ($chunks as $chunk)
                    {
                        MaterialAssessmentRiskValue::insert($chunk->toArray());
                    }
                    echo 'ok2';
                    break;

                case 500:
                    SampleId::insert($data);
                    $insert_data = collect($dataValue);
                    $chunks = $insert_data->chunk(500);
                    foreach ($chunks as $chunk)
                    {
                        SampleIdValue::insert($chunk->toArray());
                    }
                    echo 'ok2';
                    break;
                case 502:
                    SubSampleId::insert($data);
                    $insert_data = collect($dataValue);
                    $chunks = $insert_data->chunk(500);
                    foreach ($chunks as $chunk)
                    {
                        SubSampleIdValue::insert($chunk->toArray());
                    }
                    echo 'ok2';
                    break;
            }
        }catch (\Exception $e){
            Throw New Exception($e->getMessage());
        }
    }
}

//function insert base on table
//$type = table_name
//use App\Models\DropdownItem\ProductDebrisType;//3
//use App\Models\DropdownItemValue\ProductDebrisTypeValue;//3
//use App\Models\DropdownItem\Extent;//4
//use App\Models\DropdownItemValue\ExtentValue;//4
//use App\Models\DropdownItem\AsbestosType;//5
//use App\Models\DropdownItemValue\AsbestosTypeValue;//5
//use App\Models\DropdownItem\ActionRecommendation;//7
//use App\Models\DropdownItemValue\ActionRecommendationValue;//7
//use App\Models\DropdownItem\AdditionalInformation;//8
//use App\Models\DropdownItemValue\AdditionalInformationValue;//8
//use App\Models\DropdownItem\SampleComment;//9
//use App\Models\DropdownItemValue\SampleCommentValue;//9
//use App\Models\DropdownItem\SpecificLocation;//11
//use App\Models\DropdownItemValue\SpecificLocationValue;//11
//use App\Models\DropdownItem\AccessibilityVulnerability;//12
//use App\Models\DropdownItemValue\AccessibilityVulnerabilityValue;//12
//use App\Models\DropdownItem\LicensedNonLicensed;//13
//use App\Models\DropdownItemValue\LicensedNonLicensedValue;//13
//use App\Models\DropdownItem\UnableToSample;//14
//use App\Models\DropdownItemValue\UnableToSampleValue;//14
//use App\Models\DropdownItem\ItemNoAccess;//15
//use App\Models\DropdownItemValue\ItemNoAccessValue;//15
//use App\Models\DropdownItem\NoACMComments;//16
//use App\Models\DropdownItemValue\NoACMCommentsValue;//16
//use App\Models\DropdownItem\PriorityAssessmentRisk;//18
//use App\Models\DropdownItemValue\PriorityAssessmentRiskValue;//18
//use App\Models\DropdownItem\MaterialAssessmentRisk;//19
//use App\Models\DropdownItemValue\MaterialAssessmentRiskValue;//19
